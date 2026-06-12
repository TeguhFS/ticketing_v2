<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    public function createSnapToken(Order $order): string
    {
        $order->load(['user', 'orderItems.ticketType.event']);

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
                'phone'      => $order->user->phone ?? '',
            ],
            'item_details' => $order->orderItems->map(function ($item) {
                return [
                    'id'       => $item->ticketType->id,
                    'price'    => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name'     => $item->ticketType->event->title . ' — ' . $item->ticketType->name,
                ];
            })->toArray(),
            'callbacks' => [
                'finish' => route('checkout.finish'),
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
