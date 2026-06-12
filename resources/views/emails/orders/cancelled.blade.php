<x-mail::layout>
    <div class="card-header">
        <span class="badge badge-gray">
            <span class="badge-dot" style="background:#6b7280"></span>
            Order Dibatalkan
        </span>
        <h1>Order Telah Dibatalkan</h1>
        <p class="subtitle">
            Order Anda telah dibatalkan. Jika ini bukan permintaan Anda, segera hubungi tim support kami.
        </p>
    </div>

    <div class="card-body">

        <table class="info-table">
            <tr>
                <td>Nomor Order</td>
                <td style="font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td>Event</td>
                <td>{{ $order->orderItems->first()?->ticketType?->event?->title ?? '-' }}</td>
            </tr>
            <tr>
                <td>Total Order</td>
                <td style="font-weight: 800;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Dibatalkan pada</td>
                <td>{{ $order->updated_at->format('d M Y, H:i') }} WIB</td>
            </tr>
            @if ($order->notes)
                <tr>
                    <td>Alasan</td>
                    <td>{{ $order->notes }}</td>
                </tr>
            @endif
        </table>

        <div class="alert-box alert-amber">
            <div>
                <strong>Ingin membeli tiket lagi?</strong>
                Kunjungi halaman event kami untuk menemukan event seru lainnya.
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px; display: flex; gap: 12px;">
            <a href="{{ route('events.index') }}" class="btn btn-full" style="margin-bottom: 12px;">
                Jelajahi Event Lainnya →
            </a>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('user.orders.index') }}" class="btn btn-outline btn-full">
                Lihat Semua Pesanan
            </a>
        </div>

    </div>

    <div class="card-footer-inner">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            Merasa tidak membatalkan order ini?
            <a href="mailto:{{ setting('app_email', 'support@ticketin.id') }}"
                style="color: #374151; font-weight: 600; text-decoration: none;">
                Hubungi Support
            </a>
        </p>
    </div>

    <x-slot name="recipientEmail">{{ $order->user->email }}</x-slot>
</x-mail::layout>
