<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RefundRequestedNotification extends Notification
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
            'type'    => 'refund_requested',
            'icon'    => 'ti-refresh',
            'color'   => 'text-amber-500',
            'bg'      => 'bg-amber-50',
            'title'   => 'Pengajuan Refund Baru',
            'message' => "{$this->refund->user->name} mengajukan refund",
            'amount'  => $this->refund->amount,
            'url'     => route('admin.refunds.show', $this->refund),
            'meta'    => [
                'refund_number' => $this->refund->refund_number,
                'user_name'     => $this->refund->user->name,
            ],
        ];
    }
}
