<?php

namespace App\Mail;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundRequestedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Refund $refund) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pengajuan Refund #{$this->refund->refund_number} Diterima — " . setting('app_name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refunds.requested',
        );
    }
}
