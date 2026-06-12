<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'ticketTypes'])
            ->where('status', 'published')
            ->where('start_date', '>=', now());

        // Filter search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter featured
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Filter harga
        if ($request->filled('price')) {
            match ($request->price) {
                'free'     => $query->whereHas('ticketTypes', fn($q) => $q->where('price', 0)),
                'paid'     => $query->whereHas('ticketTypes', fn($q) => $q->where('price', '>', 0)),
                'under100' => $query->whereHas('ticketTypes', fn($q) => $q->where('price', '<=', 100000)),
                'under500' => $query->whereHas('ticketTypes', fn($q) => $q->where('price', '<=', 500000)),
                default    => null,
            };
        }

        // Filter tanggal
        if ($request->filled('date')) {
            match ($request->date) {
                'today'    => $query->whereDate('start_date', today()),
                'tomorrow' => $query->whereDate('start_date', today()->addDay()),
                'week'     => $query->whereBetween('start_date', [now(), now()->endOfWeek()]),
                'month'    => $query->whereMonth('start_date', now()->month),
                default    => null,
            };
        }

        // Sort
        match ($request->get('sort', 'nearest')) {
            'nearest'  => $query->orderBy('start_date'),
            'popular'  => $query->withCount('ticketTypes')->orderByDesc('ticket_types_count'),
            'latest'   => $query->latest(),
            'cheapest' => $query->orderBy(
                \App\Models\TicketType::select('price')
                    ->whereColumn('event_id', 'events.id')
                    ->orderBy('price')
                    ->limit(1)
            ),
            default    => $query->orderBy('start_date'),
        };

        $events     = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)
            ->withCount(['events' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('events_count')
            ->get();

        // Total count tanpa filter
        $totalEvents = Event::where('status', 'published')
            ->where('start_date', '>=', now())
            ->count();

        return view('user.events.index', compact(
            'events',
            'categories',
            'totalEvents'
        ));
    }

    public function show(Event $event)
    {
        // Pastikan event published
        abort_if($event->status !== 'published', 404);

        $event->load(['category', 'ticketTypes', 'admin']);

        // Related events
        $relatedEvents = Event::with(['category', 'ticketTypes'])
            ->where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('category_id', $event->category_id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(4)
            ->get();

        return view('user.events.show', compact('event', 'relatedEvents'));
    }
}
