<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\RefundRequestedNotification;
use App\Mail\RefundRequestedMail;
use Illuminate\Support\Facades\Mail;


class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::where('user_id', Auth::id())
            ->with(['order.orderItems.ticketType.event'])
            ->latest()
            ->paginate(10);

        return view('user.refunds.index', compact('refunds'));
    }

    public function create(Order $order)
    {
        // Validasi: order harus milik user ini
        abort_if($order->user_id !== Auth::id(), 403);

        // Validasi: order harus paid
        abort_if($order->status !== 'paid', 403, 'Hanya order yang sudah dibayar yang bisa direfund.');

        // Validasi: belum ada refund request
        abort_if($order->hasRefundRequest(), 403, 'Refund request sudah pernah diajukan.');

        // Validasi: event belum dimulai (refund hanya bisa sebelum event)
        $eventStarted = $order->orderItems->every(function ($item) {
            return $item->ticketType->event->start_date->lt(now());
        });

        abort_if($eventStarted, 403, 'Refund tidak dapat diajukan setelah event dimulai.');

        $order->load(['orderItems.ticketType.event', 'payment']);

        return view('user.refunds.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'paid', 403);
        abort_if($order->hasRefundRequest(), 403);

        $request->validate([
            'reason'         => 'required|string|min:20|max:1000',
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:100',
        ], [
            'reason.required'         => 'Alasan refund wajib diisi.',
            'reason.min'              => 'Alasan minimal 20 karakter.',
            'bank_name.required'      => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_name.required'   => 'Nama pemilik rekening wajib diisi.',
        ]);

        $refund = Refund::create([
            'order_id'       => $order->id,
            'user_id'        => Auth::id(),
            'refund_number'  => 'RFD-' . strtoupper(Str::random(10)),
            'amount'         => $order->total,
            'status'         => 'pending',
            'reason'         => $request->reason,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
        ]);

        Mail::to($order->user->email)
            ->queue(new RefundRequestedMail($refund));

        User::where('role', 'admin')
            ->each(function ($admin) use ($refund) {
                $admin->notify(new RefundRequestedNotification($refund));
            });

        return redirect()->route('user.refunds.index')
            ->with('success', 'Pengajuan refund berhasil dikirim! Tim kami akan memprosesnya dalam 3-5 hari kerja.');
    }

    public function show(Refund $refund)
    {
        abort_if($refund->user_id !== Auth::id(), 403);

        $refund->load([
            'order.orderItems.ticketType.event',
            'order.payment',
            'reviewedBy',
        ]);

        return view('user.refunds.show', compact('refund'));
    }
}
