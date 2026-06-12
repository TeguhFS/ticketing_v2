<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'order_paid',
            'icon'    => 'ti-circle-check',
            'color'   => 'text-emerald-500',
            'bg'      => 'bg-emerald-50',
            'title'   => 'Pembayaran Berhasil',
            'message' => "{$this->order->user->name} menyelesaikan pembayaran",
            'amount'  => $this->order->total,
            'url'     => route('admin.orders.show', $this->order, false),
            'meta'    => [
                'order_number' => $this->order->order_number,
                'user_name'    => $this->order->user->name,
            ],
        ];
    }
}
