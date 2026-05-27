<x-app-layout>
    <x-slot name="title">Detail User</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition">
            <i class="ti ti-arrow-left text-base"></i> Kembali ke Users
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.edit', $user) }}"
                class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition">
                <i class="ti ti-edit text-base"></i> Edit
            </a>
            @if ($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                    onsubmit="return confirm('Hapus user ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 h-9 px-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl hover:bg-red-100 transition">
                        <i class="ti ti-trash text-base"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Recent Orders --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold text-gray-900">Order Terbaru</p>
                    <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}"
                        class="text-xs text-gray-400 hover:text-gray-700 transition">Lihat semua →</a>
                </div>
                @forelse($user->orders as $order)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                        <div class="flex-1">
                            <p class="text-xs font-mono text-gray-500">{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-700 mt-0.5">
                                {{ $order->orderItems->first()?->ticketType?->event?->title ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                            @php
                                $cfg = [
                                    'pending' => 'bg-amber-50 text-amber-700',
                                    'paid' => 'bg-emerald-50 text-emerald-700',
                                    'cancelled' => 'bg-red-50 text-red-600',
                                ];
                            @endphp
                            <span
                                class="text-xs px-2 py-0.5 rounded-lg {{ $cfg[$order->status] ?? 'bg-gray-100 text-gray-500' }} capitalize">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="ti ti-shopping-cart-off text-3xl text-gray-200 block mb-2"></i>
                        <p class="text-sm text-gray-400">Belum ada order</p>
                    </div>
                @endforelse
            </div>

            {{-- Recent Tickets --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold text-gray-900">Tiket Terbaru</p>
                    <a href="{{ route('admin.tickets.index', ['search' => $user->email]) }}"
                        class="text-xs text-gray-400 hover:text-gray-700 transition">Lihat semua →</a>
                </div>
                @forelse($user->tickets as $ticket)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                        <div class="flex-1">
                            <p class="text-xs font-mono text-gray-500">{{ $ticket->ticket_code }}</p>
                            <p class="text-sm text-gray-700 mt-0.5">{{ $ticket->ticketType->event->title ?? '-' }}</p>
                        </div>
                        @php
                            $tCfg = [
                                'active' => 'bg-emerald-50 text-emerald-700',
                                'used' => 'bg-gray-100 text-gray-500',
                                'cancelled' => 'bg-red-50 text-red-500',
                            ];
                        @endphp
                        <span
                            class="text-xs px-2.5 py-1 rounded-lg {{ $tCfg[$ticket->status] ?? 'bg-gray-100 text-gray-500' }} capitalize">
                            {{ $ticket->status }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="ti ti-ticket-off text-3xl text-gray-200 block mb-2"></i>
                        <p class="text-sm text-gray-400">Belum ada tiket</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Right --}}
        <div class="space-y-5">

            {{-- Profile Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                        class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-2 border-gray-100"
                        alt="">
                @else
                    <div
                        class="w-20 h-20 rounded-full bg-gray-900 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <h2 class="text-base font-semibold text-gray-900">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>

                <div class="flex items-center justify-center gap-2 mt-3">
                    @php
                        $roleCfg = [
                            'admin' => 'bg-violet-50 text-violet-700 border-violet-200',
                            'user' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'field_officer' => 'bg-amber-50 text-amber-700 border-amber-200',
                        ];
                    @endphp
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $roleCfg[$user->role] ?? '' }} capitalize">
                        {{ str_replace('_', ' ', $user->role) }}
                    </span>
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-lg border
                    {{ $user->is_active
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3 mt-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $user->orders_count }}</p>
                        <p class="text-xs text-gray-400">Orders</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $user->tickets_count }}</p>
                        <p class="text-xs text-gray-400">Tiket</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ number_format($totalSpent / 1000000, 1) }}jt</p>
                        <p class="text-xs text-gray-400">Spent</p>
                    </div>
                </div>
            </div>

            {{-- Info Pribadi --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-900 mb-4">Informasi Pribadi</p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">No. HP</span>
                        <span class="text-gray-700">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Gender</span>
                        <span class="text-gray-700 capitalize">{{ $user->gender ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tgl. Lahir</span>
                        <span class="text-gray-700">{{ $user->birth_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">NIK</span>
                        <span class="text-gray-700 font-mono text-xs">{{ $user->id_card_number ?? '-' }}</span>
                    </div>
                    @if ($user->address)
                        <div>
                            <span class="text-gray-400 block mb-1">Alamat</span>
                            <span class="text-gray-700 text-xs leading-relaxed">{{ $user->address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Toggle Status --}}
            <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit"
                    class="w-full h-10 border text-sm rounded-xl transition flex items-center justify-center gap-2
                    {{ $user->is_active
                        ? 'border-amber-200 text-amber-600 hover:bg-amber-50'
                        : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                    <i class="ti {{ $user->is_active ? 'ti-user-off' : 'ti-user-check' }} text-base"></i>
                    {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                </button>
            </form>

            {{-- Meta --}}
            <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-xs text-gray-400">
                <div class="flex justify-between">
                    <span>Bergabung</span>
                    <span>{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Diperbarui</span>
                    <span>{{ $user->updated_at->format('d M Y') }}</span>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
