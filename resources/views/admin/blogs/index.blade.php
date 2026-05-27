<x-app-layout>
    <x-slot name="title">Blog</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total Blog', 'value' => $stats['total'], 'icon' => 'ti-news', 'color' => 'bg-gray-900'], ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Draft', 'value' => $stats['draft'], 'icon' => 'ti-pencil', 'color' => 'bg-amber-500'], ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'ti-star', 'color' => 'bg-violet-500']] as $stat)
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <div class="w-9 h-9 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                        <i class="ti {{ $stat['icon'] }} text-white text-base"></i>
                    </div>
                </div>
                <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-900">Daftar Blog</p>
                <p class="text-xs text-gray-400 mt-0.5">Kelola artikel dan konten blog</p>
            </div>
            <a href="{{ route('admin.blogs.create') }}"
                class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                <i class="ti ti-plus text-base"></i> Tulis Blog
            </a>
        </div>

        {{-- Filter --}}
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <form method="GET" class="flex flex-wrap gap-3 items-center">

                {{-- Input Search --}}
                <div
                    class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, excerpt..."
                        class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                </div>

                {{-- Select Status --}}
                <div class="relative flex items-center min-w-[150px]">
                    <select name="status"
                        class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published
                        </option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>

                {{-- Buttons --}}
                <button type="submit"
                    class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                    Filter
                </button>

                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.blogs.index') }}"
                        class="h-9 px-4 flex items-center justify-center border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Grid --}}
        <div class="p-5">
            @if ($blogs->isEmpty())
                <div class="text-center py-16">
                    <i class="ti ti-news-off text-5xl text-gray-200 block mb-3"></i>
                    <p class="text-sm text-gray-400 mb-4">Belum ada artikel blog</p>
                    <a href="{{ route('admin.blogs.create') }}"
                        class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                        <i class="ti ti-plus text-base"></i> Tulis Blog Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach ($blogs as $blog)
                        <div
                            class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-sm transition group">
                            {{-- Thumbnail --}}
                            <div class="relative h-44 bg-gray-100">
                                @if ($blog->thumbnail)
                                    <img src="{{ Storage::url($blog->thumbnail) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                        alt="{{ $blog->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                        <i class="ti ti-photo text-4xl text-gray-200"></i>
                                    </div>
                                @endif
                                {{-- Status badge --}}
                                <div class="absolute top-3 left-3 flex gap-2">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-lg border backdrop-blur-sm
                            {{ $blog->status === 'published'
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                    @if ($blog->is_featured)
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-gray-900 text-white">
                                            <i class="ti ti-star-filled text-xs">Featured</i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 leading-snug">
                                    {{ $blog->title }}
                                </h3>
                                @if ($blog->excerpt)
                                    <p class="text-xs text-gray-400 line-clamp-2 mb-3">{{ $blog->excerpt }}</p>
                                @endif

                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                                    <div
                                        class="w-5 h-5 rounded-full bg-gray-900 flex items-center justify-center text-white text-[9px] font-semibold">
                                        {{ strtoupper(substr($blog->author->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span>{{ $blog->author->name ?? '-' }}</span>
                                    <span class="text-gray-200">·</span>
                                    <span>{{ $blog->created_at->format('d M Y') }}</span>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                                    <a href="{{ route('admin.blogs.show', $blog) }}"
                                        class="flex-1 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition gap-1.5">
                                        <i class="ti ti-eye text-sm"></i> Preview
                                    </a>
                                    <a href="{{ route('admin.blogs.edit', $blog) }}"
                                        class="flex-1 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition gap-1.5">
                                        <i class="ti ti-edit text-sm"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                                        onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($blogs->hasPages())
                    <div class="mt-6">{{ $blogs->links() }}</div>
                @endif
            @endif
        </div>

    </div>

</x-app-layout>
