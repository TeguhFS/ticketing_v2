<x-app-layout>
    <x-slot name="title">Detail Event</x-slot>

    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('admin.events.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Events
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.events.edit', $event) }}"
                class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition">
                <i class="ti ti-edit text-base"></i> Edit
            </a>
            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                onsubmit="return confirm('Hapus event ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 h-9 px-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl hover:bg-red-100 transition">
                    <i class="ti ti-trash text-base"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Banner --}}
    @if ($event->banner)
        <div class="w-full h-52 rounded-2xl overflow-hidden mb-5">
            <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover" alt="Banner">
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Info Utama --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-start gap-4">
                    @if ($event->thumbnail)
                        <img src="{{ Storage::url($event->thumbnail) }}"
                            class="w-24 h-24 rounded-xl object-cover flex-shrink-0" alt="Thumbnail">
                    @endif
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            @php
                                $statusCfg = [
                                    'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                    'completed' => 'bg-blue-50 text-blue-600 border-blue-200',
                                ];
                            @endphp
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $statusCfg[$event->status] ?? '' }} capitalize">
                                {{ $event->status }}
                            </span>
                            @if ($event->is_featured)
                                <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-gray-900 text-white">
                                    <i class="ti ti-star-filled text-xs"></i> Featured
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $event->category->name ?? '-' }}</span>
                        </div>
                        <h1 class="text-lg font-bold text-gray-900 mb-3">{{ $event->title }}</h1>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <i class="ti ti-map-pin text-gray-300"></i>
                                {{ $event->location }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="ti ti-calendar text-gray-300"></i>
                                {{ $event->start_date->format('d M Y, H:i') }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="ti ti-calendar-due text-gray-300"></i>
                                {{ $event->end_date->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-50">
                    <p class="text-xs font-medium text-gray-400 mb-2">Deskripsi</p>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $event->description }}</p>
                </div>

                @if ($event->location_detail)
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-400 mb-1">Detail Lokasi</p>
                        <p class="text-sm text-gray-600">{{ $event->location_detail }}</p>
                    </div>
                @endif

                @if ($event->maps_url)
                    <div class="mt-4">
                        <a href="{{ $event->maps_url }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:underline">
                            <i class="ti ti-map text-base"></i> Lihat di Google Maps
                        </a>
                    </div>
                @endif
            </div>

            {{-- Ticket Types --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Tipe Tiket</p>
                @forelse($event->ticketTypes as $type)
                    <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl mb-3 last:mb-0">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $type->name }}</p>
                                @if (!$type->is_active)
                                    <span
                                        class="text-xs px-2 py-0.5 bg-gray-100 text-gray-400 rounded-lg">Nonaktif</span>
                                @endif
                            </div>
                            @if ($type->description)
                                <p class="text-xs text-gray-400 mb-2">{{ $type->description }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <span><i class="ti ti-users text-gray-300"></i>
                                    {{ $type->sold }}/{{ $type->quota }} terjual</span>
                                @if ($type->sale_start)
                                    <span><i class="ti ti-calendar text-gray-300"></i>
                                        {{ $type->sale_start->format('d M') }} -
                                        {{ $type->sale_end?->format('d M Y') }}</span>
                                @endif
                            </div>
                            {{-- Progress bar --}}
                            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-900 rounded-full"
                                    style="width: {{ $type->quota > 0 ? min(100, ($type->sold / $type->quota) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-base font-bold text-gray-900">
                                {{ $type->price == 0 ? 'Gratis' : 'Rp ' . number_format($type->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400">Maks {{ $type->max_per_order }}/order</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="ti ti-ticket text-3xl text-gray-200 block mb-2"></i>
                        <p class="text-sm text-gray-400">Belum ada tipe tiket</p>
                        <a href="{{ route('admin.events.edit', $event) }}"
                            class="text-xs text-blue-600 hover:underline mt-1 inline-block">Tambah sekarang →</a>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTicketsSold) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Tiket Terjual</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalQuota) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total Kuota</p>
                </div>
                <div class="col-span-2 bg-white rounded-2xl border border-gray-100 p-4 text-center">
                    <p class="text-xl font-bold text-gray-900">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Estimasi Pendapatan</p>
                </div>
            </div>

            {{-- Meta --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Informasi</p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dibuat oleh</span>
                        <span class="text-gray-700 font-medium">{{ $event->admin->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dibuat</span>
                        <span class="text-gray-600">{{ $event->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Diperbarui</span>
                        <span class="text-gray-600">{{ $event->updated_at->format('d M Y') }}</span>
                    </div>
                    @if ($event->max_attendees)
                        <div class="flex justify-between">
                            <span class="text-gray-400">Maks Peserta</span>
                            <span class="text-gray-600">{{ number_format($event->max_attendees) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Field Officers --}}
            @if ($event->fieldOfficers->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Petugas Lapangan</p>
                    <div class="space-y-2">
                        @foreach ($event->fieldOfficers as $officer)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr($officer->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800">{{ $officer->user->name }}</p>
                                    <p class="text-xs text-gray-400 capitalize">{{ $officer->role }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
