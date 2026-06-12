<x-auth-layout>
    <x-slot name="title">Refund Saya — {{ setting('app_name') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session('success'))
            <div
                class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200
        text-emerald-700 text-sm px-4 py-3 rounded-xl">
                <i class="ti ti-circle-check text-base flex-shrink-0"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Refund Saya</h1>
                <p class="text-sm text-gray-400 mt-1">Riwayat pengajuan refund Anda</p>
            </div>
            <a href="{{ route('user.orders.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-500
                hover:text-gray-900 transition">
                <i class="ti ti-arrow-left text-base"></i> Ke Pesanan
            </a>
        </div>

        @if ($refunds->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-2xl border border-gray-100">
                <i class="ti ti-refresh text-5xl text-gray-200 block mb-3"></i>
                <p class="text-sm text-gray-400 mb-4">Belum ada pengajuan refund</p>
                <a href="{{ route('user.orders.index') }}"
                    class="inline-flex items-center gap-2 h-10 px-5 bg-gray-900 text-white text-sm
                font-medium rounded-xl hover:bg-gray-700 transition">
                    <i class="ti ti-shopping-cart text-base"></i> Lihat Pesanan
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($refunds as $refund)
                    <a href="{{ route('user.refunds.show', $refund) }}"
                        class="group block bg-white border border-gray-100 rounded-2xl p-5
                hover:shadow-md hover:border-gray-200 transition-all duration-200">

                        <div class="flex items-start gap-4">
                            {{-- Status Icon --}}
                            @php
                                $icons = [
                                    'pending' => [
                                        'icon' => 'ti-clock',
                                        'bg' => 'bg-amber-100',
                                        'text' => 'text-amber-600',
                                    ],
                                    'approved' => [
                                        'icon' => 'ti-circle-check',
                                        'bg' => 'bg-emerald-100',
                                        'text' => 'text-emerald-600',
                                    ],
                                    'rejected' => [
                                        'icon' => 'ti-circle-x',
                                        'bg' => 'bg-red-100',
                                        'text' => 'text-red-500',
                                    ],
                                ];
                                $ic = $icons[$refund->status] ?? $icons['pending'];
                            @endphp
                            <div
                                class="w-11 h-11 {{ $ic['bg'] }} rounded-xl flex items-center
                    justify-center flex-shrink-0">
                                <i class="ti {{ $ic['icon'] }} {{ $ic['text'] }} text-lg"></i>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 truncate">
                                            {{ $refund->order->orderItems->first()?->ticketType?->event?->title ?? 'Event' }}
                                        </p>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                                            <span class="font-mono">{{ $refund->refund_number }}</span>
                                            <span>·</span>
                                            <span>{{ $refund->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @php
                                        $badge = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                        ];
                                    @endphp
                                    <span
                                        class="text-xs font-semibold px-2.5 py-1 rounded-xl border
                            flex-shrink-0 {{ $badge[$refund->status] ?? '' }} capitalize">
                                        {{ $refund->status === 'approved' ? 'Disetujui' : ($refund->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 mt-3 flex-wrap">
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($refund->amount, 0, ',', '.') }}
                                    </p>
                                    <div class="text-xs text-gray-400">
                                        {{ $refund->bank_name }} · {{ $refund->account_number }}
                                    </div>
                                </div>

                                @if ($refund->admin_notes)
                                    <div class="mt-3 p-2.5 bg-gray-50 rounded-xl">
                                        <p class="text-xs text-gray-500">
                                            <span class="font-medium text-gray-700">Catatan Admin:</span>
                                            {{ $refund->admin_notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <i
                                class="ti ti-chevron-right text-gray-300 text-sm flex-shrink-0
                    group-hover:text-gray-500 group-hover:translate-x-0.5
                    transition-all duration-200 mt-1"></i>
                        </div>

                    </a>
                @endforeach
            </div>

            @if ($refunds->hasPages())
                <div class="mt-6">{{ $refunds->links() }}</div>
            @endif
        @endif

    </div>
</x-auth-layout>
