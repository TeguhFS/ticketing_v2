<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with([
            'ticketType.event',
            'user',
            'orderItem.order',
            'validations',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_code', 'like', '%' . $request->search . '%')
                    ->orWhere('holder_name', 'like', '%' . $request->search . '%')
                    ->orWhere('holder_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('event_id')) {
            $query->whereHas('ticketType', fn($q) => $q->where('event_id', $request->event_id));
        }

        $tickets = $query->paginate(15)->withQueryString();

        $events = Event::where('status', 'published')->orderBy('title')->get();

        $stats = [
            'total'     => Ticket::count(),
            'active'    => Ticket::where('status', 'active')->count(),
            'used'      => Ticket::where('status', 'used')->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->count(),
            'expired' => Ticket::where('status', 'expired')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'events', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'ticketType.event',
            'user',
            'orderItem.order',
            'validations.officer',
        ]);

        return view('admin.tickets.show', compact('ticket'));
    }
}
