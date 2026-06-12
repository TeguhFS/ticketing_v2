<x-user-layout>
    <x-slot name="title">Tentang Kami — {{ setting('app_name') }}</x-slot>

    {{-- ── Hero ─────────────────────────────────────────────── --}}
    @if ($hero && $hero->is_active)
        <section class="bg-gray-950 pt-8 pb-20 lg:pt-16 lg:pb-28 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                @if ($hero->image)
                    <img src="{{ Storage::url($hero->image) }}" class="w-full h-full object-cover opacity-20"
                        alt="">
                    <div class="absolute inset-0 bg-gradient-to-b from-gray-950/60 via-gray-950/80 to-gray-950"></div>
                @endif
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/3 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/2 rounded-full blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.02]"
                    style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px);
                background-size: 40px 40px;">
                </div>
            </div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                <div
                    class="inline-flex items-center gap-3 bg-white/10 border border-white/10
    text-white px-4 py-2 rounded-full mb-6">

                    @if (setting('app_logo'))
                        <div class="w-8 h-8 flex items-center justify-center overflow-hidden rounded-full bg-white/10">
                            <img src="{{ Storage::url(setting('app_logo')) }}"
                                alt="{{ setting('app_name', 'TicketIn') }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10">
                            <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                        </div>
                    @endif

                    <span class="text-xs font-medium">
                        {{ setting('app_name', 'TicketIn') }}
                    </span>

                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    {{ $hero->title }}
                </h1>

                @if ($hero->subtitle)
                    <p class="text-lg text-gray-400 mb-6 leading-relaxed max-w-2xl mx-auto">
                        {{ $hero->subtitle }}
                    </p>
                @endif

                @if ($hero->content)
                    <p class="text-base text-gray-500 leading-relaxed max-w-xl mx-auto">
                        {{ $hero->content }}
                    </p>
                @endif

            </div>
        </section>
    @endif

    {{-- ── Stats ────────────────────────────────────────────── --}}
    @if ($stats && $stats->is_active && $stats->items)
        <section class="py-14 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                @if ($stats->title)
                    <div class="text-center mb-10">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                            Dalam Angka
                        </p>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats->title }}</h2>
                        @if ($stats->subtitle)
                            <p class="text-sm text-gray-400 mt-2 max-w-xl mx-auto">{{ $stats->subtitle }}</p>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($stats->items as $stat)
                        <div
                            class="text-center bg-gray-50 border border-gray-100 rounded-2xl p-6
                hover:shadow-sm hover:-translate-y-0.5 transition-all duration-300">
                            @if (!empty($stat['icon']))
                                <div
                                    class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="ti {{ $stat['icon'] }} text-white text-xl"></i>
                                </div>
                            @endif
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stat['value'] ?? '' }}</p>
                            <p class="text-sm text-gray-500">{{ $stat['label'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Vision & Mission ─────────────────────────────────── --}}
    @if (($vision && $vision->is_active) || ($mission && $mission->is_active))
        <section class="py-14 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-10">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                        Arah & Tujuan
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Visi & Misi Kami</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Vision --}}
                    @if ($vision && $vision->is_active)
                        <div class="bg-gray-950 rounded-2xl p-7 relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/3 rounded-full blur-2xl"></div>
                            @if ($vision->image)
                                <div class="absolute inset-0 overflow-hidden rounded-2xl">
                                    <img src="{{ Storage::url($vision->image) }}"
                                        class="w-full h-full object-cover opacity-10" alt="">
                                </div>
                            @endif
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-white/10 rounded-2xl flex items-center
                        justify-center mb-5">
                                    <i class="ti ti-eye text-white text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">{{ $vision->title }}</h3>
                                @if ($vision->content)
                                    <p class="text-gray-400 leading-relaxed text-sm">{{ $vision->content }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Mission --}}
                    @if ($mission && $mission->is_active)
                        <div class="bg-white border border-gray-100 rounded-2xl p-7">
                            <div
                                class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center
                    justify-center mb-5">
                                <i class="ti ti-target text-gray-700 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $mission->title }}</h3>
                            @if ($mission->subtitle)
                                <p class="text-gray-400 leading-relaxed text-sm mb-3">
                                    {{ $mission->subtitle }}</p>
                            @endif
                            @if ($mission->items)
                                <div class="space-y-3">
                                    @foreach ($mission->items as $item)
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-6 h-6 bg-gray-900 rounded-lg flex items-center
                            justify-center flex-shrink-0 mt-0.5">
                                                <i class="ti ti-check text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $item['title'] ?? '' }}
                                                </p>
                                                @if (!empty($item['desc']))
                                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                                        {{ $item['desc'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </section>
    @endif

    {{-- ── Values ───────────────────────────────────────────── --}}
    @if ($values && $values->is_active && $values->items)
        <section class="py-14 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-10">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                        Prinsip Kami
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $values->title }}</h2>
                    @if ($values->subtitle)
                        <p class="text-sm text-gray-400 mt-2 max-w-xl mx-auto">{{ $values->subtitle }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($values->items as $value)
                        <div
                            class="group bg-white border border-gray-100 rounded-2xl p-6
                hover:shadow-md hover:-translate-y-1 hover:border-gray-200
                transition-all duration-300">
                            @if (!empty($value['icon']))
                                <div
                                    class="w-12 h-12 bg-gray-100 group-hover:bg-gray-900 rounded-2xl
                    flex items-center justify-center mb-4 transition-colors duration-300">
                                    <i
                                        class="ti {{ $value['icon'] }} text-gray-600 group-hover:text-white
                        text-xl transition-colors duration-300"></i>
                                </div>
                            @endif
                            <h3 class="text-base font-bold text-gray-900 mb-2">
                                {{ $value['title'] ?? '' }}
                            </h3>
                            @if (!empty($value['desc']))
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $value['desc'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- ── Team ─────────────────────────────────────────────── --}}
    @if ($team && $team->is_active && $team->items)
        <section class="py-14 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-10">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                        Orang-orang Kami
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $team->title }}</h2>
                    @if ($team->subtitle)
                        <p class="text-sm text-gray-400 mt-2 max-w-xl mx-auto">{{ $team->subtitle }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($team->items as $member)
                        <div
                            class="bg-white border border-gray-100 rounded-2xl p-6 text-center
                hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">

                            {{-- Avatar --}}
                            @if (!empty($member['image']) && Storage::disk('public')->exists($member['image']))
                                <img src="{{ Storage::url($member['image']) }}"
                                    class="w-20 h-20 rounded-2xl object-cover mx-auto mb-4 border-2 border-gray-100"
                                    alt="{{ $member['name'] ?? '' }}">
                            @else
                                <div
                                    class="w-20 h-20 rounded-2xl bg-gray-900 flex items-center justify-center
                    text-white text-xl font-bold mx-auto mb-4">
                                    {{ strtoupper(substr($member['name'] ?? 'T', 0, 2)) }}
                                </div>
                            @endif

                            <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $member['name'] ?? '' }}</h3>
                            <p class="text-xs text-gray-400 mb-3">{{ $member['role'] ?? '' }}</p>

                            @if (!empty($member['bio']))
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $member['bio'] }}</p>
                            @endif

                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- ── CTA ──────────────────────────────────────────────── --}}
    @if ($cta && $cta->is_active)
        <section class="py-20 bg-gray-950 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-32 -right-32 w-80 h-80 bg-white/3 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-white/2 rounded-full blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.02]"
                    style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px);
                background-size: 40px 40px;">
                </div>
            </div>

            <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-rocket text-white text-2xl"></i>
                </div>

                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4 leading-tight">
                    {{ $cta->title }}
                </h2>

                @if ($cta->subtitle)
                    <p class="text-base text-gray-400 mb-8 leading-relaxed max-w-xl mx-auto">
                        {{ $cta->subtitle }}
                    </p>
                @endif

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('events.index') }}"
                        class="w-full sm:w-auto h-12 px-8 bg-white text-gray-900 font-bold text-sm
                    rounded-xl hover:bg-gray-100 transition-all duration-200
                    flex items-center justify-center gap-2">
                        <i class="ti ti-calendar-event text-base"></i>
                        Jelajahi Event
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-auto h-12 px-8 border border-white/20 text-white font-medium
                    text-sm rounded-xl hover:bg-white/10 transition-all duration-200
                    flex items-center justify-center gap-2">
                            <i class="ti ti-user-plus text-base"></i>
                            Daftar Gratis
                        </a>
                    @endguest
                </div>

            </div>
        </section>
    @endif

</x-user-layout>
