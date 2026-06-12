<x-mail::layout>
    <div class="card-header">
        <span class="badge badge-green">
            <span class="badge-dot" style="background:#15803d"></span>
            Pembayaran Berhasil
        </span>
        <h1>Tiket Anda Sudah Siap!</h1>
        <p class="subtitle">
            Pembayaran berhasil dikonfirmasi. Tiket Anda sudah aktif dan siap digunakan.
        </p>
    </div>

    <div class="card-body">

        {{-- Order Info --}}
        <table class="info-table">
            <tr>
                <td>Nomor Order</td>
                <td style="font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td>Tanggal Bayar</td>
                <td>{{ now()->format('d M Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Total Dibayar</td>
                <td style="font-size: 16px; font-weight: 800; color: #15803d;">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <span
                        style="background: #f0fdf4; color: #15803d; padding: 3px 10px;
                    border-radius: 20px; font-size: 11px; font-weight: 700;">
                        ✓ LUNAS
                    </span>
                </td>
            </tr>
        </table>

        {{-- Tickets --}}
        <p style="font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 12px;">
            Tiket Anda:
        </p>
        @foreach ($order->orderItems as $item)
            @foreach ($item->tickets as $ticket)
                <div class="ticket-card">
                    {{-- Event name --}}
                    <p
                        style="font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.5);
                letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;">
                        E-TICKET
                    </p>
                    <p style="font-size: 16px; font-weight: 800; color: #ffffff; margin-bottom: 4px;">
                        {{ $ticket->ticketType->event->title ?? '-' }}
                    </p>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.6); margin-bottom: 4px;">
                        {{ $ticket->ticketType->event->location ?? '-' }}
                    </p>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.6); margin-bottom: 0;">
                        {{ $ticket->ticketType->event->start_date->translatedFormat('l, d F Y') }}
                        · {{ $ticket->ticketType->event->start_date->format('H:i') }} WIB
                    </p>

                    {{-- Ticket type --}}
                    <div
                        style="margin-top: 16px; padding-top: 16px;
                border-top: 1px dashed rgba(255,255,255,0.15);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p style="font-size: 11px; color: rgba(255,255,255,0.4); margin-bottom: 2px;">
                                    Tipe Tiket
                                </p>
                                <p style="font-size: 13px; font-weight: 700; color: #ffffff;">
                                    {{ $ticket->ticketType->name }}
                                </p>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 11px; color: rgba(255,255,255,0.4); margin-bottom: 2px;">
                                    Pemegang
                                </p>
                                <p style="font-size: 13px; font-weight: 700; color: #ffffff;">
                                    {{ $ticket->holder_name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket Code --}}
                    <div class="ticket-code">{{ $ticket->ticket_code }}</div>

                    <p style="font-size: 11px; color: rgba(255,255,255,0.4); text-align: center; margin-top: 8px;">
                        Tunjukkan kode ini kepada petugas di lokasi event
                    </p>
                </div>
            @endforeach
        @endforeach

        {{-- Instructions --}}
        <div class="alert-box alert-blue">
            <div>
                <strong>Cara Menggunakan Tiket</strong><br>
                Tunjukkan kode tiket atau QR code kepada petugas saat masuk ke venue.
                Jangan bagikan kode tiket kepada siapapun.
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('user.tickets.index') }}" class="btn btn-full">
                Lihat Tiket Saya →
            </a>
            <p style="margin-top: 12px; font-size: 12px; color: #9ca3af;">
                atau kunjungi
                <a href="{{ route('user.orders.show', $order) }}"
                    style="color: #374151; font-weight: 600; text-decoration: none;">
                    halaman detail order
                </a>
            </p>
        </div>

    </div>

    <div class="card-footer-inner">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            Butuh bantuan?
            <a href="mailto:{{ setting('app_email', 'support@ticketin.id') }}"
                style="color: #374151; font-weight: 600; text-decoration: none;">
                Hubungi Support
            </a>
            @if (setting('social_whatsapp'))
                atau
                <a href="https://wa.me/{{ setting('social_whatsapp') }}"
                    style="color: #374151; font-weight: 600; text-decoration: none;">
                    WhatsApp
                </a>
            @endif
        </p>
    </div>

    <x-slot name="recipientEmail">{{ $order->user->email }}</x-slot>
</x-mail::layout>
