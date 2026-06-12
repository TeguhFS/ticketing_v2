<x-app-layout>
    <x-slot name="title">Field Officers</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-x text-base"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        @foreach ([['label' => 'Total Officer', 'value' => $stats['total'], 'icon' => 'ti-id-badge', 'color' => 'bg-gray-900'], ['label' => 'Aktif', 'value' => $stats['active'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Scanner', 'value' => $stats['scanner'], 'icon' => 'ti-scan', 'color' => 'bg-blue-500'], ['label' => 'Coordinator', 'value' => $stats['coordinator'], 'icon' => 'ti-sitemap', 'color' => 'bg-violet-500'], ['label' => 'Security', 'value' => $stats['security'], 'icon' => 'ti-shield', 'color' => 'bg-amber-500']] as $stat)
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left: List --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

                {{-- Header --}}
                <div class="p-5 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-900">Daftar Field Officer</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola petugas lapangan per event</p>
                </div>

                {{-- Filter --}}
                <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                    <form method="GET" class="flex flex-wrap gap-3 items-center">
                        <div
                            class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[180px]">
                            <i class="ti ti-search text-gray-300 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, kode officer..."
                                class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                        </div>

                        <div class="relative flex items-center min-w-[180px]">
                            <select name="event_id"
                                class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                                <option value="">Semua Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ Str::limit($event->title, 25) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                                <i class="ti ti-chevron-down text-xs"></i>
                            </div>
                        </div>

                        <div class="relative flex items-center min-w-[140px]">
                            <select name="role"
                                class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                                <option value="">Semua Role</option>
                                <option value="scanner" {{ request('role') === 'scanner' ? 'selected' : '' }}>Scanner
                                </option>
                                <option value="coordinator" {{ request('role') === 'coordinator' ? 'selected' : '' }}>
                                    Coordinator</option>
                                <option value="security" {{ request('role') === 'security' ? 'selected' : '' }}>
                                    Security</option>
                            </select>
                            <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                                <i class="ti ti-chevron-down text-xs"></i>
                            </div>
                        </div>

                        <div class="relative flex items-center min-w-[130px]">
                            <select name="status"
                                class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    Nonaktif</option>
                            </select>
                            <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                                <i class="ti ti-chevron-down text-xs"></i>
                            </div>
                        </div>

                        <button type="submit"
                            class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition shrink-0">
                            Filter
                        </button>

                        @if (request()->anyFilled(['search', 'event_id', 'role', 'status']))
                            <a href="{{ route('admin.officers.index') }}"
                                class="h-9 px-4 flex items-center justify-center border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Officer</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Kode</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Event</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Role</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($officers as $officer)
                        <tbody x-data="{ editOpen: false }">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if ($officer->user->avatar)
                                            <img src="{{ Storage::url($officer->user->avatar) }}"
                                                class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="">
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($officer->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $officer->user->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $officer->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                        {{ $officer->officer_code }}
                                    </span>
                                </td>

                                {{-- Kolom Event (Sudah Ditangani Aman dari Null) --}}
                                <td class="px-5 py-3.5 max-w-[150px]">
                                    @if ($officer->event)
                                        <p class="text-sm text-gray-700 truncate">{{ $officer->event->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $officer->event->start_date?->format('d M Y') ?? '-' }}
                                        </p>
                                    @else
                                        <p class="text-sm text-red-500 font-medium flex items-center gap-1">
                                            <i class="ti ti-alert-triangle text-xs"></i> Event Terhapus
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">-</p>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5">
                                    @php
                                        $roleCfg = [
                                            'scanner' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            'coordinator' => 'bg-violet-50 text-violet-600 border-violet-200',
                                            'security' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        ];
                                    @endphp
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $roleCfg[$officer->role] ?? '' }} capitalize">
                                        {{ $officer->role }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-lg border
                                            {{ $officer->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                                        {{ $officer->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.officers.show', $officer) }}"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>
                                        <button @click="editOpen = !editOpen"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                            <i class="ti ti-edit text-sm"></i>
                                        </button>
                                        <form action="{{ route('admin.officers.destroy', $officer) }}" method="POST"
                                            onsubmit="return confirm('Hapus officer ini?')">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="$dispatch('open-delete-officer', { id: '{{ $officer->id }}' })"
                                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center
                                            text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Inline Edit Row --}}
                            <tr x-cloak x-show="editOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="bg-gray-50/50">
                                <td colspan="6" class="px-5 py-4">
                                    <form action="{{ route('admin.officers.update', $officer) }}" method="POST"
                                        class="flex items-end gap-3">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="text-xs text-gray-400 block mb-1">Role</label>
                                            <select name="role"
                                                class="h-9 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                                                @foreach (['scanner', 'coordinator', 'security'] as $r)
                                                    <option value="{{ $r }}"
                                                        {{ $officer->role === $r ? 'selected' : '' }}>
                                                        {{ ucfirst($r) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex items-center gap-2 h-9">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1"
                                                    {{ $officer->is_active ? 'checked' : '' }}
                                                    class="w-4 h-4 accent-gray-900">
                                                <span class="text-sm text-gray-700">Aktif</span>
                                            </label>
                                        </div>
                                        <button type="submit"
                                            class="h-9 px-4 bg-gray-900 text-white text-xs rounded-xl hover:bg-gray-700 transition">
                                            Simpan
                                        </button>
                                        <button type="button" @click="editOpen = false"
                                            class="h-9 px-4 border border-gray-100 text-gray-500 text-xs rounded-xl hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <i class="ti ti-id-badge-off text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada field officer terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($officers->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $officers->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Add Form --}}
        <div>
            <div class="sticky top-6 space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-5">Tambah Field Officer</p>

                    <form action="{{ route('admin.officers.store') }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- Pilih User --}}
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Pilih User <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <select name="user_id"
                                    class="w-full h-10 pl-3 pr-10 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none appearance-none focus:outline-none focus:ring-0 focus:border-gray-300 bg-white">
                                    <option value="">Pilih user...</option>
                                    @foreach ($availableUsers as $u)
                                        <option value="{{ $u->id }}"
                                            {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} — {{ $u->email }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                                    <i class="ti ti-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('user_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pilih Event (Sudah Ditambahkan Nullsafe pada loop form kanan) --}}
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Event <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <select name="event_id"
                                    class="w-full h-10 pl-3 pr-10 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none appearance-none focus:outline-none focus:ring-0 focus:border-gray-300 bg-white">
                                    <option value="">Pilih event...</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }} — {{ $event->start_date?->format('d M Y') ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                                    <i class="ti ti-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('event_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ([['value' => 'scanner', 'label' => 'Scanner', 'icon' => 'ti-scan', 'desc' => 'Scan tiket'], ['value' => 'coordinator', 'label' => 'Coordinator', 'icon' => 'ti-sitemap', 'desc' => 'Koordinasi'], ['value' => 'security', 'label' => 'Security', 'icon' => 'ti-shield', 'desc' => 'Keamanan']] as $roleOpt)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="role" value="{{ $roleOpt['value'] }}"
                                            {{ old('role', 'scanner') === $roleOpt['value'] ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="border border-gray-100 rounded-xl p-3 text-center transition h-full flex flex-col items-center justify-center
                                            peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white hover:border-gray-300 text-gray-500">
                                            <i class="ti {{ $roleOpt['icon'] }} text-lg block mb-1"></i>
                                            <p class="text-xs font-semibold">{{ $roleOpt['label'] }}</p>
                                            <p class="text-[9px] opacity-70 mt-0.5 leading-tight">
                                                {{ $roleOpt['desc'] }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('role')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Checkbox Status --}}
                        <label class="flex items-center gap-3 cursor-pointer pt-1">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="w-4 h-4 rounded text-gray-900 focus:ring-0 accent-gray-900 cursor-pointer">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Aktifkan Officer</p>
                                <p class="text-xs text-gray-400">Officer dapat mengakses sistem</p>
                            </div>
                        </label>

                        <button type="submit"
                            class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2 shadow-sm">
                            <i class="ti ti-plus text-base"></i> Tambah Officer
                        </button>
                    </form>
                </div>

                {{-- Info Box --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-blue-700 mb-2">
                        <i class="ti ti-info-circle mr-1 text-sm"></i> Informasi Role
                    </p>
                    <div class="space-y-2 text-xs text-blue-600/90 leading-relaxed">
                        <div class="flex items-start gap-2">
                            <i class="ti ti-scan mt-0.5 flex-shrink-0 text-sm"></i>
                            <div><strong>Scanner</strong> — Bertugas menscan dan memvalidasi tiket di pintu masuk.</div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="ti ti-sitemap mt-0.5 flex-shrink-0 text-sm"></i>
                            <div><strong>Coordinator</strong> — Mengkoordinasi tim dan mengelola operasional event.
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="ti ti-shield mt-0.5 flex-shrink-0 text-sm"></i>
                            <div><strong>Security</strong> — Menjaga keamanan dan ketertiban di lokasi event.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div x-data="{ deleteOfficerId: null }" @open-delete-officer.window="deleteOfficerId = $event.detail.id">

        @foreach ($officers as $officer)
            <div x-show="deleteOfficerId === '{{ $officer->id }}'" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4">

                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteOfficerId = null">
                </div>

                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10 text-center"
                    x-show="deleteOfficerId === '{{ $officer->id }}'" x-transition>

                    <div
                        class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500 border border-red-100">
                        <i class="ti ti-alert-triangle text-xl"></i>
                    </div>

                    <h3 class="text-base font-semibold text-gray-900 mb-1">
                        Hapus Officer?
                    </h3>

                    <p class="text-sm text-gray-500 mb-6">
                        Officer
                        <span class="font-medium text-gray-800">
                            {{ $officer->user->name }}
                        </span>
                        akan dihapus permanen.
                    </p>

                    <div class="flex gap-3">

                        <button type="button" @click="deleteOfficerId = null"
                            class="flex-1 h-10 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </button>

                        <form action="{{ route('admin.officers.destroy', $officer) }}" method="POST"
                            class="flex-1">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="w-full h-10 bg-red-600 text-white text-sm rounded-xl hover:bg-red-700 transition">
                                Ya, Hapus
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
</x-app-layout>
