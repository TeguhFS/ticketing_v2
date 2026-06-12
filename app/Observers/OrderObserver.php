<?php

namespace App\Observers;

use App\Mail\OrderCancelledMail;
use App\Mail\OrderCreatedMail;
use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCancelledNotification;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderPaidNotification;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (empty($order->expired_at)) {
            $order->expired_at = now()->addHours(24);
        }
    }

    public function created(Order $order): void
    {
        $order->load('user');

        // Email ke user
        Mail::to($order->user->email)
            ->queue(new OrderCreatedMail($order));

        // Notifikasi ke admin
        $this->notifyAdmins(new OrderCreatedNotification($order));
    }

    public function updated(Order $order): void
    {
        if (!$order->isDirty('status')) return;

        $order->load('user');

        match ($order->status) {
            'paid' => $this->handlePaid($order),
            'cancelled' => $this->handleCancelled($order),
            default => null,
        };

        if ($order->status === 'paid') {
            $order->updateQuietly(['expired_at' => null]);
        }
    }

    private function handlePaid(Order $order): void
    {
        Mail::to($order->user->email)->queue(new OrderPaidMail($order));
        $this->notifyAdmins(new OrderPaidNotification($order));
    }

    private function handleCancelled(Order $order): void
    {
        Mail::to($order->user->email)->queue(new OrderCancelledMail($order));
        $this->notifyAdmins(new OrderCancelledNotification($order));
    }

    private function notifyAdmins($notification): void
    {
        User::where('role', 'admin')->each(fn($admin) => $admin->notify($notification));
    }
}
