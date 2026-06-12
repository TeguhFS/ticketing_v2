<x-mail::layout>
    <div class="card-header">
        <span class="badge badge-amber">
            <span class="badge-dot" style="background:#b45309"></span>
            Pengajuan Refund
        </span>
        <h1>Pengajuan Refund Diterima</h1>
        <p class="subtitle">
            Pengajuan refund Anda telah kami terima dan sedang diproses oleh tim kami.
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
                <td>Jumlah Refund</td>
                <td style="font-size: 16px; font-weight: 800; color: #15803d;">
                    Rp {{ number_format($refund->amount, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Rekening Tujuan</td>
                <td>{{ $refund->bank_name }} · {{ $refund->account_number }}</td>
            </tr>
            <tr>
                <td>Atas Nama</td>
                <td>{{ $refund->account_name }}</td>
            </tr>
            <tr>
                <td>Alasan</td>
                <td>{{ $refund->reason }}</td>
            </tr>
            <tr>
                <td>Diajukan pada</td>
                <td>{{ $refund->created_at->format('d M Y, H:i') }} WIB</td>
            </tr>
        </table>

        {{-- Timeline --}}
        <p style="font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 16px;">
            Proses Refund:
        </p>
        @foreach ([['step' => '1', 'title' => 'Pengajuan Diterima', 'desc' => 'Refund berhasil diajukan', 'done' => true], ['step' => '2', 'title' => 'Review Tim Kami', 'desc' => 'Diproses dalam 1-2 hari kerja', 'done' => false], ['step' => '3', 'title' => 'Transfer Dana', 'desc' => 'Dana ditransfer dalam 3-5 hari kerja', 'done' => false]] as $step)
            <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px;">
                <div
                    style="width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
            background: {{ $step['done'] ? '#111827' : '#f3f4f6' }};
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            color: {{ $step['done'] ? '#ffffff' : '#9ca3af' }};">
                    {{ $step['done'] ? '✓' : $step['step'] }}
                </div>
                <div>
                    <p
                        style="font-size: 13px; font-weight: 600;
                color: {{ $step['done'] ? '#111827' : '#9ca3af' }}; margin-bottom: 2px;">
                        {{ $step['title'] }}
                    </p>
                    <p style="font-size: 12px; color: #9ca3af;">{{ $step['desc'] }}</p>
                </div>
            </div>
        @endforeach

        <div class="alert-box alert-blue">
            <div>
                <strong>Estimasi Waktu:</strong> Tim kami akan memproses pengajuan Anda dalam
                <strong>1-2 hari kerja</strong> dan dana akan ditransfer dalam
                <strong>3-5 hari kerja</strong> setelah disetujui.
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('user.refunds.show', $refund) }}" class="btn btn-full">
                Cek Status Refund →
            </a>
        </div>

    </div>

    <div class="card-footer-inner">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            Pertanyaan tentang refund?
            <a href="mailto:{{ setting('app_email') }}"
                style="color: #374151; font-weight: 600; text-decoration: none;">
                Hubungi kami
            </a>
        </p>
    </div>

    <x-slot name="recipientEmail">{{ $refund->user->email }}</x-slot>
</x-mail::layout>
