<x-app-layout>
    <div x-data="{ deleteBlogModal: false }">
        <x-slot name="title">Preview Blog</x-slot>

        <div class="flex items-center justify-between mb-5">
            <a href="{{ route('admin.blogs.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-arrow-left text-base"></i> Kembali ke Blog
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.blogs.edit', $blog) }}"
                    class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition">
                    <i class="ti ti-edit text-base"></i> Edit
                </a>
                <button type="button" @click="deleteBlogModal = true"
                    class="inline-flex items-center gap-2 h-9 px-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl hover:bg-red-100 transition">
                    <i class="ti ti-trash text-base"></i> Hapus
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Left: Content --}}
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

                    {{-- Thumbnail --}}
                    @if ($blog->thumbnail)
                        <img src="{{ Storage::url($blog->thumbnail) }}" class="w-full h-64 object-cover"
                            alt="{{ $blog->title }}">
                    @endif

                    <div class="p-6">
                        {{-- Meta --}}
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-lg border
                        {{ $blog->status === 'published'
                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                            : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                            @if ($blog->is_featured)
                                <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-gray-900 text-white">
                                    <i class="ti ti-star-filled text-xs"></i> Featured
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="text-2xl font-bold text-gray-900 mb-3 leading-tight">{{ $blog->title }}</h1>

                        {{-- Author & Date --}}
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold">
                                {{ strtoupper(substr($blog->author->name ?? 'A', 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $blog->author->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $blog->published_at ? $blog->published_at->format('d M Y, H:i') : $blog->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        {{-- Excerpt --}}
                        @if ($blog->excerpt)
                            <p class="text-base text-gray-600 italic border-l-4 border-gray-200 pl-4 mb-6">
                                {{ $blog->excerpt }}
                            </p>
                        @endif

                        {{-- Content --}}
                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                            {!! $blog->content !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right --}}
            <div class="space-y-5">

                {{-- Info --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Informasi</p>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status</span>
                            <span class="capitalize font-medium text-gray-700">{{ $blog->status }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Featured</span>
                            <span class="text-gray-700">{{ $blog->is_featured ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Penulis</span>
                            <span class="text-gray-700">{{ $blog->author->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Dibuat</span>
                            <span class="text-gray-700">{{ $blog->created_at->format('d M Y') }}</span>
                        </div>
                        @if ($blog->published_at)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Dipublish</span>
                                <span class="text-gray-700">{{ $blog->published_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Slug --}}
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Slug URL</p>
                    <p class="text-xs font-mono text-gray-600 break-all">{{ $blog->slug }}</p>
                </div>

            </div>

        </div>
        {{-- Delete Blog Modal --}}
        <div x-show="deleteBlogModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteBlogModal = false">
            </div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10 text-center"
                x-show="deleteBlogModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">

                <div
                    class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500 border border-red-100">
                    <i class="ti ti-alert-triangle text-xl"></i>
                </div>

                <h3 class="text-base font-semibold text-gray-900 mb-1">
                    Hapus Artikel?
                </h3>

                <p class="text-sm text-gray-500 mb-6 px-2">
                    Apakah Anda yakin ingin menghapus artikel
                    <span class="font-medium text-gray-800">
                        "{{ $blog->title }}"
                    </span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex gap-3">
                    <button type="button" @click="deleteBlogModal = false"
                        class="flex-1 h-10 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition font-medium">
                        Batal
                    </button>

                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="w-full h-10 bg-red-600 text-white text-sm rounded-xl hover:bg-red-700 transition font-medium shadow-sm shadow-red-100">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
