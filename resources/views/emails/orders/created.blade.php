<x-mail::layout>
    {{-- Header --}}
    <div class="card-header">
        <span class="badge badge-blue">
            <span class="badge-dot" style="background:#1d4ed8"></span>
            Order Baru
        </span>
        <h1>Pesanan Anda Berhasil Dibuat!</h1>
        <p class="subtitle">
            Terima kasih, {{ $order->user->name }}! Pesanan Anda telah kami terima.
            Segera selesaikan pembayaran sebelum waktu habis.
        </p>
    </div>

    {{-- Body --}}
    <div class="card-body">

        {{-- Order Info --}}
        <table class="info-table">
            <tr>
                <td>Nomor Order</td>
                <td style="font-family: monospace; font-size: 13px;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td>Tanggal Order</td>
                <td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Batas Pembayaran</td>
                <td style="color: #dc2626; font-weight: 700;">
                    {{ $order->expired_at?->format('d M Y, H:i') }} WIB
                </td>
            </tr>
            <tr>
                <td>Total Pembayaran</td>
                <td style="font-size: 16px; color: #111827; font-weight: 800;">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        {{-- Items --}}
        <p style="font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px;">
            Item Pesanan:
        </p>
        @foreach ($order->orderItems as $item)
            <div
                style="display: flex; justify-content: space-between; align-items: center;
        padding: 12px 16px; background: #f9fafb; border-radius: 10px;
        margin-bottom: 8px; border: 1px solid #f3f4f6;">
                <div>
                    <p style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 2px;">
                        {{ $item->ticketType->event->title ?? '-' }}
                    </p>
                    <p style="font-size: 12px; color: #9ca3af;">
                        {{ $item->ticketType->name }} × {{ $item->quantity }}
                        · {{ $item->ticketType->event->start_date->format('d M Y') }}
                    </p>
                </div>
                <p style="font-size: 13px; font-weight: 700; color: #111827;">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </p>
            </div>
        @endforeach

        {{-- Alert --}}
        <div class="alert-box alert-amber">
            <div>
                <strong>Segera bayar!</strong> Order ini akan otomatis dibatalkan jika pembayaran
                tidak diselesaikan sebelum
                <strong>{{ $order->expired_at?->format('d M Y, H:i') }} WIB</strong>.
            </div>
        </div>

        {{-- CTA --}}
        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('user.orders.show', $order) }}" class="btn btn-full">
                Selesaikan Pembayaran →
            </a>
        </div>

    </div>

    {{-- Card Footer --}}
    <div class="card-footer-inner">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            Ada pertanyaan? Hubungi kami di
            <a href="mailto:{{ setting('app_email', 'support@ticketin.id') }}"
                style="color: #374151; font-weight: 600; text-decoration: none;">
                {{ setting('app_email', 'support@ticketin.id') }}
            </a>
        </p>
    </div>

    <x-slot name="recipientEmail">{{ $order->user->email }}</x-slot>
</x-mail::layout>
