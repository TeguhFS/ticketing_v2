<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\FieldOfficer;
use App\Models\Ticket;
use App\Models\TicketValidation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Tambahkan whereHas('event') agar petugas tanpa event tidak ikut ditarik
        $officers = FieldOfficer::with('event.ticketTypes')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->whereHas('event') // <── Tambahkan pengunci keamanan ini
            ->get();

        // Stats per event yang ditugaskan
        $eventStats = $officers->map(function ($officer) use ($user) {
            $event = $officer->event;

            // Sekarang baris di bawah ini 100% aman dari error null
            $totalQuota   = $event->ticketTypes->sum('quota');
            $totalSold    = $event->ticketTypes->sum('sold');

            $totalScanned = TicketValidation::where('validated_by', $user->id)
                ->whereHas('ticket.ticketType', fn($q) => $q->where('event_id', $event->id))
                ->where('status', 'valid')
                ->count();

            $totalActive = Ticket::whereHas('ticketType', fn($q) => $q->where('event_id', $event->id))
                ->where('status', 'active')
                ->count();

            return [
                'officer'       => $officer,
                'event'         => $event,
                'total_quota'   => $totalQuota,
                'total_sold'    => $totalSold,
                'total_scanned' => $totalScanned,
                'total_active'  => $totalActive,
                'total_used'    => Ticket::whereHas('ticketType', fn($q) => $q->where('event_id', $event->id))
                    ->where('status', 'used')->count(),
            ];
        });

        // Stats hari ini
        $todayScan = TicketValidation::where('validated_by', $user->id)
            ->whereDate('validated_at', today())
            ->count();

        $totalScanAll = TicketValidation::where('validated_by', $user->id)->count();

        $recentScans = TicketValidation::where('validated_by', $user->id)
            ->with('ticket.ticketType.event')
            ->latest('validated_at')
            ->take(5)
            ->get();

        return view('officer.dashboard', compact(
            'user',
            'officers',
            'eventStats',
            'todayScan',
            'totalScanAll',
            'recentScans'
        ));
    }
}
