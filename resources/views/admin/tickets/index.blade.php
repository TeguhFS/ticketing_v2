<x-app-layout>
    <x-slot name="title">Tickets</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total Tiket', 'value' => $stats['total'], 'icon' => 'ti-ticket', 'color' => 'bg-gray-900'], ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Used', 'value' => $stats['used'], 'icon' => 'ti-scan', 'color' => 'bg-blue-500'], ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-500']] as $stat)
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

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Filter --}}
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div
                    class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode tiket, nama, email..."
                        class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                </div>

                <div class="relative flex items-center min-w-[160px]">
                    <select name="event_id"
                        class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                        <option value="">Semua Event</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}"
                                {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>

                <div class="relative flex items-center min-w-[140px]">
                    <select name="status"
                        class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired
                        </option>
                    </select>
                    <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>

                <button type="submit"
                    class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition flex items-center justify-center">
                    Filter
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Kode Tiket</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Pemegang</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Event</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Tipe</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $ticket->ticket_code }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-sm font-medium text-gray-800">{{ $ticket->holder_name }}</p>
                                <p class="text-xs text-gray-400">{{ $ticket->holder_email }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 max-w-[160px]">
                                <p class="truncate text-sm">{{ $ticket->ticketType->event->title ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $ticket->ticketType->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
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
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.tickets.show', $ticket) }}"
                                    class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                    <i class="ti ti-eye text-base"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <i class="ti ti-ticket text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada tiket</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tickets->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $tickets->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
