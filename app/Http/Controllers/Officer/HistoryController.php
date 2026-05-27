<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\TicketValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = TicketValidation::where('validated_by', $user->id)
            ->with('ticket.ticketType.event')
            ->latest('validated_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('ticket_code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('validated_at', $request->date);
        }

        $validations = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => TicketValidation::where('validated_by', $user->id)->count(),
            'valid'        => TicketValidation::where('validated_by', $user->id)->where('status', 'valid')->count(),
            'invalid'      => TicketValidation::where('validated_by', $user->id)->where('status', 'invalid')->count(),
            'already_used' => TicketValidation::where('validated_by', $user->id)->where('status', 'already_used')->count(),
            'today'        => TicketValidation::where('validated_by', $user->id)->whereDate('validated_at', today())->count(),
        ];

        return view('officer.history', compact('validations', 'stats'));
    }
}
