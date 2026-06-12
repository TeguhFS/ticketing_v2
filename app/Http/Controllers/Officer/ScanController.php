<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\FieldOfficer;
use App\Models\Ticket;
use App\Models\TicketValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Perbaikan: Gunakan whereHas agar data officer yang event-nya sudah dihapus TIDAK ikut ketarik
        $events = FieldOfficer::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereHas('event') // Memastikan relasi event-nya ada di database
            ->with('event')
            ->get()
            ->pluck('event')
            ->filter(); // Membuang nilai null jika entah bagaimana masih lolos

        return view('officer.scan', compact('events'));
    }

    public function validate(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
            'event_id'    => 'required|exists:events,id',
        ]);

        $user        = Auth::user();
        $ticketCode  = strtoupper(trim($request->ticket_code));

        // Cek apakah officer ditugaskan di event ini
        $isAssigned = FieldOfficer::where('user_id', $user->id)
            ->where('event_id', $request->event_id)
            ->where('is_active', true)
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak ditugaskan di event ini.',
            ], 403);
        }

        // Cari tiket
        $ticket = Ticket::where('ticket_code', $ticketCode)
            ->whereHas('ticketType', fn($q) => $q->where('event_id', $request->event_id))
            ->with('ticketType.event', 'user')
            ->first();

        if (!$ticket) {
            // Simpan log invalid (menggunakan null atau id aman)
            TicketValidation::create([
                'ticket_id'    => null, // Disarankan pakai null jika schema membolehkan nullable, atau biarkan 0
                'validated_by' => $user->id,
                'ticket_code'  => $ticketCode,
                'status'       => 'invalid',
                'notes'        => 'Kode tiket tidak ditemukan',
                'validated_at' => now(),
            ]);

            return response()->json([
                'status'  => 'invalid',
                'message' => 'Tiket tidak ditemukan atau tidak valid untuk event ini.',
                'code'    => $ticketCode,
            ]);
        }

        // Cek sudah digunakan
        if ($ticket->status === 'used') {
            $lastValidation = $ticket->validations()->where('status', 'valid')->latest()->first();

            TicketValidation::create([
                'ticket_id'    => $ticket->id,
                'validated_by' => $user->id,
                'ticket_code'  => $ticketCode,
                'status'       => 'already_used',
                'notes'        => 'Tiket sudah digunakan sebelumnya',
                'validated_at' => now(),
            ]);

            return response()->json([
                'status'       => 'already_used',
                'message'      => 'Tiket ini sudah digunakan!',
                'code'         => $ticketCode,
                'holder'       => $ticket->holder_name,
                'event'        => $ticket->ticketType->event->title ?? '-',
                'ticket_type'  => $ticket->ticketType->name,
                'used_at'      => $lastValidation?->validated_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i'),
            ]);
        }

        // Cek status tiket
        if ($ticket->status !== 'active') {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Tiket tidak aktif (status: ' . $ticket->status . ')',
                'code'    => $ticketCode,
            ]);
        }

        DB::transaction(function () use ($ticket, $user, $ticketCode) {
            // Valid — update status tiket & simpan validasi
            $ticket->update([
                'status' => 'used'
            ]);

            TicketValidation::create([
                'ticket_id'    => $ticket->id,
                'validated_by' => $user->id,
                'ticket_code'  => $ticketCode,
                'status'       => 'valid',
                'notes'        => 'Tiket berhasil divalidasi',
                'validated_at' => now(),
            ]);
        });

        return response()->json([
            'status'      => 'valid',
            'message'     => 'Tiket valid! Selamat datang.',
            'code'        => $ticketCode,
            'holder'      => $ticket->holder_name,
            'email'       => $ticket->holder_email,
            'event'       => $ticket->ticketType->event->title ?? '-',
            'ticket_type' => $ticket->ticketType->name,
            'scanned_at'  => now()->format('d M Y, H:i'),
        ]);
    }
}
