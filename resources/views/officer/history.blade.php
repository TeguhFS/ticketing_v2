<x-officer-layout>

    <div class="mb-5">
        <h1 class="text-lg font-bold text-gray-900">Riwayat Scan</h1>
        <p class="text-xs text-gray-400 mt-0.5">Semua tiket yang telah Anda scan</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-5">
        <div class="bg-white border border-gray-100 rounded-2xl p-4 col-span-2">
            <div class="grid grid-cols-4 gap-3">
                @foreach ([['label' => 'Total', 'value' => $stats['total'], 'color' => 'text-gray-900'], ['label' => 'Valid', 'value' => $stats['valid'], 'color' => 'text-emerald-600'], ['label' => 'Invalid', 'value' => $stats['invalid'], 'color' => 'text-red-500'], ['label' => 'Used', 'value' => $stats['already_used'], 'color' => 'text-amber-600']] as $stat)
                    <div class="text-center">
                        <p class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-gray-900 rounded-2xl p-4 text-white text-center">
            <p class="text-2xl font-bold">{{ $stats['today'] }}</p>
            <p class="text-xs opacity-60 mt-1">Hari Ini</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">
                {{ $stats['total'] > 0 ? round(($stats['valid'] / $stats['total']) * 100) : 0 }}%
            </p>
            <p class="text-xs text-gray-400 mt-1">Success Rate</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-4 mb-4">
        <form method="GET" class="space-y-3">
            <div class="relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode tiket..."
                    class="w-full h-10 pl-9 pr-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white font-mono">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <select name="status"
                    class="h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-600 outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="invalid" {{ request('status') === 'invalid' ? 'selected' : '' }}>Invalid
                    </option>
                    <option value="already_used" {{ request('status') === 'already_used' ? 'selected' : '' }}>Already
                        Used</option>
                </select>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-600 outline-none bg-white">
            </div>
            <button type="submit"
                class="w-full h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                Filter
            </button>
        </form>
    </div>

    {{-- List --}}
    <div class="space-y-2">
        @forelse($validations as $validation)
            <div class="bg-white border border-gray-100 rounded-xl p-4 flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
            {{ $validation->status === 'valid'
                ? 'bg-emerald-100'
                : ($validation->status === 'invalid'
                    ? 'bg-red-100'
                    : 'bg-amber-100') }}">
                    <i
                        class="ti text-base
                {{ $validation->status === 'valid'
                    ? 'ti-check text-emerald-600'
                    : ($validation->status === 'invalid'
                        ? 'ti-x text-red-500'
                        : 'ti-clock text-amber-600') }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-mono text-gray-700 font-semibold">{{ $validation->ticket_code }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">
                        {{ $validation->ticket->ticketType->event->title ?? 'Tiket tidak ditemukan' }}
                    </p>
                    <p class="text-xs text-gray-300 mt-0.5">{{ $validation->validated_at?->format('d M Y, H:i') }}</p>
                </div>
                @php
                    $vCfg = [
                        'valid' => 'bg-emerald-50 text-emerald-700',
                        'invalid' => 'bg-red-50 text-red-600',
                        'already_used' => 'bg-amber-50 text-amber-600',
                    ];
                @endphp
                <span
                    class="text-[10px] font-semibold px-2 py-1 rounded-lg flex-shrink-0
            {{ $vCfg[$validation->status] ?? 'bg-gray-100 text-gray-500' }} capitalize">
                    {{ str_replace('_', ' ', $validation->status) }}
                </span>
            </div>
        @empty
            <div class="bg-white border border-gray-100 rounded-2xl p-10 text-center">
                <i class="ti ti-history text-4xl text-gray-200 block mb-3"></i>
                <p class="text-sm text-gray-400">Belum ada riwayat scan</p>
            </div>
        @endforelse
    </div>

    @if ($validations->hasPages())
        <div class="mt-5">{{ $validations->links() }}</div>
    @endif

</x-officer-layout>
