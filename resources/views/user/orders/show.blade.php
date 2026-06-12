<x-auth-layout>
    <x-slot name="title">Detail Pesanan — {{ setting('app_name') }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back --}}
        <a href="{{ route('user.orders.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700
            transition-colors duration-200 mb-6">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Pesanan
        </a>

        @if (session('success'))
            <div
                class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200
        text-emerald-700 text-sm px-4 py-3 rounded-xl">
                <i class="ti ti-circle-check text-base flex-shrink-0"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200
        text-red-600 text-sm px-4 py-3 rounded-xl">
                <i class="ti ti-circle-x text-base flex-shrink-0"></i> {{ session('error') }}
            </div>
        @endif

        {{-- ── PENDING: Payment + Countdown + Cancel ─────── --}}
        @if ($order->status === 'pending')
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden mb-5 shadow-sm"
                x-data="{ cancelOpen: false }">

                {{-- Payment Header --}}
                <div class="bg-amber-500 px-5 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="ti ti-clock text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">Menunggu Pembayaran</p>
                                <p class="text-xs text-amber-100 mt-0.5">
                                    Segera selesaikan pembayaran sebelum waktu habis
                                </p>
                            </div>
                        </div>
                        {{-- Countdown --}}
                        @if ($order->expired_at && now()->lt($order->expired_at))
                            <div class="hidden sm:flex items-center gap-1.5" x-data="countdown('{{ $order->expired_at->toISOString() }}')"
                                x-init="start()">
                                <template x-for="(unit, i) in timeUnits" :key="i">
                                    <div class="text-center" x-show="!expired">
                                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-black text-sm font-mono"
                                                x-text="unit.value"></span>
                                        </div>
                                        <p class="text-[9px] text-amber-100 mt-1 font-medium" x-text="unit.label"></p>
                                    </div>
                                </template>
                                <div x-show="expired" class="text-white text-xs font-semibold">
                                    Waktu habis!
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Progress bar countdown --}}
                @if ($order->expired_at && now()->lt($order->expired_at))
                    <div class="h-1.5 bg-amber-100 overflow-hidden" x-data="countdown('{{ $order->expired_at->toISOString() }}')" x-init="start()">
                        <div class="h-full bg-amber-500 transition-all duration-1000"
                            :style="'width: ' + progress + '%'"></div>
                    </div>
                @endif

                {{-- Payment Body --}}
                <div class="p-5">

                    {{-- Amount --}}
                    <div
                        class="flex items-center justify-between mb-5 p-4
                bg-gray-50 border border-gray-100 rounded-xl">
                        <div>
                            <p class="text-xs text-gray-400">Total Pembayaran</p>
                            <p class="text-2xl font-black text-gray-900 mt-0.5">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1 font-mono">
                                {{ $order->order_number }}
                            </p>
                        </div>
                        <div class="text-right">
                            @if ($order->expired_at && now()->lt($order->expired_at))
                                <p class="text-xs text-gray-400">Berlaku hingga</p>
                                <p class="text-sm font-semibold text-red-500 mt-0.5">
                                    {{ $order->expired_at->format('d M Y, H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Pay Button --}}
                    @if ($snapToken)
                        <div x-data="paymentHandler('{{ $snapToken }}', '{{ $order->order_number }}')" class="space-y-3">

                            {{-- Main Pay Button --}}
                            <button @click="pay()" :disabled="loading"
                                class="w-full h-14 bg-gray-900 text-white font-bold text-base rounded-2xl
                        hover:bg-gray-700 transition-all duration-200
                        disabled:opacity-50 disabled:cursor-not-allowed
                        flex items-center justify-center gap-3 shadow-lg shadow-gray-200">
                                <span x-show="!loading" class="flex items-center gap-3">
                                    <i class="ti ti-credit-card text-xl"></i>
                                    Bayar Sekarang
                                </span>
                                <span x-show="loading" class="flex items-center gap-3">
                                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Membuka halaman pembayaran...
                                </span>
                            </button>

                            {{-- Error message --}}
                            <div x-show="errorMsg" x-transition class="p-3 bg-red-50 border border-red-200 rounded-xl">
                                <p class="text-xs text-red-600 flex items-center gap-2">
                                    <i class="ti ti-alert-circle text-sm flex-shrink-0"></i>
                                    <span x-text="errorMsg"></span>
                                </p>
                            </div>

                            {{-- Payment methods info --}}
                            <div class="flex items-center justify-center gap-3 flex-wrap py-2">
                                @foreach ([['icon' => 'ti-building-bank', 'label' => 'Transfer Bank'], ['icon' => 'ti-credit-card', 'label' => 'Kartu Kredit'], ['icon' => 'ti-device-mobile', 'label' => 'E-Wallet'], ['icon' => 'ti-qrcode', 'label' => 'QRIS']] as $method)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                        <i class="ti {{ $method['icon'] }} text-gray-300 text-sm"></i>
                                        {{ $method['label'] }}
                                    </div>
                                @endforeach
                            </div>

                            {{-- Security badges --}}
                            <div
                                class="flex items-center justify-between p-3
                    bg-gray-50 border border-gray-100 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Midtrans_logo_2020.svg/320px-Midtrans_logo_2020.svg.png"
                                        class="h-4 object-contain opacity-60" alt="Midtrans">
                                </div>
                                <div class="flex items-center gap-3">
                                    @foreach ([['icon' => 'ti-shield-check', 'text' => 'SSL Secure'], ['icon' => 'ti-lock', 'text' => 'Encrypted']] as $badge)
                                        <div class="flex items-center gap-1 text-xs text-gray-400">
                                            <i class="ti {{ $badge['icon'] }} text-emerald-400 text-sm"></i>
                                            {{ $badge['text'] }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @else
                        {{-- Snap token error fallback --}}
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mb-3">
                            <p class="text-sm text-amber-700 flex items-center gap-2">
                                <i class="ti ti-alert-triangle text-base flex-shrink-0"></i>
                                Terjadi kesalahan memuat halaman pembayaran.
                                Silakan refresh halaman atau coba beberapa saat lagi.
                            </p>
                        </div>
                        <button onclick="window.location.reload()"
                            class="w-full h-11 border border-gray-200 text-gray-600 text-sm font-medium
                    rounded-xl hover:bg-gray-50 transition-all duration-200
                    flex items-center justify-center gap-2">
                            <i class="ti ti-refresh text-base"></i>
                            Refresh Halaman
                        </button>
                    @endif

                </div>

                {{-- Cancel Order --}}
                <div class="border-t border-gray-100 px-5 py-4">
                    <button type="button" @click="cancelOpen = !cancelOpen"
                        class="w-full flex items-center justify-center gap-2 text-xs text-gray-400
                    hover:text-red-500 transition-colors duration-200 py-1">
                        <i class="ti ti-x text-sm"></i>
                        <span x-text="cancelOpen ? 'Tutup' : 'Batalkan Order Ini'"></span>
                    </button>

                    {{-- Cancel Form --}}
                    <div x-show="cancelOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-4 space-y-3">

                        <div
                            class="p-3 bg-amber-50 border border-amber-200 rounded-xl
                    flex items-start gap-2">
                            <i class="ti ti-alert-triangle text-amber-500 text-sm flex-shrink-0 mt-0.5"></i>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                Order yang dibatalkan tidak dapat dipulihkan.
                                Jika sudah transfer, hubungi admin untuk pengembalian dana.
                            </p>
                        </div>

                        <form action="{{ route('user.orders.cancel', $order) }}" method="POST" class="space-y-3">
                            @csrf @method('PATCH')

                            {{-- Quick reasons --}}
                            <div class="flex flex-wrap gap-2" x-data="{ sel: '' }">
                                @foreach (['Salah pilih tiket', 'Ada keperluan mendadak', 'Ingin ganti event', 'Lainnya'] as $reason)
                                    <button type="button"
                                        @click="sel = '{{ $reason }}';
                                document.getElementById('cancel_reason_show').value = '{{ $reason }}';"
                                        :class="sel === '{{ $reason }}'
                                            ?
                                            'border-red-400 bg-red-50 text-red-600' :
                                            'border-gray-200 text-gray-500 hover:border-gray-300'"
                                        class="px-3 py-1.5 text-xs border rounded-xl
                                transition-all duration-150">
                                        {{ $reason }}
                                    </button>
                                @endforeach
                            </div>

                            <textarea id="cancel_reason_show" name="cancel_reason" rows="2"
                                placeholder="Tuliskan alasan pembatalan... (min. 10 karakter)"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm
                            text-gray-700 outline-none bg-white resize-none
                            focus:border-red-300 transition-all duration-200">{{ old('cancel_reason') }}</textarea>
                            @error('cancel_reason')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <i class="ti ti-alert-circle text-xs"></i>
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="flex gap-2">
                                <button type="button" @click="cancelOpen = false"
                                    class="flex-1 h-10 border border-gray-200 text-gray-600 text-sm
                                font-medium rounded-xl hover:bg-gray-50
                                transition-all duration-200">
                                    Kembali
                                </button>
                                <button type="submit" onclick="return confirm('Yakin ingin membatalkan order ini?')"
                                    class="flex-1 h-10 bg-red-500 text-white text-sm font-semibold
                                rounded-xl hover:bg-red-600 transition-all duration-200
                                flex items-center justify-center gap-1.5">
                                    <i class="ti ti-x text-base"></i>
                                    Ya, Batalkan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        @endif

        {{-- ── EXPIRED ──────────────────────────────────────── --}}
        @if ($order->status === 'expired')
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-xl flex items-center justify-center">
                        <i class="ti ti-clock-off text-gray-500 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700">Order Telah Expired</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Melewati batas waktu pembayaran pada
                            {{ $order->updated_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('events.index') }}"
                    class="mt-3 inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white
                text-xs font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                    <i class="ti ti-calendar-event text-sm"></i>
                    Beli Tiket Lagi
                </a>
            </div>
        @endif

        {{-- ── CANCELLED ────────────────────────────────────── --}}
        @if ($order->status === 'cancelled')
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-xl flex items-center justify-center">
                        <i class="ti ti-x text-gray-500 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700">Order Dibatalkan</p>
                        @if ($order->notes)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->notes }}</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('events.index') }}"
                    class="mt-3 inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white
                text-xs font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                    <i class="ti ti-calendar-event text-sm"></i>
                    Beli Tiket Lagi
                </a>
            </div>
        @endif

        {{-- ── REFUND BUTTON (paid orders) ─────────────────── --}}
        @if ($order->status === 'paid' && !$order->hasRefundRequest())
            @php
                $eventStarted = $order->orderItems->every(fn($item) => $item->ticketType->event->start_date->lt(now()));
            @endphp
            @if (!$eventStarted)
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Ingin membatalkan?</p>
                            <p class="text-xs text-gray-400 mt-0.5">Ajukan refund sebelum event dimulai</p>
                        </div>
                        <a href="{{ route('user.refunds.create', $order) }}"
                            class="flex-shrink-0 inline-flex items-center gap-2 h-9 px-4 border
                    border-gray-200 text-gray-600 text-sm font-medium rounded-xl
                    hover:bg-white hover:border-gray-300 transition-all duration-200">
                            <i class="ti ti-refresh text-base"></i>
                            Ajukan Refund
                        </a>
                    </div>
                </div>
            @endif
        @elseif($order->hasRefundRequest())
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-5">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <i class="ti ti-refresh text-blue-500 text-lg flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Refund Diajukan</p>
                            <p class="text-xs text-blue-600 mt-0.5 capitalize">
                                Status: {{ $order->refund->status }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('user.refunds.show', $order->refund) }}"
                        class="text-xs font-medium text-blue-700 hover:text-blue-900
                    transition flex items-center gap-1">
                        Detail <i class="ti ti-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        @endif

        {{-- ── Order Content ────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Left --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Order Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-sm font-bold text-gray-900">Detail Pesanan</p>
                            <p class="text-xs font-mono text-gray-400 mt-0.5">
                                {{ $order->order_number }}
                            </p>
                        </div>
                        @php
                            $badge = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                                'expired' => 'bg-gray-100 text-gray-400 border-gray-200',
                                'refunded' => 'bg-blue-50 text-blue-600 border-blue-200',
                            ];
                        @endphp
                        <span
                            class="text-sm font-semibold px-3 py-1.5 rounded-xl border
                        {{ $badge[$order->status] ?? '' }} capitalize">
                            {{ $order->status }}
                        </span>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-3 mb-5">
                        @foreach ($order->orderItems as $item)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-14 h-14 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                                    @if ($item->ticketType->event->thumbnail)
                                        <img src="{{ Storage::url($item->ticketType->event->thumbnail) }}"
                                            class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="ti ti-calendar-event text-xl text-gray-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">
                                        {{ $item->ticketType->event->title }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $item->ticketType->name }} × {{ $item->quantity }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1.5">
                                        <i class="ti ti-calendar text-gray-300"></i>
                                        {{ $item->ticketType->event->start_date->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <p class="text-sm font-bold text-gray-900 flex-shrink-0">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if ($order->discount > 0)
                            <div class="flex justify-between text-sm text-emerald-600">
                                <span>Diskon</span>
                                <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div
                            class="flex justify-between text-base font-bold text-gray-900
                        pt-2 border-t border-gray-100">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Tickets (only if paid) --}}
                @if ($order->status === 'paid' && $order->orderItems->flatMap->tickets->count() > 0)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-bold text-gray-900">Tiket Saya</p>
                            <a href="{{ route('user.tickets.index') }}"
                                class="text-xs text-gray-400 hover:text-gray-700 transition">
                                Lihat semua →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($order->orderItems->flatMap->tickets as $ticket)
                                <a href="{{ route('user.tickets.show', $ticket) }}"
                                    class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl
                            hover:border-gray-300 hover:shadow-sm transition-all duration-200 group">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-xl flex items-center
                            justify-center flex-shrink-0">
                                        <i class="ti ti-qrcode text-gray-500 text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-mono font-bold text-gray-700">
                                            {{ $ticket->ticket_code }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $ticket->ticketType->name }}
                                        </p>
                                    </div>
                                    @php
                                        $tBadge = [
                                            'active' => 'bg-emerald-50 text-emerald-700',
                                            'used' => 'bg-gray-100 text-gray-500',
                                            'cancelled' => 'bg-red-50 text-red-500',
                                            'expired' => 'bg-gray-100 text-gray-400',
                                        ];
                                    @endphp
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-lg flex-shrink-0
                            {{ $tBadge[$ticket->status] ?? '' }} capitalize">
                                        {{ $ticket->status }}
                                    </span>
                                    <i
                                        class="ti ti-arrow-right text-gray-300 text-sm flex-shrink-0
                            group-hover:text-gray-500 group-hover:translate-x-0.5
                            transition-all duration-200"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right --}}
            <div class="space-y-4">

                {{-- Payment Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Informasi Pembayaran</p>
                    @if ($order->payment)
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Metode</span>
                                <span class="font-medium text-gray-700">
                                    {{ $order->payment->paymentMethod->name ?? 'Midtrans' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Kode</span>
                                <span class="font-mono text-xs text-gray-600">
                                    {{ $order->payment->payment_code }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Status</span>
                                @php
                                    $pBadge = [
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'verified' => 'bg-emerald-50 text-emerald-700',
                                        'rejected' => 'bg-red-50 text-red-600',
                                        'expired' => 'bg-gray-100 text-gray-500',
                                    ];
                                @endphp
                                <span
                                    class="text-xs font-medium px-2 py-0.5 rounded-lg
                            {{ $pBadge[$order->payment->status] ?? '' }} capitalize">
                                    {{ $order->payment->status }}
                                </span>
                            </div>
                            @if ($order->payment->verified_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Verified</span>
                                    <span class="text-gray-600 text-xs">
                                        {{ $order->payment->verified_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-credit-card-off text-3xl text-gray-200 block mb-2"></i>
                            <p class="text-xs text-gray-400">Belum ada data pembayaran</p>
                        </div>
                    @endif
                </div>

                {{-- Meta --}}
                <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-xs text-gray-400">
                    <div class="flex justify-between">
                        <span>Dibuat</span>
                        <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diperbarui</span>
                        <span>{{ $order->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if ($order->expired_at)
                        <div class="flex justify-between">
                            <span>Expired</span>
                            <span class="{{ now()->gt($order->expired_at) ? 'text-red-400' : '' }}">
                                {{ $order->expired_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Help --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-blue-700 mb-2">
                        <i class="ti ti-help-circle text-sm mr-1"></i> Butuh bantuan?
                    </p>
                    <p class="text-xs text-blue-600 mb-3">
                        Hubungi kami jika ada masalah dengan pesanan.
                    </p>
                    @if (setting('social_whatsapp'))
                        <a href="https://wa.me/{{ setting('social_whatsapp') }}?text={{ urlencode('Halo, saya butuh bantuan untuk order ' . $order->order_number) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 text-xs font-medium
                        text-blue-700 hover:text-blue-900 transition">
                            <i class="ti ti-brand-whatsapp text-sm text-emerald-500"></i>
                            Chat WhatsApp
                        </a>
                    @endif
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        {{-- Midtrans Snap --}}
        @if ($order->status === 'pending' && $snapToken)
            <script
                src="{{ config('services.midtrans.is_production')
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        @endif

        <script>
            {{-- Payment handler --}}

            function paymentHandler(snapToken, orderNumber) {
                return {
                    loading: false,
                    errorMsg: '',

                    pay() {
                        if (!snapToken) {
                            this.errorMsg = 'Token pembayaran tidak valid. Silakan refresh halaman.';
                            return;
                        }

                        this.loading = true;
                        this.errorMsg = '';

                        window.snap.pay(snapToken, {
                            onSuccess: (result) => {
                                window.location.href = '{{ route('checkout.finish') }}?order_id=' + orderNumber;
                            },
                            onPending: (result) => {
                                window.location.href = '{{ route('checkout.finish') }}?order_id=' + orderNumber;
                            },
                            onError: (result) => {
                                this.errorMsg = 'Pembayaran gagal. Silakan coba lagi.';
                                this.loading = false;
                            },
                            onClose: () => {
                                this.loading = false;
                                this.errorMsg = '';
                            },
                        });
                    }
                }
            }

            {{-- Countdown timer --}}

            function countdown(expiresAt) {
                return {
                    expired: false,
                    progress: 100,
                    timeUnits: [{
                            label: 'JAM',
                            value: '00'
                        },
                        {
                            label: 'MNT',
                            value: '00'
                        },
                        {
                            label: 'DTK',
                            value: '00'
                        },
                    ],

                    start() {
                        const endTime = new Date(expiresAt).getTime();
                        const totalDuration = 24 * 60 * 60 * 1000;

                        const tick = () => {
                            const now = new Date().getTime();
                            const diff = endTime - now;

                            if (diff <= 0) {
                                this.expired = true;
                                this.progress = 0;
                                setTimeout(() => window.location.reload(), 2000);
                                return;
                            }

                            const hours = Math.floor(diff / (1000 * 60 * 60));
                            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                            this.timeUnits[0].value = String(hours).padStart(2, '0');
                            this.timeUnits[1].value = String(minutes).padStart(2, '0');
                            this.timeUnits[2].value = String(seconds).padStart(2, '0');
                            this.progress = Math.max(0, (diff / totalDuration) * 100);

                            setTimeout(tick, 1000);
                        };

                        tick();
                    }
                }
            }
        </script>
    @endpush

</x-auth-layout>
