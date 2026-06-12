<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([
                'orders'   => [],
                'payments' => [],
                'tickets'  => [],
                'events'   => [],
                'users'    => [],
                'total'    => 0,
            ]);
        }

        // ── Orders ────────────────────────────────────────────
        $orders = Order::with(['user', 'orderItems.ticketType.event'])
            ->where(function ($query) use ($q) {
                $query->where('order_number', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%")
                    ->orWhereHas(
                        'user',
                        fn($u) =>
                        $u->where('name',  'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                    );
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($order) => [
                'title'    => $order->order_number,
                'subtitle' => $order->user->name . ' · Rp ' . number_format($order->total, 0, ',', '.'),
                'status'   => $order->status,
                'url'      => route('admin.orders.show', $order),
            ]);

        // ── Payments ──────────────────────────────────────────
        $payments = Payment::with(['order.user', 'paymentMethod'])
            ->where(function ($query) use ($q) {
                $query->where('payment_code', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%")
                    ->orWhereHas(
                        'order.user',
                        fn($u) =>
                        $u->where('name',  'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                    );
            })
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($payment) => [
                'title'    => $payment->payment_code,
                'subtitle' => ($payment->order->user->name ?? '-') . ' · ' . ($payment->paymentMethod->name ?? 'Midtrans'),
                'status'   => $payment->status,
                'url'      => route('admin.payments.show', $payment),
            ]);

        // ── Tickets ───────────────────────────────────────────
        $tickets = Ticket::with(['ticketType.event', 'user'])
            ->where(function ($query) use ($q) {
                $query->where('ticket_code',  'like', "%{$q}%")
                    ->orWhere('holder_name', 'like', "%{$q}%")
                    ->orWhere('holder_email', 'like', "%{$q}%")
                    ->orWhereHas(
                        'ticketType.event',
                        fn($e) =>
                        $e->where('title', 'like', "%{$q}%")
                    );
            })
            ->latest()
            ->take(4)
            ->get()
            ->map(fn($ticket) => [
                'title'    => $ticket->ticket_code,
                'subtitle' => ($ticket->ticketType->event->title ?? '-') . ' · ' . $ticket->holder_name,
                'status'   => $ticket->status,
                'url'      => route('admin.tickets.show', $ticket),
            ]);

        // ── Events ────────────────────────────────────────────
        $events = Event::with('category')
            ->where(function ($query) use ($q) {
                $query->where('title',    'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhereHas(
                        'category',
                        fn($c) =>
                        $c->where('name', 'like', "%{$q}%")
                    );
            })
            ->latest()
            ->take(4)
            ->get()
            ->map(fn($event) => [
                'title'    => $event->title,
                'subtitle' => $event->location . ' · ' . $event->start_date->format('d M Y'),
                'status'   => $event->status,
                'url'      => route('admin.events.show', $event),
            ]);

        // ── Users ─────────────────────────────────────────────
        $users = User::where(function ($query) use ($q) {
            $query->where('name',  'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%");
        })
            ->latest()
            ->take(4)
            ->get()
            ->map(fn($user) => [
                'title'    => $user->name,
                'subtitle' => $user->email . ' · ' . ucfirst($user->role),
                'avatar'   => strtoupper(substr($user->name, 0, 2)),
                'status'   => $user->role,
                'url'      => route('admin.users.show', $user),
            ]);

        $total = $orders->count()
            + $payments->count()
            + $tickets->count()
            + $events->count()
            + $users->count();

        return response()->json([
            'orders'   => $orders,
            'payments' => $payments,
            'tickets'  => $tickets,
            'events'   => $events,
            'users'    => $users,
            'total'    => $total,
            'query'    => $q,
        ]);
    }
}
