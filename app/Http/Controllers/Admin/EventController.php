<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'ticketTypes'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $events     = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'     => Event::count(),
            'published' => Event::where('status', 'published')->count(),
            'draft'     => Event::where('status', 'draft')->count(),
            'cancelled' => Event::where('status', 'cancelled')->count(),
            'completed' => Event::where('status', 'completed')->count(),
        ];

        return view('admin.events.index', compact('events', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'description'     => 'required|string',
            'location'        => 'required|string|max:255',
            'location_detail' => 'nullable|string',
            'maps_url'        => 'nullable|url',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'status'          => 'required|in:draft,published,cancelled,completed',
            'is_featured'     => 'boolean',
            'max_attendees'   => 'nullable|integer|min:1',
            'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            // Ticket Types
            'ticket_types'                => 'nullable|array',
            'ticket_types.*.name'         => 'required|string|max:100',
            'ticket_types.*.price'        => 'required|numeric|min:0',
            'ticket_types.*.quota'        => 'required|integer|min:1',
            'ticket_types.*.max_per_order' => 'required|integer|min:1',
            'ticket_types.*.sale_start'   => 'nullable|date',
            'ticket_types.*.sale_end'     => 'nullable|date',
            'ticket_types.*.description'  => 'nullable|string',
        ]);

        // Upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        // Upload banner
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('events/banners', 'public');
        }

        $event = Event::create([
            'user_id'         => Auth::id(),
            'category_id'     => $validated['category_id'],
            'title'           => $validated['title'],
            'slug'            => Str::slug($validated['title']) . '-' . Str::random(5),
            'description'     => $validated['description'],
            'location'        => $validated['location'],
            'location_detail' => $validated['location_detail'] ?? null,
            'maps_url'        => $validated['maps_url'] ?? null,
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
            'status'          => $validated['status'],
            'is_featured'     => $request->boolean('is_featured'),
            'max_attendees'   => $validated['max_attendees'] ?? null,
            'thumbnail'       => $thumbnailPath,
            'banner'          => $bannerPath,
        ]);

        // Simpan Ticket Types
        if (!empty($validated['ticket_types'])) {
            foreach ($validated['ticket_types'] as $type) {
                $event->ticketTypes()->create([
                    'name'          => $type['name'],
                    'description'   => $type['description'] ?? null,
                    'price'         => $type['price'],
                    'quota'         => $type['quota'],
                    'max_per_order' => $type['max_per_order'],
                    'sale_start'    => $type['sale_start'] ?? null,
                    'sale_end'      => $type['sale_end'] ?? null,
                    'is_active'     => true,
                ]);
            }
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event berhasil dibuat!');
    }

    public function show(Event $event)
    {
        $event->load(['category', 'admin', 'ticketTypes', 'fieldOfficers.user']);

        $totalTicketsSold = $event->ticketTypes->sum('sold');
        $totalQuota       = $event->ticketTypes->sum('quota');
        $totalRevenue     = $event->ticketTypes->sum(fn($t) => $t->sold * $t->price);

        return view('admin.events.show', compact(
            'event',
            'totalTicketsSold',
            'totalQuota',
            'totalRevenue',
        ));
    }

    public function edit(Event $event)
    {
        $event->load('ticketTypes');
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'description'     => 'required|string',
            'location'        => 'required|string|max:255',
            'location_detail' => 'nullable|string',
            'maps_url'        => 'nullable|url',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'status'          => 'required|in:draft,published,cancelled,completed',
            'is_featured'     => 'boolean',
            'max_attendees'   => 'nullable|integer|min:1',
            'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'ticket_types'                   => 'nullable|array',
            'ticket_types.*.id'              => 'nullable|exists:ticket_types,id',
            'ticket_types.*.name'            => 'required|string|max:100',
            'ticket_types.*.price'           => 'required|numeric|min:0',
            'ticket_types.*.quota'           => 'required|integer|min:1',
            'ticket_types.*.max_per_order'   => 'required|integer|min:1',
            'ticket_types.*.sale_start'      => 'nullable|date',
            'ticket_types.*.sale_end'        => 'nullable|date',
            'ticket_types.*.description'     => 'nullable|string',
            'ticket_types.*.is_active'       => 'boolean',
        ]);

        // Upload thumbnail baru
        $thumbnailPath = $event->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($event->thumbnail) Storage::disk('public')->delete($event->thumbnail);
            $thumbnailPath = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        // Upload banner baru
        $bannerPath = $event->banner;
        if ($request->hasFile('banner')) {
            if ($event->banner) Storage::disk('public')->delete($event->banner);
            $bannerPath = $request->file('banner')->store('events/banners', 'public');
        }

        $event->update([
            'category_id'     => $validated['category_id'],
            'title'           => $validated['title'],
            'slug'            => Str::slug($validated['title']) . '-' . Str::random(5),
            'description'     => $validated['description'],
            'location'        => $validated['location'],
            'location_detail' => $validated['location_detail'] ?? null,
            'maps_url'        => $validated['maps_url'] ?? null,
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
            'status'          => $validated['status'],
            'is_featured'     => $request->boolean('is_featured'),
            'max_attendees'   => $validated['max_attendees'] ?? null,
            'thumbnail'       => $thumbnailPath,
            'banner'          => $bannerPath,
        ]);

        // Update Ticket Types
        $existingIds = [];
        if (!empty($validated['ticket_types'])) {
            foreach ($validated['ticket_types'] as $type) {
                if (!empty($type['id'])) {
                    // Update existing
                    TicketType::where('id', $type['id'])->update([
                        'name'          => $type['name'],
                        'description'   => $type['description'] ?? null,
                        'price'         => $type['price'],
                        'quota'         => $type['quota'],
                        'max_per_order' => $type['max_per_order'],
                        'sale_start'    => $type['sale_start'] ?? null,
                        'sale_end'      => $type['sale_end'] ?? null,
                        'is_active'     => $type['is_active'] ?? true,
                    ]);
                    $existingIds[] = $type['id'];
                } else {
                    // Create new
                    $newType = $event->ticketTypes()->create([
                        'name'          => $type['name'],
                        'description'   => $type['description'] ?? null,
                        'price'         => $type['price'],
                        'quota'         => $type['quota'],
                        'max_per_order' => $type['max_per_order'],
                        'sale_start'    => $type['sale_start'] ?? null,
                        'sale_end'      => $type['sale_end'] ?? null,
                        'is_active'     => true,
                    ]);
                    $existingIds[] = $newType->id;
                }
            }
        }

        // Hapus ticket type yang dihapus dari form
        $event->ticketTypes()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        if ($event->thumbnail) Storage::disk('public')->delete($event->thumbnail);
        if ($event->banner) Storage::disk('public')->delete($event->banner);

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
