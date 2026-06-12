<x-auth-layout>
    <x-slot name="title">Detail Refund — {{ setting('app_name') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('user.refunds.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700
            transition mb-6">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Refund
        </a>

        {{-- Status Banner --}}
        @php
            $bannerCfg = [
                'pending' => [
                    'bg' => 'bg-amber-50 border-amber-200',
                    'icon' => 'ti-clock',
                    'iconColor' => 'text-amber-500',
                    'title' => 'Menunggu Review Admin',
                    'desc' => 'Pengajuan Anda sedang diproses. Tim kami akan menghubungi dalam 1-2 hari kerja.',
                ],
                'approved' => [
                    'bg' => 'bg-emerald-50 border-emerald-200',
                    'icon' => 'ti-circle-check',
                    'iconColor' => 'text-emerald-500',
                    'title' => 'Refund Disetujui!',
                    'desc' => 'Dana akan ditransfer ke rekening Anda dalam 3-5 hari kerja.',
                ],
                'rejected' => [
                    'bg' => 'bg-red-50 border-red-200',
                    'icon' => 'ti-circle-x',
                    'iconColor' => 'text-red-500',
                    'title' => 'Refund Ditolak',
                    'desc' => 'Maaf, pengajuan refund Anda tidak dapat diproses.',
                ],
            ];
            $bc = $bannerCfg[$refund->status] ?? $bannerCfg['pending'];
        @endphp
        <div class="flex items-start gap-4 p-5 border rounded-2xl {{ $bc['bg'] }} mb-6">
            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="ti {{ $bc['icon'] }} {{ $bc['iconColor'] }} text-2xl"></i>
            </div>
            <div>
                <p class="text-base font-bold text-gray-900">{{ $bc['title'] }}</p>
                <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $bc['desc'] }}</p>
                @if ($refund->admin_notes)
                    <div class="mt-3 p-3 bg-white/70 rounded-xl">
                        <p class="text-xs font-semibold text-gray-600 mb-1">Catatan Admin:</p>
                        <p class="text-sm text-gray-700">{{ $refund->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Left --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Refund Detail --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-sm font-bold text-gray-900">Detail Refund</p>
                        <span class="font-mono text-xs text-gray-400">{{ $refund->refund_number }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Order Number</p>
                            <a href="{{ route('user.orders.show', $refund->order) }}"
                                class="font-mono text-blue-600 hover:underline text-xs">
                                {{ $refund->order->order_number }}
                            </a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Jumlah Refund</p>
                            <p class="font-bold text-emerald-600">
                                Rp {{ number_format($refund->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Tanggal Pengajuan</p>
                            <p class="text-gray-700">{{ $refund->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if ($refund->reviewed_at)
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Tanggal Review</p>
                                <p class="text-gray-700">{{ $refund->reviewed_at->format('d M Y, H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Alasan Refund</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $refund->reason }}</p>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-5">Status Proses</p>
                    <div class="relative">
                        <div class="absolute left-3.5 top-0 bottom-0 w-px bg-gray-100"></div>
                        <div class="space-y-5">
                            @php
                                $steps = [
                                    [
                                        'title' => 'Pengajuan Dikirim',
                                        'desc' => 'Refund berhasil diajukan',
                                        'done' => true,
                                        'date' => $refund->created_at->format('d M Y, H:i'),
                                    ],
                                    [
                                        'title' => 'Review Admin',
                                        'desc' => 'Tim kami sedang memproses pengajuan',
                                        'done' => !$refund->isPending(),
                                        'date' => $refund->reviewed_at?->format('d M Y, H:i'),
                                    ],
                                    [
                                        'title' => 'Keputusan',
                                        'desc' => $refund->isApproved()
                                            ? 'Refund disetujui'
                                            : ($refund->isRejected()
                                                ? 'Refund ditolak'
                                                : 'Menunggu keputusan'),
                                        'done' => !$refund->isPending(),
                                        'date' => $refund->reviewed_at?->format('d M Y, H:i'),
                                    ],
                                    [
                                        'title' => 'Transfer Dana',
                                        'desc' => 'Dana ditransfer ke rekening Anda',
                                        'done' => $refund->isApproved(),
                                        'date' => $refund->isApproved() ? 'Dalam 3-5 hari kerja' : null,
                                    ],
                                ];
                            @endphp
                            @foreach ($steps as $step)
                                <div class="relative flex items-start gap-4 pl-8">
                                    <div
                                        class="absolute left-0 w-7 h-7 rounded-full border-2 flex items-center
                                justify-center z-10 flex-shrink-0
                                {{ $step['done'] ? 'bg-gray-900 border-gray-900' : 'bg-white border-gray-200' }}">
                                        @if ($step['done'])
                                            <i class="ti ti-check text-white text-xs"></i>
                                        @else
                                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $step['title'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $step['desc'] }}</p>
                                        @if ($step['date'])
                                            <p class="text-xs text-gray-300 mt-1">{{ $step['date'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right --}}
            <div class="space-y-4">

                {{-- Bank Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Info Rekening</p>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-400">Bank</p>
                            <p class="font-semibold text-gray-900 mt-0.5">{{ $refund->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">No. Rekening</p>
                            <p class="font-mono text-gray-700 mt-0.5">{{ $refund->account_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Atas Nama</p>
                            <p class="text-gray-700 mt-0.5">{{ $refund->account_name }}</p>
                        </div>
                        <div class="pt-2 border-t border-gray-100">
                            <p class="text-xs text-gray-400">Total Transfer</p>
                            <p class="text-base font-bold text-emerald-600 mt-0.5">
                                Rp {{ number_format($refund->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Help --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-blue-700 mb-2">
                        <i class="ti ti-help-circle text-sm mr-1"></i> Butuh Bantuan?
                    </p>
                    <p class="text-xs text-blue-600 mb-3 leading-relaxed">
                        Jika ada pertanyaan seputar proses refund, hubungi tim support kami.
                    </p>
                    @if (setting('social_whatsapp'))
                        <a href="https://wa.me/{{ setting('social_whatsapp') }}?text={{ urlencode('Halo, saya ingin menanyakan status refund ' . $refund->refund_number) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 text-xs font-medium
                        text-blue-700 hover:text-blue-900 transition">
                            <i class="ti ti-brand-whatsapp text-sm text-emerald-500"></i>
                            Chat WhatsApp
                        </a>
                    @endif
                </div>

                {{-- Meta --}}
                <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-xs text-gray-400">
                    <div class="flex justify-between">
                        <span>Diajukan</span>
                        <span>{{ $refund->created_at->format('d M Y') }}</span>
                    </div>
                    @if ($refund->reviewed_at)
                        <div class="flex justify-between">
                            <span>Direview</span>
                            <span>{{ $refund->reviewed_at->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</x-auth-layout>
