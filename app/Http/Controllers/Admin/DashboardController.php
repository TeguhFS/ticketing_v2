<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Refund;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalEvents    = Event::count();
        $totalUsers     = User::where('role', 'user')->count();
        $totalTickets   = Ticket::count();
        $totalRevenue = Payment::where('status', 'verified')
            ->whereHas('order', function ($query) {
                $query->whereNotIn('status', ['refunded', 'cancelled']);
            })
            ->sum('total_paid');

        // Pending
        $pendingOrders   = Order::where('status', 'pending')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Chart data - pendapatan 6 bulan terakhir
        $revenueChart = Payment::where('status', 'verified')
            ->whereHas('order', function ($query) {
                $query->whereNotIn('status', ['refunded', 'cancelled']);
            })
            ->selectRaw('MONTH(verified_at) as month, SUM(total_paid) as total')
            ->whereYear('verified_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = [];
        $revenues = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->translatedFormat('M');
            $revenues[] = $revenueChart->get($month->month)?->total ?? 0;
        }

        // Recent orders
        $recentOrders = Order::with(['user', 'orderItems.ticketType.event'])
            ->latest()
            ->take(8)
            ->get();

        // Upcoming events
        $upcomingEvents = Event::with('category')
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Expired order
        $expiredOrders  = Order::where('status', 'expired')
            ->whereDate('updated_at', today())
            ->count();

        // Expired ticket
        $expiredTickets = Ticket::where('status', 'expired')
            ->whereDate('updated_at', today())
            ->count();

        // Refund
        $pendingRefunds = Refund::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalUsers',
            'totalTickets',
            'totalRevenue',
            'pendingOrders',
            'pendingPayments',
            'months',
            'revenues',
            'recentOrders',
            'upcomingEvents',
            'expiredOrders',
            'expiredTickets',
            'pendingRefunds'
        ));
    }
}
