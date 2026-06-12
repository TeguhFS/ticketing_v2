<x-app-layout>
    <x-slot name="title">Detail Refund</x-slot>

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
        <a href="{{ route('admin.refunds.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Refund
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Refund Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-sm font-bold text-gray-900">Detail Pengajuan Refund</p>
                        <p class="font-mono text-xs text-gray-400 mt-0.5">{{ $refund->refund_number }}</p>
                    </div>
                    @php
                        $statusCfg = [
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'rejected' => 'bg-red-50 text-red-600 border-red-200',
                        ];
                    @endphp
                    <span
                        class="text-sm font-semibold px-3 py-1.5 rounded-xl border
                    {{ $statusCfg[$refund->status] ?? '' }} capitalize">
                        {{ $refund->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Nomor Order</p>
                        <a href="{{ route('admin.orders.show', $refund->order) }}"
                            class="text-sm font-mono text-blue-600 hover:underline">
                            {{ $refund->order->order_number }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jumlah Refund</p>
                        <p class="text-base font-bold text-gray-900">
                            Rp {{ number_format($refund->amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Pengajuan</p>
                        <p class="text-sm text-gray-700">{{ $refund->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($refund->reviewed_at)
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Tanggal Review</p>
                            <p class="text-sm text-gray-700">{{ $refund->reviewed_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Direview Oleh</p>
                            <p class="text-sm text-gray-700">{{ $refund->reviewedBy->name ?? '-' }}</p>
                        </div>
                    @endif
                </div>

                {{-- Reason --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Alasan Pengajuan</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $refund->reason }}</p>
                </div>

                {{-- Admin Notes --}}
                @if ($refund->admin_notes)
                    <div
                        class="border border-gray-100 rounded-xl p-4
                {{ $refund->isApproved() ? 'bg-emerald-50/50' : 'bg-red-50/50' }}">
                        <p class="text-xs font-semibold text-gray-500 mb-2">Catatan Admin</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $refund->admin_notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-bold text-gray-900 mb-4">Item yang Direfund</p>
                <div class="space-y-3">
                    @foreach ($refund->order->orderItems as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
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
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $item->ticketType->event->title ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $item->ticketType->name }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="text-sm font-bold text-gray-900 flex-shrink-0">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tickets Status --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-bold text-gray-900 mb-4">Status Tiket</p>
                <div class="space-y-2">
                    @foreach ($refund->order->orderItems->flatMap->tickets as $ticket)
                        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                        {{ $ticket->status === 'cancelled' ? 'bg-red-100' : ($ticket->status === 'active' ? 'bg-emerald-100' : 'bg-gray-100') }}">
                                <i
                                    class="ti text-sm
                            {{ $ticket->status === 'cancelled' ? 'ti-x text-red-500' : ($ticket->status === 'active' ? 'ti-check text-emerald-600' : 'ti-clock text-gray-500') }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-mono font-semibold text-gray-700">
                                    {{ $ticket->ticket_code }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $ticket->ticketType->name }}</p>
                            </div>
                            @php
                                $tBadge = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'used' => 'bg-blue-50 text-blue-600 border-blue-200',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                    'expired' => 'bg-gray-100 text-gray-500 border-gray-200',
                                ];
                            @endphp
                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-lg border flex-shrink-0
                        {{ $tBadge[$ticket->status] ?? '' }} capitalize">
                                {{ $ticket->status === 'cancelled' ? 'Dibatalkan' : ucfirst($ticket->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- Action Card --}}
            @if ($refund->isPending())
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Tindakan Admin</p>

                    {{-- Approve --}}
                    <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST" class="mb-4">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                Catatan Approval <span class="text-gray-400">(opsional)</span>
                            </label>
                            <textarea name="admin_notes" rows="3" placeholder="Tambahkan catatan untuk user..."
                                class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm
                            text-gray-700 outline-none focus:border-gray-300 bg-white resize-none"></textarea>
                        </div>
                        <button type="submit"
                            onclick="return confirm('Approve refund ini? Tiket akan dibatalkan dan kuota dikembalikan.')"
                            class="w-full h-11 bg-emerald-500 text-white text-sm font-semibold rounded-xl
                        hover:bg-emerald-600 transition flex items-center justify-center gap-2">
                            <i class="ti ti-circle-check text-base"></i>
                            Approve Refund
                        </button>
                    </form>

                    <div class="relative flex items-center gap-3 my-4">
                        <div class="flex-1 h-px bg-gray-100"></div>
                        <span class="text-xs text-gray-400">atau</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>

                    {{-- Reject --}}
                    <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST" x-data="{ open: false }">
                        @csrf @method('PATCH')
                        <button type="button" @click="open = !open"
                            class="w-full h-10 border border-red-200 text-red-500 text-sm font-medium
                        rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
                            <i class="ti ti-circle-x text-base"></i>
                            Tolak Refund
                        </button>
                        <div x-show="open" x-transition class="mt-3 space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="admin_notes" rows="3" required placeholder="Jelaskan alasan penolakan kepada user..."
                                    class="w-full px-3 py-2.5 border border-red-200 rounded-xl text-sm
                                text-gray-700 outline-none focus:border-red-300 bg-white resize-none"></textarea>
                            </div>
                            <button type="submit" onclick="return confirm('Tolak refund ini?')"
                                class="w-full h-10 bg-red-500 text-white text-sm font-semibold rounded-xl
                            hover:bg-red-600 transition">
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

                    @if ($refund->user->avatar)
                        <img src="{{ Storage::url($refund->user->avatar) }}" alt="{{ $refund->user->name }}"
                            class="w-12 h-12 rounded-full object-cover border border-gray-100">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr($refund->user->name, 0, 2)) }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $refund->user->name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $refund->user->email }}
                        </p>
                    </div>

                    <div class="ml-auto">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                {{ $refund->user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                            {{ $refund->user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                </div>

                {{-- Detail --}}
                <div class="space-y-3 text-sm">

                    <div class="flex items-start gap-3">
                        <i class="ti ti-mail text-gray-300 text-base mt-0.5"></i>
                        <span class="text-gray-600">
                            {{ $refund->user->email }}
                        </span>
                    </div>

                    @if ($refund->user->phone)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-phone text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $refund->user->phone }}
                            </span>
                        </div>
                    @endif

                    @if ($refund->user->gender)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-user text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $refund->user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    @endif

                    @if ($refund->user->birth_date)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-calendar text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600">
                                {{ $refund->user->birth_date->format('d M Y') }}
                            </span>
                        </div>
                    @endif

                    @if ($refund->user->id_card_number)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-id text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 font-mono">
                                {{ $refund->user->id_card_number }}
                            </span>
                        </div>
                    @endif

                    @if ($refund->user->address)
                        <div class="flex items-start gap-3">
                            <i class="ti ti-map-pin text-gray-300 text-base mt-0.5"></i>
                            <span class="text-gray-600 leading-relaxed">
                                {{ $refund->user->address }}
                            </span>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Bank Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-bold text-gray-900 mb-4">Info Rekening Refund</p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Bank</span>
                        <span class="font-semibold text-gray-900">{{ $refund->bank_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">No. Rekening</span>
                        <span class="font-mono text-gray-700">{{ $refund->account_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Atas Nama</span>
                        <span class="text-gray-700">{{ $refund->account_name }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-base pt-2 border-t border-gray-100">
                        <span class="text-gray-900">Total Transfer</span>
                        <span class="text-emerald-600">
                            Rp {{ number_format($refund->amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if ($refund->isApproved())
                    <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <p class="text-xs text-emerald-700 flex items-center gap-2">
                            <i class="ti ti-circle-check text-sm"></i>
                            Silakan transfer dana ke rekening di atas sesuai nominal.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Meta --}}
            <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-xs text-gray-400">
                <div class="flex justify-between">
                    <span>Diajukan</span>
                    <span>{{ $refund->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if ($refund->reviewed_at)
                    <div class="flex justify-between">
                        <span>Direview</span>
                        <span>{{ $refund->reviewed_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
