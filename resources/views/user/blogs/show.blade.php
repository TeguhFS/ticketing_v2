<x-user-layout>
    <x-slot name="title">{{ $blog->title }} — {{ setting('app_name') }}</x-slot>
    <x-slot name="metaDescription">{{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 160) }}</x-slot>

    {{-- Hero --}}
    <section class="bg-gray-950 relative overflow-hidden">
        @if ($blog->thumbnail)
            <div class="absolute inset-0">
                <img src="{{ Storage::url($blog->thumbnail) }}" class="w-full h-full object-cover opacity-15"
                    alt="{{ $blog->title }}">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-950/70 via-gray-950/85 to-gray-950"></div>
            </div>
        @endif

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
                <a href="{{ route('welcome') }}" class="hover:text-gray-300 transition">Home</a>
                <i class="ti ti-chevron-right text-gray-700 text-xs"></i>
                <a href="{{ route('blogs.index') }}" class="hover:text-gray-300 transition">Blog</a>
                <i class="ti ti-chevron-right text-gray-700 text-xs"></i>
                <span class="text-gray-400 truncate max-w-[200px]">{{ $blog->title }}</span>
            </nav>

            {{-- Badges --}}
            <div class="flex items-center gap-2 flex-wrap mb-4">
                @if ($blog->is_featured)
                    <span
                        class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1
                bg-amber-400 text-amber-900 rounded-full">
                        <i class="ti ti-star-filled text-xs"></i> Featured
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-5 leading-tight max-w-3xl">
                {{ $blog->title }}
            </h1>

            {{-- Meta --}}
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-ticketing.png') }}" alt="Logo Ticketing"
                        class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                    <div>
                        <p class="text-sm font-medium text-gray-300">{{ $blog->author->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Penulis</p>
                    </div>
                </div>
                <div class="w-px h-8 bg-gray-700"></div>
                <div>
                    <p class="text-xs text-gray-500">Dipublikasikan</p>
                    <p class="text-sm text-gray-300">
                        {{ $blog->published_at?->translatedFormat('d F Y') ?? $blog->created_at->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="w-px h-8 bg-gray-700 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <p class="text-xs text-gray-500">Estimasi baca</p>
                    <p class="text-sm text-gray-300">
                        {{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} menit
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- Article Content  --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main Article --}}
            <article class="lg:col-span-2">

                {{-- Thumbnail --}}
                @if ($blog->thumbnail)
                    <div class="rounded-2xl overflow-hidden mb-8 border border-gray-100">
                        <img src="{{ Storage::url($blog->thumbnail) }}" class="w-full max-h-[480px] object-cover"
                            alt="{{ $blog->title }}">
                    </div>
                @endif

                {{-- Excerpt --}}
                @if ($blog->excerpt)
                    <div class="bg-gray-50 border-l-4 border-gray-900 rounded-r-xl px-5 py-4 mb-8">
                        <p class="text-base text-gray-700 italic leading-relaxed">{{ $blog->excerpt }}</p>
                    </div>
                @endif

                {{-- Content --}}
                <div
                    class="prose prose-base max-w-none text-gray-700 leading-relaxed
                prose-headings:font-bold prose-headings:text-gray-900
                prose-h2:text-xl prose-h3:text-lg
                prose-p:text-gray-600 prose-p:leading-relaxed
                prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                prose-img:rounded-xl prose-img:border prose-img:border-gray-100
                prose-blockquote:border-l-gray-900 prose-blockquote:bg-gray-50
                prose-blockquote:rounded-r-xl prose-blockquote:py-1
                prose-code:bg-gray-100 prose-code:text-gray-800 prose-code:rounded-md
                prose-code:px-1.5 prose-code:py-0.5
                prose-pre:bg-gray-950 prose-pre:text-gray-100 prose-pre:rounded-2xl
                prose-ul:text-gray-600 prose-ol:text-gray-600
                prose-strong:text-gray-900 prose-strong:font-semibold">
                    {!! $blog->content !!}
                </div>

                {{-- Share --}}
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Bagikan Artikel</p>
                    <div class="flex items-center gap-3 flex-wrap">
                        @foreach ([
        [
            'href' => 'https://wa.me/?text=' . urlencode($blog->title . ' — ' . request()->url())
                            ,
                            'icon' => 'ti-brand-whatsapp',
                            'admin_setting' => 'icon_whatsapp', // Nama key setting di admin
                            'label' => 'WhatsApp',
                            'color' => 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100',
                            ],
                            [
                            'href' => 'https://twitter.com/intent/tweet?text=' . urlencode($blog->title) . '&url=' .
                            urlencode(request()->url()),
                            'icon' => 'ti-brand-x',
                            'admin_setting' => 'icon_twitter',
                            'label' => 'Twitter',
                            'color' => 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100',
                            ],
                            [
                            'href' =>
                            'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(request()->url()),
            'icon'
                            => 'ti-brand-facebook',
                            'admin_setting' => 'icon_facebook',
                            'label' => 'Facebook',
                            'color' => 'bg-blue-50 border-blue-200 text-blue-700 hover:bg-blue-100',
                            ],
                            ] as $share)
                            <a href="{{ $share['href'] }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 h-9 px-4 border text-xs font-medium
        rounded-xl transition-all duration-200 {{ $share['color'] }}">

                                {{-- Logika Pengecekan Ikon Admin --}}
                                @if (setting($share['admin_setting']))
                                    <img src="{{ Storage::url(setting($share['admin_setting'])) }}"
                                        class="w-4 h-4 object-contain flex-shrink-0" alt="{{ $share['label'] }}">
                                @else
                                    <i class="ti {{ $share['icon'] }} text-base"></i>
                                @endif

                                <span>{{ $share['label'] }}</span>
                            </a>
                        @endforeach

                        <button onclick="copyLink()" id="copy-btn"
                            class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200
                            text-gray-600 text-xs font-medium rounded-xl hover:bg-gray-50
                            transition-all duration-200">
                            <i class="ti ti-copy text-base" id="copy-icon"></i>
                            <span id="copy-text">Salin Link</span>
                        </button>
                    </div>
                </div>

                {{-- Related Articles --}}
                @if ($relatedBlogs->count() > 0)
                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-5">Artikel Terkait</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach ($relatedBlogs as $related)
                                <a href="{{ route('blogs.show', $related->slug) }}"
                                    class="group bg-white border border-gray-100 rounded-2xl overflow-hidden
                            hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                    <div class="h-32 bg-gray-100 overflow-hidden">
                                        @if ($related->thumbnail)
                                            <img src="{{ Storage::url($related->thumbnail) }}"
                                                class="w-full h-full object-cover group-hover:scale-105
                                    transition-transform duration-500"
                                                alt="{{ $related->title }}">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                                flex items-center justify-center">
                                                <i class="ti ti-news text-3xl text-gray-300"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <p class="text-xs text-gray-400 mb-1">
                                            {{ $related->published_at?->diffForHumans() }}
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug
                                group-hover:text-gray-700 transition-colors duration-200">
                                            {{ $related->title }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </article>

            {{-- Sidebar --}}
            <aside class="space-y-5">

                {{-- Author Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Penulis</p>
                    <div class="flex items-center gap-3 mb-3">
                        @if (setting('app_logo'))
                            <img src="{{ Storage::url(setting('app_logo')) }}"
                                alt="{{ setting('app_name', 'TicketIn') }}"
                                class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                        @else
                            <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $blog->author->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Penulis & Editor</p>
                        </div>
                    </div>
                </div>

                {{-- Article Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">
                        Info Artikel
                    </p>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Dipublikasikan</span>
                            <span class="text-gray-700 font-medium text-right">
                                {{ $blog->published_at?->translatedFormat('d M Y') ?? '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Diperbarui</span>
                            <span class="text-gray-700 font-medium">
                                {{ $blog->updated_at->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Estimasi Baca</span>
                            <span class="text-gray-700 font-medium">
                                {{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} menit
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Recent Articles --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">
                        Artikel Lainnya
                    </p>
                    <div class="space-y-4">
                        @foreach ($recentBlogs as $recent)
                            <a href="{{ route('blogs.show', $recent->slug) }}" class="flex gap-3 group">
                                <div class="w-14 h-12 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                    @if ($recent->thumbnail)
                                        <img src="{{ Storage::url($recent->thumbnail) }}"
                                            class="w-full h-full object-cover group-hover:scale-105
                                    transition-transform duration-500"
                                            alt="{{ $recent->title }}">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                                flex items-center justify-center">
                                            <i class="ti ti-news text-lg text-gray-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-xs font-semibold text-gray-900 line-clamp-2 leading-snug
                                group-hover:text-gray-700 transition-colors duration-200">
                                        {{ $recent->title }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $recent->published_at?->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('blogs.index') }}"
                        class="flex items-center justify-center gap-2 h-9 border border-gray-100
                        text-gray-500 text-xs font-medium rounded-xl hover:bg-gray-50
                        transition-all duration-200 mt-4">
                        Lihat semua artikel <i class="ti ti-arrow-right text-sm"></i>
                    </a>
                </div>

                {{-- CTA --}}
                <div class="bg-gray-950 rounded-2xl p-5 relative overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-28 h-28 bg-white/3 rounded-full blur-2xl"></div>
                    <div class="relative">
                        <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center mb-3">
                            <i class="ti ti-ticket text-white text-base"></i>
                        </div>
                        <p class="text-sm font-bold text-white mb-1">Cari Event Seru?</p>
                        <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                            Temukan event impianmu dan beli tiket sekarang.
                        </p>
                        <a href="{{ route('events.index') }}"
                            class="inline-flex items-center gap-1.5 h-9 px-4 bg-white text-gray-900
                            text-xs font-semibold rounded-xl hover:bg-gray-100 transition-all duration-200">
                            <i class="ti ti-calendar-event text-sm"></i>
                            Jelajahi Event
                        </a>
                    </div>
                </div>

            </aside>

        </div>
    </div>

    @push('scripts')
        <script>
            function copyLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    document.getElementById('copy-icon').classList.replace('ti-copy', 'ti-check');
                    document.getElementById('copy-text').textContent = 'Tersalin!';
                    setTimeout(() => {
                        document.getElementById('copy-icon').classList.replace('ti-check', 'ti-copy');
                        document.getElementById('copy-text').textContent = 'Salin Link';
                    }, 2000);
                });
            }
        </script>
    @endpush

</x-user-layout>
