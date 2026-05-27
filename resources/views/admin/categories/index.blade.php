<x-app-layout>
    <x-slot name="title">Kategori Event</x-slot>

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
    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach ([['label' => 'Total Kategori', 'value' => $stats['total'], 'icon' => 'ti-tag', 'color' => 'bg-gray-900'], ['label' => 'Aktif', 'value' => $stats['active'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Nonaktif', 'value' => $stats['inactive'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-400']] as $stat)
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

                {{-- Header + Filter --}}
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-semibold text-gray-900">Daftar Kategori</p>
                    </div>
                    <form method="GET" class="flex gap-3">
                        <div
                            class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-xl px-3 h-9 flex-1">
                            <i class="ti ti-search text-gray-300 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari kategori..."
                                class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                        </div>

                        <div class="relative flex items-center min-w-[120px]">
                            <select name="status"
                                class="h-9 pl-3 pr-10 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                                <option value="">Semua</option>
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
                            class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition flex items-center justify-center">
                            Filter
                        </button>
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Kategori</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Slug</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Event</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                                <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50/50 transition" x-data="{ editOpen: false }">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if ($category->image)
                                                <img src="{{ Storage::url($category->image) }}"
                                                    class="w-9 h-9 rounded-xl object-cover flex-shrink-0"
                                                    alt="">
                                            @else
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                    <i class="ti ti-tag text-gray-400 text-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                                @if ($category->description)
                                                    <p class="text-xs text-gray-400 truncate max-w-[160px]">
                                                        {{ $category->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-lg">
                                            {{ $category->slug }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-sm text-gray-600">{{ $category->events_count }} event</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span
                                            class="text-xs font-medium px-2.5 py-1 rounded-lg border
                                    {{ $category->is_active
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                        : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('buka-modal-edit', { id: '{{ $category->id }}' })"
                                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                                <i class="ti ti-edit text-sm"></i>
                                            </button>
                                            <button type="button"
                                                @click="$dispatch('buka-modal-hapus', { id: '{{ $category->id }}' })"
                                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <i class="ti ti-tag text-4xl text-gray-200 block mb-3"></i>
                                        <p class="text-sm text-gray-400">Belum ada kategori</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $categories->links() }}
                    </div>
                @endif

            </div>
        </div>

        {{-- Right: Forms --}}
        <div class="space-y-5">

            {{-- Add Form --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Tambah Kategori</p>

                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Musik, Olahraga, Seminar..."
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('name') @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Deskripsi singkat kategori..."
                            class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ old('description') }}</textarea>
                    </div>

                    {{-- Image Upload --}}
                    <div x-data="imagePreview()">
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Gambar</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden cursor-pointer hover:border-gray-300 transition"
                            @click="$refs.imgInput.click()">
                            <img x-show="preview" :src="preview" class="w-full h-32 object-cover"
                                alt="">
                            <div x-show="!preview" class="h-32 flex flex-col items-center justify-center">
                                <i class="ti ti-photo text-2xl text-gray-200 mb-1.5"></i>
                                <p class="text-xs text-gray-400">Klik untuk upload gambar</p>
                            </div>
                        </div>
                        <input type="file" name="image" accept="image/*" x-ref="imgInput"
                            @change="showPreview($event)" class="hidden">
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="w-4 h-4 rounded accent-gray-900">
                        <span class="text-sm text-gray-700">Aktifkan kategori</span>
                    </label>

                    <button type="submit"
                        class="w-full h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                        <i class="ti ti-plus text-base"></i> Tambah Kategori
                    </button>
                </form>
            </div>

        </div>

    </div>

    {{-- Delete Confirmation Modals Container --}}
    <div x-data="{ deleteModalId: null }" @buka-modal-hapus.window="deleteModalId = $event.detail.id"
        @tutup-modal-hapus.window="deleteModalId = null" x-cloak>

        @foreach ($categories as $category)
            <div x-show="deleteModalId === '{{ $category->id }}'"
                class="fixed inset-0 z-50 flex items-center justify-center p-4">

                {{-- Backdrop dengan efek blur halus --}}
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteModalId = null"></div>

                {{-- Konten Kotak Dialog Modern --}}
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10 text-center"
                    x-show="deleteModalId === '{{ $category->id }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                    {{-- Icon Peringatan Merah --}}
                    <div
                        class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500 border border-red-100">
                        <i class="ti ti-alert-triangle text-xl"></i>
                    </div>

                    {{-- Text Informasi --}}
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Hapus Kategori?</h3>
                    <p class="text-sm text-gray-500 mb-6 px-2">
                        Apakah Anda yakin ingin menghapus kategori <span
                            class="font-medium text-gray-800">"{{ $category->name }}"</span>? Tindakan ini tidak dapat
                        dibatalkan.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button type="button" @click="deleteModalId = null"
                            class="flex-1 h-10 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition font-medium">
                            Batal
                        </button>

                        {{-- Form Proses Hapus yang Sebenarnya --}}
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                            class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full h-10 bg-red-600 text-white text-sm rounded-xl hover:bg-red-700 transition font-medium shadow-sm shadow-red-100">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Edit Modals --}}
    <div x-data="{ activeModalId: null }" @buka-modal-edit.window="activeModalId = $event.detail.id"
        @tutup-modal-edit.window="activeModalId = null" x-cloak>

        @foreach ($categories as $category)
            <div x-show="activeModalId === '{{ $category->id }}'"
                class="fixed inset-0 z-50 flex items-center justify-center p-4">

                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="activeModalId = null"></div>

                {{-- Modal Content --}}
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10"
                    x-show="activeModalId === '{{ $category->id }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                    <div class="flex items-center justify-between mb-5">
                        <p class="text-sm font-semibold text-gray-900">Edit Kategori</p>
                        <button type="button" @click="activeModalId = null"
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-700 transition">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf @method('PUT')

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Nama Kategori</label>
                            <input type="text" name="name" value="{{ $category->name }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ $category->description }}</textarea>
                        </div>

                        {{-- Image Preview --}}
                        <div x-data="imagePreview()">
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Gambar</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden cursor-pointer hover:border-gray-300 transition h-28"
                                @click="$refs.imgInput.click()">
                                <img x-show="preview" :src="preview" class="w-full h-full object-cover"
                                    alt="">
                                @if ($category->image)
                                    <img x-show="!preview" src="{{ Storage::url($category->image) }}"
                                        class="w-full h-full object-cover" alt="">
                                @else
                                    <div x-show="!preview" class="h-full flex flex-col items-center justify-center">
                                        <i class="ti ti-photo text-2xl text-gray-200 mb-1.5"></i>
                                        <p class="text-xs text-gray-400">Klik untuk ganti gambar</p>
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="image" accept="image/*" x-ref="imgInput"
                                @change="showPreview($event)" class="hidden">
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ $category->is_active ? 'checked' : '' }} class="w-4 h-4 rounded accent-gray-900">
                            <span class="text-sm text-gray-700">Aktifkan kategori</span>
                        </label>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="activeModalId = null"
                                class="flex-1 h-10 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
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
