<x-app-layout>
    <x-slot name="title">Detail Payment</x-slot>

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

    <div class="mb-5">
        <a href="{{ route('admin.payments.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Payment
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Payment Detail --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Detail Pembayaran</p>
                        <p class="font-mono text-xs text-gray-400 mt-0.5">{{ $payment->payment_code }}</p>
                    </div>
                    @php
                        $cfg = [
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'rejected' => 'bg-red-50 text-red-600 border-red-200',
                            'expired' => 'bg-gray-100 text-gray-400 border-gray-200',
                        ];
                    @endphp
                    <span
                        class="text-sm font-medium px-3 py-1.5 rounded-xl border {{ $cfg[$payment->status] ?? '' }} capitalize">
                        {{ $payment->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Metode Pembayaran</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $payment->paymentMethod->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ $payment->paymentMethod->type ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Order Number</p>
                        <a href="{{ route('admin.orders.show', $payment->order) }}"
                            class="text-sm font-mono text-blue-600 hover:underline">
                            {{ $payment->order->order_number }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Pembayaran</p>
                        <p class="text-sm text-gray-700">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($payment->expired_at)
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Batas Waktu</p>
                            <p
                                class="text-sm {{ now()->gt($payment->expired_at) ? 'text-red-500' : 'text-gray-700' }}">
                                {{ $payment->expired_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    @endif
                    @if ($payment->verified_at)
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Diverifikasi Oleh</p>
                            <p class="text-sm text-gray-700">{{ $payment->verifiedBy->name ?? 'System / Midtrans' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Tanggal Verifikasi</p>
                            <p class="text-sm text-gray-700">{{ $payment->verified_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Amount --}}
                <div class="border-t border-gray-50 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Jumlah</span>
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($payment->fee > 0)
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Biaya Admin</span>
                            <span>Rp {{ number_format($payment->fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100">
                        <span>Total Dibayar</span>
                        <span>Rp {{ number_format($payment->total_paid, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($payment->notes)
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-400 mb-1">Catatan</p>
                        <p class="text-sm text-gray-700">{{ $payment->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Bukti Transfer --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Bukti Transfer</p>
                @if ($payment->proof_image)
                    <img src="{{ Storage::url($payment->proof_image) }}"
                        class="w-full max-w-md rounded-xl border border-gray-100 cursor-pointer hover:opacity-90 transition"
                        onclick="window.open('{{ Storage::url($payment->proof_image) }}', '_blank')"
                        alt="Bukti Transfer">
                    <p class="text-xs text-gray-400 mt-2">Klik gambar untuk membuka ukuran penuh</p>
                @else
                    <div
                        class="h-40 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center">
                        <i class="ti ti-photo-off text-3xl text-gray-200 mb-2"></i>
                        <p class="text-sm text-gray-400">Belum ada bukti transfer</p>
                    </div>
                @endif
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Item yang Dibeli</p>
                <div class="space-y-3">
                    @foreach ($payment->order->orderItems as $item)
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

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- Action Card --}}
            @if ($payment->status === 'pending')
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Tindakan</p>

                    {{-- Verify --}}
                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="mb-3">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" placeholder="Tambahkan catatan verifikasi..."
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none"></textarea>
                        </div>
                        <button type="submit" onclick="return confirm('Verifikasi pembayaran ini?')"
                            class="w-full h-10 bg-emerald-500 text-white text-sm rounded-xl hover:bg-emerald-600 transition font-medium flex items-center justify-center gap-2">
                            <i class="ti ti-circle-check text-base"></i> Verifikasi Pembayaran
                        </button>
                    </form>

                    <div class="relative flex items-center gap-3 my-4">
                        <div class="flex-1 h-px bg-gray-100"></div>
                        <span class="text-xs text-gray-400">atau</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>

                    {{-- Reject --}}
                    <form action="{{ route('admin.payments.reject', $payment) }}" method="POST"
                        x-data="{ open: false }">
                        @csrf @method('PATCH')
                        <button type="button" @click="open = !open"
                            class="w-full h-10 border border-red-200 text-red-500 text-sm rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
                            <i class="ti ti-circle-x text-base"></i> Tolak Pembayaran
                        </button>
                        <div x-show="open" x-transition class="mt-3 space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="notes" rows="3" required placeholder="Jelaskan alasan penolakan..."
                                    class="w-full px-3 py-2.5 border border-red-200 rounded-xl text-sm text-gray-700 outline-none focus:border-red-300 bg-white resize-none"></textarea>
                            </div>
                            <button type="submit" onclick="return confirm('Tolak pembayaran ini?')"
                                class="w-full h-10 bg-red-500 text-white text-sm rounded-xl hover:bg-red-600 transition font-medium">
                                Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- User Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">

                <p class="text-sm font-semibold text-gray-900 mb-4">
                    Informasi User
                </p>

                {{-- Header --}}
                <div class="flex items-center gap-3 mb-5">

                    @if ($payment->order->user->avatar)
                        <img src="{{ Storage::url($payment->order->user->avatar) }}"
                            alt="{{ $payment->order->user->name }}"
                            class="w-12 h-12 rounded-full object-cover border border-gray-100">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr($payment->order->user->name, 0, 2)) }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $payment->order->user->name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $payment->order->user->email }}
                        </p>
                    </div>

                    <div class="ml-auto">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                {{ $payment->order->user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                            {{ $payment->order->user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                </div>

                {{-- Detail --}}
                <div class="space-y-3 text-sm">

                    <div class="flex items-start gap-3">
                        <i class="ti ti-mail text-gray-300 text-base mt-0.5"></i>
                        <span class="text-gray-600">
                            {{ $payment->order->user->email }}
                        </span>
                    </div>

                    @if ($payment->order->user->phone)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-phone text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $payment->order->user->phone }}
                            </span>
                        </div>
                    @endif

                    @if ($payment->order->user->gender)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-user text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $payment->order->user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    @endif

                    @if ($payment->order->user->birth_date)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-calendar text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $payment->order->user->birth_date->format('d M Y') }}
                            </span>
                        </div>
                    @endif

                    @if ($payment->order->user->id_card_number)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-id text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 font-mono">
                                {{ $payment->order->user->id_card_number }}
                            </span>
                        </div>
                    @endif

                    @if ($payment->order->user->address)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-map-pin text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 leading-relaxed">
                                {{ $payment->order->user->address }}
                            </span>
                        </div>
                    @endif

                </div>

            </div>

            {{-- Bank Account Info --}}
            @if ($payment->paymentMethod && $payment->paymentMethod->bankAccounts->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Rekening Tujuan</p>
                    @foreach ($payment->paymentMethod->bankAccounts->where('is_active', true) as $bank)
                        <div class="p-3 bg-gray-50 rounded-xl mb-2 last:mb-0">
                            <p class="text-sm font-semibold text-gray-900">{{ $bank->bank_name }}</p>
                            <p class="text-sm font-mono text-gray-700 mt-1">{{ $bank->account_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">a/n {{ $bank->account_name }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Meta --}}
            <div class="bg-gray-50 rounded-2xl p-5 space-y-2 text-xs text-gray-400">
                <div class="flex justify-between">
                    <span>Dibuat</span>
                    <span>{{ $payment->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if ($payment->expired_at)
                    <div class="flex justify-between">
                        <span>Expired</span>
                        <span>{{ $payment->expired_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>

        </div>

    </div>

</x-app-layout>
