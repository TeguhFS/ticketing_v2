<footer class="bg-gray-950 text-gray-400">

    {{-- Main Footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Kolom 1: Brand & Deskripsi --}}
            <div>
                <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 mb-4 group w-fit">
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden bg-gray-900">

                        @if (setting('app_logo'))
                            <img src="{{ Storage::url(setting('app_logo')) }}" alt="{{ setting('app_name', 'TicketIn') }}"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                        @endif

                    </div>
                    <span class="text-base font-bold text-white">
                        {{ setting('app_name', config('app.name')) }}
                    </span>
                </a>

                <p class="text-sm text-gray-400 leading-relaxed mb-6 max-w-xs">
                    {{ setting('app_description', 'Platform tiket event terbaik. Temukan dan beli tiket konser, seminar, festival, dan banyak lagi.') }}
                </p>

                {{-- Social Media (WhatsApp Sudah Disatukan ke Dalam Loop Agar Pasti Muncul) --}}
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach ([['key' => 'social_instagram', 'icon' => 'ti-brand-instagram', 'label' => 'Instagram', 'is_wa' => false], ['key' => 'social_twitter', 'icon' => 'ti-brand-x', 'label' => 'Twitter', 'is_wa' => false], ['key' => 'social_facebook', 'icon' => 'ti-brand-facebook', 'label' => 'Facebook', 'is_wa' => false], ['key' => 'social_youtube', 'icon' => 'ti-brand-youtube', 'label' => 'YouTube', 'is_wa' => false], ['key' => 'social_tiktok', 'icon' => 'ti-brand-tiktok', 'label' => 'TikTok', 'is_wa' => false], ['key' => 'social_whatsapp', 'icon' => 'ti-brand-whatsapp', 'label' => 'WhatsApp', 'is_wa' => true]] as $social)
                        @if (setting($social['key']))
                            <a href="{{ $social['is_wa'] ? 'https://wa.me/' . setting($social['key']) : setting($social['key']) }}"
                                target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}"
                                class="w-9 h-9 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center
                                text-gray-400 hover:bg-white/15 hover:text-white hover:border-white/20
                                transition-all duration-200">
                                <i class="ti {{ $social['icon'] }} text-base"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Kolom 2: Links Platform --}}
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Platform</p>
                <ul class="space-y-2.5">
                    @foreach ([['route' => 'events.index', 'label' => 'Jelajahi Event'], ['route' => 'blogs.index', 'label' => 'Blog'], ['route' => 'faqs.index', 'label' => 'FAQ'], ['route' => 'about.index', 'label' => 'Tentang Kami']] as $link)
                        <li>
                            <a href="{{ route($link['route']) }}"
                                class="text-sm text-gray-400 hover:text-white transition-colors duration-200
                                flex items-center gap-2 group w-fit">
                                <i
                                    class="ti ti-arrow-right text-xs text-gray-600
                                    group-hover:text-gray-400 group-hover:translate-x-0.5
                                    transition-all duration-200"></i>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kolom 3: Legal & Dukungan --}}
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Legal & Bantuan</p>
                <ul class="space-y-2.5">
                    @foreach ([['url' => route('pages.privacy'), 'label' => 'Kebijakan Privasi'], ['url' => route('pages.terms'), 'label' => 'Syarat & Ketentuan'], ['url' => route('faqs.index'), 'label' => 'Pusat Bantuan']] as $legal)
                        <li>
                            <a href="{{ $legal['url'] }}"
                                class="text-sm text-gray-400 hover:text-white transition-colors duration-200
                                flex items-center gap-2 group w-fit">
                                <i
                                    class="ti ti-arrow-right text-xs text-gray-600
                                    group-hover:text-gray-400 group-hover:translate-x-0.5
                                    transition-all duration-200"></i>
                                {{ $legal['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kolom 4: Kontak --}}
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Kontak</p>
                <ul class="space-y-3">
                    @if (setting('app_email'))
                        <li>
                            <a href="mailto:{{ setting('app_email') }}" class="flex items-start gap-3 group w-fit">
                                <div
                                    class="w-7 h-7 bg-white/5 rounded-lg flex items-center justify-center
                                    flex-shrink-0 group-hover:bg-white/10 transition-colors duration-200 mt-0.5">
                                    <i class="ti ti-mail text-sm text-gray-500 group-hover:text-gray-300"></i>
                                </div>
                                <span
                                    class="text-sm text-gray-400 group-hover:text-white transition-colors duration-200">
                                    {{ setting('app_email') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (setting('app_phone'))
                        <li>
                            <a href="tel:{{ setting('app_phone') }}" class="flex items-start gap-3 group w-fit">
                                <div
                                    class="w-7 h-7 bg-white/5 rounded-lg flex items-center justify-center
                                    flex-shrink-0 group-hover:bg-white/10 transition-colors duration-200 mt-0.5">
                                    <i class="ti ti-phone text-sm text-gray-500 group-hover:text-gray-300"></i>
                                </div>
                                <span
                                    class="text-sm text-gray-400 group-hover:text-white transition-colors duration-200">
                                    {{ setting('app_phone') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (setting('app_address'))
                        <li class="flex items-start gap-3">
                            <div
                                class="w-7 h-7 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ti ti-map-pin text-sm text-gray-500"></i>
                            </div>
                            <span class="text-sm text-gray-400 leading-relaxed">
                                {{ setting('app_address') }}
                            </span>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-gray-600">
                    &copy; {{ date('Y') }} {{ setting('app_name', config('app.name')) }}. All rights reserved.
                </p>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span>Maju Bersama Industri Kreatif {{ setting('app_name', config('app.name')) }}</span>
                </div>
            </div>
        </div>
    </div>

</footer>
