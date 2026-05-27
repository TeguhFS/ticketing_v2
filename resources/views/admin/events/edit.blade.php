<x-app-layout>
    <x-slot name="title">Edit Event</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.events.show', $event) }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Detail
        </a>
    </div>

    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Left: Main Form --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Info Dasar --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-5">Informasi Event</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Judul Event <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('title') @enderror">
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="category_id"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Deskripsi <span
                                    class="text-red-500">*</span></label>
                            <textarea name="description" rows="5"
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Tanggal Mulai</label>
                                <input type="datetime-local" name="start_date"
                                    value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}"
                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Tanggal Selesai</label>
                                <input type="datetime-local" name="end_date"
                                    value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}"
                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-5">Lokasi</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Nama Lokasi / Venue</label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Detail Alamat</label>
                            <textarea name="location_detail" rows="2"
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ old('location_detail', $event->location_detail) }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Google Maps URL</label>
                            <input type="url" name="maps_url" value="{{ old('maps_url', $event->maps_url) }}"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>
                    </div>
                </div>

                {{-- Ticket Types --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5" x-data="ticketTypesEdit({{ $event->ticketTypes->toJson() }})">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Tipe Tiket</p>
                            <p class="text-xs text-gray-400 mt-0.5">Edit atau tambah tipe tiket</p>
                        </div>
                        <button type="button" @click="addType()"
                            class="inline-flex items-center gap-1.5 h-8 px-3 border border-gray-200 text-gray-600 text-xs rounded-xl hover:bg-gray-50 transition">
                            <i class="ti ti-plus text-sm"></i> Tambah Tipe
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(type, index) in types" :key="index">
                            <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                                <input type="hidden" :name="'ticket_types[' + index + '][id]'" :value="type.id">

                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-xs font-semibold text-gray-600" x-text="'Tipe #' + (index + 1)"></p>
                                    <div class="flex items-center gap-2">
                                        <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                                            <input type="checkbox" :name="'ticket_types[' + index + '][is_active]'"
                                                value="1" :checked="type.is_active"
                                                class="accent-gray-900 w-3.5 h-3.5">
                                            Aktif
                                        </label>
                                        <button type="button" @click="removeType(index)"
                                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                                            <i class="ti ti-x text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-400 block mb-1">Nama Tipe</label>
                                        <input type="text" :name="'ticket_types[' + index + '][name]'"
                                            x-model="type.name"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400 block mb-1">Harga</label>
                                        <input type="number" :name="'ticket_types[' + index + '][price]'"
                                            x-model="type.price" min="0"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400 block mb-1">Kuota</label>
                                        <input type="number" :name="'ticket_types[' + index + '][quota]'"
                                            x-model="type.quota" min="1"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400 block mb-1">Maks per Order</label>
                                        <input type="number" :name="'ticket_types[' + index + '][max_per_order]'"
                                            x-model="type.max_per_order" min="1"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400 block mb-1">Mulai Penjualan</label>
                                        <input type="datetime-local" :name="'ticket_types[' + index + '][sale_start]'"
                                            x-model="type.sale_start"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400 block mb-1">Akhir Penjualan</label>
                                        <input type="datetime-local" :name="'ticket_types[' + index + '][sale_end]'"
                                            x-model="type.sale_end"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-400 block mb-1">Deskripsi</label>
                                        <input type="text" :name="'ticket_types[' + index + '][description]'"
                                            x-model="type.description"
                                            class="w-full h-9 px-3 border border-gray-100 rounded-lg text-sm outline-none focus:border-gray-300 bg-white">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- Right --}}
            <div class="space-y-5">

                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Pengaturan</p>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Status</label>
                            <select name="status"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                                @foreach (['draft', 'published', 'cancelled', 'completed'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status', $event->status) === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Maks Peserta</label>
                            <input type="number" name="max_attendees"
                                value="{{ old('max_attendees', $event->max_attendees) }}" min="1"
                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                                class="w-4 h-4 rounded accent-gray-900">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Featured Event</p>
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
                        <img x-show="preview" :src="preview" class="w-full h-40 object-cover"
                            alt="">
                        @if ($event->thumbnail)
                            <img x-show="!preview" src="{{ Storage::url($event->thumbnail) }}"
                                class="w-full h-40 object-cover" alt="">
                        @else
                            <div x-show="!preview" class="h-40 flex flex-col items-center justify-center">
                                <i class="ti ti-photo text-3xl text-gray-200 mb-2"></i>
                                <p class="text-xs text-gray-400">Klik untuk ganti thumbnail</p>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="thumbnail" accept="image/*" x-ref="thumbInput"
                        @change="showPreview($event)" class="hidden">
                </div>

                {{-- Banner --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5" x-data="imagePreview()">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Banner</p>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden cursor-pointer hover:border-gray-300 transition"
                        @click="$refs.bannerInput.click()">
                        <img x-show="preview" :src="preview" class="w-full h-32 object-cover"
                            alt="">
                        @if ($event->banner)
                            <img x-show="!preview" src="{{ Storage::url($event->banner) }}"
                                class="w-full h-32 object-cover" alt="">
                        @else
                            <div x-show="!preview" class="h-32 flex flex-col items-center justify-center">
                                <i class="ti ti-panorama text-3xl text-gray-200 mb-2"></i>
                                <p class="text-xs text-gray-400">Klik untuk ganti banner</p>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="banner" accept="image/*" x-ref="bannerInput"
                        @change="showPreview($event)" class="hidden">
                </div>

                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="ti ti-check text-base"></i> Update Event
                </button>
                <a href="{{ route('admin.events.show', $event) }}"
                    class="w-full h-11 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition flex items-center justify-center">
                    Batal
                </a>

            </div>

        </div>

    </form>

    @push('scripts')
        <script>
            function ticketTypesEdit(existing) {
                return {
                    types: existing.map(t => ({
                        id: t.id,
                        name: t.name,
                        price: t.price,
                        quota: t.quota,
                        max_per_order: t.max_per_order,
                        sale_start: t.sale_start ? t.sale_start.substring(0, 16) : '',
                        sale_end: t.sale_end ? t.sale_end.substring(0, 16) : '',
                        description: t.description || '',
                        is_active: t.is_active,
                    })),
                    addType() {
                        this.types.push({
                            id: null,
                            name: '',
                            price: 0,
                            quota: 100,
                            max_per_order: 5,
                            sale_start: '',
                            sale_end: '',
                            description: '',
                            is_active: true,
                        });
                    },
                    removeType(index) {
                        this.types.splice(index, 1);
                    }
                }
            }

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
