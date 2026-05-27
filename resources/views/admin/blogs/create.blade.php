<x-app-layout>
    <x-slot name="title">Tulis Blog</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.blogs.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Blog
        </a>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Left Area --}}
            <div class="xl:col-span-2 space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="space-y-4">

                        {{-- Title --}}
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Judul Artikel <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                placeholder="Masukkan judul artikel yang menarik..."
                                class="w-full h-11 px-4 border border-gray-100 focus:ring-0 focus:outline-none rounded-xl text-base font-semibold text-gray-900 focus:border-gray-300 bg-white @error('title') @enderror">
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Excerpt --}}
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Excerpt <span
                                    class="text-gray-400 font-normal">(ringkasan singkat)</span></label>
                            <textarea name="excerpt" rows="2" placeholder="Tulis ringkasan singkat artikel max 500 karakter..."
                                maxlength="500"
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 focus:ring-0 bg-white resize-none @error('excerpt') @enderror">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content Editor --}}
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Konten Artikel <span
                                    class="text-red-500">*</span></label>
                            <div class="rounded-xl overflow-hidden">
                                <textarea name="content" id="blog-editor" class="w-full">{{ old('content') }}</textarea>
                            </div>
                            @error('content')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Right Area --}}
            <div class="space-y-5">

                {{-- Settings --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Pengaturan</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Status</label>
                            <div class="relative flex items-center w-full">
                                <select name="status"
                                    class="w-full h-10 pl-3 pr-10 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none appearance-none focus:outline-none focus:ring-0 bg-white cursor-pointer">
                                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>
                                        Published</option>
                                </select>
                                <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                                    <i class="ti ti-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer pt-2">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured') ? 'checked' : '' }}
                                class="w-4 h-4 rounded text-gray-900 focus:ring-0 accent-gray-900 cursor-pointer">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Featured</p>
                                <p class="text-xs text-gray-400">Tampilkan di halaman utama</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5" x-data="imagePreview()">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Thumbnail</p>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden cursor-pointer hover:border-gray-300 transition"
                        @click="$refs.thumbInput.click()">
                        <img x-show="preview" :src="preview" class="w-full h-44 object-cover" x-cloak>
                        <div x-show="!preview" class="h-44 flex flex-col items-center justify-center">
                            <i class="ti ti-photo text-3xl text-gray-200 mb-2"></i>
                            <p class="text-xs text-gray-400">Klik untuk upload thumbnail</p>
                            <p class="text-xs text-gray-300 mt-1">JPG, PNG, WEBP max 2MB</p>
                        </div>
                    </div>
                    <input type="file" name="thumbnail" accept="image/*" x-ref="thumbInput"
                        @change="showPreview($event)" class="hidden">
                    @error('thumbnail')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="space-y-2">
                    <button type="submit"
                        class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2 shadow-sm">
                        <i class="ti ti-check text-base"></i> Simpan Artikel
                    </button>
                    <a href="{{ route('admin.blogs.index') }}"
                        class="w-full h-11 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition flex items-center justify-center font-medium">
                        Batal
                    </a>
                </div>
            </div>

        </div>
    </form>

    @push('scripts')
        <script>
            tinymce.init({
                selector: '#blog-editor',
                height: 480,
                menubar: false,
                plugins: 'lists link image code table wordcount',
                toolbar: 'undo redo | blocks | bold italic underline backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image table | code',
                skin: 'oxide',
                content_style: 'body { font-family:Figtree, sans-serif; font-size:14px; color: #374151; }',
                placeholder: 'Mulai menulis konten artikel...',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });

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
