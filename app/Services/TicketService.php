<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function generateTickets(Order $order): void
    {
        DB::transaction(function () use ($order) {

            $order->load([
                'user',
                'orderItems.ticketType',
                'orderItems.tickets',
            ]);

            foreach ($order->orderItems as $item) {

                // Proteksi agar tidak membuat tiket dua kali
                if ($item->tickets()->exists()) {

                    Log::warning('Ticket generation skipped', [
                        'order_id'      => $order->id,
                        'order_item_id' => $item->id,
                        'reason'        => 'Tickets already exist',
                    ]);

                    continue;
                }

                for ($i = 0; $i < $item->quantity; $i++) {

                    $ticketCode = $this->generateUniqueTicketCode();

                    $qrPath = $this->generateQrCode(
                        $ticketCode
                    );

                    Ticket::create([
                        'order_item_id'  => $item->id,
                        'ticket_type_id' => $item->ticket_type_id,
                        'user_id'        => $order->user_id,
                        'ticket_code'    => $ticketCode,
                        'qr_code'        => $qrPath,
                        'holder_name'    => $order->user->name,
                        'holder_email'   => $order->user->email,
                        'holder_phone'   => $order->user->phone,
                        'status'         => 'active',
                    ]);
                }

                // Update sold hanya sekali
                $ticketType = $item->ticketType()
                    ->lockForUpdate()
                    ->first();

                $ticketType->increment(
                    'sold',
                    $item->quantity
                );

                Log::info('Tickets generated', [
                    'order_id'      => $order->id,
                    'order_item_id' => $item->id,
                    'quantity'      => $item->quantity,
                ]);
            }
        });
    }

    private function generateUniqueTicketCode(): string
    {
        do {

            $code = strtoupper(
                'TKT-' .
                    Str::random(4) .
                    '-' .
                    Str::random(6)
            );
        } while (
            Ticket::where(
                'ticket_code',
                $code
            )->exists()
        );

        return $code;
    }

    private function generateQrCode(string $ticketCode): ?string
    {
        try {

            $qrImage = QrCode::format('png')
                ->size(400)
                ->errorCorrection('H')
                ->generate($ticketCode);

            $path = 'tickets/qr/' . $ticketCode . '.png';

            Storage::disk('public')
                ->put($path, $qrImage);

            return $path;
        } catch (\Throwable $e) {

            Log::error('QR generation failed', [
                'ticket_code' => $ticketCode,
                'message'     => $e->getMessage(),
            ]);

            return null;
        }
    }
}
