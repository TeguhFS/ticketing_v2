<x-app-layout>
    <x-slot name="title">Order</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total Order', 'value' => $stats['total'], 'icon' => 'ti-shopping-cart', 'color' => 'bg-gray-900'], ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'ti-clock', 'color' => 'bg-amber-500'], ['label' => 'Paid', 'value' => $stats['paid'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-500']] as $stat)
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <div class="w-9 h-9 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                        <i class="ti {{ $stat['icon'] }} text-white text-base"></i>
                    </div>
                </div>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stat['value']) }}</p>
            </div>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Filter Bar --}}
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div
                    class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[180px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari order number, nama, email..."
                        class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                </div>

                <div class="relative flex items-center min-w-[140px]">
                    <select name="status"
                        class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded
                        </option>
                    </select>
                    <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>

                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none focus:outline-none focus:ring-0 cursor-pointer text-center md:text-left">

                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none focus:outline-none focus:ring-0 cursor-pointer text-center md:text-left">

                <button type="submit"
                    class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition flex items-center justify-center">
                    Filter
                </button>

                <a href="{{ route('admin.orders.export', request()->query()) }}"
                    class="h-9 px-4 border border-gray-200 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    <i class="ti ti-download text-base"></i> Export
                </a>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Order #</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">User</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Event</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Total</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Tanggal</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs text-gray-500">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-800 font-medium leading-tight">
                                            {{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 max-w-[160px]">
                                <p class="truncate">
                                    {{ $order->orderItems->first()?->ticketType?->event?->title ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $order->orderItems->count() }} item</p>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5">
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
                                    class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $cfg[$order->status] ?? '' }} capitalize">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                {{ $order->created_at->format('d M Y') }}<br>
                                {{ $order->created_at->format('H:i') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                        <i class="ti ti-eye text-base"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                        onsubmit="return confirm('Hapus order ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                                            <i class="ti ti-trash text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <i class="ti ti-inbox text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
