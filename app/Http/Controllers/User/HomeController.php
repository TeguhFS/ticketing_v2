<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Blog;
use App\Models\Ticket;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Categories dengan jumlah event
        $categories = Category::withCount([
            'events' => fn($q) =>
            $q->where('status', 'published')
        ])
            ->where('is_active', true)
            ->orderByDesc('events_count')
            ->take(8)
            ->get();

        // Featured events
        $featuredEvents = Event::with(['category', 'ticketTypes'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(6)
            ->get();

        // Latest events
        $latestEvents = Event::with(['category', 'ticketTypes'])
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(8)
            ->get();

        // Latest blogs
        $latestBlogs = Blog::with('author')
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // Stats
        $stats = [
            'events'  => Event::where('status', 'published')->count(),
            'tickets' => Ticket::where('status', 'active')->count(),
            'users'   => User::where('role', 'user')->count(),
        ];

        return view('user.home', compact(
            'categories',
            'featuredEvents',
            'latestEvents',
            'latestBlogs',
            'stats',
        ));
    }
}
