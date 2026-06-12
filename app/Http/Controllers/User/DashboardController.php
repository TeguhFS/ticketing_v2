<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Stats
        $stats = [
            'total_orders'  => Order::where('user_id', $user->id)->count(),
            'total_tickets' => Ticket::where('user_id', $user->id)->count(),
            'active_tickets' => Ticket::where('user_id', $user->id)->where('status', 'active')->count(),
            'used_tickets'  => Ticket::where('user_id', $user->id)->where('status', 'used')->count(),
            'total_spent'   => Order::where('user_id', $user->id)->where('status', 'paid')->sum('total'),
        ];

        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['orderItems.ticketType.event', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming tickets
        $upcomingTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['ticketType.event'])
            ->whereHas(
                'ticketType.event',
                fn($q) =>
                $q->where('start_date', '>=', now())
                    ->orderBy('start_date')
            )
            ->take(3)
            ->get();

        // Recommended events
        $recommendedEvents = Event::with(['category', 'ticketTypes'])
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->where('is_featured', true)
            ->take(4)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'stats',
            'recentOrders',
            'upcomingTickets',
            'recommendedEvents',
        ));
    }
}
