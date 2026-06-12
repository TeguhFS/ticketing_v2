<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RefundApprovedNotification extends Notification
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
            'type'    => 'refund_approved',
            'icon'    => 'ti-circle-check',
            'color'   => 'text-emerald-500',
            'bg'      => 'bg-emerald-50',
            'title'   => 'Refund Disetujui',
            'message' => "Refund {$this->refund->refund_number} telah disetujui",
            'amount'  => $this->refund->amount,
            'url'     => route('admin.refunds.show', $this->refund),
            'meta'    => [
                'refund_number' => $this->refund->refund_number,
                'user_name'     => $this->refund->user->name,
            ],
        ];
    }
}
