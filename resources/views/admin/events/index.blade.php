<x-app-layout>
    <div x-data="{ deleteEventId: null }">
        <x-slot name="title">Event</x-slot>

        {{-- Stats --}}
        <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
            @foreach ([['label' => 'Total Event', 'value' => $stats['total'], 'icon' => 'ti-calendar-event', 'color' => 'bg-gray-900'], ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Draft', 'value' => $stats['draft'], 'icon' => 'ti-pencil', 'color' => 'bg-amber-500'], ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-500'], ['label' => 'Completed', 'value' => $stats['completed'], 'icon' => 'ti-circle-dashed-check', 'color' => 'bg-green-500']] as $stat)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                        <div class="w-9 h-9 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                            <i class="ti {{ $stat['icon'] }} text-white text-base"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stat['value']) }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Daftar Event</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola semua event</p>
                </div>
                <a href="{{ route('admin.events.create') }}"
                    class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                    <i class="ti ti-plus text-base"></i> Tambah Event
                </a>
            </div>

            {{-- Filter --}}
            <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                <form method="GET" class="flex flex-wrap gap-3 items-center">
                    <div
                        class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                        <i class="ti ti-search text-gray-300 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul event, lokasi..."
                            class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                    </div>

                    <div class="relative flex items-center min-w-[150px]">
                        <select name="category_id"
                            class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                            <i class="ti ti-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <div class="relative flex items-center min-w-[140px]">
                        <select name="status"
                            class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                                Published
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                        <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                            <i class="ti ti-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <button type="submit"
                        class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                        Filter
                    </button>

                    @if (request()->anyFilled(['search', 'status', 'category_id']))
                        <a href="{{ route('admin.events.index') }}"
                            class="h-9 px-4 flex items-center justify-center border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Grid Event --}}
            <div class="p-5">
                @if ($events->isEmpty())
                    <div class="text-center py-16">
                        <i class="ti ti-calendar-off text-5xl text-gray-200 block mb-3"></i>
                        <p class="text-sm text-gray-400 mb-4">Belum ada event</p>
                        <a href="{{ route('admin.events.create') }}"
                            class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                            <i class="ti ti-plus text-base"></i> Buat Event Pertama
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($events as $event)
                            @php
                                $statusCfg = [
                                    'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                    'completed' => 'bg-blue-50 text-blue-600 border-blue-200',
                                ];
                            @endphp
                            <div
                                class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-sm transition group">
                                {{-- Thumbnail --}}
                                <div class="relative h-40 bg-gray-100">
                                    @if ($event->thumbnail)
                                        <img src="{{ Storage::url($event->thumbnail) }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                            alt="{{ $event->title }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                            <i class="ti ti-photo text-4xl text-gray-200"></i>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $statusCfg[$event->status] ?? '' }} capitalize backdrop-blur-sm">
                                            {{ $event->status }}
                                        </span>
                                    </div>
                                    @if ($event->is_featured)
                                        <div class="absolute top-3 right-3">
                                            <span
                                                class="text-xs font-medium px-2.5 py-1 rounded-lg bg-gray-900 text-white">
                                                <i class="ti ti-star-filled text-xs"></i> Featured
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="p-4">
                                    <p class="text-xs text-gray-400 mb-1">{{ $event->category->name ?? '-' }}</p>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $event->title }}
                                    </h3>

                                    <div class="space-y-1.5 mb-3">
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <i class="ti ti-map-pin text-gray-300"></i>
                                            <span class="truncate">{{ $event->location }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <i class="ti ti-calendar text-gray-300"></i>
                                            <span>{{ $event->start_date->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>

                                    {{-- Ticket Types --}}
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="flex items-center gap-1 text-xs text-gray-500">
                                            <i class="ti ti-ticket text-gray-300 text-sm"></i>
                                            {{ $event->ticketTypes->count() }} tipe tiket
                                        </div>
                                        <span class="text-gray-200">·</span>
                                        <div class="text-xs text-gray-500">
                                            {{ number_format($event->ticketTypes->sum('sold')) }}/{{ number_format($event->ticketTypes->sum('quota')) }}
                                            terjual
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                                        <a href="{{ route('admin.events.show', $event) }}"
                                            class="flex-1 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition gap-1.5">
                                            <i class="ti ti-eye text-sm"></i> Detail
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event) }}"
                                            class="flex-1 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition gap-1.5">
                                            <i class="ti ti-edit text-sm"></i> Edit
                                        </a>
                                        <button type="button"
                                            @click="$dispatch('buka-modal-hapus-event', { id: '{{ $event->id }}' })"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($events->hasPages())
                        <div class="mt-6">
                            {{ $events->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>

        <div x-data="{ deleteEventId: null }" @buka-modal-hapus-event.window="deleteEventId = $event.detail.id" x-cloak>

            @foreach ($events as $event)
                <div x-show="deleteEventId == '{{ $event->id }}'" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4">

                    {{-- Backdrop --}}
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteEventId = null">
                    </div>

                    {{-- Modal --}}
                    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10 text-center"
                        x-show="deleteEventId == '{{ $event->id }}'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                        <div
                            class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500 border border-red-100">
                            <i class="ti ti-alert-triangle text-xl"></i>
                        </div>

                        <h3 class="text-base font-semibold text-gray-900 mb-1">
                            Hapus Event?
                        </h3>

                        <p class="text-sm text-gray-500 mb-6 px-2">
                            Apakah Anda yakin ingin menghapus event
                            <span class="font-medium text-gray-800">
                                "{{ $event->title }}"
                            </span>?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>

                        <div class="flex gap-3">
                            <button type="button" @click="deleteEventId = null"
                                class="flex-1 h-10 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition font-medium">
                                Batal
                            </button>

                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                class="flex-1">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-full h-10 bg-red-600 text-white text-sm rounded-xl hover:bg-red-700 transition font-medium">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
