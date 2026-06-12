<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckExpiredOrder
{
    public function handle(Request $request, Closure $next): Response
    {
        // Auto expire order yang sudah melewati waktu saat user akses
        if (Auth::check()) {
            Order::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->where('expired_at', '<=', now())
                ->whereNotNull('expired_at')
                ->each(function ($order) {
                    $order->update(['status' => 'expired']);

                    if ($order->payment?->status === 'pending') {
                        $order->payment->update(['status' => 'expired']);
                    }

                    // Kembalikan kuota
                    foreach ($order->orderItems as $item) {
                        $item->ticketType->decrement('sold', $item->quantity);
                    }
                });
        }

        return $next($request);
    }
}
