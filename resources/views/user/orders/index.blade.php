<x-auth-layout>
    <x-slot name="title">Pesanan Saya — {{ config('app_name') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ── Halaman Header ───────────────────────────────────────── --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
                <p class="text-sm text-gray-400 mt-1">Riwayat semua pembelian tiket</p>
            </div>
            <a href="{{ route('events.index') }}"
                class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-all duration-200">
                <i class="ti ti-plus text-base"></i> Beli Tiket
            </a>
        </div>

        {{-- ── Panel Statistik Ringkas ──────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            @foreach ([
        [
            'label' => 'Total',
            'value' => $stats['total'],
            'color' => 'text-gray-900',
            'bg' => 'bg-gray-900',
            'icon' => 'ti-shopping-cart',
        ],
        [
            'label' => 'Pending',
            'value' => $stats['pending'],
            'color' => 'text-amber-600',
            'bg' => 'bg-amber-500',
            'icon' => 'ti-clock',
        ],
        [
            'label' => 'Paid',
            'value' => $stats['paid'],
            'color' => 'text-emerald-600',
            'bg' => 'bg-emerald-500',
            'icon' => 'ti-circle-check',
        ],
        [
            'label' => 'Cancelled',
            'value' => $stats['cancelled'],
            'color' => 'text-red-500',
            'bg' => 'bg-red-500',
            'icon' => 'ti-circle-x',
        ],
    ] as $stat)
                <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center shadow-sm">
                    {{-- Icon Block Box dengan Ikon Dinamis --}}
                    <div class="w-8 h-8 {{ $stat['bg'] }} rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="ti {{ $stat['icon'] }} text-white text-sm"></i>
                    </div>

                    {{-- Value & Label --}}
                    <p class="text-xl font-bold {{ $stat['color'] }} leading-tight">{{ $stat['value'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ── Filter Kategori Status (Pill Badges) ──────────────────── --}}
        <div class="flex gap-2 mb-5 overflow-x-auto pb-1 no-scrollbar">
            @foreach ([
        '' => 'Semua',
        'pending' => 'Pending',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
        'expired' => 'Expired',
    ] as $val => $label)
                <a href="{{ route('user.orders.index', $val ? ['status' => $val] : []) }}"
                    class="flex-shrink-0 h-9 px-4 text-sm font-medium rounded-xl transition-all duration-200 flex items-center
                    {{ request('status', '') === $val
                        ? 'bg-gray-900 text-white'
                        : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- ── Daftar Orders / Empty State ──────────────────────────── --}}
        @if ($orders->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-2xl border border-gray-100">
                <i class="ti ti-shopping-cart-off text-5xl text-gray-200 block mb-3"></i>
                <p class="text-sm text-gray-400 mb-4">Belum ada pesanan</p>
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center gap-2 h-10 px-5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-all duration-200">
                    <i class="ti ti-ticket text-base"></i> Beli Tiket Sekarang
                </a>
            </div>
        @else
            <div class="space-y-3">
                {{-- Ganti bagian loop orders dengan ini --}}
                @foreach ($orders as $order)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5
    hover:shadow-md hover:border-gray-200 transition-all duration-200"
                        x-data="{ cancelOpen: false }">

                        <a href="{{ route('user.orders.show', $order) }}" class="group block">
                            <div class="flex items-start gap-4">

                                {{-- Event Thumb --}}
                                <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                    @php $thumb = $order->orderItems->first()?->ticketType?->event?->thumbnail; @endphp
                                    @if ($thumb)
                                        <img src="{{ Storage::url($thumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-105
                        transition-transform duration-300"
                                            alt="">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                    flex items-center justify-center">
                                            <i class="ti ti-calendar-event text-2xl text-gray-300"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-base font-bold text-gray-900 truncate">
                                                {{ $order->orderItems->first()?->ticketType?->event?->title ?? 'Event' }}
                                            </p>
                                            <p class="text-xs font-mono text-gray-400 mt-0.5">
                                                {{ $order->order_number }}
                                            </p>
                                        </div>
                                        @php
                                            $badge = [
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                                                'expired' => 'bg-gray-100 text-gray-400 border-gray-200',
                                                'refunded' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            ];
                                        @endphp
                                        <span
                                            class="text-xs font-semibold px-2.5 py-1 rounded-xl border flex-shrink-0
                        {{ $badge[$order->status] ?? '' }} capitalize">
                                            {{ $order->status }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-4 mt-2 flex-wrap text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <i class="ti ti-ticket text-gray-300"></i>
                                            {{ $order->orderItems->sum('quantity') }} tiket
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="ti ti-calendar text-gray-300"></i>
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </span>
                                        @if ($order->status === 'pending' && $order->expired_at)
                                            <span class="flex items-center gap-1 text-amber-600 font-medium">
                                                <i class="ti ti-clock text-amber-400"></i>
                                                Berlaku hingga {{ $order->expired_at->format('d M, H:i') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between mt-3">
                                        <p class="text-base font-bold text-gray-900">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </p>
                                        <i
                                            class="ti ti-arrow-right text-gray-300 text-sm
                        group-hover:text-gray-500 group-hover:translate-x-0.5
                        transition-all duration-200"></i>
                                    </div>
                                </div>

                            </div>
                        </a>

                        {{-- Cancel button for pending orders --}}
                        @if ($order->status === 'pending')
                            <div class="border-t border-gray-50 mt-4 pt-4">
                                <button type="button" @click="cancelOpen = !cancelOpen"
                                    class="w-full flex items-center justify-center gap-2 h-9 border border-gray-200
                text-gray-500 text-xs font-medium rounded-xl hover:border-red-200
                hover:text-red-500 hover:bg-red-50 transition-all duration-200">
                                    <i class="ti ti-x text-sm"></i>
                                    Batalkan Order
                                </button>

                                {{-- Inline cancel form --}}
                                <div x-show="cancelOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="mt-3 bg-red-50/50 border border-red-100 rounded-xl p-4">

                                    <p class="text-xs font-semibold text-red-700 mb-3 flex items-center gap-2">
                                        <i class="ti ti-alert-triangle text-sm"></i>
                                        Konfirmasi Pembatalan
                                    </p>

                                    <form action="{{ route('user.orders.cancel', $order) }}" method="POST"
                                        class="space-y-3">
                                        @csrf @method('PATCH')

                                        <div>
                                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                                Alasan <span class="text-red-500">*</span>
                                            </label>

                                            {{-- Quick reasons --}}
                                            <div class="flex flex-wrap gap-2 mb-2" x-data="{ sel: '' }">
                                                @foreach (['Salah pilih tiket', 'Ada keperluan mendadak', 'Ingin ganti event', 'Lainnya'] as $reason)
                                                    <button type="button"
                                                        @click="sel = '{{ $reason }}';
                                $el.closest('form').querySelector('textarea').value = '{{ $reason }}';"
                                                        :class="sel === '{{ $reason }}'
                                                            ?
                                                            'border-red-400 bg-red-50 text-red-600' :
                                                            'border-gray-200 text-gray-500 hover:border-gray-300'"
                                                        class="px-2.5 py-1 text-xs border rounded-lg transition-all duration-150">
                                                        {{ $reason }}
                                                    </button>
                                                @endforeach
                                            </div>

                                            <textarea name="cancel_reason" rows="2" placeholder="Tuliskan alasan pembatalan..."
                                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm
                            text-gray-700 outline-none bg-white resize-none
                            focus:border-red-300 transition-all duration-200"></textarea>
                                        </div>

                                        <div class="flex gap-2">
                                            <button type="button" @click="cancelOpen = false"
                                                class="flex-1 h-9 border border-gray-200 text-gray-500 text-xs
                            font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin membatalkan order ini?')"
                                                class="flex-1 h-9 bg-red-500 text-white text-xs font-semibold
                            rounded-xl hover:bg-red-600 transition-all duration-200
                            flex items-center justify-center gap-1.5">
                                                <i class="ti ti-x text-sm"></i>
                                                Ya, Batalkan
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

            {{-- Navigasi Paginasi --}}
            @if ($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif

    </div>
</x-auth-layout>
