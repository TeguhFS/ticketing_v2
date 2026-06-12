<x-user-layout>
    <x-slot name="title">FAQ — {{ setting('app_name') }}</x-slot>

    {{-- Page Header --}}
    <section class="bg-gray-950 pt-8 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/3 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/2 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.02]"
                style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px);
                background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                Bantuan &<br>
                <span class="text-gray-400">Pertanyaan Umum</span>
            </h1>

            <p class="text-sm text-gray-400 mb-8 leading-relaxed max-w-lg mx-auto">
                Temukan jawaban atas pertanyaan yang sering ditanyakan seputar
                platform ticketing kami.
            </p>

            {{-- Search --}}
            <form action="{{ route('faqs.index') }}" method="GET" class="max-w-lg mx-auto">
                <div
                    class="flex items-center gap-2 bg-white rounded-2xl p-1.5
                shadow-xl shadow-black/30">
                    <div class="flex items-center gap-3 flex-1 px-4">
                        <i class="ti ti-search text-gray-400 text-lg flex-shrink-0"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari pertanyaan..."
                            class="w-full bg-transparent border-none outline-none text-sm
                            text-gray-700 placeholder-gray-400 py-2.5">
                        @if (request('search'))
                            <a href="{{ route('faqs.index') }}"
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
            </form>

        </div>
    </section>

    {{-- FAQ Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main: FAQ List --}}
            <div class="lg:col-span-2">

                {{-- Search result info --}}
                @if (request('search'))
                    <div class="mb-6 flex items-center gap-3">
                        <p class="text-sm text-gray-500">
                            Hasil untuk
                            <span class="font-semibold text-gray-900">"{{ request('search') }}"</span>
                            —
                            <span class="font-semibold text-gray-900">{{ $faqs->count() }}</span>
                            pertanyaan ditemukan
                        </p>
                        <a href="{{ route('faqs.index') }}"
                            class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                            Hapus
                        </a>
                    </div>
                @endif

                @if ($faqs->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-16 bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="ti ti-help-circle text-5xl text-gray-200 block mb-3"></i>
                        <p class="text-base font-semibold text-gray-700 mb-2">
                            @if (request('search'))
                                Pertanyaan tidak ditemukan
                            @else
                                Belum ada FAQ tersedia
                            @endif
                        </p>
                        <p class="text-sm text-gray-400 mb-5">
                            @if (request('search'))
                                Coba kata kunci yang berbeda atau lihat semua FAQ
                            @else
                                Silakan hubungi kami jika memiliki pertanyaan
                            @endif
                        </p>
                        @if (request('search'))
                            <a href="{{ route('faqs.index') }}"
                                class="inline-flex items-center gap-2 h-10 px-5 bg-gray-900 text-white
                        text-sm font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                                <i class="ti ti-refresh text-base"></i> Lihat Semua FAQ
                            </a>
                        @endif
                    </div>
                @else
                    {{-- FAQ Accordion --}}
                    <div class="space-y-3" x-data="{ active: null }">
                        @foreach ($faqs as $index => $faq)
                            {{-- Highlight search term --}}
                            @php
                                $question = $faq->question;
                                $answer = $faq->answer;
                                if (request('search')) {
                                    $term = preg_quote(request('search'), '/');
                                    $question = preg_replace(
                                        '/(' . $term . ')/i',
                                        '<mark class="bg-amber-100 text-amber-900 rounded px-0.5">$1</mark>',
                                        e($question),
                                    );
                                    $answer = preg_replace(
                                        '/(' . $term . ')/i',
                                        '<mark class="bg-amber-100 text-amber-900 rounded px-0.5">$1</mark>',
                                        e($answer),
                                    );
                                }
                            @endphp

                            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden
                    transition-all duration-200
                    hover:border-gray-200 hover:shadow-sm"
                                :class="active === {{ $index }} ? 'border-gray-200 shadow-sm' : ''">

                                {{-- Question --}}
                                <button @click="active = active === {{ $index }} ? null : {{ $index }}"
                                    class="w-full flex items-center gap-4 px-5 py-4 text-left transition-all duration-200
                            hover:bg-gray-50/50"
                                    :class="active === {{ $index }} ? 'bg-gray-50/50' : ''">

                                    {{-- Number --}}
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center
                            text-xs font-bold flex-shrink-0 transition-all duration-200"
                                        :class="active === {{ $index }} ?
                                            'bg-gray-900 text-white' :
                                            'bg-gray-100 text-gray-500'">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>

                                    {{-- Question text --}}
                                    <p
                                        class="flex-1 text-sm font-semibold text-gray-900 leading-snug text-left
                            pr-3">
                                        {!! $question !!}
                                    </p>

                                    {{-- Chevron --}}
                                    <div class="w-7 h-7 rounded-lg border border-gray-100 flex items-center
                            justify-center flex-shrink-0 transition-all duration-200"
                                        :class="active === {{ $index }} ?
                                            'bg-gray-900 border-gray-900' :
                                            'bg-white'">
                                        <i class="ti ti-chevron-down text-sm transition-all duration-300"
                                            :class="active === {{ $index }} ?
                                                'rotate-180 text-white' :
                                                'text-gray-400'"></i>
                                    </div>

                                </button>

                                {{-- Answer --}}
                                <div x-cloak x-show="active === {{ $index }}"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1">

                                    <div class="px-5 pb-5 border-t border-gray-50">
                                        <div class="flex gap-4 pt-4">
                                            {{-- Connector line --}}
                                            <div class="flex flex-col items-center flex-shrink-0">
                                                <div class="w-px h-full bg-gray-100 mt-1"></div>
                                            </div>
                                            <div class="flex-1 pl-10">
                                                <p class="text-sm text-gray-600 leading-relaxed">
                                                    {!! $answer !!}
                                                </p>

                                                {{-- Helpful? --}}
                                                <div class="flex items-center gap-3 mt-4 pt-3 border-t border-gray-50"
                                                    x-data="{ voted: null }">
                                                    <p class="text-xs text-gray-400">Apakah jawaban ini membantu?</p>
                                                    <button @click="voted = 'yes'"
                                                        :class="voted === 'yes'
                                                            ?
                                                            'bg-emerald-50 border-emerald-200 text-emerald-700' :
                                                            'border-gray-100 text-gray-400 hover:border-gray-200 hover:text-gray-600'"
                                                        class="inline-flex items-center gap-1.5 h-7 px-3 border
                                                rounded-lg text-xs font-medium transition-all duration-200">
                                                        <i class="ti ti-thumb-up text-sm"></i>
                                                        <span x-text="voted === 'yes' ? 'Terima kasih!' : 'Ya'"></span>
                                                    </button>
                                                    <button @click="voted = 'no'"
                                                        :class="voted === 'no'
                                                            ?
                                                            'bg-red-50 border-red-200 text-red-600' :
                                                            'border-gray-100 text-gray-400 hover:border-gray-200 hover:text-gray-600'"
                                                        class="inline-flex items-center gap-1.5 h-7 px-3 border
                                                rounded-lg text-xs font-medium transition-all duration-200">
                                                        <i class="ti ti-thumb-down text-sm"></i>
                                                        <span
                                                            x-text="voted === 'no' ? 'Oke, kami catat' : 'Tidak'"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                @endif

            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Quick Links --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Navigasi Cepat</p>
                    <div class="space-y-1">
                        @foreach ([['icon' => 'ti-calendar-event', 'label' => 'Jelajahi Event', 'route' => 'events.index'], ['icon' => 'ti-ticket', 'label' => 'Tiket Saya', 'route' => 'user.tickets.index'], ['icon' => 'ti-shopping-cart', 'label' => 'Pesanan Saya', 'route' => 'user.orders.index'], ['icon' => 'ti-news', 'label' => 'Blog & Artikel', 'route' => 'blogs.index']] as $link)
                            @php
                                $isAuth = in_array($link['route'], ['user.tickets.index', 'user.orders.index']);
                            @endphp
                            @if (!$isAuth || auth()->check())
                                <a href="{{ route($link['route']) }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                            text-gray-600 hover:bg-gray-50 hover:text-gray-900
                            transition-all duration-150 group">
                                    <div
                                        class="w-8 h-8 bg-gray-100 rounded-xl flex items-center justify-center
                            flex-shrink-0 group-hover:bg-gray-200 transition-colors duration-200">
                                        <i class="ti {{ $link['icon'] }} text-gray-500 text-base"></i>
                                    </div>
                                    {{ $link['label'] }}
                                    <i
                                        class="ti ti-arrow-right text-gray-300 text-sm ml-auto
                            group-hover:text-gray-500 group-hover:translate-x-0.5
                            transition-all duration-200"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Stats --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">FAQ Stats</p>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Total Pertanyaan</span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ number_format($totalFaqs) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Ditampilkan</span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ $faqs->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Still have questions? --}}
                <div class="bg-gray-950 rounded-2xl p-5 relative overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-28 h-28 bg-white/3 rounded-full blur-2xl"></div>
                    <div class="relative">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-3">
                            <i class="ti ti-message-circle text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-white mb-1">Masih ada pertanyaan?</p>
                        <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                            Tidak menemukan jawaban yang kamu cari? Hubungi tim support kami.
                        </p>
                        <div class="space-y-2">
                            @if (setting('app_email'))
                                <a href="mailto:{{ setting('app_email') }}"
                                    class="flex items-center gap-2 h-9 px-4 bg-white text-gray-900 text-xs
                                font-semibold rounded-xl hover:bg-gray-100 transition-all duration-200">
                                    <i class="ti ti-mail text-sm"></i>
                                    Email Kami
                                </a>
                            @endif
                            @if (setting('social_whatsapp'))
                                <a href="https://wa.me/{{ setting('social_whatsapp') }}" target="_blank"
                                    class="flex items-center gap-2 h-9 px-4 bg-white/10 border border-white/10
                                text-white text-xs font-medium rounded-xl hover:bg-white/20
                                transition-all duration-200">
                                    <i class="ti ti-brand-whatsapp text-sm text-emerald-400"></i>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
                    <p class="text-xs font-semibold text-blue-700 mb-3">
                        <i class="ti ti-bulb text-sm mr-1"></i> Tips Penggunaan
                    </p>
                    <ul class="space-y-2 text-xs text-blue-600">
                        <li class="flex items-start gap-2">
                            <i class="ti ti-search text-sm flex-shrink-0 mt-0.5"></i>
                            Gunakan search untuk menemukan jawaban lebih cepat
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ti ti-click text-sm flex-shrink-0 mt-0.5"></i>
                            Klik pertanyaan untuk melihat jawaban lengkapnya
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ti ti-thumb-up text-sm flex-shrink-0 mt-0.5"></i>
                            Beri feedback agar kami terus berkembang
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </div>

    {{-- CTA --}}
    <section class="bg-gray-50 border-t border-gray-100 py-14">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                Siap Menemukan Event Seru?
            </h2>
            <p class="text-sm text-gray-500 mb-7 leading-relaxed max-w-xl mx-auto">
                Jelajahi ratusan event menarik dan dapatkan tiketmu dengan mudah,
                aman, dan cepat.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('events.index') }}"
                    class="w-full sm:w-auto h-11 px-7 bg-gray-900 text-white font-semibold text-sm
                    rounded-xl hover:bg-gray-700 transition-all duration-200
                    flex items-center justify-center gap-2">
                    <i class="ti ti-calendar-event text-base"></i>
                    Jelajahi Event
                </a>
                @guest
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto h-11 px-7 border border-gray-200 text-gray-700
                    font-medium text-sm rounded-xl hover:bg-gray-50 transition-all duration-200
                    flex items-center justify-center gap-2">
                        <i class="ti ti-user-plus text-base"></i>
                        Daftar Gratis
                    </a>
                @endguest
            </div>

        </div>
    </section>

</x-user-layout>
