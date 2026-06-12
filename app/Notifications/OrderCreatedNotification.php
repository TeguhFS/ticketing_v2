<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
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
            'type'    => 'order_created',
            'icon'    => 'ti-shopping-cart',
            'color'   => 'text-blue-500',
            'bg'      => 'bg-blue-50',
            'title'   => 'Order Baru',
            'message' => "{$this->order->user->name} membuat order baru",
            'amount'  => $this->order->total,
            'url'     => route('admin.orders.show', $this->order),
            'meta'    => [
                'order_number' => $this->order->order_number,
                'user_name'    => $this->order->user->name,
            ],
        ];
    }
}
