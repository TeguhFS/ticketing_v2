<x-officer-layout>

    {{-- Header --}}
    <div class="mb-5">
        <p class="text-xs text-gray-400">Selamat datang,</p>
        <h1 class="text-lg font-bold text-gray-900">{{ auth()->user()->name }}</h1>
    </div>

    {{-- Today Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-5">
        <div class="bg-gray-900 rounded-2xl p-4 text-white">
            <div class="flex items-center gap-2 mb-2">
                <i class="ti ti-scan text-lg opacity-70"></i>
                <p class="text-xs font-medium opacity-70">Scan Hari Ini</p>
            </div>
            <p class="text-3xl font-bold">{{ $todayScan }}</p>
            <p class="text-xs opacity-50 mt-1">{{ now()->format('d M Y') }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="ti ti-history text-lg text-gray-400"></i>
                <p class="text-xs font-medium text-gray-400">Total Scan</p>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalScanAll }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
        </div>
    </div>

    {{-- Event Assignment --}}
    <div class="mb-5">
        <p class="text-sm font-semibold text-gray-900 mb-3">Event Ditugaskan</p>
        <div class="space-y-3">
            @forelse($eventStats as $stat)
                <div class="bg-white border border-gray-100 rounded-2xl p-4">

                    {{-- Event Info --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0 pr-3">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">
                                {{ $stat['event']->title }}
                            </p>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                                <span><i class="ti ti-map-pin text-xs"></i> {{ $stat['event']->location }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <i class="ti ti-calendar text-xs"></i>
                                {{ $stat['event']->start_date->format('d M Y, H:i') }}
                            </p>
                        </div>
                        @php
                            $roleCfg = [
                                'scanner' => 'bg-blue-50 text-blue-600',
                                'coordinator' => 'bg-violet-50 text-violet-600',
                                'security' => 'bg-amber-50 text-amber-600',
                            ];
                        @endphp
                        <span
                            class="text-xs font-medium px-2.5 py-1 rounded-lg flex-shrink-0
                    {{ $roleCfg[$stat['officer']->role] ?? 'bg-gray-100 text-gray-500' }} capitalize">
                            {{ $stat['officer']->role }}
                        </span>
                    </div>

                    {{-- Progress --}}
                    <div class="space-y-2 mb-3">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Tiket Terscan</span>
                            <span class="font-semibold text-gray-900">
                                {{ $stat['total_scanned'] }}/{{ $stat['total_sold'] }}
                            </span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gray-900 rounded-full transition-all"
                                style="width: {{ $stat['total_sold'] > 0 ? min(100, ($stat['total_scanned'] / $stat['total_sold']) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Mini Stats --}}
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center p-2 bg-gray-50 rounded-xl">
                            <p class="text-base font-bold text-gray-900">{{ $stat['total_sold'] }}</p>
                            <p class="text-[10px] text-gray-400">Terjual</p>
                        </div>
                        <div class="text-center p-2 bg-emerald-50 rounded-xl">
                            <p class="text-base font-bold text-emerald-600">{{ $stat['total_active'] }}</p>
                            <p class="text-[10px] text-emerald-500">Belum Masuk</p>
                        </div>
                        <div class="text-center p-2 bg-blue-50 rounded-xl">
                            <p class="text-base font-bold text-blue-600">{{ $stat['total_used'] }}</p>
                            <p class="text-[10px] text-blue-500">Sudah Masuk</p>
                        </div>
                    </div>

                    {{-- Officer Code --}}
                    <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between">
                        <p class="text-xs text-gray-400">Kode Officer</p>
                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                            {{ $stat['officer']->officer_code }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center">
                    <i class="ti ti-calendar-off text-4xl text-gray-200 block mb-3"></i>
                    <p class="text-sm text-gray-400">Belum ada event yang ditugaskan</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Scans --}}
    @if ($recentScans->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-900">Scan Terakhir</p>
                <a href="{{ route('officer.history') }}" class="text-xs text-gray-400 hover:text-gray-700">
                    Lihat semua →
                </a>
            </div>
            <div class="space-y-2">
                @foreach ($recentScans as $scan)
                    <div class="bg-white border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                {{ $scan->status === 'valid' ? 'bg-emerald-100' : ($scan->status === 'invalid' ? 'bg-red-100' : 'bg-amber-100') }}">
                            <i
                                class="ti text-sm
                    {{ $scan->status === 'valid'
                        ? 'ti-check text-emerald-600'
                        : ($scan->status === 'invalid'
                            ? 'ti-x text-red-500'
                            : 'ti-clock text-amber-600') }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-mono text-gray-600 truncate">{{ $scan->ticket_code }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                {{ $scan->ticket->ticketType->event->title ?? 'Event tidak ditemukan' }}
                            </p>
                        </div>
                        <p class="text-xs text-gray-400 flex-shrink-0">
                            {{ $scan->validated_at?->format('H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-officer-layout>
