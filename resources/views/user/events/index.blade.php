<x-user-layout>
    <x-slot name="title">Jelajahi Event {{ setting('app_name', config('app.name')) }}</x-slot>

    {{-- Page Header --}}
    <section class="bg-gray-950 pt-8 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">
                    {{ number_format($totalEvents) }} event tersedia
                </p>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">Jelajahi Event</h1>

                {{-- Search --}}
                <form action="{{ route('events.index') }}" method="GET" id="filter-form">
                    <div class="flex items-center gap-2 bg-white rounded-2xl p-1.5 shadow-xl shadow-black/30">
                        <div class="flex items-center gap-3 flex-1 px-4">
                            <i class="ti ti-search text-gray-400 text-lg flex-shrink-0"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari event, konser, seminar, lokasi..."
                                class="w-full bg-transparent border-none outline-none focus:outline-none focus:ring-0  text-sm text-gray-700
                                placeholder-gray-400 py-2.5">
                            @if (request('search'))
                                <a href="{{ route('events.index', request()->except('search', 'page')) }}"
                                    class="text-gray-300 hover:text-gray-500 transition flex-shrink-0">
                                    <i class="ti ti-x text-sm"></i>
                                </a>
                            @endif
                        </div>
                        <button type="submit"
                            class="h-11 px-5 bg-gray-900 text-white text-sm font-semibold rounded-xl
                            hover:bg-gray-700 transition-all duration-200 flex-shrink-0
                            flex items-center gap-2">
                            <span class="hidden sm:block">Cari</span>
                            <i class="ti ti-arrow-right text-base"></i>
                        </button>
                    </div>

                    {{-- Hidden filters carried over --}}
                    @foreach (request()->except('search', 'page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                </form>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- Sidebar Filter  --}}
            <aside class="w-full lg:w-64 flex-shrink-0">

                {{-- Mobile filter toggle --}}
                <div class="lg:hidden mb-4" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between h-11 px-4 bg-white border
                        border-gray-200 rounded-2xl text-sm font-medium text-gray-700
                        hover:bg-gray-50 transition-all duration-200">
                        <span class="flex items-center gap-2">
                            <i class="ti ti-filter text-base text-gray-400"></i>
                            Filter Event
                            @if (request()->anyFilled(['category', 'price', 'date', 'featured']))
                                <span
                                    class="w-5 h-5 bg-gray-900 text-white text-xs rounded-full flex items-center justify-center">
                                    {{ collect(['category', 'price', 'date', 'featured'])->filter(fn($k) => request()->filled($k))->count() }}
                                </span>
                            @endif
                        </span>
                        <i class="ti text-base text-gray-400 transition-transform duration-200"
                            :class="open ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
                    </button>

                    <div x-show="open" x-transition class="mt-3">
                        @include('user.events._filter', ['categories' => $categories])
                    </div>
                </div>

                {{-- Desktop filter --}}
                <div class="hidden lg:block">
                    @include('user.events._filter', ['categories' => $categories])
                </div>

            </aside>

            {{-- ── Main Content ─────────────────────────────────── --}}
            <div class="flex-1 min-w-0">

                {{-- Toolbar --}}
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3 flex-wrap">
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold text-gray-900">{{ $events->total() }}</span> event ditemukan
                        </p>

                        {{-- Active filters --}}
                        @if (request()->anyFilled(['search', 'category', 'price', 'date', 'featured']))
                            <div class="flex items-center gap-2 flex-wrap">
                                @if (request('search'))
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs bg-gray-100 text-gray-700
                            px-3 py-1.5 rounded-full">
                                        <i class="ti ti-search text-xs"></i> "{{ request('search') }}"
                                        <a href="{{ route('events.index', request()->except('search', 'page')) }}"
                                            class="text-gray-400 hover:text-gray-700 ml-0.5">×</a>
                                    </span>
                                @endif

                                @if (request('category'))
                                    @php $activeCat = $categories->find(request('category')); @endphp
                                    @if ($activeCat)
                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs bg-gray-100 text-gray-700
                            px-3 py-1.5 rounded-full">
                                            <i class="ti ti-tag text-xs"></i> {{ $activeCat->name }}
                                            <a href="{{ route('events.index', request()->except('category', 'page')) }}"
                                                class="text-gray-400 hover:text-gray-700 ml-0.5">×</a>
                                        </span>
                                    @endif
                                @endif

                                <a href="{{ route('events.index', request()->only('search', 'sort')) }}"
                                    class="text-xs text-red-500 hover:text-red-700 transition font-medium">
                                    Reset filter
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Sort --}}
                    <div class="relative flex-shrink-0">
                        <select name="sort" onchange="updateSort(this.value)"
                            class="h-9 pl-3 pr-10 bg-white border border-gray-200 rounded-xl text-xs
        text-gray-600 outline-none focus:border-gray-400 appearance-none cursor-pointer">
                            @foreach ([
        'nearest' => 'Terdekat',
        'latest' => 'Terbaru',
        'popular' => 'Terpopuler',
        'cheapest' => 'Termurah',
    ] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ request('sort', 'nearest') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Event Grid --}}
                @if ($events->isEmpty())
                    <div class="text-center py-20 bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="ti ti-calendar-search text-6xl text-gray-200 block mb-4"></i>
                        <h3 class="text-base font-semibold text-gray-700 mb-2">Event tidak ditemukan</h3>
                        <p class="text-sm text-gray-400 mb-5">
                            Coba ubah filter atau kata kunci pencarian Anda
                        </p>
                        <a href="{{ route('events.index') }}"
                            class="inline-flex items-center gap-2 h-10 px-5 bg-gray-900 text-white text-sm
                        font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                            <i class="ti ti-refresh text-base"></i> Reset Semua Filter
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($events as $event)
                            @php
                                $minPrice = $event->ticketTypes->min('price');
                                $isFree = $minPrice == 0;
                                $totalQuota = $event->ticketTypes->sum('quota');
                                $totalSold = $event->ticketTypes->sum('sold');
                                $percentage = $totalQuota > 0 ? ($totalSold / $totalQuota) * 100 : 0;
                                $isAlmostFull = $percentage >= 80;
                                $isSoldOut = $percentage >= 100;
                            @endphp

                            <a href="{{ route('events.show', $event->slug) }}"
                                class="group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden
                        hover:shadow-md hover:shadow-gray-200/50 hover:-translate-y-0.5
                        transition-all duration-300 {{ $isSoldOut ? 'opacity-75' : '' }}">

                                {{-- Thumbnail --}}
                                <div class="relative h-44 bg-gray-100 overflow-hidden flex-shrink-0">
                                    @if ($event->thumbnail)
                                        <img src="{{ Storage::url($event->thumbnail) }}"
                                            class="w-full h-full object-cover group-hover:scale-105
                                transition-transform duration-500"
                                            alt="{{ $event->title }}">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                            flex items-center justify-center">
                                            <i class="ti ti-calendar-event text-4xl text-gray-300"></i>
                                        </div>
                                    @endif

                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent">
                                    </div>

                                    {{-- Badges --}}
                                    <div class="absolute top-3 left-3 flex gap-2 flex-wrap">
                                        @if ($event->is_featured)
                                            <span
                                                class="text-xs font-semibold px-2.5 py-1 bg-amber-400 text-amber-900 rounded-full">
                                                <i class="ti ti-star-filled text-xs"></i> Featured
                                            </span>
                                        @endif
                                        @if ($event->category)
                                            <span
                                                class="text-xs px-2.5 py-1 bg-white/90 text-gray-700 rounded-full backdrop-blur-sm">
                                                {{ $event->category->name }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($isSoldOut)
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                            <span
                                                class="text-sm font-bold text-white bg-black/60 px-4 py-2 rounded-xl backdrop-blur-sm">
                                                SOLD OUT
                                            </span>
                                        </div>
                                    @elseif($isAlmostFull)
                                        <div class="absolute top-3 right-3">
                                            <span
                                                class="text-xs font-semibold px-2.5 py-1 bg-red-500 text-white rounded-full">
                                                Hampir Habis!
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Date --}}
                                    <div class="absolute bottom-3 left-3">
                                        <div
                                            class="bg-white/95 backdrop-blur-sm rounded-xl px-2.5 py-1.5 text-center min-w-[48px]">
                                            <p class="text-[10px] font-semibold text-gray-500 uppercase leading-none">
                                                {{ $event->start_date->format('M') }}
                                            </p>
                                            <p class="text-base font-black text-gray-900 leading-none mt-0.5">
                                                {{ $event->start_date->format('d') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="p-4 flex flex-col flex-1">
                                    <h3
                                        class="text-sm font-bold text-gray-900 mb-2 line-clamp-2 leading-snug flex-1
                            group-hover:text-gray-700 transition-colors duration-200">
                                        {{ $event->title }}
                                    </h3>

                                    <div class="space-y-1 mb-3">
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <i class="ti ti-map-pin text-gray-300 flex-shrink-0"></i>
                                            <span class="truncate">{{ $event->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <i class="ti ti-clock text-gray-300 flex-shrink-0"></i>
                                            <span>{{ $event->start_date->translatedFormat('l, d M Y') }}</span>
                                        </div>
                                    </div>

                                    {{-- Progress bar --}}
                                    @if ($totalQuota > 0 && !$isSoldOut)
                                        <div class="mb-3">
                                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500
                                    {{ $percentage >= 80 ? 'bg-red-400' : ($percentage >= 50 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                                    style="width: {{ min(100, $percentage) }}%"></div>
                                            </div>
                                            <p class="text-[10px] text-gray-400 mt-1">
                                                {{ number_format($totalQuota - $totalSold) }} tiket tersisa
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Footer --}}
                                    <div
                                        class="flex items-center justify-between pt-3 border-t border-gray-50 mt-auto">
                                        <div>
                                            @if ($isFree)
                                                <span class="text-sm font-bold text-emerald-600">GRATIS</span>
                                            @else
                                                <p class="text-[10px] text-gray-400 leading-none">Mulai dari</p>
                                                <p class="text-sm font-bold text-gray-900 leading-tight mt-0.5">
                                                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div
                                            class="w-8 h-8 bg-gray-900 rounded-xl flex items-center justify-center
                                group-hover:bg-gray-700 transition-colors duration-200
                                {{ $isSoldOut ? 'opacity-40' : '' }}">
                                            <i class="ti ti-arrow-right text-white text-sm"></i>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($events->hasPages())
                        <div class="mt-8">
                            {{ $events->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateSort(value) {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', value);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }
        </script>
    @endpush

</x-user-layout>
