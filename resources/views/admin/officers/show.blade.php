<x-app-layout>
    <x-slot name="title">Detail Field Officer</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('admin.officers.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Field Officers
        </a>
        <form action="{{ route('admin.officers.destroy', $officer) }}" method="POST"
            onsubmit="return confirm('Hapus officer ini?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-2 h-9 px-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl hover:bg-red-100 transition">
                <i class="ti ti-trash text-base"></i> Hapus
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Validation Stats --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach ([['label' => 'Total Scan', 'value' => $validationStats['total'], 'icon' => 'ti-scan', 'color' => 'bg-gray-900'], ['label' => 'Valid', 'value' => $validationStats['valid'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Invalid', 'value' => $validationStats['invalid'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-500'], ['label' => 'Already Used', 'value' => $validationStats['already_used'], 'icon' => 'ti-clock-off', 'color' => 'bg-amber-500']] as $stat)
                    <div class="bg-white rounded-2xl border border-gray-100 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">{{ $stat['label'] }}</p>
                            <div class="w-8 h-8 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                                <i class="ti {{ $stat['icon'] }} text-white text-sm"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Recent Validations --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Riwayat Validasi Tiket</p>

                @forelse($recentValidations as $validation)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                        <div
                            class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $validation->status === 'valid'
                        ? 'bg-emerald-100'
                        : ($validation->status === 'invalid'
                            ? 'bg-red-100'
                            : 'bg-amber-100') }}">
                            <i
                                class="ti text-sm
                        {{ $validation->status === 'valid'
                            ? 'ti-check text-emerald-600'
                            : ($validation->status === 'invalid'
                                ? 'ti-x text-red-500'
                                : 'ti-clock text-amber-600') }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-mono text-gray-600">{{ $validation->ticket_code }}</p>
                            <p class="text-sm text-gray-700 mt-0.5 truncate">
                                {{ $validation->ticket->ticketType->event->title ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @php
                                $vCfg = [
                                    'valid' => 'bg-emerald-50 text-emerald-700',
                                    'invalid' => 'bg-red-50 text-red-600',
                                    'already_used' => 'bg-amber-50 text-amber-600',
                                ];
                            @endphp
                            <span
                                class="text-xs px-2.5 py-1 rounded-lg {{ $vCfg[$validation->status] ?? '' }} capitalize">
                                {{ str_replace('_', ' ', $validation->status) }}
                            </span>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $validation->validated_at?->format('d M, H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="ti ti-scan text-4xl text-gray-200 block mb-3"></i>
                        <p class="text-sm text-gray-400">Belum ada riwayat validasi</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- Officer Profile --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
                @if ($officer->user->avatar)
                    <img src="{{ Storage::url($officer->user->avatar) }}"
                        class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-2 border-gray-100"
                        alt="">
                @else
                    <div
                        class="w-20 h-20 rounded-full bg-gray-900 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">
                        {{ strtoupper(substr($officer->user->name, 0, 2)) }}
                    </div>
                @endif

                <h2 class="text-base font-semibold text-gray-900">{{ $officer->user->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $officer->user->email }}</p>

                <div class="flex items-center justify-center gap-2 mt-3">
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
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-lg border
                    {{ $officer->is_active
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                        {{ $officer->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                {{-- Officer Code --}}
                <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 mb-1">Kode Officer</p>
                    <p class="font-mono text-sm font-semibold text-gray-900">{{ $officer->officer_code }}</p>
                </div>
            </div>

            {{-- Event Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Informasi Event</p>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Nama Event</p>
                        <p class="text-sm font-medium text-gray-900">{{ $officer->event->title ?? '-' }}</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Lokasi</span>
                        <span class="text-gray-700">{{ $officer->event->location ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tanggal</span>
                        <span class="text-gray-700">{{ $officer->event->start_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Status Event</span>
                        <span class="text-gray-700 capitalize">{{ $officer->event->status ?? '-' }}</span>
                    </div>
                    <a href="{{ route('admin.events.show', $officer->event) }}"
                        class="flex items-center justify-center gap-2 h-9 border border-gray-100 text-gray-500 text-xs rounded-xl hover:bg-gray-50 transition mt-2">
                        <i class="ti ti-external-link text-sm"></i> Lihat Detail Event
                    </a>
                </div>
            </div>

            {{-- Edit Form --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5" x-data="{ open: false }">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold text-gray-900">Edit Officer</p>
                    <button @click="open = !open" class="text-xs text-gray-400 hover:text-gray-700 transition">
                        <i class="ti ti-edit text-sm"></i>
                    </button>
                </div>

                <div x-show="open" x-transition>
                    <form action="{{ route('admin.officers.update', $officer) }}" method="POST" class="space-y-3">
                        @csrf @method('PUT')
                        <div>
                            <label class="text-xs text-gray-400 block mb-1">Role</label>
                            <select name="role"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                                @foreach (['scanner', 'coordinator', 'security'] as $r)
                                    <option value="{{ $r }}" {{ $officer->role === $r ? 'selected' : '' }}>
                                        {{ ucfirst($r) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ $officer->is_active ? 'checked' : '' }} class="w-4 h-4 accent-gray-900">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                        <button type="submit"
                            class="w-full h-9 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <div x-show="!open" class="text-center py-4">
                    <i class="ti ti-id-badge text-3xl text-gray-200 block mb-2"></i>
                    <p class="text-xs text-gray-400">Klik icon edit untuk mengubah data</p>
                </div>
            </div>

            {{-- Meta --}}
            <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-xs text-gray-400">
                <div class="flex justify-between">
                    <span>Ditambahkan</span>
                    <span>{{ $officer->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Diperbarui</span>
                    <span>{{ $officer->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
