<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventCompletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Event $event) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'event_completed',
            'icon'    => 'ti-calendar-check',
            'color'   => 'text-emerald-500',
            'bg'      => 'bg-emerald-50',
            'title'   => 'Event Telah Selesai',
            'message' => "Event \"{$this->event->title}\" telah berakhir sesuai jadwal.",
            'amount'  => null,
            'url'     => route('admin.events.show', $this->event),
            'meta'    => [
                'event_id'    => $this->event->id,
                'event_title' => $this->event->title,
                'slug'        => $this->event->slug,
                'end_date'    => $this->event->end_date->format('d M Y H:i'),
            ],
        ];
    }
}
