<x-mail::layout>
    <div class="card-header">
        <span class="badge badge-red">
            <span class="badge-dot" style="background:#dc2626"></span>
            Refund Ditolak
        </span>
        <h1>Maaf, Refund Tidak Dapat Diproses</h1>
        <p class="subtitle">
            Setelah ditinjau, pengajuan refund Anda tidak dapat kami setujui.
            Berikut penjelasannya.
        </p>
    </div>

    <div class="card-body">

        <table class="info-table">
            <tr>
                <td>Nomor Refund</td>
                <td style="font-family: monospace;">{{ $refund->refund_number }}</td>
            </tr>
            <tr>
                <td>Nomor Order</td>
                <td style="font-family: monospace;">{{ $refund->order->order_number }}</td>
            </tr>
            <tr>
                <td>Jumlah yang Diajukan</td>
                <td style="font-weight: 800;">
                    Rp {{ number_format($refund->amount, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Ditolak pada</td>
                <td>{{ $refund->reviewed_at?->format('d M Y, H:i') }} WIB</td>
            </tr>
        </table>

        @if ($refund->admin_notes)
            <div class="alert-box alert-red">
                <div>
                    <strong>Alasan Penolakan:</strong><br>
                    {{ $refund->admin_notes }}
                </div>
            </div>
        @endif

        <div class="alert-box alert-amber">
            <div>
                <strong>Tidak setuju dengan keputusan ini?</strong>
                Hubungi tim support kami dan kami akan membantu menyelesaikan masalah Anda.
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px; display: grid; gap: 12px;">
            @if (setting('social_whatsapp'))
                <a href="https://wa.me/{{ setting('social_whatsapp') }}?text={{ urlencode('Halo, saya ingin menanyakan refund ' . $refund->refund_number) }}"
                    class="btn btn-full">
                    Hubungi Support via WhatsApp
                </a>
            @endif
            <a href="mailto:{{ setting('app_email') }}" class="btn btn-outline btn-full">
                Kirim Email Support
            </a>
        </div>

    </div>

    <div class="card-footer-inner">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            Nomor Refund: <strong>{{ $refund->refund_number }}</strong>
            · Sertakan nomor ini saat menghubungi support.
        </p>
    </div>

    <x-slot name="recipientEmail">{{ $refund->user->email }}</x-slot>
</x-mail::layout>
