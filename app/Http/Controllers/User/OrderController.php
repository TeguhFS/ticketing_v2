<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans
    ) {}

    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['orderItems.ticketType.event', 'payment'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Order::where('user_id', Auth::id())->count(),
            'pending'   => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'paid'      => Order::where('user_id', Auth::id())->where('status', 'paid')->count(),
            'cancelled' => Order::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('user.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load([
            'orderItems.ticketType.event',
            'orderItems.tickets',
            'payment.paymentMethod',
        ]);

        // Generate snap token jika order masih pending
        $snapToken = null;
        if ($order->status === 'pending') {
            try {
                $snapToken = $this->midtrans->createSnapToken($order);
            } catch (\Exception $e) {
                Log::error('Failed to generate snap token: ' . $e->getMessage());
            }
        }

        return view('user.orders.show', compact('order', 'snapToken'));
    }

    public function cancel(Request $request, Order $order)
    {
        // Validasi kepemilikan
        abort_if($order->user_id !== Auth::id(), 403);

        // Validasi: hanya order pending yang bisa dicancel
        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya order dengan status pending yang dapat dibatalkan.');
        }

        // Validasi: order belum expired
        if ($order->expired_at && now()->gt($order->expired_at)) {
            return back()->with('error', 'Order ini sudah expired dan tidak dapat dibatalkan.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|min:10|max:500',
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
            'cancel_reason.min'      => 'Alasan minimal 10 karakter.',
        ]);

        DB::beginTransaction();

        try {
            // Update order status ke cancelled
            $order->update([
                'status' => 'cancelled',
                'notes'  => 'Dibatalkan oleh user: ' . $request->cancel_reason,
            ]);

            // Update payment ke cancelled jika ada
            if ($order->payment && $order->payment->status === 'pending') {
                $order->payment->update(['status' => 'expired']);
            }

            // Kembalikan kuota tiket
            foreach ($order->orderItems as $item) {
                if ($item->sold > 0 || $item->ticketType->sold > 0) {
                    $item->ticketType->decrement('sold', $item->quantity);
                }
            }

            DB::commit();

            Log::info('Order cancelled by user', [
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'user_id'      => Auth::id(),
                'reason'       => $request->cancel_reason,
            ]);

            return redirect()->route('user.orders')
                ->with('success', 'Order berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order cancel failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
