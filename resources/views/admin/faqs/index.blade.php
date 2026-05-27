<x-app-layout>
    <x-slot name="title">FAQ</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach ([['label' => 'Total FAQ', 'value' => $stats['total'], 'icon' => 'ti-help-circle', 'color' => 'bg-gray-900'], ['label' => 'Aktif', 'value' => $stats['active'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Nonaktif', 'value' => $stats['inactive'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-400']] as $stat)
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

        {{-- Left: FAQ List --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Daftar FAQ</p>
                        <p class="text-xs text-gray-400 mt-0.5">Drag untuk mengubah urutan tampilan</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="ti ti-grip-vertical text-base"></i>
                        <span>Drag & drop</span>
                    </div>
                </div>

                {{-- FAQ List --}}
                <div class="p-4" id="faq-sortable">
                    @forelse($faqs as $faq)
                        <div class="faq-item border border-gray-100 rounded-2xl mb-3 last:mb-0 overflow-hidden"
                            data-id="{{ $faq->id }}" x-data="{ editOpen: false, expanded: false }">

                            {{-- Header --}}
                            <div class="flex items-center gap-3 p-4">

                                {{-- Drag Handle --}}
                                <div
                                    class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 transition flex-shrink-0">
                                    <i class="ti ti-grip-vertical text-xl"></i>
                                </div>

                                {{-- Order Badge --}}
                                <div
                                    class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-semibold text-gray-500 flex-shrink-0">
                                    {{ $faq->order + 1 }}
                                </div>

                                {{-- Question --}}
                                <div class="flex-1 min-w-0 cursor-pointer" @click="expanded = !expanded">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $faq->question }}</p>
                                </div>

                                {{-- Status --}}
                                <span
                                    class="text-xs px-2.5 py-1 rounded-lg flex-shrink-0
                            {{ $faq->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <button @click="editOpen = !editOpen; expanded = false"
                                        class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                        <i class="ti ti-edit text-sm"></i>
                                    </button>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                                        onsubmit="return confirm('Hapus FAQ ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </form>
                                    <button @click="expanded = !expanded; editOpen = false"
                                        class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                        <i class="ti ti-chevron-down text-sm transition-transform duration-200"
                                            :class="expanded ? 'rotate-180' : ''"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Answer Preview --}}
                            <div x-show="expanded" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="px-4 pb-4 border-t border-gray-50">
                                <p class="text-sm text-gray-600 leading-relaxed pt-3">{{ $faq->answer }}</p>
                            </div>

                            {{-- Edit Form --}}
                            <div x-show="editOpen" x-transition class="border-t border-gray-50">
                                <div class="p-4 bg-gray-50/50">
                                    <p class="text-xs font-semibold text-gray-500 mb-3">Edit FAQ</p>
                                    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST"
                                        class="space-y-3">
                                        @csrf @method('PUT')

                                        <div>
                                            <label class="text-xs text-gray-400 block mb-1">Pertanyaan</label>
                                            <input type="text" name="question" value="{{ $faq->question }}"
                                                class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                                        </div>

                                        <div>
                                            <label class="text-xs text-gray-400 block mb-1">Jawaban</label>
                                            <textarea name="answer" rows="4"
                                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ $faq->answer }}</textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-xs text-gray-400 block mb-1">Urutan</label>
                                                <input type="number" name="order" value="{{ $faq->order }}"
                                                    min="0"
                                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                                            </div>
                                            <div class="flex items-end pb-1">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="is_active" value="1"
                                                        {{ $faq->is_active ? 'checked' : '' }}
                                                        class="w-4 h-4 accent-gray-900">
                                                    <span class="text-sm text-gray-700">Aktif</span>
                                                </label>
                                            </div>
                                        </div>

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
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-16">
                            <i class="ti ti-help-circle text-5xl text-gray-200 block mb-3"></i>
                            <p class="text-sm text-gray-400">Belum ada FAQ. Tambahkan FAQ pertama!</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Right: Add Form --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-6">
                <p class="text-sm font-semibold text-gray-900 mb-5">Tambah FAQ</p>

                <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                            Pertanyaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="question" value="{{ old('question') }}"
                            placeholder="Tulis pertanyaan yang sering ditanyakan..."
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('question')  @enderror">
                        @error('question')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">
                            Jawaban <span class="text-red-500">*</span>
                        </label>
                        <textarea name="answer" rows="5" placeholder="Tulis jawaban yang jelas dan informatif..."
                            class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none @error('answer')  @enderror">{{ old('answer') }}</textarea>
                        @error('answer')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 block mb-1.5">Urutan</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                            placeholder="0"
                            class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        <p class="text-xs text-gray-400 mt-1">Semakin kecil angka, semakin atas posisinya</p>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded accent-gray-900">
                        <div>
                            <p class="text-sm text-gray-700 font-medium">Aktifkan FAQ</p>
                            <p class="text-xs text-gray-400">Tampilkan di halaman publik</p>
                        </div>
                    </label>

                    <button type="submit"
                        class="w-full h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2">
                        <i class="ti ti-plus text-base"></i> Tambah FAQ
                    </button>
                </form>
            </div>

            {{-- Tips --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mt-4">
                <p class="text-xs font-semibold text-blue-700 mb-2">
                    <i class="ti ti-info-circle mr-1"></i> Tips FAQ yang Baik
                </p>
                <ul class="text-xs text-blue-600 space-y-1.5">
                    <li>· Tulis pertanyaan dari sudut pandang user</li>
                    <li>· Jawaban singkat, jelas, dan informatif</li>
                    <li>· Kelompokkan FAQ berdasarkan topik</li>
                    <li>· Update secara berkala sesuai kebutuhan</li>
                </ul>
            </div>

        </div>

    </div>

    @push('scripts')
        {{-- SortableJS untuk drag & drop --}}
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script>
            const sortable = Sortable.create(document.getElementById('faq-sortable'), {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: function() {
                    const items = document.querySelectorAll('.faq-item');
                    const orders = [];
                    items.forEach((item, index) => {
                        orders.push(item.dataset.id);
                    });

                    fetch('{{ route('admin.faqs.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                orders
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Urutan FAQ berhasil disimpan');
                            }
                        });
                }
            });
        </script>
    @endpush

</x-app-layout>
