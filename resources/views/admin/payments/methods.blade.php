<x-app-layout>
    <x-slot name="title">Payment Methods</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('admin.payments.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Payment
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left: List Methods --}}
        <div class="xl:col-span-2 space-y-4">

            @forelse($methods as $method)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden" x-data="{ bankOpen: false, editOpen: false }">

                    {{-- Method Header --}}
                    <div class="flex items-center gap-4 p-5">
                        @if ($method->logo)
                            <img src="{{ Storage::url($method->logo) }}"
                                class="w-12 h-12 rounded-xl object-cover border border-gray-100 flex-shrink-0"
                                alt="">
                        @else
                            <div
                                class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-credit-card text-gray-400 text-xl"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $method->name }}</p>
                                <span
                                    class="font-mono text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg">{{ $method->code }}</span>
                                <span
                                    class="text-xs px-2 py-0.5 rounded-lg
                            {{ $method->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="capitalize">{{ str_replace('_', ' ', $method->type) }}</span>
                                @if ($method->fee > 0)
                                    <span>· Fee Rp {{ number_format($method->fee, 0, ',', '.') }}</span>
                                @endif
                                @if ($method->fee_percent > 0)
                                    <span>· {{ $method->fee_percent }}%</span>
                                @endif
                                <span>· {{ $method->payments_count }} transaksi</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="editOpen = !editOpen"
                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                <i class="ti ti-edit text-sm"></i>
                            </button>
                            <form action="{{ route('admin.payments.methods.destroy', $method) }}" method="POST"
                                onsubmit="return confirm('Hapus metode ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                    <i class="ti ti-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Form --}}
                    <div x-show="editOpen" x-transition class="px-5 pb-5 border-t border-gray-50">
                        <p class="text-xs font-semibold text-gray-500 mt-4 mb-3">Edit Metode</p>
                        <form action="{{ route('admin.payments.methods.update', $method) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-3">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-400 block mb-1">Nama</label>
                                    <input type="text" name="name" value="{{ $method->name }}"
                                        class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-1">Kode</label>
                                    <input type="text" name="code" value="{{ $method->code }}"
                                        class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white uppercase">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-1">Tipe</label>
                                    <select name="type"
                                        class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none bg-white">
                                        @foreach (['bank_transfer', 'e_wallet', 'qris', 'credit_card', 'other'] as $type)
                                            <option value="{{ $type }}"
                                                {{ $method->type === $type ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-1">Fee (Rp)</label>
                                    <input type="number" name="fee" value="{{ $method->fee }}" min="0"
                                        class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-1">Fee (%)</label>
                                    <input type="number" name="fee_percent" value="{{ $method->fee_percent }}"
                                        min="0" max="100" step="0.01"
                                        class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-1">Logo</label>
                                    <input type="file" name="logo" accept="image/*"
                                        class="w-full h-9 px-3 border border-gray-100 rounded-xl text-xs text-gray-500 bg-white file:mr-2 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:rounded-lg">
                                </div>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ $method->is_active ? 'checked' : '' }} class="w-3.5 h-3.5 accent-gray-900">
                                <span class="text-xs text-gray-600">Aktif</span>
                            </label>
                            <div class="flex gap-2 pt-1">
                                <button type="button" @click="editOpen = false"
                                    class="flex-1 h-9 border border-gray-100 text-gray-500 text-xs rounded-xl hover:bg-gray-50 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 h-9 bg-gray-900 text-white text-xs rounded-xl hover:bg-gray-700 transition">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Bank Accounts --}}
                    @if ($method->type === 'bank_transfer')
                        <div class="px-5 pb-5 border-t border-gray-50">
                            <div class="flex items-center justify-between mt-4 mb-3">
                                <p class="text-xs font-semibold text-gray-500">Rekening Bank</p>
                                <button @click="bankOpen = !bankOpen"
                                    class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-700 transition">
                                    <i class="ti ti-plus text-sm"></i> Tambah
                                </button>
                            </div>

                            {{-- Bank List --}}
                            <div class="space-y-2 mb-3">
                                @forelse($method->bankAccounts as $bank)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">{{ $bank->bank_name }}</p>
                                            <p class="text-xs font-mono text-gray-600 mt-0.5">
                                                {{ $bank->account_number }}</p>
                                            <p class="text-xs text-gray-400">a/n {{ $bank->account_name }}</p>
                                        </div>
                                        <form action="{{ route('admin.payments.bank-accounts.destroy', $bank) }}"
                                            method="POST" onsubmit="return confirm('Hapus rekening ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 transition">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400 py-2">Belum ada rekening bank terdaftar.</p>
                                @endforelse
                            </div>

                            {{-- Add Bank Form --}}
                            <div x-show="bankOpen" x-transition>
                                <form action="{{ route('admin.payments.bank-accounts.store', $method) }}"
                                    method="POST" enctype="multipart/form-data"
                                    class="border border-gray-100 rounded-xl p-4 bg-gray-50/50 space-y-3">
                                    @csrf
                                    <p class="text-xs font-semibold text-gray-600">Tambah Rekening</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-xs text-gray-400 block mb-1">Nama Bank</label>
                                            <input type="text" name="bank_name" placeholder="BCA, Mandiri, BNI..."
                                                class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-400 block mb-1">No. Rekening</label>
                                            <input type="text" name="account_number" placeholder="1234567890"
                                                class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-400 block mb-1">Nama Pemilik</label>
                                            <input type="text" name="account_name"
                                                placeholder="Nama sesuai rekening"
                                                class="w-full h-9 px-3 border border-gray-100 rounded-xl text-sm outline-none focus:border-gray-300 bg-white">
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" @click="bankOpen = false"
                                            class="flex-1 h-9 border border-gray-100 text-gray-500 text-xs rounded-xl hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 h-9 bg-gray-900 text-white text-xs rounded-xl hover:bg-gray-700 transition">
                                            Simpan Rekening
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                    <i class="ti ti-credit-card-off text-5xl text-gray-200 block mb-3"></i>
                    <p class="text-sm text-gray-400">Belum ada metode pembayaran</p>
                </div>
            @endforelse

        </div>

        {{-- Right: Add Form --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-5">Tambah Metode Pembayaran</p>

                <form action="{{ route('admin.payments.methods.store') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Nama <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Transfer Bank, OVO, GoPay..."
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('name')  @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Kode <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}"
                            placeholder="BCA, OVO, GOPAY..."
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white uppercase @error('code') @enderror">
                        @error('code')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Tipe <span
                                class="text-red-500">*</span></label>
                        <select name="type"
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                            @foreach (['bank_transfer' => 'Transfer Bank', 'e_wallet' => 'E-Wallet', 'qris' => 'QRIS', 'credit_card' => 'Kartu Kredit', 'other' => 'Lainnya'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Fee (Rp)</label>
                            <input type="number" name="fee" value="{{ old('fee', 0) }}" min="0"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Fee (%)</label>
                            <input type="number" name="fee_percent" value="{{ old('fee_percent', 0) }}"
                                min="0" max="100" step="0.01"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>
                    </div>

                    <div x-data="imagePreview()">
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Logo</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden cursor-pointer hover:border-gray-300 transition h-24"
                            @click="$refs.logoInput.click()">
                            <img x-show="preview" :src="preview" class="w-full h-full object-contain p-2"
                                alt="">
                            <div x-show="!preview" class="h-full flex flex-col items-center justify-center">
                                <i class="ti ti-photo text-2xl text-gray-200 mb-1"></i>
                                <p class="text-xs text-gray-400">Upload logo</p>
                            </div>
                        </div>
                        <input type="file" name="logo" accept="image/*" x-ref="logoInput"
                            @change="showPreview($event)" class="hidden">
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="w-4 h-4 accent-gray-900">
                        <span class="text-sm text-gray-700">Aktifkan metode pembayaran</span>
                    </label>

                    <button type="submit"
                        class="w-full h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                        <i class="ti ti-plus text-base"></i> Tambah Metode
                    </button>
                </form>
            </div>
        </div>

    </div>

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
