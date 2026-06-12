<x-app-layout>
    <x-slot name="title">Tambah Halaman</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.pages.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Halaman
        </a>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Left: Editor --}}
            <div class="xl:col-span-2 space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-5">Konten Halaman</p>
                    <div class="space-y-4">

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Judul Halaman <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                placeholder="Contoh: Kebijakan Privasi"
                                class="w-full h-11 px-4 border border-gray-100 rounded-xl text-base
                            font-medium text-gray-900 outline-none focus:border-gray-300 bg-white
                            @error('title') @enderror">
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Slug URL
                                <span class="text-gray-400 font-normal">(otomatis dari judul jika kosong)</span>
                            </label>
                            <div
                                class="flex items-center gap-2 border border-gray-100 rounded-xl
                        overflow-hidden focus-within:border-gray-300 transition">
                                <span
                                    class="px-3 py-2.5 bg-gray-50 border-r border-gray-100 text-xs
                            text-gray-400 flex-shrink-0">
                                    {{ config('app.url') }}/
                                </span>
                                <input type="text" name="slug" value="{{ old('slug') }}"
                                    placeholder="kebijakan-privasi"
                                    class="flex-1 h-10 px-3 border-none outline-none text-sm text-gray-700
                                bg-white">
                            </div>
                            @error('slug')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Konten <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" id="page-editor" class="@error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Right: Settings --}}
            <div class="space-y-5">

                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Pengaturan</p>
                    <div class="space-y-4">

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Tipe Halaman <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                @foreach ([['value' => 'privacy', 'label' => 'Kebijakan Privasi', 'icon' => 'ti-shield-lock', 'desc' => 'Halaman kebijakan privasi'], ['value' => 'terms', 'label' => 'Syarat & Ketentuan', 'icon' => 'ti-license', 'desc' => 'Halaman syarat & ketentuan'], ['value' => 'custom', 'label' => 'Custom', 'icon' => 'ti-layout', 'desc' => 'Halaman kustom lainnya']] as $type)
                                    <label
                                        class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer
                            transition-all duration-150 hover:border-gray-300
                            has-[:checked]:border-gray-900 has-[:checked]:bg-gray-50">
                                        <input type="radio" name="type" value="{{ $type['value'] }}"
                                            {{ old('type', 'custom') === $type['value'] ? 'checked' : '' }}
                                            class="sr-only">
                                        <div
                                            class="w-8 h-8 bg-gray-100 rounded-lg flex items-center
                                justify-center flex-shrink-0">
                                            <i class="ti {{ $type['icon'] }} text-gray-500 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $type['label'] }}</p>
                                            <p class="text-xs text-gray-400">{{ $type['desc'] }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('type')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Tanggal Publikasi
                            </label>
                            <input type="datetime-local" name="published_at"
                                value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm
                            text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 accent-gray-900">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Aktifkan Halaman</p>
                                <p class="text-xs text-gray-400">Tampilkan di website publik</p>
                            </div>
                        </label>

                    </div>
                </div>

                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl
                hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="ti ti-check text-base"></i> Simpan Halaman
                </button>
                <a href="{{ route('admin.pages.index') }}"
                    class="w-full h-11 border border-gray-100 text-gray-500 text-sm rounded-xl
                hover:bg-gray-50 transition flex items-center justify-center">
                    Batal
                </a>

            </div>

        </div>

    </form>

    @push('scripts')
        <script>
            tinymce.init({
                selector: '#page-editor',
                height: 500,
                menubar: false,
                plugins: 'lists link code table',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | code',
                placeholder: 'Mulai menulis content anda',
                setup: (editor) => editor.on('change', () => editor.save()),
            });
        </script>
    @endpush

</x-app-layout>
