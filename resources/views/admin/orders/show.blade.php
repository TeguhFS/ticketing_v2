<x-app-layout>
    <x-slot name="title">Detail Order</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Orders
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left: Order Detail --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Order Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Order Detail</p>
                        <p class="font-mono text-xs text-gray-400 mt-0.5">{{ $order->order_number }}</p>
                    </div>
                    @php
                        $cfg = [
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                            'expired' => 'bg-gray-100 text-gray-500 border-gray-200',
                            'refunded' => 'bg-blue-50 text-blue-600 border-blue-200',
                        ];
                    @endphp
                    <span
                        class="text-sm font-medium px-3 py-1.5 rounded-xl border {{ $cfg[$order->status] ?? '' }} capitalize">
                        {{ $order->status }}
                    </span>
                </div>

                @if ($order->status === 'cancelled' && $order->notes)
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-3">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-red-100 text-red-600
                flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-alert-circle text-lg"></i>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-700">
                                    Alasan Pembatalan
                                </p>

                                <p class="text-sm text-red-600 mt-2 leading-relaxed">
                                    {{ $order->notes }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Items --}}
                <div class="space-y-3 mb-5">
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $item->ticketType->event->title ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $item->ticketType->name }} &times; {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">
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
                    @if ($order->tax > 0)
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Pajak</span>
                            <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div
                        class="flex justify-between text-base font-semibold text-gray-900 pt-2 border-t border-gray-100">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Tickets --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Tiket yang Diterbitkan</p>
                <div class="space-y-3">
                    @forelse($order->orderItems->flatMap->tickets as $ticket)
                        <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->holder_name }}</p>
                                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $ticket->ticket_code }}</p>
                            </div>
                            @php
                                $tCfg = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'used' => 'bg-gray-100 text-gray-500 border-gray-200',
                                    'cancelled' => 'bg-red-50 text-red-500 border-red-200',
                                    'expired' => 'bg-gray-100 text-gray-400 border-gray-200',
                                ];
                            @endphp
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $tCfg[$ticket->status] ?? '' }} capitalize">
                                {{ $ticket->status }}
                            </span>
                            <a href="{{ route('admin.tickets.show', $ticket) }}"
                                class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition">
                                <i class="ti ti-arrow-right text-base"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-6">Belum ada tiket diterbitkan</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right: Sidebar Info --}}
        <div class="space-y-5">

            {{-- User Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">

                <p class="text-sm font-semibold text-gray-900 mb-4">
                    Informasi Pemesan
                </p>

                {{-- Header --}}
                <div class="flex items-center gap-3 mb-5">

                    @if ($order->user->avatar)
                        <img src="{{ Storage::url($order->user->avatar) }}" alt="{{ $order->user->name }}"
                            class="w-12 h-12 rounded-full object-cover border border-gray-100">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $order->user->name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $order->user->email }}
                        </p>
                    </div>

                    <div class="ml-auto">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                {{ $order->user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                            {{ $order->user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                </div>

                {{-- Detail --}}
                <div class="space-y-3 text-sm">

                    <div class="flex items-start gap-3">
                        <i class="ti ti-mail text-gray-300 text-base mt-0.5"></i>
                        <span class="text-gray-600">
                            {{ $order->user->email }}
                        </span>
                    </div>

                    @if ($order->user->phone)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-phone text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $order->user->phone }}
                            </span>
                        </div>
                    @endif

                    @if ($order->user->gender)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-user text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $order->user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    @endif

                    @if ($order->user->birth_date)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-calendar text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $order->user->birth_date->format('d M Y') }}
                            </span>
                        </div>
                    @endif

                    @if ($order->user->id_card_number)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-id text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 font-mono">
                                {{ $order->user->id_card_number }}
                            </span>
                        </div>
                    @endif

                    @if ($order->user->address)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-map-pin text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 leading-relaxed">
                                {{ $order->user->address }}
                            </span>
                        </div>
                    @endif

                </div>

            </div>

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Informasi Pembayaran</p>
                @if ($order->payment)
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Metode</span>
                            <span
                                class="text-gray-700 font-medium">{{ $order->payment->paymentMethod->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kode Bayar</span>
                            <span class="font-mono text-gray-700">{{ $order->payment->payment_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jumlah</span>
                            <span class="text-gray-700 font-semibold">Rp
                                {{ number_format($order->payment->total_paid, 0, ',', '.') }}</span>
                        </div>
                        @php
                            $pCfg = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                'expired' => 'bg-gray-100 text-gray-400 border-gray-200',
                            ];
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Status</span>
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $pCfg[$order->payment->status] ?? '' }} capitalize">
                                {{ $order->payment->status }}
                            </span>
                        </div>
                        @if ($order->payment->proof_image)
                            <div class="pt-2">
                                <p class="text-xs text-gray-400 mb-2">Bukti Transfer</p>
                                <img src="{{ Storage::url($order->payment->proof_image) }}"
                                    class="w-full rounded-xl border border-gray-100 object-cover" alt="Bukti Transfer">
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400">Belum ada data pembayaran</p>
                @endif
            </div>

            {{-- Update Status --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Update Status Order</p>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status"
                        class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none bg-white">
                        @foreach (['pending', 'paid', 'cancelled', 'refunded', 'expired'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}
                                class="capitalize">
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full h-10 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Order Meta --}}
            <div class="bg-gray-50 rounded-2xl p-5 space-y-2 text-xs text-gray-400">
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
                        <span>{{ $order->expired_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>
