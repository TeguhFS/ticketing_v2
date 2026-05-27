<x-officer-layout>

    @if (session('success'))
        <div
            class="mb-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Profile Header --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4 text-center">
        @if ($user->avatar)
            <img src="{{ Storage::url($user->avatar) }}"
                class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-2 border-gray-100" alt="">
        @else
            <div
                class="w-20 h-20 rounded-full bg-gray-900 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-3">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
        @endif
        <h2 class="text-base font-bold text-gray-900">{{ $user->name }}</h2>
        <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
        <span class="inline-block mt-2 text-xs font-medium px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700">
            Field Officer
        </span>
    </div>

    {{-- Assignment Info --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4">
        <p class="text-sm font-semibold text-gray-900 mb-3">Penugasan Aktif</p>
        @forelse($officers as $officer)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-calendar-event text-gray-500 text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $officer->event->title ?? '-' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ $officer->role }} ·
                        {{ $officer->officer_code }}</p>
                </div>
                <span
                    class="text-xs px-2 py-0.5 rounded-lg
            {{ $officer->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                    {{ $officer->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada penugasan aktif</p>
        @endforelse
    </div>

    {{-- Edit Profile --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4" x-data="{ open: false }">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-semibold text-gray-900">Edit Profil</p>
            <button @click="open = !open" class="text-xs text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-edit text-base"></i>
            </button>
        </div>

        <div x-show="open" x-transition>
            <form action="{{ route('officer.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf @method('PATCH')

                <div x-data="imagePreview()">
                    <label class="text-xs font-medium text-gray-500 block mb-2">Foto Profil</label>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-dashed border-gray-200 cursor-pointer flex-shrink-0 flex items-center justify-center bg-gray-50"
                            @click="$refs.avatarInput.click()">
                            <img x-show="preview" :src="preview" class="w-full h-full object-cover"
                                alt="">
                            @if ($user->avatar)
                                <img x-show="!preview" src="{{ Storage::url($user->avatar) }}"
                                    class="w-full h-full object-cover" alt="">
                            @else
                                <i x-show="!preview" class="ti ti-camera text-gray-300 text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-medium">Ganti foto profil</p>
                            <p class="text-xs text-gray-400">JPG, PNG max 1MB</p>
                        </div>
                    </div>
                    <input type="file" name="avatar" accept="image/*" x-ref="avatarInput"
                        @change="showPreview($event)" class="hidden">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">
                        Password Baru
                        <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" placeholder="Min. 8 karakter"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                </div>

                <button type="submit"
                    class="w-full h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <div x-show="!open" class="flex items-center gap-3 text-sm text-gray-600">
            <div>
                <p class="font-medium">{{ $user->name }}</p>
                <p class="text-xs text-gray-400">{{ $user->phone ?? 'No. HP belum diisi' }}</p>
            </div>
        </div>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('officer.logout') }}">
        @csrf
        <button type="submit"
            class="w-full h-11 border border-red-200 text-red-500 text-sm rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
            <i class="ti ti-logout text-base"></i> Logout
        </button>
    </form>

    @push('scripts')
        <script>
            function imagePreview() {
                return {
                    preview: null,
                    showPreview(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                }
            }
        </script>
    @endpush

</x-officer-layout>
