<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RefundRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Refund $refund) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'refund_rejected',
            'icon'    => 'ti-circle-x',
            'color'   => 'text-red-500',
            'bg'      => 'bg-red-50',
            'title'   => 'Refund Ditolak',
            'message' => "Refund {$this->refund->refund_number} ditolak",
            'amount'  => $this->refund->amount,
            'url'     => route('admin.refunds.show', $this->refund),
            'meta'    => [
                'refund_number' => $this->refund->refund_number,
                'user_name'     => $this->refund->user->name,
            ],
        ];
    }
}
