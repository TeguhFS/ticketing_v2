<x-user-layout>
    <x-slot name="title">{{ $event->title }} — {{ setting('app_name') }}</x-slot>
    <x-slot name="metaDescription">{{ Str::limit(strip_tags($event->description), 160) }}</x-slot>

    {{-- ── Hero / Banner ────────────────────────────────────── --}}
    <section class="relative bg-gray-950 overflow-hidden">
        @if ($event->banner)
            <div class="absolute inset-0">
                <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover opacity-20"
                    alt="{{ $event->title }}">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-950/60 via-gray-950/80 to-gray-950"></div>
            </div>
        @endif

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
                <a href="{{ route('welcome') }}" class="hover:text-gray-300 transition">Home</a>
                <i class="ti ti-chevron-right text-gray-700 text-xs"></i>
                <a href="{{ route('events.index') }}" class="hover:text-gray-300 transition">Event</a>
                <i class="ti ti-chevron-right text-gray-700 text-xs"></i>
                <span class="text-gray-400 truncate max-w-[200px]">{{ $event->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- Left: Info --}}
                <div class="lg:col-span-2">

                    {{-- Badges --}}
                    <div class="flex items-center gap-2 flex-wrap mb-4">
                        @if ($event->is_featured)
                            <span
                                class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1
                        bg-amber-400 text-amber-900 rounded-full">
                                <i class="ti ti-star text-xs"></i> Featured
                            </span>
                        @endif
                        @if ($event->category)
                            <span
                                class="text-xs font-medium px-2.5 py-1 bg-white/10 text-gray-300
                        rounded-full border border-white/10">
                                {{ $event->category->name }}
                            </span>
                        @endif
                        <span
                            class="text-xs font-medium px-2.5 py-1 rounded-full border
                        {{ $event->status === 'published'
                            ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'
                            : 'bg-gray-500/20 text-gray-400 border-gray-500/30' }}
                        capitalize">
                            {{ $event->status }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-5 leading-tight">
                        {{ $event->title }}
                    </h1>

                    {{-- Event Meta --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ([['icon' => 'ti-calendar', 'label' => 'Tanggal Mulai', 'value' => $event->start_date->translatedFormat('l, d F Y')], ['icon' => 'ti-clock', 'label' => 'Waktu', 'value' => $event->start_date->format('H:i') . ' — ' . $event->end_date->format('H:i') . ' WIB'], ['icon' => 'ti-map-pin', 'label' => 'Lokasi', 'value' => $event->location], ['icon' => 'ti-users', 'label' => 'Kapasitas', 'value' => $event->max_attendees ? number_format($event->max_attendees) . ' peserta' : 'Tidak terbatas']] as $meta)
                            <div class="flex items-start gap-3 bg-white/5 border border-white/10 rounded-xl p-3.5">
                                <div
                                    class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ti {{ $meta['icon'] }} text-gray-300 text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">{{ $meta['label'] }}</p>
                                    <p class="text-sm font-medium text-white mt-0.5">{{ $meta['value'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- Right: Thumbnail --}}
                <div class="hidden lg:block">
                    @if ($event->thumbnail)
                        <img src="{{ Storage::url($event->thumbnail) }}"
                            class="w-full aspect-square object-cover rounded-2xl border border-white/10 shadow-2xl"
                            alt="{{ $event->title }}">
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ── Main Content ─────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- Left: Detail --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Description --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ti ti-info-circle text-gray-400"></i> Tentang Event
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                {{-- Location --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ti ti-map-pin text-gray-400"></i> Lokasi
                    </h2>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-building text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $event->location }}</p>
                            @if ($event->location_detail)
                                <p class="text-sm text-gray-500 mt-1">{{ $event->location_detail }}</p>
                            @endif
                            @if ($event->maps_url)
                                <a href="{{ $event->maps_url }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 mt-3 text-xs font-medium text-blue-600
                                hover:text-blue-800 transition-colors">
                                    <i class="ti ti-map text-sm"></i> Lihat di Google Maps
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Organizer --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ti ti-user text-gray-400"></i> Penyelenggara
                    </h2>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/logo-ticketing.png') }}" alt="Logo Ticketing"
                            class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $event->admin->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Penyelenggara resmi</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Ticket Sidebar (Sticky) --}}
            <div class="lg:sticky lg:top-24 space-y-4">

                {{-- Ticket Types --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">

                    <div class="px-5 py-4 border-b border-gray-50">
                        <h2 class="text-base font-bold text-gray-900">Pilih Tiket</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $event->ticketTypes->count() }} tipe tiket tersedia
                        </p>
                    </div>

                    <div class="p-4 space-y-3">
                        @forelse($event->ticketTypes->where('is_active', true) as $type)
                            @php
                                $remaining = $type->quota - $type->sold;
                                $isSoldOut = $remaining <= 0;
                                $percentage = $type->quota > 0 ? ($type->sold / $type->quota) * 100 : 0;
                                $isOnSale =
                                    (!$type->sale_start || now()->gte($type->sale_start)) &&
                                    (!$type->sale_end || now()->lte($type->sale_end));
                            @endphp
                            <div
                                class="border rounded-xl p-4 transition-all duration-200
                        {{ $isSoldOut || !$isOnSale
                            ? 'border-gray-100 bg-gray-50 opacity-60'
                            : 'border-gray-200 hover:border-gray-300 hover:shadow-sm' }}">

                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 min-w-0 pr-3">
                                        <p class="text-sm font-bold text-gray-900">{{ $type->name }}</p>
                                        @if ($type->description)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $type->description }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        @if ($type->price == 0)
                                            <p class="text-base font-bold text-emerald-600">GRATIS</p>
                                        @else
                                            <p class="text-base font-bold text-gray-900">
                                                Rp {{ number_format($type->price, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="mb-2">
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full
                                    {{ $percentage >= 80 ? 'bg-red-400' : ($percentage >= 50 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                            style="width: {{ min(100, $percentage) }}%"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        @if ($isSoldOut)
                                            <span class="text-red-500 font-medium">Habis terjual</span>
                                        @elseif(!$isOnSale && $type->sale_start && now()->lt($type->sale_start))
                                            <span class="text-amber-600 font-medium">
                                                Mulai {{ $type->sale_start->format('d M') }}
                                            </span>
                                        @elseif(!$isOnSale)
                                            <span class="text-gray-400">Penjualan berakhir</span>
                                        @else
                                            <span>{{ number_format($remaining) }} tersisa</span>
                                        @endif
                                        <span>· Maks {{ $type->max_per_order }}/order</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <i class="ti ti-ticket-off text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-sm text-gray-400">Belum ada tiket tersedia</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- CTA --}}
                    <div class="px-4 pb-4">
                        @php
                            $hasAvailable =
                                $event->ticketTypes
                                    ->where('is_active', true)
                                    ->filter(fn($t) => $t->quota - $t->sold > 0)
                                    ->count() > 0;
                        @endphp

                        @if ($hasAvailable)
                            @auth
                                <a href="{{ route('events.checkout', $event->slug) }}"
                                    class="w-full h-12 bg-gray-900 text-white font-semibold text-sm
                                rounded-xl hover:bg-gray-700 transition-all duration-200
                                flex items-center justify-center gap-2">
                                    <i class="ti ti-ticket text-base"></i>
                                    Beli Tiket Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full h-12 bg-gray-900 text-white font-semibold text-sm
                                rounded-xl hover:bg-gray-700 transition-all duration-200
                                flex items-center justify-center gap-2">
                                    <i class="ti ti-login text-base"></i>
                                    Login untuk Beli Tiket
                                </a>
                                <p class="text-center text-xs text-gray-400 mt-2">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}" class="text-gray-700 font-medium hover:underline">
                                        Daftar gratis
                                    </a>
                                </p>
                            @endauth
                        @else
                            <button disabled
                                class="w-full h-12 bg-gray-200 text-gray-400 font-semibold text-sm
                            rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="ti ti-ticket-off text-base"></i>
                                Tiket Habis
                            </button>
                        @endif
                    </div>

                </div>

                {{-- Share --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Bagikan Event</p>
                    <div class="flex items-center gap-2">
                        @foreach ([
        [
            'href' => 'https://wa.me/?text=' . urlencode($event->title . ' ' . request()->url())
                            ,
                            'icon' => 'ti-brand-whatsapp',
                            'color' => 'hover:bg-emerald-50 hover:text-emerald-600
                            hover:border-emerald-200',
                            ],
                            [
                            'href' => 'https://twitter.com/intent/tweet?text=' . urlencode($event->title) . '&url=' .
                            urlencode(request()->url()),
                            'icon' => 'ti-brand-x',
                            'color' => 'hover:bg-gray-100
                            hover:text-gray-900 hover:border-gray-300',
                            ],
                            [
                            'href' =>
                            'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(request()->url()),
            'icon'
                            => 'ti-brand-facebook',
                            'color' => 'hover:bg-blue-50 hover:text-blue-600
                            hover:border-blue-200',
                            ],
                            ] as $share)
                            <a href="{{ $share['href'] }}" target="_blank" rel="noopener"
                                class="flex-1 h-10 border border-gray-100 rounded-xl flex items-center justify-center
                            text-gray-400 transition-all duration-200 {{ $share['color'] }}">
                                <i class="ti {{ $share['icon'] }} text-lg"></i>
                            </a>
                        @endforeach
                        <button onclick="copyLink()"
                            class="flex-1 h-10 border border-gray-100 rounded-xl flex items-center justify-center
                            text-gray-400 hover:bg-gray-50 hover:text-gray-700 hover:border-gray-300
                            transition-all duration-200">
                            <i class="ti ti-copy text-lg" id="copy-icon"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        {{-- Related Events --}}
        @if ($relatedEvents->count() > 0)
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Event Serupa</h2>
                    <a href="{{ route('events.index', ['category' => $event->category_id]) }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition
                    flex items-center gap-1.5">
                        Lihat semua <i class="ti ti-arrow-right text-base"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($relatedEvents as $related)
                        @php $minPrice = $related->ticketTypes->min('price'); @endphp
                        <a href="{{ route('events.show', $related->slug) }}"
                            class="group bg-white border border-gray-100 rounded-2xl overflow-hidden
                    hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                            <div class="h-36 bg-gray-100 overflow-hidden">
                                @if ($related->thumbnail)
                                    <img src="{{ Storage::url($related->thumbnail) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ $related->title }}">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                        flex items-center justify-center">
                                        <i class="ti ti-calendar-event text-3xl text-gray-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug mb-1">
                                    {{ $related->title }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $related->start_date->format('d M Y') }}</p>
                                <p class="text-xs font-bold text-gray-900 mt-2">
                                    {{ $minPrice == 0 ? 'GRATIS' : 'Rp ' . number_format($minPrice, 0, ',', '.') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            function copyLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const icon = document.getElementById('copy-icon');
                    icon.classList.replace('ti-copy', 'ti-check');
                    setTimeout(() => icon.classList.replace('ti-check', 'ti-copy'), 2000);
                });
            }
        </script>
    @endpush

</x-user-layout>
