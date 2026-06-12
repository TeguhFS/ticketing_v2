<x-auth-layout>
    <x-slot name="title">Tiket — {{ $ticket->ticket_code }}</x-slot>

    <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('user.tickets.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700
            transition-colors duration-200 mb-6">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Tiket
        </a>

        {{-- E-Ticket Card --}}
        <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-lg shadow-gray-100/50 mb-5">

            {{-- Header --}}
            <div class="bg-gray-950 p-6 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/3 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">
                            E-TICKET
                        </p>
                        @php
                            $tBadge = [
                                'active' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'used' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                'expired' => 'bg-gray-600/20 text-gray-500 border-gray-600/30',
                            ];
                        @endphp
                        <span
                            class="text-xs font-semibold px-3 py-1 rounded-full border
                        {{ $tBadge[$ticket->status] ?? '' }} capitalize">
                            {{ $ticket->status }}
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-white leading-tight mb-2">
                        {{ $ticket->ticketType->event->title }}
                    </h2>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="text-sm font-semibold text-white mt-0.5">
                                {{ $ticket->ticketType->event->start_date->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Waktu</p>
                            <p class="text-sm font-semibold text-white mt-0.5">
                                {{ $ticket->ticketType->event->start_date->format('H:i') }} WIB
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500">Lokasi</p>
                            <p class="text-sm font-semibold text-white mt-0.5">
                                {{ $ticket->ticketType->event->location }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perforated edge --}}
            <div class="relative h-4 bg-gray-950">
                <div class="absolute inset-0 flex items-center">
                    <div class="flex-1 border-t-2 border-dashed border-white/10"></div>
                </div>
                <div class="absolute -left-3 top-0 w-6 h-6 bg-gray-50 rounded-full border border-gray-100"></div>
                <div class="absolute -right-3 top-0 w-6 h-6 bg-gray-50 rounded-full border border-gray-100"></div>
            </div>

            {{-- Ticket Body --}}
            <div class="p-6">

                {{-- Holder Info --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs text-gray-400">Pemegang Tiket</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $ticket->holder_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Tipe Tiket</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $ticket->ticketType->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="text-xs text-gray-700 mt-0.5 truncate">{{ $ticket->holder_email }}</p>
                    </div>
                    @if ($ticket->holder_phone)
                        <div>
                            <p class="text-xs text-gray-400">No. HP</p>
                            <p class="text-xs text-gray-700 mt-0.5">{{ $ticket->holder_phone }}</p>
                        </div>
                    @endif
                </div>

                {{-- QR Code --}}
                <div class="flex flex-col items-center py-5 px-4 bg-gray-50 rounded-2xl border border-gray-100">
                    @if ($ticket->qr_code)
                        <img src="{{ Storage::url($ticket->qr_code) }}"
                            class="w-52 h-52 object-contain mb-3
                        {{ $ticket->status === 'used' ? 'opacity-40 grayscale' : '' }}"
                            alt="QR Code">
                    @else
                        <div
                            class="w-52 h-52 bg-white border-2 border-dashed border-gray-200 rounded-xl
                    flex flex-col items-center justify-center mb-3">
                            <i class="ti ti-qrcode text-5xl text-gray-300 block mb-2"></i>
                            <p class="text-xs text-gray-400 text-center px-4">
                                QR Code akan tersedia setelah pembayaran terverifikasi
                            </p>
                        </div>
                    @endif

                    {{-- Ticket Code --}}
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Kode Tiket</p>
                        <p
                            class="text-lg font-black font-mono tracking-widest text-gray-900 bg-white
                        border border-gray-200 px-5 py-2.5 rounded-xl">
                            {{ $ticket->ticket_code }}
                        </p>
                    </div>

                    @if ($ticket->status === 'used')
                        <div class="mt-3 flex items-center gap-2 text-sm text-gray-400">
                            <i class="ti ti-circle-check text-base text-emerald-500"></i>
                            Tiket telah digunakan
                        </div>
                    @endif
                </div>

                {{-- Warning for active --}}
                @if ($ticket->status === 'active')
                    <div
                        class="mt-4 p-3 bg-amber-50 border border-amber-100 rounded-xl
                flex items-start gap-2">
                        <i class="ti ti-alert-triangle text-amber-500 text-sm flex-shrink-0 mt-0.5"></i>
                        <p class="text-xs text-amber-700">
                            Tunjukkan QR code ini kepada petugas saat masuk ke venue.
                            Jangan bagikan QR code kepada orang lain.
                        </p>
                    </div>
                @endif

            </div>
        </div>

        {{-- Order Info --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4">
            <p class="text-sm font-bold text-gray-900 mb-4">Informasi Pesanan</p>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Order Number</span>
                    <a href="{{ route('user.orders.show', $ticket->orderItem->order) }}"
                        class="font-mono text-xs text-blue-600 hover:underline">
                        {{ $ticket->orderItem->order->order_number }}
                    </a>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Harga Tiket</span>
                    <span class="font-semibold text-gray-900">
                        Rp {{ number_format($ticket->ticketType->price, 0, ',', '.') }}
                    </span>
                </div>
                @if ($ticket->orderItem->order->payment)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Metode Bayar</span>
                        <span class="text-gray-700">
                            {{ $ticket->orderItem->order->payment->paymentMethod->name ?? 'Midtrans' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Validation History --}}
        @if ($ticket->validations->count() > 0)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4">
                <p class="text-sm font-bold text-gray-900 mb-4">Riwayat Validasi</p>
                <div class="space-y-3">
                    @foreach ($ticket->validations as $validation)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $validation->status === 'valid' ? 'bg-emerald-100' : 'bg-red-100' }}">
                                <i
                                    class="ti text-sm
                        {{ $validation->status === 'valid' ? 'ti-check text-emerald-600' : 'ti-x text-red-500' }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 capitalize">
                                    {{ str_replace('_', ' ', $validation->status) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $validation->validated_at?->format('d M Y, H:i') }}
                                    @if ($validation->officer)
                                        — oleh {{ $validation->officer->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('events.show', $ticket->ticketType->event->slug) }}"
                class="flex-1 h-11 border border-gray-200 text-gray-700 text-sm font-medium
                rounded-xl hover:bg-gray-50 transition-all duration-200
                flex items-center justify-center gap-2">
                <i class="ti ti-calendar-event text-base"></i> Detail Event
            </a>
            @if (setting('social_whatsapp'))
                <a href="https://wa.me/{{ setting('social_whatsapp') }}?text={{ urlencode('Halo, saya butuh bantuan untuk tiket ' . $ticket->ticket_code) }}"
                    target="_blank"
                    class="flex-1 h-11 border border-gray-200 text-gray-700 text-sm font-medium
                rounded-xl hover:bg-gray-50 transition-all duration-200
                flex items-center justify-center gap-2">
                    <i class="ti ti-brand-whatsapp text-base text-emerald-500"></i> Bantuan
                </a>
            @endif
        </div>
    </div>
</x-auth-layout>
