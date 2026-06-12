<x-app-layout>
    <x-slot name="title">Refund</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        @foreach ([['label' => 'Total', 'value' => $stats['total'], 'icon' => 'ti-refresh', 'color' => 'bg-gray-900'], ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'ti-clock', 'color' => 'bg-amber-500'], ['label' => 'Approved', 'value' => $stats['approved'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Rejected', 'value' => $stats['rejected'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-500'], ['label' => 'Total Refunded', 'value' => 'Rp ' . number_format($stats['amount'], 0, ',', '.'), 'icon' => 'ti-currency-dollar', 'color' => 'bg-blue-500']] as $stat)
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <div class="w-9 h-9 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                        <i class="ti {{ $stat['icon'] }} text-white text-base"></i>
                    </div>
                </div>
                <p class="text-xl font-bold text-gray-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-900">Daftar Pengajuan Refund</p>
                <p class="text-xs text-gray-400 mt-0.5">Kelola semua request refund dari user</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div
                    class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nomor refund, nama, order..."
                        class="bg-transparent border-none outline-none text-sm text-gray-700 w-full placeholder-gray-300">
                </div>
                <select name="status"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit"
                    class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                    Filter
                </button>
                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.refunds.index') }}"
                        class="h-9 px-4 border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Refund #</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">User</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Event</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Jumlah</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Alasan</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Tanggal</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($refunds as $refund)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $refund->refund_number }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center
                                text-white text-[10px] font-semibold flex-shrink-0">
                                        @if ($refund->user->avatar)
                                            <img src="{{ Storage::url($refund->user->avatar) }}"
                                                class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($refund->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 leading-tight">
                                            {{ $refund->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $refund->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 max-w-[150px]">
                                <p class="text-sm text-gray-700 truncate">
                                    {{ $refund->order->orderItems->first()?->ticketType?->event?->title ?? '-' }}
                                </p>
                                <p class="text-xs font-mono text-gray-400 mt-0.5">
                                    {{ $refund->order->order_number }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-gray-900">
                                Rp {{ number_format($refund->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 max-w-[160px]">
                                <p class="text-xs text-gray-500 truncate">{{ $refund->reason }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $statusCfg = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                    ];
                                @endphp
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            {{ $statusCfg[$refund->status] ?? '' }} capitalize">
                                    {{ $refund->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                {{ $refund->created_at->format('d M Y') }}<br>
                                {{ $refund->created_at->format('H:i') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.refunds.show', $refund) }}"
                                    class="w-8 h-8 border border-gray-100 rounded-lg flex items-center
                                justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <i class="ti ti-refresh text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada pengajuan refund</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($refunds->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $refunds->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
