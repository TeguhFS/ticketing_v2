<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id())
            ->with(['ticketType.event', 'orderItem.order'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_code', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'ticketType.event',
                        fn($e) =>
                        $e->where('title', 'like', '%' . $request->search . '%')
                    );
            });
        }

        $tickets = $query->paginate(12)->withQueryString();

        $stats = [
            'total'     => Ticket::where('user_id', Auth::id())->count(),
            'active'    => Ticket::where('user_id', Auth::id())->where('status', 'active')->count(),
            'used'      => Ticket::where('user_id', Auth::id())->where('status', 'used')->count(),
            'cancelled' => Ticket::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('user.tickets.index', compact('tickets', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        abort_if($ticket->user_id !== Auth::id(), 403);

        $ticket->load([
            'ticketType.event.category',
            'orderItem.order.payment.paymentMethod',
            'validations.officer',
        ]);

        return view('user.tickets.show', compact('ticket'));
    }
}
