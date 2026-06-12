<x-auth-layout>
    <x-slot name="title">Profil Saya — {{ setting('app_name') }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-sm text-gray-400 mt-1">Kelola informasi pribadi dan keamanan akun Anda</p>
        </div>

        {{-- Success Messages --}}
        @if (session('success'))
            <div
                class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl shadow-sm">
                <i class="ti ti-circle-check text-base flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('success_password'))
            <div
                class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl shadow-sm">
                <i class="ti ti-circle-check text-base flex-shrink-0"></i>
                <span>{{ session('success_password') }}</span>
            </div>
        @endif

        {{-- ── Global Error Messages Alert ─────────────────────────── --}}
        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-2 font-semibold">
                    <i class="ti ti-alert-triangle text-base flex-shrink-0 text-red-500"></i>
                    <span>Gagal memperbarui profil. Silakan periksa kembali:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 pl-1 text-xs text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Left: Profile Summary ─────────────────────── --}}
            <div class="space-y-5">

                {{-- Profile Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center">

                    {{-- Avatar --}}
                    <div class="relative inline-block mb-4" x-data="avatarUpload()">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-gray-100
                        mx-auto cursor-pointer relative group"
                            @click="$refs.avatarInput.click()">
                            @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover"
                                    alt="{{ $user->name }}" id="avatar-preview">
                            @else
                                <div class="w-full h-full bg-gray-900 flex items-center justify-center"
                                    id="avatar-placeholder">
                                    <span class="text-white text-2xl font-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif

                            {{-- Hover overlay --}}
                            <div
                                class="absolute inset-0 bg-black/50 flex items-center justify-center
                            opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <i class="ti ti-camera text-white text-xl"></i>
                            </div>
                        </div>

                        {{-- Upload form --}}
                        <form id="avatar-form" action="{{ route('user.profile.avatar') }}"method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            <input type="file" name="avatar" accept="image/*" x-ref="avatarInput"
                                @change="previewAndSubmit($event)" class="hidden">
                        </form>

                        {{-- Edit badge --}}
                        <button @click="$refs.avatarInput.click()"
                            class="absolute -bottom-2 -right-2 w-8 h-8 bg-gray-900 rounded-xl
                            flex items-center justify-center border-2 border-white
                            hover:bg-gray-700 transition-colors duration-200">
                            <i class="ti ti-pencil text-white text-xs"></i>
                        </button>
                    </div>

                    <h2 class="text-base font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>

                    {{-- Role Badge --}}
                    <div class="flex items-center justify-center gap-2 mt-3">
                        <span
                            class="text-xs font-medium px-3 py-1 rounded-full
                        bg-blue-50 text-blue-700 border border-blue-200 capitalize">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                        @if ($user->is_active)
                            <span
                                class="text-xs font-medium px-3 py-1 rounded-full
                        bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-3 mt-5 pt-5 border-t border-gray-100">
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $user->orders_count }}</p>
                            <p class="text-xs text-gray-400">Order</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $user->tickets_count }}</p>
                            <p class="text-xs text-gray-400">Tiket</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $totalSpent >= 1000000
                                    ? 'Rp' . number_format($totalSpent / 1000000, 1) . 'jt'
                                    : 'Rp' . number_format($totalSpent / 1000, 0) . 'rb' }}
                            </p>
                            <p class="text-xs text-gray-400">Spent</p>
                        </div>
                    </div>

                </div>

                {{-- Profile Completion --}}
                @php
                    $fields = [
                        'name' => 'Nama Lengkap',
                        'email' => 'Email',
                        'phone' => 'Nomor HP',
                        'address' => 'Alamat',
                        'birth_date' => 'Tanggal Lahir',
                        'id_card_number' => 'NIK KTP',
                    ];
                    $filled = collect($fields)->filter(fn($l, $f) => !empty($user->$f))->count();
                    $percentage = round(($filled / count($fields)) * 100);
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-bold text-gray-900">Kelengkapan Profil</p>
                        <span
                            class="text-sm font-bold
                        {{ $percentage >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $percentage }}%
                        </span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full transition-all duration-700
                        {{ $percentage >= 80 ? 'bg-emerald-400' : ($percentage >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                            style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="space-y-2">
                        @foreach ($fields as $field => $label)
                            <div class="flex items-center gap-2 text-xs">
                                @if (!empty($user->$field))
                                    <i class="ti ti-circle-check text-emerald-500 text-sm flex-shrink-0"></i>
                                    <span class="text-gray-500">{{ $label }}</span>
                                @else
                                    <i class="ti ti-circle text-gray-200 text-sm flex-shrink-0"></i>
                                    <span class="text-gray-700">{{ $label }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Right: Forms ────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Tab Navigation --}}
                <div x-data="{ tab: 'personal' }">

                    {{-- Tabs --}}
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden mb-5">
                        <div class="flex border-b border-gray-100">
                            @foreach ([['key' => 'personal', 'icon' => 'ti-user', 'label' => 'Data Diri'], ['key' => 'security', 'icon' => 'ti-lock', 'label' => 'Keamanan'], ['key' => 'identity', 'icon' => 'ti-id-badge', 'label' => 'Identitas'], ['key' => 'danger', 'icon' => 'ti-alert-circle', 'label' => 'Bahaya']] as $t)
                                <button @click="tab = '{{ $t['key'] }}'"
                                    :class="tab === '{{ $t['key'] }}'
                                        ?
                                        'border-b-2 border-gray-900 text-gray-900 bg-gray-50/50' :
                                        'text-gray-400 hover:text-gray-600'"
                                    class="flex-1 flex items-center justify-center gap-1.5 px-3 py-3.5
                                text-xs font-medium transition-all duration-200">
                                    <i class="ti {{ $t['icon'] }} text-sm"></i>
                                    <span class="hidden sm:block">{{ $t['label'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- ── Tab: Data Diri ────────────────────────── --}}
                        <div x-show="tab === 'personal'" x-transition class="p-6">

                            <div class="mb-5">
                                <h3 class="text-sm font-bold text-gray-900">Informasi Pribadi</h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Perbarui nama, email, dan informasi kontak Anda
                                </p>
                            </div>

                            <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-4">
                                @csrf @method('PATCH')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                    <div class="sm:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <i
                                                class="ti ti-user absolute left-3 top-1/2 -translate-y-1/2
                                            text-gray-300 text-sm"></i>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                                class="w-full h-11 pl-9 pr-4 border border-gray-100 rounded-xl
                                                text-sm text-gray-700 outline-none
                                                focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                                bg-white transition-all duration-200
                                                @error('name')  @enderror">
                                        </div>
                                        @error('name')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <i
                                                class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2
                                            text-gray-300 text-sm"></i>
                                            <input type="email" name="email"
                                                value="{{ old('email', $user->email) }}"
                                                class="w-full h-11 pl-9 pr-4 border border-gray-100 rounded-xl
                                                text-sm text-gray-700 outline-none
                                                focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                                bg-white transition-all duration-200
                                                @error('email')  @enderror">
                                        </div>
                                        @error('email')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                            <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                                <i class="ti ti-alert-triangle text-xs"></i>
                                                Email belum diverifikasi.
                                                <button form="send-verification" class="underline font-medium">
                                                    Kirim ulang
                                                </button>
                                            </p>
                                        @endif
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                            Nomor HP
                                        </label>
                                        <div class="relative">
                                            <i
                                                class="ti ti-phone absolute left-3 top-1/2 -translate-y-1/2
                                            text-gray-300 text-sm"></i>
                                            <input type="text" name="phone"
                                                value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx"
                                                class="w-full h-11 pl-9 pr-4 border border-gray-100 rounded-xl
                                                text-sm text-gray-700 outline-none
                                                focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                                bg-white transition-all duration-200">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                            Jenis Kelamin
                                        </label>
                                        <div class="relative">
                                            <i
                                                class="ti ti-gender-bigender absolute left-3 top-1/2
                                            -translate-y-1/2 text-gray-300 text-sm"></i>
                                            <select name="gender"
                                                class="w-full h-11 pl-9 pr-4 border border-gray-100 rounded-xl
                                                text-sm text-gray-700 outline-none
                                                focus:border-gray-400 bg-white appearance-none
                                                transition-all duration-200">
                                                <option value="">Pilih...</option>
                                                <option value="male"
                                                    {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>
                                                    Laki-laki
                                                </option>
                                                <option value="female"
                                                    {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>
                                                    Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                            Tanggal Lahir
                                        </label>
                                        <div class="relative">
                                            <i
                                                class="ti ti-calendar absolute left-3 top-1/2 -translate-y-1/2
                                            text-gray-300 text-sm"></i>
                                            <input type="date" name="birth_date"
                                                value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                                class="w-full h-11 pl-9 pr-4 border border-gray-100 rounded-xl
                                                text-sm text-gray-700 outline-none
                                                focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                                bg-white transition-all duration-200">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                            Alamat
                                        </label>
                                        <div class="relative">
                                            <i
                                                class="ti ti-map-pin absolute left-3 top-3
                                            text-gray-300 text-sm"></i>
                                            <textarea name="address" rows="3" placeholder="Alamat lengkap Anda..."
                                                class="w-full pl-9 pr-4 py-3 border border-gray-100 rounded-xl
                                                text-sm text-gray-700 outline-none
                                                focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                                bg-white resize-none transition-all duration-200">{{ old('address', $user->address) }}</textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 h-11 px-6 bg-gray-900
                                        text-white text-sm font-semibold rounded-xl
                                        hover:bg-gray-700 transition-all duration-200">
                                        <i class="ti ti-device-floppy text-base"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>

                            </form>

                        </div>

                        {{-- ── Tab: Keamanan ──────────────────────────── --}}
                        <div x-show="tab === 'security'" x-transition class="p-6">

                            <div class="mb-5">
                                <h3 class="text-sm font-bold text-gray-900">Keamanan Akun</h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Perbarui password untuk menjaga keamanan akun Anda
                                </p>
                            </div>

                            <form method="POST" action="{{ route('user.profile.password') }}" class="space-y-4">
                                @csrf @method('PATCH')

                                <div>
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                        Password Saat Ini <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative" x-data="{ show: false }">
                                        <i
                                            class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2
                                        text-gray-300 text-sm"></i>
                                        <input :type="show ? 'text' : 'password'" name="current_password"
                                            placeholder="Masukkan password saat ini"
                                            class="w-full h-11 pl-9 pr-11 border border-gray-100 rounded-xl
                                            text-sm text-gray-700 outline-none
                                            focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                            bg-white transition-all duration-200
                                            @error('current_password')  @enderror">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2
                                            text-gray-400 hover:text-gray-600 transition">
                                            <i class="ti text-base" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                        Password Baru <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative" x-data="{ show: false }">
                                        <i
                                            class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2
                                        text-gray-300 text-sm"></i>
                                        <input :type="show ? 'text' : 'password'" name="password"
                                            placeholder="Min. 8 karakter"
                                            class="w-full h-11 pl-9 pr-11 border border-gray-100 rounded-xl
                                            text-sm text-gray-700 outline-none
                                            focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                            bg-white transition-all duration-200
                                            @error('password')  @enderror">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2
                                            text-gray-400 hover:text-gray-600 transition">
                                            <i class="ti text-base" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative" x-data="{ show: false }">
                                        <i
                                            class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2
                                        text-gray-300 text-sm"></i>
                                        <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                            placeholder="Ulangi password baru"
                                            class="w-full h-11 pl-9 pr-11 border border-gray-100 rounded-xl
                                            text-sm text-gray-700 outline-none
                                            focus:border-gray-400 focus:ring-2 focus:ring-gray-100
                                            bg-white transition-all duration-200">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2
                                            text-gray-400 hover:text-gray-600 transition">
                                            <i class="ti text-base" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Password Strength Tips --}}
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs font-semibold text-gray-600 mb-2">
                                        <i class="ti ti-shield-check text-sm text-emerald-500 mr-1"></i>
                                        Tips Password Kuat
                                    </p>
                                    <ul class="space-y-1.5 text-xs text-gray-500">
                                        @foreach (['Minimal 8 karakter', 'Kombinasi huruf besar dan kecil', 'Gunakan angka dan simbol (!@#$)', 'Jangan gunakan informasi pribadi'] as $tip)
                                            <li class="flex items-center gap-2">
                                                <i class="ti ti-point-filled text-gray-300 text-xs"></i>
                                                {{ $tip }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 h-11 px-6 bg-gray-900
                                        text-white text-sm font-semibold rounded-xl
                                        hover:bg-gray-700 transition-all duration-200">
                                        <i class="ti ti-lock text-base"></i>
                                        Update Password
                                    </button>
                                </div>

                            </form>

                        </div>

                        {{-- ── Tab: Identitas ─────────────────────────── --}}
                        <div x-show="tab === 'identity'" x-transition class="p-6">

                            <div class="mb-5">
                                <h3 class="text-sm font-bold text-gray-900">Data Identitas</h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Data ini diperlukan untuk verifikasi identitas Anda
                                </p>
                            </div>

                            <form method="POST" action="{{ route('user.profile.update') }}"
                                enctype="multipart/form-data" class="space-y-4">
                                @csrf @method('PATCH')

                                {{-- Keep existing data --}}
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">

                                <div>
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                        NIK (Nomor Induk Kependudukan)
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="ti ti-id-badge absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                                        <input type="text" name="id_card_number"
                                            value="{{ old('id_card_number', $user->id_card_number) }}"
                                            placeholder="16 digit NIK sesuai KTP" maxlength="16"
                                            class="w-full h-11 pl-9 pr-4 border rounded-xl text-sm text-gray-700 outline-none focus:ring-2 focus:ring-gray-100 bg-white font-mono tracking-wider transition-all duration-200 {{ $errors->has('id_card_number') ? 'border-red-300 focus:border-red-400 focus:ring-red-50' : 'border-gray-100 focus:border-gray-400' }}">
                                    </div>

                                    {{-- Pesan Error NIK --}}
                                    @error('id_card_number')
                                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                            <i class="ti ti-alert-circle text-sm"></i> {{ $message }}
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1">
                                            NIK akan digunakan untuk verifikasi tiket di lokasi event
                                        </p>
                                    @enderror
                                </div>

                                {{-- ID Card Image --}}
                                <div x-data="imagePreview('{{ $user->id_card_image ? Storage::url($user->id_card_image) : '' }}')">
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                        Foto KTP
                                    </label>
                                    <div class="border-2 border-dashed rounded-2xl overflow-hidden cursor-pointer transition-colors duration-200 {{ $errors->has('id_card_image') ? 'border-red-200 bg-red-50/10 hover:border-red-300' : 'border-gray-200 hover:border-gray-300' }}"
                                        @click="$refs.idInput.click()">

                                        <img x-show="preview" :src="preview" class="w-full h-40 object-cover"
                                            alt="">

                                        <div x-show="!preview"
                                            class="h-40 flex flex-col items-center justify-center bg-gray-50/50">
                                            <i class="ti ti-id-badge text-4xl text-gray-200 block mb-2"></i>
                                            <p class="text-sm text-gray-400">Upload foto KTP</p>
                                            <p class="text-xs text-gray-300 mt-1">JPG, PNG max 2MB</p>
                                        </div>
                                    </div>
                                    <input type="file" name="id_card_image" accept="image/*" x-ref="idInput"
                                        @change="showPreview($event)" class="hidden">

                                    {{-- Pesan Error Foto KTP --}}
                                    @error('id_card_image')
                                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                            <i class="ti ti-alert-circle text-sm"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Privacy notice --}}
                                <div
                                    class="bg-blue-50 border border-blue-100 rounded-xl p-4
                                flex items-start gap-3">
                                    <i class="ti ti-shield-lock text-blue-500 text-lg flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-semibold text-blue-700 mb-1">
                                            Keamanan Data
                                        </p>
                                        <p class="text-xs text-blue-600 leading-relaxed">
                                            Data identitas Anda dienkripsi dan hanya digunakan untuk keperluan
                                            verifikasi. Kami tidak membagikan data Anda kepada pihak ketiga.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 h-11 px-6 bg-gray-900
                                        text-white text-sm font-semibold rounded-xl
                                        hover:bg-gray-700 transition-all duration-200">
                                        <i class="ti ti-device-floppy text-base"></i>
                                        Simpan Identitas
                                    </button>
                                </div>

                            </form>

                        </div>

                        {{-- ── Tab: Bahaya ────────────────────────────── --}}
                        <div x-show="tab === 'danger'" x-transition class="p-6">

                            <div class="mb-5">
                                <h3 class="text-sm font-bold text-gray-900">Zona Bahaya</h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Tindakan di sini bersifat permanen dan tidak dapat dibatalkan
                                </p>
                            </div>

                            {{-- Logout all devices --}}
                            <div class="border border-gray-100 rounded-2xl p-5 mb-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            Logout dari Semua Perangkat
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                            Keluar dari semua sesi aktif di perangkat lain.
                                            Anda tetap login di perangkat ini.
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                                        @csrf
                                        <button type="submit"
                                            class="h-9 px-4 border border-gray-200 text-gray-600 text-xs
                                            font-medium rounded-xl hover:bg-gray-50
                                            transition-all duration-200 whitespace-nowrap">
                                            <i class="ti ti-logout text-sm mr-1.5"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Delete Account --}}
                            <div class="border border-red-100 rounded-2xl p-5 bg-red-50/30" x-data="{ open: false }">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div>
                                        <p class="text-sm font-semibold text-red-700">
                                            Hapus Akun Permanen
                                        </p>
                                        <p class="text-xs text-red-500 mt-1 leading-relaxed">
                                            Setelah dihapus, semua data termasuk tiket dan riwayat
                                            order tidak dapat dipulihkan.
                                        </p>
                                    </div>
                                    <button type="button" @click="open = true"
                                        class="flex-shrink-0 h-9 px-4 bg-red-500 text-white text-xs
                                        font-semibold rounded-xl hover:bg-red-600
                                        transition-all duration-200 whitespace-nowrap">
                                        <i class="ti ti-trash text-sm mr-1.5"></i>
                                        Hapus Akun
                                    </button>
                                </div>

                                {{-- Confirmation --}}
                                <div x-show="open" x-transition class="border-t border-red-100 pt-4 mt-2">
                                    <p class="text-xs font-semibold text-red-700 mb-3">
                                        Masukkan password untuk konfirmasi penghapusan:
                                    </p>
                                    <form method="POST" action="{{ route('user.profile.destroy') }}"
                                        class="space-y-3">
                                        @csrf @method('DELETE')
                                        <div class="relative" x-data="{ show: false }">
                                            <i
                                                class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2
                                            text-red-300 text-sm"></i>
                                            <input :type="show ? 'text' : 'password'" name="password"
                                                placeholder="Masukkan password Anda"
                                                class="w-full h-10 pl-9 pr-11 border border-red-200
                                                rounded-xl text-sm text-gray-700 outline-none
                                                focus:border-red-400 bg-white transition-all duration-200">
                                            <button type="button" @click="show = !show"
                                                class="absolute right-3 top-1/2 -translate-y-1/2
                                                text-red-300 hover:text-red-500 transition">
                                                <i class="ti text-sm" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                                            </button>
                                        </div>
                                        @error('password', 'userDeletion')
                                            <p class="text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                        <div class="flex gap-2">
                                            <button type="button" @click="open = false"
                                                class="flex-1 h-9 border border-gray-200 text-gray-600
                                                text-xs font-medium rounded-xl hover:bg-gray-50
                                                transition-all duration-200">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="flex-1 h-9 bg-red-500 text-white text-xs
                                                font-semibold rounded-xl hover:bg-red-600
                                                transition-all duration-200">
                                                Ya, Hapus Akun Saya
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- Email verification form --}}
    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
        <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="hidden">
            @csrf
        </form>
    @endif

    @push('scripts')
        <script>
            function avatarUpload() {
                return {
                    previewAndSubmit(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        // Preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const preview = document.getElementById('avatar-preview');
                            const placeholder = document.getElementById('avatar-placeholder');
                            if (preview) {
                                preview.src = e.target.result;
                            } else if (placeholder) {
                                placeholder.outerHTML = `<img id="avatar-preview"
                        src="${e.target.result}"
                        class="w-full h-full object-cover" alt="">`;
                            }
                        };
                        reader.readAsDataURL(file);

                        // Submit form
                        document.getElementById('avatar-form').submit();
                    }
                }
            }

            function imagePreview(existing = '') {
                return {
                    preview: existing || null,
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

    </x-au-layout>
