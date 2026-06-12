<x-app-layout>
    <x-slot name="title">Tentang Kami</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200
    text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base flex-shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Tentang Kami</h1>
            <p class="text-xs text-gray-400 mt-1">Kelola konten halaman Tentang Kami</p>
        </div>
        <a href="{{ route('about.index') }}" target="_blank"
            class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200
            text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition">
            <i class="ti ti-external-link text-base"></i> Preview
        </a>
    </div>

    {{-- Sections --}}
    <div class="space-y-4" id="about-sortable">
        @foreach ($sections as $section)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden about-section"
                data-id="{{ $section->id }}" x-data="{ open: false }">

                {{-- Section Header --}}
                <div class="flex items-center gap-4 px-5 py-4 cursor-pointer" @click="open = !open">

                    {{-- Drag handle --}}
                    <div
                        class="drag-handle cursor-grab text-gray-300 hover:text-gray-500
                transition flex-shrink-0">
                        <i class="ti ti-grip-vertical text-xl"></i>
                    </div>

                    {{-- Key Badge --}}
                    @php
                        $keyConfig = [
                            'hero' => ['icon' => 'ti-home', 'bg' => 'bg-gray-900', 'text' => 'text-white'],
                            'stats' => ['icon' => 'ti-chart-bar', 'bg' => 'bg-blue-500', 'text' => 'text-white'],
                            'vision' => ['icon' => 'ti-eye', 'bg' => 'bg-violet-500', 'text' => 'text-white'],
                            'mission' => ['icon' => 'ti-target', 'bg' => 'bg-amber-500', 'text' => 'text-white'],
                            'values' => ['icon' => 'ti-heart', 'bg' => 'bg-rose-500', 'text' => 'text-white'],
                            'team' => ['icon' => 'ti-users', 'bg' => 'bg-emerald-500', 'text' => 'text-white'],
                            'cta' => ['icon' => 'ti-rocket', 'bg' => 'bg-cyan-500', 'text' => 'text-white'],
                        ];
                        $kc = $keyConfig[$section->key] ?? [
                            'icon' => 'ti-layout',
                            'bg' => 'bg-gray-500',
                            'text' => 'text-white',
                        ];
                    @endphp
                    <div
                        class="w-9 h-9 {{ $kc['bg'] }} rounded-xl flex items-center
                justify-center flex-shrink-0">
                        <i class="ti {{ $kc['icon'] }} {{ $kc['text'] }} text-base"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $section->title ?? ucfirst($section->key) }}
                            </p>
                            <span
                                class="font-mono text-xs bg-gray-100 text-gray-500
                        px-2 py-0.5 rounded-lg">
                                {{ $section->key }}
                            </span>
                        </div>
                        @if ($section->subtitle)
                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $section->subtitle }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        {{-- Toggle active --}}
                        <form action="{{ route('admin.about.toggle', $section) }}" method="POST" @click.stop>
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="h-6 px-2.5 text-xs font-medium rounded-full border transition
                            {{ $section->is_active
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                                {{ $section->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>

                        <i class="ti ti-chevron-down text-gray-400 text-base
                    transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </div>

                {{-- Section Form --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="border-t border-gray-50">

                    <form action="{{ route('admin.about.update', $section) }}" method="POST"
                        enctype="multipart/form-data" class="p-5 space-y-4">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div
                                class="{{ in_array($section->key, ['hero', 'stats', 'values', 'cta']) ? 'sm:col-span-2' : '' }}">
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Judul</label>
                                <input type="text" name="title" value="{{ $section->title }}"
                                    placeholder="Judul section..."
                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm
                                text-gray-700 outline-none focus:border-gray-300 bg-white">
                            </div>

                            @if (!in_array($section->key, ['vision']))
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Subtitle</label>
                                    <textarea name="subtitle" rows="2" placeholder="Subtitle atau deskripsi singkat..."
                                        class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm
                                text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ $section->subtitle }}</textarea>
                                </div>
                            @endif

                            @if (in_array($section->key, ['hero', 'vision']))
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Konten</label>
                                    <textarea name="content" rows="4" placeholder="Konten utama..."
                                        class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm
                                text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ $section->content }}</textarea>
                                </div>
                            @endif

                            {{-- Image Upload --}}
                            @if (in_array($section->key, ['hero', 'vision']))
                                <div class="sm:col-span-2" x-data="imagePreview('{{ $section->image ? Storage::url($section->image) : '' }}')">
                                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Gambar</label>
                                    <div class="flex items-center gap-4">
                                        <div class="w-24 h-20 border-2 border-dashed border-gray-200 rounded-xl
                                overflow-hidden cursor-pointer hover:border-gray-300 transition
                                flex-shrink-0 flex items-center justify-center bg-gray-50"
                                            @click="$refs.imgInput.click()">
                                            <img x-show="preview" :src="preview"
                                                class="w-full h-full object-cover" alt="">
                                            <i x-show="!preview" class="ti ti-photo text-2xl text-gray-200"></i>
                                        </div>
                                        <div class="flex-1">
                                            <button type="button" @click="$refs.imgInput.click()"
                                                class="inline-flex items-center gap-1.5 h-8 px-3 border
                                        border-gray-200 text-gray-600 text-xs rounded-xl
                                        hover:bg-gray-50 transition mb-2">
                                                <i class="ti ti-upload text-sm"></i> Upload Gambar
                                            </button>
                                            @if ($section->image)
                                                <label class="flex items-center gap-2 cursor-pointer mt-1">
                                                    <input type="checkbox" name="delete_image" value="1"
                                                        class="w-3.5 h-3.5 accent-red-500">
                                                    <span class="text-xs text-red-500">Hapus gambar</span>
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="file" name="image" accept="image/*" x-ref="imgInput"
                                        @change="showPreview($event)" class="hidden">
                                </div>
                            @endif

                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ $section->is_active ? 'checked' : '' }} class="w-4 h-4 accent-gray-900">
                                <span class="text-sm text-gray-700">Tampilkan section ini</span>
                            </label>
                            <button type="submit"
                                class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white
                            text-xs font-medium rounded-xl hover:bg-gray-700 transition">
                                <i class="ti ti-device-floppy text-sm"></i> Simpan
                            </button>
                        </div>

                    </form>

                    {{-- Items Editor --}}
                    @if ($section->items && count($section->items) > 0)
                        <div class="border-t border-gray-50 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Items ({{ count($section->items) }})
                                </p>
                            </div>

                            {{-- PERBAIKAN: Ditambahkan enctype="multipart/form-data" untuk menangani upload gambar item --}}
                            <form action="{{ route('admin.about.items', $section) }}" method="POST"
                                enctype="multipart/form-data" x-data="itemsEditor({{ json_encode($section->items) }})">
                                @csrf @method('PUT')

                                <div class="space-y-3 mb-4">
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                                            <div class="flex items-center justify-between mb-3">
                                                <p class="text-xs font-semibold text-gray-500"
                                                    x-text="'Item #' + (index + 1)"></p>
                                                <button type="button" @click="removeItem(index)"
                                                    class="w-6 h-6 flex items-center justify-center
                                            text-gray-300 hover:text-red-500 transition">
                                                    <i class="ti ti-x text-sm"></i>
                                                </button>
                                            </div>

                                            {{-- Dynamic fields based on section type --}}
                                            @if ($section->key === 'stats')
                                                <div class="grid grid-cols-3 gap-2">
                                                    <div>
                                                        <label class="text-xs text-gray-400 block mb-1">Value</label>
                                                        <input type="text" :name="'items[' + index + '][value]'"
                                                            x-model="item.value" placeholder="10.000+"
                                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg
                                                text-sm outline-none focus:border-gray-300 bg-white">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs text-gray-400 block mb-1">Label</label>
                                                        <input type="text" :name="'items[' + index + '][label]'"
                                                            x-model="item.label" placeholder="Event Terlaksana"
                                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg
                                                text-sm outline-none focus:border-gray-300 bg-white">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs text-gray-400 block mb-1">Icon</label>
                                                        <input type="text" :name="'items[' + index + '][icon]'"
                                                            x-model="item.icon" placeholder="ti-calendar-event"
                                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg
                                                text-sm outline-none focus:border-gray-300 bg-white font-mono">
                                                    </div>
                                                </div>
                                            @elseif($section->key === 'team')
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                                                    {{-- Foto --}}
                                                    <div class="flex flex-col items-center">
                                                        <label class="text-xs text-gray-400 block mb-2">
                                                            Foto Anggota
                                                        </label>

                                                        <div class="relative w-24 h-24 rounded-full overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer group"
                                                            @click="$event.currentTarget.nextElementSibling.click()">

                                                            {{-- Preview upload baru --}}
                                                            <template x-if="item.preview_url">
                                                                <img :src="item.preview_url"
                                                                    class="w-full h-full object-cover">
                                                            </template>

                                                            {{-- Foto lama --}}
                                                            <template x-if="!item.preview_url && item.image">
                                                                <img :src="'{{ asset('storage') }}/' + item.image"
                                                                    class="w-full h-full object-cover">
                                                            </template>

                                                            {{-- Placeholder --}}
                                                            <template x-if="!item.preview_url && !item.image">
                                                                <div
                                                                    class="w-full h-full flex items-center justify-center">
                                                                    <i class="ti ti-user text-3xl text-gray-300"></i>
                                                                </div>
                                                            </template>

                                                            <div
                                                                class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center">
                                                                <i class="ti ti-camera text-white text-lg"></i>
                                                            </div>
                                                        </div>

                                                        <input type="file" :name="'items[' + index + '][image]'"
                                                            accept="image/*" class="hidden"
                                                            @change="handleFileChange($event,index)">

                                                        <input type="hidden" :name="'items[' + index + '][old_image]'"
                                                            x-model="item.image">

                                                        <p class="text-[10px] text-gray-400 mt-2 text-center">
                                                            JPG, PNG, WEBP
                                                        </p>
                                                    </div>

                                                    {{-- Data anggota --}}
                                                    <div class="md:col-span-3 grid grid-cols-2 gap-2">

                                                        <div>
                                                            <label class="text-xs text-gray-400 block mb-1">
                                                                Nama
                                                            </label>

                                                            <input type="text" :name="'items[' + index + '][name]'"
                                                                x-model="item.name" placeholder="Nama lengkap"
                                                                class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm bg-white">
                                                        </div>

                                                        <div>
                                                            <label class="text-xs text-gray-400 block mb-1">
                                                                Jabatan
                                                            </label>

                                                            <input type="text" :name="'items[' + index + '][role]'"
                                                                x-model="item.role" placeholder="CEO & Founder"
                                                                class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm bg-white">
                                                        </div>

                                                        <div class="col-span-2">
                                                            <label class="text-xs text-gray-400 block mb-1">
                                                                Bio
                                                            </label>

                                                            <textarea :name="'items[' + index + '][bio]'" x-model="item.bio" rows="2" placeholder="Deskripsi singkat..."
                                                                class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm bg-white resize-none"></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                            @elseif(in_array($section->key, ['values', 'mission']))
                                                <div class="grid grid-cols-1 gap-2">
                                                    @if ($section->key === 'values')
                                                        <div>
                                                            <label
                                                                class="text-xs text-gray-400 block mb-1">Icon</label>
                                                            <input type="text" :name="'items[' + index + '][icon]'"
                                                                x-model="item.icon" placeholder="ti-heart"
                                                                class="w-full h-9 px-3 border border-gray-100 rounded-lg
                                                text-sm outline-none focus:border-gray-300 bg-white font-mono">
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <label class="text-xs text-gray-400 block mb-1">Judul</label>
                                                        <input type="text"
                                                            :name="'items[' + index + '][' + (
                                                                @if ($section->key === 'values') 'title' @else 'title' @endif
                                                            ) + ']'"
                                                            x-model="item.title" placeholder="Judul item..."
                                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg
                                                text-sm outline-none focus:border-gray-300 bg-white">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-xs text-gray-400 block mb-1">Deskripsi</label>
                                                        <textarea :name="'items[' + index + '][desc]'" x-model="item.desc" rows="2" placeholder="Deskripsi item..."
                                                            class="w-full px-3 py-2 border border-gray-100 rounded-lg
                                                text-sm outline-none focus:border-gray-300 bg-white resize-none"></textarea>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </template>
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="button" @click="addItem()"
                                        class="inline-flex items-center gap-1.5 h-8 px-3 border border-gray-200
                                text-gray-600 text-xs rounded-xl hover:bg-gray-50 transition">
                                        <i class="ti ti-plus text-sm"></i> Tambah Item
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white
                                text-xs font-medium rounded-xl hover:bg-gray-700 transition">
                                        <i class="ti ti-device-floppy text-sm"></i> Simpan Items
                                    </button>
                                </div>

                            </form>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script>
            // Drag & drop reorder
            Sortable.create(document.getElementById('about-sortable'), {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd() {
                    const items = document.querySelectorAll('.about-section');
                    const orders = [...items].map(el => el.dataset.id);
                    fetch('{{ route('admin.about.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            orders
                        }),
                    });
                }
            });

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

            function itemsEditor(existing = []) {
                return {
                    items: existing.map(item => ({
                        value: item.value ?? '',
                        label: item.label ?? '',
                        icon: item.icon ?? '',
                        title: item.title ?? '',
                        desc: item.desc ?? '',
                        name: item.name ?? '',
                        role: item.role ?? '',
                        bio: item.bio ?? '',
                        image: item.image ?? '',
                        preview_url: null,
                    })),

                    addItem() {
                        this.items.push({
                            value: '',
                            label: '',
                            icon: '',
                            title: '',
                            desc: '',
                            name: '',
                            role: '',
                            bio: '',
                            image: '',
                            preview_url: null,
                        });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                    },

                    handleFileChange(event, index) {
                        const file = event.target.files[0];

                        if (!file) return;

                        const reader = new FileReader();

                        reader.onload = (e) => {
                            this.items[index].preview_url = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    }
                }
            }
        </script>
    @endpush

</x-app-layout>
