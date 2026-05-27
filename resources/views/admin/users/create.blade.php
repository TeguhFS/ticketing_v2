<x-app-layout>
    <x-slot name="title">Tambah User</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Users
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Left --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Info Akun --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-5">Informasi Akun</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap..."
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('name')  @enderror">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="email@example.com"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('email')  @enderror">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Role <span
                                    class="text-red-500">*</span></label>
                            <select name="role"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white @error('role') @enderror">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : 'selected' }}>
                                    User</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin
                                </option>
                                <option value="field_officer" {{ old('role') === 'field_officer' ? 'selected' : '' }}>
                                    Field Officer</option>
                            </select>
                            @error('role')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Min. 8 karakter"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('password') @enderror">
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Konfirmasi Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>
                    </div>
                </div>

                {{-- Info Pribadi --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-5">Informasi Pribadi</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">No. HP</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Jenis Kelamin</label>
                            <select name="gender"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                                <option value="">Pilih...</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">NIK (KTP)</label>
                            <input type="text" name="id_card_number" value="{{ old('id_card_number') }}"
                                placeholder="16 digit NIK"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>

                        <div class="col-span-2">
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Alamat</label>
                            <textarea name="address" rows="3" placeholder="Alamat lengkap..."
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right --}}
            <div class="space-y-5">

                {{-- Avatar --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5" x-data="imagePreview()">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Foto Profil</p>
                    <div class="flex flex-col items-center">
                        <div class="w-28 h-28 rounded-full border-2 border-dashed border-gray-200 overflow-hidden cursor-pointer hover:border-gray-300 transition mb-3 flex items-center justify-center bg-gray-50"
                            @click="$refs.avatarInput.click()">
                            <img x-show="preview" :src="preview" class="w-full h-full object-cover"
                                alt="">
                            <div x-show="!preview" class="text-center">
                                <i class="ti ti-user text-3xl text-gray-200 block mb-1"></i>
                                <p class="text-xs text-gray-400">Upload foto</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 text-center">JPG, PNG max 1MB</p>
                        <input type="file" name="avatar" accept="image/*" x-ref="avatarInput"
                            @change="showPreview($event)" class="hidden">
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Pengaturan</p>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="w-4 h-4 rounded accent-gray-900">
                        <div>
                            <p class="text-sm text-gray-700 font-medium">Aktifkan Akun</p>
                            <p class="text-xs text-gray-400">User dapat login ke sistem</p>
                        </div>
                    </label>
                </div>

                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="ti ti-check text-base"></i> Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="w-full h-11 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition flex items-center justify-center">
                    Batal
                </a>

            </div>

        </div>

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

</x-app-layout>
