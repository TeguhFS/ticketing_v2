<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\User;
use App\Mail\EventCompletedMail; // Siapkan kelas Mail ini jika dibutuhkan nanti
use App\Notifications\EventCompletedNotification; // Siapkan kelas Notification jika dibutuhkan nanti
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EventObserver
{
    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Pastikan hanya berjalan jika kolom 'status' yang berubah
        if (!$event->isDirty('status')) return;

        // Ambil data user/creator yang membuat event
        $event->load('admin');

        match ($event->status) {
            'completed' => $this->handleCompleted($event),
            'cancelled' => $this->handleCancelled($event),
            default => null,
        };
    }

    /**
     * Logika penanganan saat event selesai (completed)
     */
    private function handleCompleted(Event $event): void
    {
        Log::info("EventObserver: Event '{$event->title}' telah selesai.");

        // 1. Kirim Email ke Creator/Admin yang membuat event (jika class mail sudah ada)
        if ($event->admin?->email) {
            // Mail::to($event->admin->email)->queue(new EventCompletedMail($event));
        }

        // 2. Kirim Notifikasi ke Super Admin sistem
        $this->notifySuperAdmins(new EventCompletedNotification($event));
    }

    /**
     * Logika penanganan saat event dibatalkan (cancelled)
     */
    private function handleCancelled(Event $event): void
    {
        Log::warning("EventObserver: Event '{$event->title}' telah dibatalkan.");
        // Tempat menaruh logic tambahan jika event dibatalkan (misal refund otomatis massal)
    }

    /**
     * Helper untuk mengirim notifikasi ke semua user ber-role admin
     */
    private function notifySuperAdmins($notification): void
    {
        User::where('role', 'admin')->each(fn($admin) => $admin->notify($notification));
    }
}
