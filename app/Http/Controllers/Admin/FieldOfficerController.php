<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldOfficer;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FieldOfficerController extends Controller
{
    public function index(Request $request)
    {
        $query = FieldOfficer::with(['user', 'event'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('officer_code', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'user',
                        fn($u) =>
                        $u->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                    );
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $officers = $query->paginate(15)->withQueryString();

        $events = Event::where('status', 'published')
            ->orderBy('start_date', 'desc')
            ->get();

        $availableUsers = User::whereIn('role', ['field_officer'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $stats = [
            'total'       => FieldOfficer::count(),
            'active'      => FieldOfficer::where('is_active', true)->count(),
            'scanner'     => FieldOfficer::where('role', 'scanner')->count(),
            'coordinator' => FieldOfficer::where('role', 'coordinator')->count(),
            'security'    => FieldOfficer::where('role', 'security')->count(),
        ];

        return view('admin.officers.index', compact(
            'officers',
            'events',
            'availableUsers',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'role'     => 'required|in:scanner,coordinator,security',
            'is_active' => 'boolean',
        ]);

        // Cek apakah user sudah terdaftar di event yang sama
        $exists = FieldOfficer::where('user_id', $request->user_id)
            ->where('event_id', $request->event_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'User ini sudah terdaftar sebagai petugas di event tersebut!');
        }

        // Generate officer code
        $officerCode = 'OFF-' . strtoupper(Str::random(8));

        FieldOfficer::create([
            'user_id'      => $request->user_id,
            'event_id'     => $request->event_id,
            'officer_code' => $officerCode,
            'role'         => $request->role,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.officers.index')
            ->with('success', 'Field Officer berhasil ditambahkan!');
    }

    public function show(FieldOfficer $officer)
    {
        $officer->load(['user', 'event.ticketTypes']);

        $validationStats = [
            'total'        => $officer->ticketValidations()->count(),
            'valid'        => $officer->ticketValidations()->where('status', 'valid')->count(),
            'invalid'      => $officer->ticketValidations()->where('status', 'invalid')->count(),
            'already_used' => $officer->ticketValidations()->where('status', 'already_used')->count(),
        ];

        $recentValidations = $officer->ticketValidations()
            ->with('ticket.ticketType.event')
            ->latest('validated_at')
            ->take(10)
            ->get();

        return view('admin.officers.show', compact(
            'officer',
            'validationStats',
            'recentValidations'
        ));
    }

    public function update(Request $request, FieldOfficer $officer)
    {
        $request->validate([
            'role'      => 'required|in:scanner,coordinator,security',
            'is_active' => 'boolean',
        ]);

        $officer->update([
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Field Officer berhasil diperbarui!');
    }

    public function destroy(FieldOfficer $officer)
    {
        $officer->delete();

        return redirect()->route('admin.officers.index')
            ->with('success', 'Field Officer berhasil dihapus.');
    }
}
