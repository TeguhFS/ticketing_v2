<x-app-layout>
    <x-slot name="title">Detail Transaksi</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.transactions.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Transaksi
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Detail Pembayaran</p>
                        <p class="font-mono text-xs text-gray-400 mt-0.5">{{ $transaction->payment_code }}</p>
                    </div>
                    <span
                        class="text-xs font-medium px-3 py-1.5 rounded-xl border bg-emerald-50 text-emerald-700 border-emerald-200">
                        <i class="ti ti-circle-check text-sm mr-1"></i> Verified
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Metode Pembayaran</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->paymentMethod->name ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Order Number</p>
                        <a href="{{ route('admin.orders.show', $transaction->order) }}"
                            class="text-sm font-mono text-blue-600 hover:underline">
                            {{ $transaction->order->order_number }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Pembayaran</p>
                        <p class="text-sm text-gray-700">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Verifikasi</p>
                        <p class="text-sm text-gray-700">{{ $transaction->verified_at?->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Diverifikasi Oleh</p>
                        <p class="text-sm text-gray-700">{{ $transaction->verifiedBy->name ?? 'System / Midtrans' }}</p>
                    </div>
                </div>

                {{-- Amount Breakdown --}}
                <div class="border-t border-gray-50 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Jumlah</span>
                        <span>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($transaction->fee > 0)
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Biaya Admin</span>
                            <span>Rp {{ number_format($transaction->fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100">
                        <span>Total Dibayar</span>
                        <span>Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Item Order</p>
                <div class="space-y-3">
                    @foreach ($transaction->order->orderItems as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $item->ticketType->event->title ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $item->ticketType->name ?? '-' }} &times; {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bukti Transfer --}}
            @if ($transaction->proof_image)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Bukti Transfer</p>
                    <img src="{{ Storage::url($transaction->proof_image) }}"
                        class="w-full max-w-sm rounded-xl border border-gray-100" alt="Bukti Transfer">
                </div>
            @endif

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- User Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">

                <p class="text-sm font-semibold text-gray-900 mb-4">
                    Informasi Pembayar
                </p>

                {{-- Header --}}
                <div class="flex items-center gap-3 mb-5">

                    @if ($transaction->order->user->avatar)
                        <img src="{{ Storage::url($transaction->order->user->avatar) }}"
                            alt="{{ $transaction->order->user->name }}"
                            class="w-12 h-12 rounded-full object-cover border border-gray-100">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr($transaction->order->user->name, 0, 2)) }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $transaction->order->user->name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $transaction->order->user->email }}
                        </p>
                    </div>

                    <div class="ml-auto">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                {{ $transaction->order->user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                            {{ $transaction->order->user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                </div>

                {{-- Detail --}}
                <div class="space-y-3 text-sm">

                    <div class="flex items-start gap-3">
                        <i class="ti ti-mail text-gray-300 text-base mt-0.5"></i>
                        <span class="text-gray-600">
                            {{ $transaction->order->user->email }}
                        </span>
                    </div>

                    @if ($transaction->order->user->phone)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-phone text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $transaction->order->user->phone }}
                            </span>
                        </div>
                    @endif

                    @if ($transaction->order->user->gender)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-user text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $transaction->order->user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    @endif

                    @if ($transaction->order->user->birth_date)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-calendar text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $transaction->order->user->birth_date->format('d M Y') }}
                            </span>
                        </div>
                    @endif

                    @if ($transaction->order->user->id_card_number)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-id text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 font-mono">
                                {{ $transaction->order->user->id_card_number }}
                            </span>
                        </div>
                    @endif

                    @if ($transaction->order->user->address)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-map-pin text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 leading-relaxed">
                                {{ $transaction->order->user->address }}
                            </span>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Tiket --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Tiket</p>
                @foreach ($transaction->order->orderItems as $item)
                    @foreach ($item->tickets as $ticket)
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-xs font-mono text-gray-600">{{ $ticket->ticket_code }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $ticket->holder_name }}</p>
                            </div>
                            @php
                                $tCfg = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'used' => 'bg-gray-100 text-gray-500 border-gray-200',
                                    'cancelled' => 'bg-red-50 text-red-500 border-red-200',
                                ];
                            @endphp
                            <span
                                class="text-xs font-medium px-2 py-0.5 rounded-lg border {{ $tCfg[$ticket->status] ?? '' }} capitalize">
                                {{ $ticket->status }}
                            </span>
                        </div>
                    @endforeach
                @endforeach
            </div>

            {{-- Meta --}}
            <div class="bg-gray-50 rounded-2xl p-5 space-y-2 text-xs text-gray-400">
                <div class="flex justify-between">
                    <span>Dibuat</span>
                    <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Diverifikasi</span>
                    <span>{{ $transaction->verified_at?->format('d M Y, H:i') ?? '-' }}</span>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
