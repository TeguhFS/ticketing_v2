<x-auth-layout>
    <x-slot name="title">Tiket Saya — {{ config('app_name') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tiket Saya</h1>
                <p class="text-sm text-gray-400 mt-1">Semua tiket event yang Anda miliki</p>
            </div>
        </div>

        {{-- ── Panel Statistik Ringkas (Tiket) ─────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            @foreach ([
        [
            'label' => 'Total',
            'value' => $stats['total'],
            'color' => 'text-gray-900',
            'bg' => 'bg-gray-900',
            'icon' => 'ti-ticket',
        ],
        [
            'label' => 'Aktif',
            'value' => $stats['active'],
            'color' => 'text-emerald-600',
            'bg' => 'bg-emerald-500',
            'icon' => 'ti-circle-check',
        ],
        [
            'label' => 'Digunakan',
            'value' => $stats['used'],
            'color' => 'text-blue-600',
            'bg' => 'bg-blue-500',
            'icon' => 'ti-scan',
        ],
        [
            'label' => 'Dibatalkan',
            'value' => $stats['cancelled'],
            'color' => 'text-red-500',
            'bg' => 'bg-red-500',
            'icon' => 'ti-circle-x',
        ],
    ] as $stat)
                <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center shadow-sm">
                    {{-- Icon Block Box dengan Ikon Tiket Dinamis --}}
                    <div class="w-8 h-8 {{ $stat['bg'] }} rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="ti {{ $stat['icon'] }} text-white text-sm"></i>
                    </div>

                    {{-- Value & Label --}}
                    <p class="text-xl font-bold {{ $stat['color'] }} leading-tight">{{ $stat['value'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filter & Search --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-4 mb-5">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div
                    class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-xl
                px-3 h-10 flex-1 min-w-[200px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode tiket atau nama event..."
                        class="bg-transparent border-none outline-none text-sm text-gray-700
                        placeholder-gray-300 w-full">
                </div>
                <select name="status"
                    class="h-10 px-3 bg-gray-50 border border-gray-100 rounded-xl text-sm
                    text-gray-600 outline-none">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Digunakan</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan
                    </option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <button type="submit"
                    class="h-10 px-4 bg-gray-900 text-white text-sm rounded-xl
                    hover:bg-gray-700 transition-all duration-200">
                    Filter
                </button>
            </form>
        </div>

        {{-- Tickets --}}
        @if ($tickets->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-2xl border border-gray-100">
                <i class="ti ti-ticket-off text-5xl text-gray-200 block mb-3"></i>
                <p class="text-sm text-gray-400 mb-4">
                    @if (request()->anyFilled(['search', 'status']))
                        Tiket tidak ditemukan
                    @else
                        Belum ada tiket
                    @endif
                </p>
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center gap-2 h-10 px-5 bg-gray-900 text-white text-sm
                font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                    <i class="ti ti-ticket text-base"></i> Beli Tiket Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($tickets as $ticket)
                    @php
                        $event = $ticket->ticketType->event;
                        $isPast = $event->start_date->lt(now());
                        $isToday = $event->start_date->isToday();
                    @endphp
                    <a href="{{ route('user.tickets.show', $ticket) }}"
                        class="group bg-white border border-gray-100 rounded-2xl overflow-hidden
                hover:shadow-md hover:-translate-y-0.5 transition-all duration-300
                {{ $ticket->status === 'used' ? 'opacity-70' : '' }}">

                        {{-- Event Banner --}}
                        <div class="relative h-36 bg-gray-100 overflow-hidden">
                            @if ($event->thumbnail)
                                <img src="{{ Storage::url($event->thumbnail) }}"
                                    class="w-full h-full object-cover group-hover:scale-105
                        transition-transform duration-500"
                                    alt="">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                    flex items-center justify-center">
                                    <i class="ti ti-calendar-event text-4xl text-gray-300"></i>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent">
                            </div>

                            {{-- Status --}}
                            <div class="absolute top-3 right-3">
                                @php
                                    $tBadge = [
                                        'active' => 'bg-emerald-500 text-white',
                                        'used' => 'bg-gray-800 text-gray-300',
                                        'cancelled' => 'bg-red-500 text-white',
                                        'expired' => 'bg-gray-600 text-gray-300',
                                    ];
                                @endphp
                                <span
                                    class="text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $tBadge[$ticket->status] ?? '' }} capitalize">
                                    {{ $ticket->status }}
                                </span>
                            </div>

                            {{-- Today badge --}}
                            @if ($isToday && $ticket->status === 'active')
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="text-xs font-bold px-2.5 py-1 bg-amber-400 text-amber-900
                        rounded-full animate-pulse">
                                        Hari Ini!
                                    </span>
                                </div>
                            @endif

                            {{-- Date --}}
                            <div class="absolute bottom-3 left-3">
                                <div class="bg-white/95 backdrop-blur-sm rounded-xl px-2.5 py-1.5 text-center">
                                    <p class="text-[10px] font-semibold text-gray-500 uppercase leading-none">
                                        {{ $event->start_date->format('M') }}
                                    </p>
                                    <p class="text-sm font-black text-gray-900 leading-none mt-0.5">
                                        {{ $event->start_date->format('d') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-4">
                            <p class="text-sm font-bold text-gray-900 line-clamp-1 leading-snug mb-1">
                                {{ $event->title }}
                            </p>
                            <p class="text-xs text-gray-400 mb-2">
                                {{ $ticket->ticketType->name }}
                            </p>

                            {{-- QR Preview --}}
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                                <div>
                                    <p class="text-xs text-gray-400">Kode Tiket</p>
                                    <p class="text-xs font-mono font-bold text-gray-900 mt-0.5">
                                        {{ $ticket->ticket_code }}
                                    </p>
                                </div>
                                <div
                                    class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center
                        group-hover:bg-gray-200 transition-colors duration-200">
                                    <i class="ti ti-qrcode text-gray-500 text-lg"></i>
                                </div>
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>

            @if ($tickets->hasPages())
                <div class="mt-6">{{ $tickets->links() }}</div>
            @endif
        @endif

    </div>

</x-auth-layout>
