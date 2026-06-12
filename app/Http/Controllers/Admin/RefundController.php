<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\RefundApprovedNotification;
use App\Notifications\RefundRejectedNotification;
use App\Mail\RefundApprovedMail;
use App\Mail\RefundRejectedMail;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::with(['order.orderItems.ticketType.event', 'user', 'reviewedBy'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('refund_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'user',
                        fn($u) =>
                        $u->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                    )
                    ->orWhereHas(
                        'order',
                        fn($o) =>
                        $o->where('order_number', 'like', '%' . $request->search . '%')
                    );
            });
        }

        $refunds = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => Refund::count(),
            'pending'  => Refund::where('status', 'pending')->count(),
            'approved' => Refund::where('status', 'approved')->count(),
            'rejected' => Refund::where('status', 'rejected')->count(),
            'amount'   => Refund::where('status', 'approved')->sum('amount'),
        ];

        return view('admin.refunds.index', compact('refunds', 'stats'));
    }

    public function show(Refund $refund)
    {
        $refund->load([
            'order.user',
            'order.orderItems.ticketType.event',
            'order.orderItems.tickets',
            'order.payment.paymentMethod',
            'user',
            'reviewedBy',
        ]);

        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(Request $request, Refund $refund)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if (!$refund->isPending()) {
            return back()->with('error', 'Refund ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();

        try {
            // 1. Update refund status
            $refund->update([
                'status'      => 'approved',
                'admin_notes' => $request->admin_notes,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // 2. Update order status ke refunded
            $refund->order->update(['status' => 'refunded']);

            // 3. Update semua tiket dari order ini menjadi invalid
            $refund->order->orderItems->each(function ($item) {
                $item->tickets()->where('status', '!=', 'used')->update(['status' => 'cancelled']);
            });

            // 4. Kembalikan kuota tiket
            $refund->order->orderItems->each(function ($item) {
                // Hitung tiket yang belum digunakan
                $unusedCount = $item->tickets()
                    ->whereIn('status', ['cancelled'])
                    ->count();

                if ($unusedCount > 0) {
                    $item->ticketType->decrement('sold', $unusedCount);
                }
            });

            DB::commit();

            // Notifikasi ke user
            Mail::to($refund->user->email)->queue(new RefundApprovedMail($refund));
            $refund->user->notify(new RefundApprovedNotification($refund));

            Log::info('Refund approved', [
                'refund_id'     => $refund->id,
                'refund_number' => $refund->refund_number,
                'approved_by'   => Auth::id(),
                'amount'        => $refund->amount,
            ]);

            return redirect()->route('admin.refunds.show', $refund)
                ->with('success', 'Refund berhasil diapprove! Tiket telah dibatalkan dan kuota dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund approve failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Refund $refund)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        if (!$refund->isPending()) {
            return back()->with('error', 'Refund ini sudah diproses sebelumnya.');
        }

        $refund->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Notifikasi ke user
        Mail::to($refund->user->email)->queue(new RefundRejectedMail($refund));
        $refund->user->notify(new RefundRejectedNotification($refund));

        Log::info('Refund rejected', [
            'refund_id'     => $refund->id,
            'refund_number' => $refund->refund_number,
            'rejected_by'   => Auth::id(),
            'notes'         => $request->admin_notes,
        ]);

        return redirect()->route('admin.refunds.show', $refund)
            ->with('success', 'Refund berhasil ditolak.');
    }
}
