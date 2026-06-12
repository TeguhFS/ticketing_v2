<x-mail::layout>
    <div class="card-header">
        <span class="badge badge-green">
            <span class="badge-dot" style="background:#15803d"></span>
            Refund Disetujui
        </span>
        <h1>Refund Anda Disetujui!</h1>
        <p class="subtitle">
            Kabar baik! Pengajuan refund Anda telah disetujui.
            Dana akan segera ditransfer ke rekening Anda.
        </p>
    </div>

    <div class="card-body">

        <table class="info-table">
            <tr>
                <td>Nomor Refund</td>
                <td style="font-family: monospace;">{{ $refund->refund_number }}</td>
            </tr>
            <tr>
                <td>Jumlah Refund</td>
                <td style="font-size: 18px; font-weight: 800; color: #15803d;">
                    Rp {{ number_format($refund->amount, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Bank Tujuan</td>
                <td style="font-weight: 700;">{{ $refund->bank_name }}</td>
            </tr>
            <tr>
                <td>No. Rekening</td>
                <td style="font-family: monospace; font-weight: 700;">{{ $refund->account_number }}</td>
            </tr>
            <tr>
                <td>Atas Nama</td>
                <td>{{ $refund->account_name }}</td>
            </tr>
            <tr>
                <td>Disetujui pada</td>
                <td>{{ $refund->reviewed_at?->format('d M Y, H:i') }} WIB</td>
            </tr>
        </table>

        @if ($refund->admin_notes)
            <div class="alert-box alert-green">
                <div>
                    <strong>Catatan dari Tim Kami:</strong><br>
                    {{ $refund->admin_notes }}
                </div>
            </div>
        @endif

        <div class="alert-box alert-blue">
            <div>
                <strong>Estimasi Transfer:</strong>
                Dana akan masuk ke rekening Anda dalam <strong>3-5 hari kerja</strong>
                sejak tanggal persetujuan. Harap pastikan nomor rekening yang Anda daftarkan sudah benar.
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('user.refunds.show', $refund) }}" class="btn btn-full">
                Lihat Detail Refund →
            </a>
        </div>

    </div>

    <div class="card-footer-inner">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            Ada pertanyaan tentang transfer? Hubungi kami di
            <a href="mailto:{{ setting('app_email') }}"
                style="color: #374151; font-weight: 600; text-decoration: none;">
                {{ setting('app_email') }}
            </a>
        </p>
    </div>

    <x-slot name="recipientEmail">{{ $refund->user->email }}</x-slot>
</x-mail::layout>
