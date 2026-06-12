<x-app-layout>
    <x-slot name="title">Edit Halaman</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.pages.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Halaman
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Left: Editor --}}
            <div class="xl:col-span-2 space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-sm font-semibold text-gray-900">Konten Halaman</p>
                        @if ($page->type === 'privacy')
                        <a href="{{ route('pages.privacy') }}" target="_blank" @elseif($page->type === 'terms')
                            <a href="{{ route('pages.terms') }}" target="_blank" @else <a
                                href="{{ route('pages.show', $page->slug) }}" target="_blank" @endif
                                class="inline-flex items-center gap-1.5 text-xs text-blue-600
                        hover:text-blue-800 transition">
                                <i class="ti ti-external-link text-sm"></i> Preview
                            </a>
                    </div>
                    <div class="space-y-4">

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Judul Halaman <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $page->title) }}"
                                class="w-full h-11 px-4 border border-gray-100 rounded-xl text-base
                            font-medium text-gray-900 outline-none focus:border-gray-300 bg-white
                            @error('title') @enderror">
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Slug URL</label>
                            <div
                                class="flex items-center gap-2 border border-gray-100 rounded-xl
                        overflow-hidden focus-within:border-gray-300 transition">
                                <span
                                    class="px-3 py-2.5 bg-gray-50 border-r border-gray-100 text-xs
                            text-gray-400 flex-shrink-0">
                                    {{ config('app.url') }}/
                                </span>
                                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}"
                                    @if (in_array($page->type, ['privacy', 'terms'])) readonly @endif
                                    class="flex-1 h-10 px-3 border-none outline-none text-sm text-gray-700
                                bg-white {{ in_array($page->type, ['privacy', 'terms']) ? 'opacity-50' : '' }}">
                            </div>
                            @error('slug')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Konten <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" id="page-editor">{{ old('content', $page->content) }}</textarea>
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
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Tipe Halaman</label>
                            <div class="space-y-2">
                                @foreach ([['value' => 'privacy', 'label' => 'Kebijakan Privasi', 'icon' => 'ti-shield-lock'], ['value' => 'terms', 'label' => 'Syarat & Ketentuan', 'icon' => 'ti-license'], ['value' => 'custom', 'label' => 'Custom', 'icon' => 'ti-layout']] as $type)
                                    <label
                                        class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer
                            transition-all duration-150 hover:border-gray-300
                            has-[:checked]:border-gray-900 has-[:checked]:bg-gray-50
                            {{ in_array($page->type, ['privacy', 'terms']) && $type['value'] !== $page->type ? 'opacity-40' : '' }}">
                                        <input type="radio" name="type" value="{{ $type['value'] }}"
                                            {{ old('type', $page->type) === $type['value'] ? 'checked' : '' }}
                                            {{ in_array($page->type, ['privacy', 'terms']) ? 'disabled' : '' }}
                                            class="sr-only">
                                        <div
                                            class="w-8 h-8 bg-gray-100 rounded-lg flex items-center
                                justify-center flex-shrink-0">
                                            <i class="ti {{ $type['icon'] }} text-gray-500 text-sm"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">{{ $type['label'] }}</p>
                                    </label>
                                @endforeach
                            </div>
                            @if (in_array($page->type, ['privacy', 'terms']))
                                <input type="hidden" name="type" value="{{ $page->type }}">
                                <p class="text-xs text-amber-600 mt-2 flex items-center gap-1">
                                    <i class="ti ti-lock text-xs"></i>
                                    Tipe halaman ini tidak dapat diubah
                                </p>
                            @endif
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Tanggal Publikasi
                            </label>
                            <input type="datetime-local" name="published_at"
                                value="{{ old('published_at', $page->published_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm
                            text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 accent-gray-900">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Aktifkan Halaman</p>
                                <p class="text-xs text-gray-400">Tampilkan di website publik</p>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- Meta info --}}
                <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-xs text-gray-400">
                    <div class="flex justify-between">
                        <span>Dibuat</span>
                        <span>{{ $page->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diperbarui</span>
                        <span>{{ $page->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl
                hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="ti ti-check text-base"></i> Update Halaman
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
                setup: (editor) => editor.on('change', () => editor.save()),
            });
        </script>
    @endpush

</x-app-layout>
