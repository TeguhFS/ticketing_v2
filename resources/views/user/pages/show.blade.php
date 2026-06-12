<x-user-layout>
    <x-slot name="title">{{ $page->title }} — {{ setting('app_name') }}</x-slot>

    {{-- Hero --}}
    <section class="bg-gray-950 pt-8 pb-12 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/3 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.02]"
                style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px);
                background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
                <a href="{{ route('welcome') }}" class="hover:text-gray-300 transition">Home</a>
                <i class="ti ti-chevron-right text-gray-700 text-xs"></i>
                <span class="text-gray-400">{{ $page->title }}</span>
            </nav>

            {{-- Type Badge --}}
            @php
                $typeBadge = [
                    'privacy' => [
                        'icon' => 'ti-shield-lock',
                        'bg' => 'bg-blue-500/20',
                        'text' => 'text-blue-400',
                        'border' => 'border-blue-500/30',
                    ],
                    'terms' => [
                        'icon' => 'ti-license',
                        'bg' => 'bg-violet-500/20',
                        'text' => 'text-violet-400',
                        'border' => 'border-violet-500/30',
                    ],
                    'custom' => [
                        'icon' => 'ti-layout',
                        'bg' => 'bg-gray-500/20',
                        'text' => 'text-gray-400',
                        'border' => 'border-gray-500/30',
                    ],
                ];
                $tb = $typeBadge[$page->type] ?? $typeBadge['custom'];
            @endphp
            <div
                class="inline-flex items-center gap-2 {{ $tb['bg'] }} border {{ $tb['border'] }}
            {{ $tb['text'] }} text-xs font-medium px-3 py-1.5 rounded-full mb-5">
                <i class="ti {{ $tb['icon'] }} text-sm"></i>
                {{ $page->type === 'privacy' ? 'Kebijakan Privasi' : ($page->type === 'terms' ? 'Syarat & Ketentuan' : 'Halaman') }}
            </div>

            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4 leading-tight">
                {{ $page->title }}
            </h1>

            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5">
                    <i class="ti ti-calendar text-gray-600"></i>
                    Dipublikasikan {{ $page->published_at?->translatedFormat('d F Y') }}
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="ti ti-refresh text-gray-600"></i>
                    Diperbarui {{ $page->updated_at->translatedFormat('d F Y') }}
                </span>
            </div>

        </div>
    </section>

    {{-- ── Content ──────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- Main Content --}}
            <article class="lg:col-span-3">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8">
                    <div
                        class="prose prose-base max-w-none
                    prose-headings:font-bold prose-headings:text-gray-900
                    prose-h1:text-2xl prose-h2:text-xl prose-h2:mt-8 prose-h2:mb-3
                    prose-h3:text-lg prose-h3:mt-6 prose-h3:mb-2
                    prose-p:text-gray-600 prose-p:leading-relaxed prose-p:mb-4
                    prose-li:text-gray-600 prose-li:leading-relaxed
                    prose-ul:mb-4 prose-ol:mb-4
                    prose-ul:list-disc prose-ul:pl-5
                    prose-ol:list-decimal prose-ol:pl-5
                    prose-strong:text-gray-900 prose-strong:font-semibold
                    prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                    prose-blockquote:border-l-4 prose-blockquote:border-gray-200
                    prose-blockquote:bg-gray-50 prose-blockquote:rounded-r-xl
                    prose-blockquote:px-4 prose-blockquote:py-2 prose-blockquote:my-4
                    prose-code:bg-gray-100 prose-code:text-gray-800
                    prose-code:rounded-md prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm
                    [&>h2]:border-b [&>h2]:border-gray-100 [&>h2]:pb-2">
                        {!! $page->content !!}
                    </div>
                </div>

                {{-- Share --}}
                <div class="mt-5 bg-gray-50 border border-gray-100 rounded-2xl p-5">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Bagikan Halaman Ini</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Bantu teman Anda memahami kebijakan kami
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="https://wa.me/?text={{ urlencode($page->title . ' — ' . request()->url()) }}"
                                target="_blank"
                                class="w-9 h-9 border border-gray-200 rounded-xl flex items-center
                                justify-center text-gray-400 hover:bg-emerald-50 hover:text-emerald-600
                                hover:border-emerald-200 transition">
                                <i class="ti ti-brand-whatsapp text-base"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($page->title) }}"
                                target="_blank"
                                class="w-9 h-9 border border-gray-200 rounded-xl flex items-center
                                justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-900
                                hover:border-gray-300 transition">
                                <i class="ti ti-brand-x text-base"></i>
                            </a>
                            <button onclick="copyLink()"
                                class="w-9 h-9 border border-gray-200 rounded-xl flex items-center
                                justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700
                                hover:border-gray-300 transition">
                                <i class="ti ti-copy text-base" id="copy-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-4">

                {{-- Quick Nav --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 sticky top-24">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
                        Halaman Hukum
                    </p>
                    <div class="space-y-1">
                        <a href="{{ route('pages.privacy') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                            transition-all duration-150 group
                            {{ $page->type === 'privacy'
                                ? 'bg-gray-900 text-white font-medium'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i
                                class="ti ti-shield-lock text-base
                            {{ $page->type === 'privacy' ? 'text-white' : 'text-gray-400' }}"></i>
                            Kebijakan Privasi
                            @if ($page->type === 'privacy')
                                <i class="ti ti-arrow-right text-xs ml-auto"></i>
                            @endif
                        </a>
                        <a href="{{ route('pages.terms') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                            transition-all duration-150 group
                            {{ $page->type === 'terms'
                                ? 'bg-gray-900 text-white font-medium'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i
                                class="ti ti-license text-base
                            {{ $page->type === 'terms' ? 'text-white' : 'text-gray-400' }}"></i>
                            Syarat & Ketentuan
                            @if ($page->type === 'terms')
                                <i class="ti ti-arrow-right text-xs ml-auto"></i>
                            @endif
                        </a>
                    </div>

                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            Butuh Bantuan?
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('faqs.index') }}"
                                class="flex items-center gap-2 text-xs text-gray-500
                                hover:text-gray-900 transition group">
                                <i
                                    class="ti ti-help-circle text-gray-300 text-sm
                                group-hover:text-gray-500"></i>
                                Lihat FAQ
                            </a>
                            @if (setting('app_email'))
                                <a href="mailto:{{ setting('app_email') }}"
                                    class="flex items-center gap-2 text-xs text-gray-500
                                hover:text-gray-900 transition group">
                                    <i
                                        class="ti ti-mail text-gray-300 text-sm
                                group-hover:text-gray-500"></i>
                                    {{ setting('app_email') }}
                                </a>
                            @endif
                            @if (setting('social_whatsapp'))
                                <a href="https://wa.me/{{ setting('social_whatsapp') }}" target="_blank"
                                    class="flex items-center gap-2 text-xs text-gray-500
                                hover:text-gray-900 transition group">
                                    <i
                                        class="ti ti-brand-whatsapp text-gray-300 text-sm
                                group-hover:text-emerald-500"></i>
                                    WhatsApp Kami
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </aside>

        </div>
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
