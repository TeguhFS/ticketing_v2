<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\TicketType;
use App\Services\MidtransService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans,
        protected TicketService $ticketService,
    ) {}

    public function index(Event $event)
    {
        abort_if($event->status !== 'published', 404);

        $event->load(['ticketTypes' => fn($q) => $q->where('is_active', true)]);

        $availableTypes = $event->ticketTypes->filter(function ($type) {
            $isAvailable = ($type->quota - $type->sold) > 0;
            $isOnSale    = (!$type->sale_start || now()->gte($type->sale_start))
                && (!$type->sale_end   || now()->lte($type->sale_end));
            return $isAvailable && $isOnSale;
        });

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('user.checkout.index', compact('event', 'availableTypes', 'paymentMethods'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'tickets'                   => 'required|array|min:1',
            'tickets.*.ticket_type_id'  => 'required|exists:ticket_types,id',
            'tickets.*.quantity'        => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $items    = [];

            foreach ($request->tickets as $item) {
                if (empty($item['quantity']) || $item['quantity'] < 1) continue;

                $type = TicketType::where('id', $item['ticket_type_id'])
                    ->where('event_id', $event->id)
                    ->where('is_active', true)
                    ->firstOrFail();

                $remaining = $type->quota - $type->sold;
                if ($item['quantity'] > $remaining) {
                    return back()->withErrors(['tickets' => "Stok tiket {$type->name} tidak mencukupi."]);
                }

                if ($item['quantity'] > $type->max_per_order) {
                    return back()->withErrors(['tickets' => "Maksimal {$type->max_per_order} tiket untuk {$type->name}."]);
                }

                $lineTotal  = $type->price * $item['quantity'];
                $subtotal  += $lineTotal;
                $items[]    = [
                    'ticket_type' => $type,
                    'quantity'    => $item['quantity'],
                    'price'       => $type->price,
                    'subtotal'    => $lineTotal,
                ];
            }

            if (empty($items)) {
                return back()->withErrors(['tickets' => 'Pilih minimal 1 tiket.']);
            }

            // Create order
            $order = Order::create([
                'user_id'      => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'subtotal'     => $subtotal,
                'tax'          => 0,
                'discount'     => 0,
                'total'        => $subtotal,
                'status'       => 'pending',
                'notes'        => 'Pembayaran belum di bayar',
                'expired_at'   => now()->addHours(24),
            ]);

            foreach ($items as $item) {

                $order->orderItems()->create([
                    'ticket_type_id' => $item['ticket_type']->id,
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['subtotal'],
                ]);
            }

            // Get Midtrans snap token
            $snapToken = $this->midtrans->createSnapToken($order);

            // Simpan snap token di session
            session(['snap_token' => $snapToken, 'order_id' => $order->id]);

            DB::commit();

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'order_id'   => $order->id,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        // Midtrans webhook notification
        $serverKey  = config('services.midtrans.server_key');
        $orderId    = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        // Verify signature
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType       = $request->payment_type;

        if (in_array($transactionStatus, ['capture', 'settlement'])) {

            try {

                $this->handleSuccessPayment(
                    $order,
                    $request,
                    $paymentType
                );
            } catch (\Throwable $e) {

                Log::error($e);

                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny') {
            $order->update(['status' => 'cancelled']);
        } elseif ($transactionStatus === 'expire') {
            $order->update(['status' => 'expired']);
        }

        return response()->json(['message' => 'OK']);
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $order   = Order::where('order_number', $orderId)
            ->where('user_id', Auth::id())
            ->with(['orderItems.tickets'])
            ->firstOrFail();

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Pembayaran berhasil! Tiket Anda sudah siap.');
    }

    private function handleSuccessPayment(
        Order $order,
        Request $request,
        string $paymentType
    ): void {

        DB::transaction(function () use ($order, $request, $paymentType) {

            $order = Order::where('id', $order->id)
                ->lockForUpdate()
                ->first();

            // Jika sudah pernah diproses
            if ($order->status === 'paid') {
                return;
            }

            $method = PaymentMethod::where('type', $paymentType)
                ->where('is_active', true)
                ->first();

            if (!$method) {
                throw new \Exception("Payment method {$paymentType} tidak ditemukan.");
            }

            $fixedFee = $method->fee;

            $percentFee = (
                $order->total * $method->fee_percent
            ) / 100;

            $totalFee = $fixedFee + $percentFee;

            $totalPaid = $order->total + $totalFee;

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method_id' => $method->id,
                    'payment_code'      => 'MID-' . strtoupper(Str::random(10)),
                    'amount'            => $order->total,
                    'fee'               => $totalFee,
                    'total_paid'        => $totalPaid,
                    'status'            => 'verified',
                    'verified_at'       => now(),
                    'notes'             => 'Midtrans: ' . $request->transaction_id,
                ]
            );

            $order->update([
                'status' => 'paid',
                'notes'  => 'Pembayaran berhasil melalui Midtrans pada '
                    . now()->format('d M Y H:i'),
            ]);

            $order->load([
                'user',
                'orderItems.ticketType',
                'orderItems.tickets'
            ]);

            // Double protection
            if ($order->orderItems->flatMap->tickets->isNotEmpty()) {
                return;
            }

            app(TicketService::class)
                ->generateTickets($order);
        });
    }
}
