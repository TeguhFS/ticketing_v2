<x-user-layout>
    <x-slot name="title">Blog — {{ setting('app_name') }}</x-slot>

    {{-- Page Header --}}
    <section class="bg-gray-950 pt-8 pb-12 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/3 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.02]"
                style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px);
                background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">
                    {{ number_format($totalBlogs) }} artikel tersedia
                </p>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">Blog & Artikel</h1>
                <p class="text-sm text-gray-400 mb-6 leading-relaxed">
                    Tips, panduan, dan informasi terbaru seputar dunia event dan ticketing.
                </p>

                {{-- Search --}}
                <form action="{{ route('blogs.index') }}" method="GET">
                    <div class="flex items-center gap-2 bg-white rounded-2xl p-1.5 shadow-xl shadow-black/30 max-w-lg">
                        <div class="flex items-center gap-3 flex-1 px-4">
                            <i class="ti ti-search text-gray-400 text-lg flex-shrink-0"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari artikel..."
                                class="w-full bg-transparent border-none outline-none focus:outline-none focus:ring-0  text-sm
                                text-gray-700 placeholder-gray-400 py-2.5">
                            @if (request('search'))
                                <a href="{{ route('blogs.index') }}"
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
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Search result info --}}
        @if (request('search'))
            <div class="mb-6 flex items-center gap-3">
                <p class="text-sm text-gray-500">
                    Hasil pencarian untuk
                    <span class="font-semibold text-gray-900">"{{ request('search') }}"</span>
                    — <span class="font-semibold">{{ $blogs->total() }}</span> artikel ditemukan
                </p>
                <a href="{{ route('blogs.index') }}"
                    class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                    Hapus pencarian
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Main: Blog Grid --}}
            <div class="lg:col-span-2">

                {{-- Featured (only when no search) --}}
                @if (!request('search') && $featuredBlogs->count() > 0 && $blogs->currentPage() === 1)
                    <div class="mb-8">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">
                            <i class="ti ti-star-filled text-amber-400 mr-1"></i> Artikel Unggulan
                        </p>

                        {{-- Featured: first big, rest small --}}
                        @if ($featuredBlogs->count() >= 1)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

                                {{-- Big featured --}}
                                <a href="{{ route('blogs.show', $featuredBlogs->first()->slug) }}"
                                    class="sm:col-span-2 group bg-white border border-gray-100 rounded-2xl overflow-hidden
                                    hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                    @if ($featuredBlogs->first()->thumbnail)
                                        <div class="h-56 sm:h-64 overflow-hidden">
                                            <img src="{{ Storage::url($featuredBlogs->first()->thumbnail) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                alt="{{ $featuredBlogs->first()->title }}">
                                        </div>
                                    @endif
                                    <div class="p-5">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span
                                                class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full">
                                                <i class="ti ti-star-filled text-xs mr-1"></i> Featured
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $featuredBlogs->first()->published_at?->diffForHumans() }}
                                            </span>
                                        </div>
                                        <h2
                                            class="text-lg font-bold text-gray-900 mb-2 leading-snug
                                            group-hover:text-gray-700 transition-colors duration-200">
                                            {{ $featuredBlogs->first()->title }}
                                        </h2>
                                        @if ($featuredBlogs->first()->excerpt)
                                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                                {{ $featuredBlogs->first()->excerpt }}
                                            </p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-4">
                                            <img src="{{ asset('images/logo-ticketing.png') }}" alt="Logo Ticketing"
                                                class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                            <span class="text-xs text-gray-500">
                                                {{ $featuredBlogs->first()->author->name ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                {{-- Other featured --}}
                                @foreach ($featuredBlogs->skip(1) as $featured)
                                    <a href="{{ route('blogs.show', $featured->slug) }}"
                                        class="group bg-white border border-gray-100 rounded-2xl overflow-hidden
                                        hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                        @if ($featured->thumbnail)
                                            <div class="h-36 overflow-hidden">
                                                <img src="{{ Storage::url($featured->thumbnail) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                    alt="{{ $featured->title }}">
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <p class="text-xs text-gray-400 mb-2">
                                                {{ $featured->published_at?->diffForHumans() }}
                                            </p>
                                            <h3
                                                class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug
                                                group-hover:text-gray-700 transition-colors duration-200">
                                                {{ $featured->title }}
                                            </h3>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        @endif {{-- Ditambahkan penutup di sini --}}
                    </div>
                @endif

                {{-- All Blogs --}}
                <div>
                    @if (!request('search'))
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">
                            Semua Artikel
                        </p>
                    @endif

                    @if ($blogs->isEmpty())
                        <div class="text-center py-16 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="ti ti-news-off text-5xl text-gray-200 block mb-3"></i>
                            <p class="text-sm text-gray-400 mb-4">
                                @if (request('search'))
                                    Tidak ada artikel untuk "{{ request('search') }}"
                                @else
                                    Belum ada artikel tersedia
                                @endif
                            </p>
                            @if (request('search'))
                                <a href="{{ route('blogs.index') }}"
                                    class="inline-flex items-center gap-2 h-10 px-5 bg-gray-900 text-white
                                    text-sm font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                                    <i class="ti ti-refresh text-base"></i> Lihat Semua Artikel
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($blogs as $blog)
                                <a href="{{ route('blogs.show', $blog->slug) }}"
                                    class="group flex gap-4 bg-white border border-gray-100 rounded-2xl p-4
                                    hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">

                                    {{-- Thumbnail --}}
                                    <div
                                        class="w-28 h-24 sm:w-36 sm:h-28 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                        @if ($blog->thumbnail)
                                            <img src="{{ Storage::url($blog->thumbnail) }}"
                                                class="w-full h-full object-cover group-hover:scale-105
                                                transition-transform duration-500"
                                                alt="{{ $blog->title }}">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                                                flex items-center justify-center">
                                                <i class="ti ti-news text-3xl text-gray-300"></i>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                @if ($blog->is_featured)
                                                    <span
                                                        class="text-xs font-semibold px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full">
                                                        Featured
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-400">
                                                    {{ $blog->published_at?->translatedFormat('d M Y') ?? $blog->created_at->translatedFormat('d M Y') }}
                                                </span>
                                            </div>

                                            <h3
                                                class="text-sm sm:text-base font-bold text-gray-900 mb-1.5
                                                line-clamp-2 leading-snug
                                                group-hover:text-gray-700 transition-colors duration-200">
                                                {{ $blog->title }}
                                            </h3>

                                            @if ($blog->excerpt)
                                                <p
                                                    class="text-xs sm:text-sm text-gray-500 line-clamp-2 leading-relaxed hidden sm:block">
                                                    {{ $blog->excerpt }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="flex items-center gap-2">
                                                @if (setting('app_logo'))
                                                    <img src="{{ Storage::url(setting('app_logo')) }}"
                                                        alt="{{ setting('app_name', 'TicketIn') }}"
                                                        class="w-7 h-7 rounded-full object-cover flex-shrink-0">
                                                @else
                                                    <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                                    </svg>
                                                @endif
                                                <span class="text-xs text-gray-400">
                                                    {{ $blog->author->name ?? '-' }}
                                                </span>
                                            </div>
                                            <span
                                                class="text-xs font-medium text-gray-900 flex items-center gap-1
                                                group-hover:gap-2 transition-all duration-200">
                                                Baca <i class="ti ti-arrow-right text-sm"></i>
                                            </span>
                                        </div>
                                    </div>

                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if ($blogs->hasPages())
                            <div class="mt-8">
                                {{ $blogs->links() }}
                            </div>
                        @endif

                    @endif
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Recent Posts --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Artikel Terbaru</p>
                    <div class="space-y-4">
                        @foreach ($latestBlogs as $recent)
                            <a href="{{ route('blogs.show', $recent->slug) }}" class="flex gap-3 group">
                                <div class="w-16 h-14 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                    @if ($recent->thumbnail)
                                        <img src="{{ Storage::url($recent->thumbnail) }}"
                                            class="w-full h-full object-cover group-hover:scale-105
                                            transition-transform duration-500"
                                            alt="{{ $recent->title }}">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200
                                            flex items-center justify-center">
                                            <i class="ti ti-news text-xl text-gray-300"></i>
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
                </div>

                {{-- CTA --}}
                <div class="bg-gray-950 rounded-2xl p-5 relative overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/3 rounded-full blur-2xl"></div>
                    <div class="relative">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-3">
                            <i class="ti ti-ticket text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-white mb-1">Temukan Event Seru</p>
                        <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                            Jelajahi ratusan event menarik dan dapatkan tiket terbaikmu.
                        </p>
                        <a href="{{ route('events.index') }}"
                            class="inline-flex items-center gap-2 h-9 px-4 bg-white text-gray-900 text-xs
                            font-semibold rounded-xl hover:bg-gray-100 transition-all duration-200">
                            <i class="ti ti-calendar-event text-sm"></i>
                            Jelajahi Event
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-user-layout>
