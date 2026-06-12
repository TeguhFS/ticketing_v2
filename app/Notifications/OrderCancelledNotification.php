<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
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
            'type'    => 'order_cancelled',
            'icon'    => 'ti-circle-x',
            'color'   => 'text-red-500',
            'bg'      => 'bg-red-50',
            'title'   => 'Order Dibatalkan',
            'message' => "{$this->order->user->name} membatalkan order",
            'amount'  => $this->order->total,
            'url'     => route('admin.orders.show', $this->order),
            'meta'    => [
                'order_number' => $this->order->order_number,
                'user_name'    => $this->order->user->name,
            ],
        ];
    }
}
