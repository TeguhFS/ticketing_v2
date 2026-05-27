<x-app-layout>
    <x-slot name="title">Payment</x-slot>

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

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'ti-clock', 'color' => 'bg-amber-500'], ['label' => 'Verified', 'value' => $stats['verified'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Rejected', 'value' => $stats['rejected'], 'icon' => 'ti-circle-x', 'color' => 'bg-red-500'], ['label' => 'Expired', 'value' => $stats['expired'], 'icon' => 'ti-clock-off', 'color' => 'bg-gray-400']] as $stat)
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

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-900">Daftar Pembayaran</p>
                <p class="text-xs text-gray-400 mt-0.5">Verifikasi pembayaran masuk dari user</p>
            </div>
            <a href="{{ route('admin.payments.methods') }}"
                class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition">
                <i class="ti ti-building-bank text-base"></i> Payment Methods
            </a>
        </div>

        {{-- Filter --}}
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <form method="GET" class="flex flex-wrap gap-3 items-center">

                {{-- Input Search --}}
                <div
                    class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode, nama, email..."
                        class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                </div>

                {{-- Select Status --}}
                <div class="relative flex items-center min-w-[150px]">
                    <select name="status"
                        class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired
                        </option>
                    </select>
                    <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>

                {{-- Input Date From --}}
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none focus:outline-none focus:ring-0 cursor-pointer">

                {{-- Input Date To --}}
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none focus:outline-none focus:ring-0 cursor-pointer">

                {{-- Buttons --}}
                <button type="submit"
                    class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                    Filter
                </button>

                @if (request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.payments.index') }}"
                        class="h-9 px-4 flex items-center justify-center border border-gray-100 text-gray-500 text-sm rounded-xl hover:bg-gray-50 transition">
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
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Kode Bayar</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">User</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Event</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Metode</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Total</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Bukti</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $payment->payment_code }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($payment->order->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 leading-tight">
                                            {{ $payment->order->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $payment->order->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 max-w-[150px]">
                                <p class="text-sm text-gray-700 truncate">
                                    {{ $payment->order->orderItems->first()?->ticketType?->event?->title ?? '-' }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $payment->paymentMethod->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">
                                Rp {{ number_format($payment->total_paid, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($payment->proof_image)
                                    <a href="{{ Storage::url($payment->proof_image) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline">
                                        <i class="ti ti-photo text-sm"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $cfg = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                        'expired' => 'bg-gray-100 text-gray-400 border-gray-200',
                                    ];
                                @endphp
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $cfg[$payment->status] ?? '' }} capitalize">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                        <i class="ti ti-eye text-base"></i>
                                    </a>
                                    @if ($payment->status === 'pending')
                                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                onclick="return confirm('Verifikasi pembayaran ini?')"
                                                class="w-8 h-8 border border-emerald-200 rounded-lg flex items-center justify-center text-emerald-500 hover:bg-emerald-50 transition">
                                                <i class="ti ti-check text-base"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <i class="ti ti-credit-card-off text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada data pembayaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
