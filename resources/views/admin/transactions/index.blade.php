<x-app-layout>
    <x-slot name="title">Transaksi</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Pendapatan</p>
                <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center">
                    <i class="ti ti-currency-dollar text-white text-base"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ number_format($totalCount) }} transaksi</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Hari Ini</p>
                <div class="w-9 h-9 bg-blue-500 rounded-xl flex items-center justify-center">
                    <i class="ti ti-sun text-white text-base"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalToday, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Minggu Ini</p>
                <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center">
                    <i class="ti ti-calendar-week text-white text-base"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalThisWeek, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->startOfWeek()->format('d') }} -
                {{ now()->endOfWeek()->format('d M') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Bulan Ini</p>
                <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="ti ti-calendar-month text-white text-base"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalThisMonth, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-sm font-semibold text-gray-900">Grafik Pendapatan</p>
                <p class="text-xs text-gray-400 mt-0.5">Berdasarkan transaksi terverifikasi</p>
            </div>
            {{-- Period Toggle --}}
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl">
                @foreach (['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan'] as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['period' => $key]) }}"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition
                {{ $period === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
        <canvas id="transactionChart" height="80"></canvas>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-900">Riwayat Transaksi</p>
                <p class="text-xs text-gray-400 mt-0.5">Semua pembayaran yang sudah diverifikasi</p>
            </div>
            <a href="{{ route('admin.transactions.export') }}"
                class="inline-flex items-center gap-2 h-9 px-4 border border-gray-200 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition">
                <i class="ti ti-file-spreadsheet text-base"></i> Export Excel
            </a>
        </div>

        {{-- Filter --}}
        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="hidden" name="period" value="{{ $period }}">

                {{-- Input Search --}}
                <div
                    class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 h-9 flex-1 min-w-[200px]">
                    <i class="ti ti-search text-gray-300 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode, nama, email..."
                        class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-300">
                </div>

                {{-- Select Metode Pembayaran --}}
                <div class="relative flex items-center min-w-[160px]">
                    <select name="payment_method_id"
                        class="h-9 pl-3 pr-10 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none appearance-none focus:outline-none focus:ring-0 w-full cursor-pointer">
                        <option value="">Semua Metode</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}"
                                {{ request('payment_method_id') == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 pointer-events-none text-gray-400 flex items-center">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>

                {{-- Date From --}}
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none focus:outline-none focus:ring-0 cursor-pointer">

                {{-- Date To --}}
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="h-9 px-3 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 outline-none focus:outline-none focus:ring-0 cursor-pointer">

                {{-- Buttons --}}
                <button type="submit"
                    class="h-9 px-4 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                    Filter
                </button>

                @if (request()->anyFilled(['search', 'payment_method_id', 'date_from', 'date_to']))
                    <a href="{{ route('admin.transactions.index', ['period' => $period]) }}"
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
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Diverifikasi</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Tanggal</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $trx->payment_code }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0">
                                        @if ($trx->order->user->avatar)
                                            <img src="{{ Storage::url($trx->order->user->avatar) }}"
                                                class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($trx->order->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $trx->order->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $trx->order->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 max-w-[150px]">
                                <p class="text-sm text-gray-700 truncate">
                                    {{ $trx->order->orderItems->first()?->ticketType?->event?->title ?? '-' }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    {{ $trx->paymentMethod->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">
                                Rp {{ number_format($trx->total_paid, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">
                                {{ $trx->verifiedBy->name ?? 'System / Midtrans' }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                {{ $trx->verified_at?->format('d M Y') }}<br>
                                {{ $trx->verified_at?->format('H:i') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.transactions.show', $trx) }}"
                                    class="w-8 h-8 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                    <i class="ti ti-eye text-base"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <i class="ti ti-receipt-off text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada transaksi terverifikasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('transactionChart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($chartData['data']) !!},
                        borderColor: '#111827',
                        backgroundColor: 'rgba(17,24,39,0.06)',
                        borderWidth: 2,
                        pointBackgroundColor: '#111827',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                color: '#f3f4f6'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                callback: (v) => {
                                    if (v >= 1000000) return 'Rp ' + (v / 1000000).toFixed(0) + 'jt';
                                    if (v >= 1000) return 'Rp ' + (v / 1000).toFixed(0) + 'rb';
                                    return 'Rp ' + v;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
