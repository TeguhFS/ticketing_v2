<x-app-layout>
    <x-slot name="title">Detail Tiket</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.tickets.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Tickets
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Ticket Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-900 p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">E-Ticket</p>
                        @php
                            $tCfg = [
                                'active' => 'bg-emerald-500',
                                'used' => 'bg-gray-500',
                                'cancelled' => 'bg-red-500',
                                'expired' => 'bg-gray-600',
                            ];
                        @endphp
                        <span
                            class="text-xs font-semibold px-3 py-1 rounded-full {{ $tCfg[$ticket->status] ?? 'bg-gray-500' }} capitalize">
                            {{ $ticket->status }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold mb-1">{{ $ticket->ticketType->event->title ?? '-' }}</h2>
                    <p class="text-gray-400 text-sm">
                        <i class="ti ti-map-pin text-sm mr-1"></i>
                        {{ $ticket->ticketType->event->location ?? '-' }}
                    </p>
                    @if ($ticket->ticketType->event)
                        <p class="text-gray-400 text-sm mt-1">
                            <i class="ti ti-calendar text-sm mr-1"></i>
                            {{ $ticket->ticketType->event->start_date->format('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
                <div class="p-6 border-t-2 border-dashed border-gray-100">
                    <div class="grid grid-cols-2 gap-5 mb-5">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Pemegang Tiket</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $ticket->holder_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Email</p>
                            <p class="text-sm text-gray-700">{{ $ticket->holder_email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Tipe Tiket</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $ticket->ticketType->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Kode Tiket</p>
                            <p class="text-sm font-mono bg-gray-100 px-2.5 py-1 rounded-lg inline-block text-gray-700">
                                {{ $ticket->ticket_code }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Validation History --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Riwayat Validasi</p>
                @forelse($ticket->validations as $validation)
                    <div
                        class="flex items-start gap-3 pb-3 mb-3 border-b border-gray-50 last:border-0 last:mb-0 last:pb-0">
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                    {{ $validation->status === 'valid' ? 'bg-emerald-100' : 'bg-red-100' }}">
                            <i
                                class="ti {{ $validation->status === 'valid' ? 'ti-check text-emerald-600' : 'ti-x text-red-500' }} text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800 font-medium capitalize">{{ $validation->status }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Oleh {{ $validation->officer->name ?? '-' }} &bull;
                                {{ $validation->validated_at?->format('d M Y, H:i') }}
                            </p>
                            @if ($validation->notes)
                                <p class="text-xs text-gray-500 mt-1">{{ $validation->notes }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="ti ti-scan text-3xl text-gray-200 block mb-2"></i>
                        <p class="text-sm text-gray-400">Belum ada riwayat validasi</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- QR Code --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
                <p class="text-sm font-semibold text-gray-900 mb-4">QR Code</p>
                @if ($ticket->qr_code)
                    <img src="{{ Storage::url($ticket->qr_code) }}" class="w-48 h-48 mx-auto rounded-xl"
                        alt="QR Code">
                @else
                    <div
                        class="w-48 h-48 mx-auto bg-gray-50 rounded-xl border border-dashed border-gray-200 flex items-center justify-center">
                        <div class="text-center">
                            <i class="ti ti-qrcode text-4xl text-gray-300 block mb-2"></i>
                            <p class="text-xs text-gray-400">QR Code belum dibuat</p>
                        </div>
                    </div>
                @endif
                <p class="font-mono text-xs text-gray-400 mt-3">{{ $ticket->ticket_code }}</p>
            </div>

            {{-- Order Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Informasi Order</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Order #</span>
                        <a href="{{ route('admin.orders.show', $ticket->orderItem->order) }}"
                            class="font-mono text-blue-600 hover:underline text-xs">
                            {{ $ticket->orderItem->order->order_number }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status Order</span>
                        <span class="text-gray-700 capitalize">{{ $ticket->orderItem->order->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Harga</span>
                        <span class="text-gray-700 font-semibold">
                            Rp {{ number_format($ticket->ticketType->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="bg-gray-50 rounded-2xl p-5 space-y-2 text-xs text-gray-400">
                <div class="flex justify-between">
                    <span>Dibuat</span>
                    <span>{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Diperbarui</span>
                    <span>{{ $ticket->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
