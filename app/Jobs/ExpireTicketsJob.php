<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\Event; // Tambahkan import Model Event di atas
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function handle(): void
    {
        try {
            $expiredTickets = Ticket::where('status', 'active')
                ->whereHas('ticketType.event', function ($query) {
                    $query->where('start_date', '<', now())
                        ->whereIn('status', ['published', 'completed']);
                })
                ->with(['ticketType.event'])
                ->get();

            $ticketCount = 0;

            foreach ($expiredTickets as $ticket) {
                $ticket->update(['status' => 'expired']);

                $ticketCount++;

                Log::info('Ticket expired', [
                    'ticket_id'   => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'event_title' => $ticket->ticketType->event->title ?? '-',
                    'event_date'  => $ticket->ticketType->event->start_date ?? '-',
                ]);
            }

            $completedEvents = Event::where('status', 'published')
                ->where('end_date', '<', now())
                ->get();

            $eventCount = 0;

            foreach ($completedEvents as $event) {
                $event->update(['status' => 'completed']);

                $eventCount++;

                Log::info('Event completed', [
                    'event_id'    => $event->id,
                    'event_title' => $event->title,
                    'end_date'    => $event->end_date,
                ]);
            }

            Log::info("ExpireTicketsJob: {$ticketCount} ticket(s) expired and {$eventCount} event(s) completed successfully.");
        } catch (\Exception $e) {
            Log::error('ExpireTicketsJob failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
