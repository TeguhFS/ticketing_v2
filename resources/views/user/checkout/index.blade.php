<x-user-layout>
    <x-slot name="title">Checkout — {{ $event->title }}</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
            <a href="{{ route('welcome') }}" class="hover:text-gray-700 transition">Home</a>
            <i class="ti ti-chevron-right text-gray-300 text-xs"></i>
            <a href="{{ route('events.index') }}" class="hover:text-gray-700 transition">Event</a>
            <i class="ti ti-chevron-right text-gray-300 text-xs"></i>
            <a href="{{ route('events.show', $event->slug) }}"
                class="hover:text-gray-700 transition truncate max-w-[150px]">
                {{ $event->title }}
            </a>
            <i class="ti ti-chevron-right text-gray-300 text-xs"></i>
            <span class="text-gray-900 font-medium">Checkout</span>
        </nav>

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
            <p class="text-sm text-gray-400 mt-1">Pilih tiket dan selesaikan pembayaran</p>
        </div>

        {{-- PERBAIKAN: Alpine.js sekarang membaca data lengkap ticketTypes langsung dari properti relasi event --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="checkout({{ $event->ticketTypes->toJson() }})">

            {{-- ── Left: Ticket Selection ─────────────────────── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Event Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <div class="flex items-start gap-4">
                        @if ($event->thumbnail)
                            <img src="{{ Storage::url($event->thumbnail) }}"
                                class="w-20 h-20 rounded-xl object-cover flex-shrink-0 border border-gray-100"
                                alt="{{ $event->title }}">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 mb-1">{{ $event->category->name ?? '' }}</p>
                            <h2 class="text-base font-bold text-gray-900 leading-snug mb-2">
                                {{ $event->title }}
                            </h2>
                            <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-calendar text-gray-300"></i>
                                    {{ $event->start_date->translatedFormat('l, d M Y') }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-clock text-gray-300"></i>
                                    {{ $event->start_date->format('H:i') }} WIB
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-map-pin text-gray-300"></i>
                                    {{ $event->location }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ticket Selection --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-900">Pilih Tiket</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pilih jenis dan jumlah tiket</p>
                    </div>
                    <div class="p-5 space-y-3">
                        <template x-for="(type, index) in ticketTypes" :key="type.id">
                            {{-- PERBAIKAN: Jika tiket tidak valid/habis masa jual, beri background abu-abu pudar --}}
                            <div class="border rounded-2xl p-4 transition-all duration-200"
                                :class="!isAvailable(type) ? 'bg-gray-50/70 border-gray-100 opacity-75' : (quantities[type.id] >
                                    0 ? 'border-gray-900 bg-gray-50/50 shadow-sm' :
                                    'border-gray-100 hover:border-gray-200')">

                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-sm font-bold text-gray-900"
                                                :class="!isAvailable(type) && 'text-gray-400'" x-text="type.name"></p>

                                            {{-- Badge Sisa Kuota (Hanya muncul jika tiket masih aktif dijual) --}}
                                            <span x-show="isAvailable(type)"
                                                class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full"
                                                x-text="(type.quota - type.sold) + ' tersisa'"></span>

                                            {{-- PERBAIKAN: Badge Pesan Error Penjualan --}}
                                            <span x-show="!isAvailable(type)"
                                                class="text-xs px-2.5 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded-full font-medium flex items-center gap-1">
                                                <i class="ti ti-alert-circle text-xs"></i>
                                                <span x-text="getErrorMessage(type)"></span>
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mb-2" x-text="type.description || ''"
                                            x-show="type.description"></p>
                                        <p class="text-base font-bold text-gray-900"
                                            :class="!isAvailable(type) && 'text-gray-400'">
                                            <span x-show="type.price == 0" class="text-emerald-600">GRATIS</span>
                                            <span x-show="type.price > 0"
                                                x-text="'Rp ' + formatNumber(type.price)"></span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5"
                                            x-text="'Maks ' + type.max_per_order + ' per order'"></p>
                                    </div>

                                    {{-- Qty control --}}
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button type="button" @click="decreaseQty(type.id)"
                                            :disabled="quantities[type.id] <= 0 || !isAvailable(type)"
                                            class="w-8 h-8 rounded-xl border flex items-center justify-center
                                            transition-all duration-200 text-sm font-bold
                                            disabled:opacity-30 disabled:cursor-not-allowed"
                                            :class="quantities[type.id] > 0 ?
                                                'border-gray-900 bg-gray-900 text-white hover:bg-gray-700' :
                                                'border-gray-200 text-gray-400 hover:border-gray-300'">
                                            <i class="ti ti-minus text-sm"></i>
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-gray-900"
                                            x-text="quantities[type.id] || 0"></span>
                                        <button type="button"
                                            @click="increaseQty(type.id, type.max_per_order, type.quota - type.sold)"
                                            :disabled="!isAvailable(type) || quantities[type.id] >= type.max_per_order ||
                                                quantities[type.id] >= (type.quota - type.sold)"
                                            class="w-8 h-8 rounded-xl border border-gray-900 bg-gray-900
                                            text-white flex items-center justify-center
                                            hover:bg-gray-700 transition-all duration-200 text-sm
                                            disabled:opacity-30 disabled:cursor-not-allowed">
                                            <i class="ti ti-plus text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Line total --}}
                                <div x-show="quantities[type.id] > 0" x-transition
                                    class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                                    <p class="text-xs text-gray-500"
                                        x-text="quantities[type.id] + ' tiket × Rp ' + formatNumber(type.price)">
                                    </p>
                                    <p class="text-sm font-bold text-gray-900"
                                        x-text="'Rp ' + formatNumber(quantities[type.id] * type.price)">
                                    </p>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>

                {{-- Buyer Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-900">Informasi Pemesan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Data ini akan digunakan untuk tiket Anda</p>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            @if (auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                    class="w-12 h-12 rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center
                            text-white font-bold flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                @if (auth()->user()->phone)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->phone }}</p>
                                @endif
                            </div>
                            <a href="{{ route('user.profile.edit') }}"
                                class="ml-auto text-xs text-gray-500 hover:text-gray-900 transition
                                flex items-center gap-1">
                                <i class="ti ti-edit text-sm"></i> Edit
                            </a>
                        </div>
                        @if (!auth()->user()->phone)
                            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2">
                                <i class="ti ti-alert-triangle text-amber-500 text-sm flex-shrink-0 mt-0.5"></i>
                                <p class="text-xs text-amber-700">
                                    Lengkapi nomor HP di
                                    <a href="{{ route('user.profile.edit') }}" class="font-semibold underline">
                                        profil Anda
                                    </a>
                                    untuk informasi tiket yang lebih lengkap.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Right: Order Summary ──────────────────────── --}}
            <div class="lg:sticky lg:top-24 space-y-4">

                {{-- Summary Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-900">Ringkasan Order</h3>
                    </div>

                    <div class="p-5">

                        {{-- Selected tickets --}}
                        <div class="space-y-2 mb-4" x-show="totalItems > 0">
                            <template x-for="type in selectedItems" :key="type.id">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-700 truncate" x-text="type.name"></p>
                                        <p class="text-xs text-gray-400"
                                            x-text="quantities[type.id] + ' × Rp ' + formatNumber(type.price)">
                                        </p>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-900 flex-shrink-0"
                                        x-text="'Rp ' + formatNumber(quantities[type.id] * type.price)">
                                    </p>
                                </div>
                            </template>
                        </div>

                        {{-- Empty state --}}
                        <div x-show="totalItems === 0" class="text-center py-6 bg-gray-50 rounded-xl mb-4">
                            <i class="ti ti-ticket text-3xl text-gray-200 block mb-2"></i>
                            <p class="text-xs text-gray-400">Belum ada tiket dipilih</p>
                        </div>

                        {{-- Totals --}}
                        <div class="border-t border-gray-50 pt-4 space-y-2">
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Subtotal (<span x-text="totalItems"></span> tiket)</span>
                                <span x-text="'Rp ' + formatNumber(subtotal)"></span>
                            </div>
                            <div
                                class="flex justify-between text-base font-bold text-gray-900
                            pt-2 border-t border-gray-100">
                                <span>Total</span>
                                <span x-text="'Rp ' + formatNumber(subtotal)"></span>
                            </div>
                        </div>

                        {{-- Pay Button --}}
                        <button @click="pay()" :disabled="totalItems === 0 || loading"
                            class="w-full h-12 bg-gray-900 text-white font-bold text-sm rounded-xl
                            hover:bg-gray-700 transition-all duration-200 mt-4
                            disabled:opacity-40 disabled:cursor-not-allowed
                            flex items-center justify-center gap-2">
                            <span x-show="!loading" class="flex items-center gap-2">
                                <i class="ti ti-credit-card text-base"></i>
                                Bayar Sekarang
                            </span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>

                        {{-- Error msg --}}
                        <div x-show="errorMsg" x-transition
                            class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-xs text-red-600" x-text="errorMsg"></p>
                        </div>

                    </div>
                </div>

                {{-- Security badge --}}
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div class="space-y-2">
                        @foreach ([['icon' => 'ti-shield-check', 'text' => 'Pembayaran diproses oleh Midtrans'], ['icon' => 'ti-lock', 'text' => 'Data dienkripsi SSL 256-bit'], ['icon' => 'ti-credit-card', 'text' => 'Berbagai metode pembayaran'], ['icon' => 'ti-refresh', 'text' => 'Refund mudah jika ada masalah']] as $item)
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="ti {{ $item['icon'] }} text-emerald-500 text-sm flex-shrink-0"></i>
                                {{ $item['text'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Terms --}}
                <p class="text-xs text-gray-400 text-center leading-relaxed">
                    Dengan melanjutkan, Anda menyetujui
                    <a href="#" class="text-gray-700 underline">Syarat & Ketentuan</a>
                    dan
                    <a href="#" class="text-gray-700 underline">Kebijakan Privasi</a>
                    kami.
                </p>

            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            function checkout(ticketTypes) {
                return {
                    ticketTypes: ticketTypes,
                    quantities: {},
                    loading: false,
                    errorMsg: '',

                    init() {
                        ticketTypes.forEach(t => {
                            this.quantities[t.id] = 0;
                        });
                    },

                    // PERBAIKAN: Fungsi Client-Side validation untuk mendeteksi status penjualan tiket
                    isAvailable(type) {
                        const now = new Date();
                        const isStockAvailable = (type.quota - type.sold) > 0;

                        // Konversi string tanggal dari DB ke Objek Date Javascript
                        const saleStart = type.sale_start ? new Date(type.sale_start) : null;
                        const saleEnd = type.sale_end ? new Date(type.sale_end) : null;

                        const isStarted = !saleStart || now >= saleStart;
                        const isNotExpired = !saleEnd || now <= saleEnd;

                        return isStockAvailable && isStarted && isNotExpired;
                    },

                    // PERBAIKAN: Fungsi penentu pesan kesalahan teks penutupan tiket secara real-time
                    getErrorMessage(type) {
                        const now = new Date();
                        const isStockAvailable = (type.quota - type.sold) > 0;
                        const saleStart = type.sale_start ? new Date(type.sale_start) : null;
                        const saleEnd = type.sale_end ? new Date(type.sale_end) : null;

                        if (!isStockAvailable) return 'Kuota Habis';
                        if (saleStart && now < saleStart) return 'Belum Dimulai';
                        if (saleEnd && now > saleEnd) return 'Penjualan Habis';
                        return 'Tidak Tersedia';
                    },

                    get selectedItems() {
                        return this.ticketTypes.filter(t => this.quantities[t.id] > 0);
                    },

                    get totalItems() {
                        return Object.values(this.quantities).reduce((a, b) => a + b, 0);
                    },

                    get subtotal() {
                        return this.ticketTypes.reduce((sum, t) => {
                            return sum + (this.quantities[t.id] || 0) * t.price;
                        }, 0);
                    },

                    increaseQty(id, max, remaining) {
                        const curr = this.quantities[id] || 0;
                        if (curr < max && curr < remaining) {
                            this.quantities[id] = curr + 1;
                        }
                    },

                    decreaseQty(id) {
                        if (this.quantities[id] > 0) {
                            this.quantities[id]--;
                        }
                    },

                    formatNumber(n) {
                        return new Intl.NumberFormat('id-ID').format(n);
                    },

                    async pay() {
                        if (this.totalItems === 0) return;
                        this.loading = true;
                        this.errorMsg = '';

                        const tickets = this.ticketTypes
                            .filter(t => this.quantities[t.id] > 0)
                            .map(t => ({
                                ticket_type_id: t.id,
                                quantity: this.quantities[t.id],
                            }));

                        try {
                            const res = await fetch('{{ route('checkout.store', $event->slug) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    tickets
                                }),
                            });

                            const data = await res.json();

                            if (!data.success) {
                                this.errorMsg = data.message || 'Terjadi kesalahan.';
                                this.loading = false;
                                return;
                            }

                            // Open Midtrans Snap
                            window.snap.pay(data.snap_token, {
                                onSuccess: (result) => {
                                    window.location.href = '{{ route('checkout.finish') }}?order_id=' + data
                                        .order_number;
                                },
                                onPending: (result) => {
                                    window.location.href = '{{ route('checkout.finish') }}?order_id=' + data
                                        .order_number;
                                },
                                onError: (result) => {
                                    this.errorMsg = 'Pembayaran gagal. Silakan coba lagi.';
                                    this.loading = false;
                                },
                                onClose: () => {
                                    this.loading = false;
                                    this.errorMsg =
                                        'Pembayaran dibatalkan. Silahkan cek pesanan saya untuk membayar/membatalkan';
                                },
                            });

                        } catch (err) {
                            this.errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                            this.loading = false;
                        }
                    },
                }
            }
        </script>
    @endpush

</x-user-layout>
