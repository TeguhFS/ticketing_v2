<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Event</p>
                <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center">
                    <i class="ti ti-calendar-event text-white text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight">{{ number_format($totalEvents) }}</p>
            <p class="text-xs text-gray-400 mt-1">Event terdaftar</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Tiket Terjual</p>
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ti ti-ticket text-white text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight">{{ number_format($totalTickets) }}</p>
            <p class="text-xs text-gray-400 mt-1">Total tiket aktif</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Pendapatan</p>
                <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="ti ti-currency-dollar text-white text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Total pendapatan verified</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total User</p>
                <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center">
                    <i class="ti ti-users text-white text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-semibold text-gray-900 tracking-tight">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-gray-400 mt-1">User terdaftar</p>
        </div>

    </div>

    {{-- Pending Alerts --}}
    @if ($pendingOrders > 0 || $pendingPayments > 0)
        <div class="flex flex-wrap gap-3 mb-6">
            @if ($pendingOrders > 0)
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-2.5 rounded-xl hover:bg-amber-100 transition">
                    <i class="ti ti-clock text-base"></i>
                    <span><strong>{{ $pendingOrders }}</strong> order menunggu konfirmasi</span>
                    <i class="ti ti-arrow-right text-sm"></i>
                </a>
            @endif
            @if ($pendingPayments > 0)
                <a href="{{ route('admin.payments.index') }}"
                    class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2.5 rounded-xl hover:bg-red-100 transition">
                    <i class="ti ti-credit-card text-base"></i>
                    <span><strong>{{ $pendingPayments }}</strong> pembayaran menunggu verifikasi</span>
                    <i class="ti ti-arrow-right text-sm"></i>
                </a>
            @endif
        </div>
    @endif

    {{-- Expired Today Alert --}}
    @if ($expiredOrders > 0 || $expiredTickets > 0)
        <div class="flex flex-wrap gap-3 mb-6">
            @if ($expiredOrders > 0)
                <div
                    class="flex items-center gap-2 bg-gray-50 border border-gray-200
        text-gray-600 text-sm px-4 py-2.5 rounded-xl">
                    <i class="ti ti-clock-off text-base text-gray-400"></i>
                    <span>
                        <strong>{{ $expiredOrders }}</strong> order expired hari ini
                    </span>
                </div>
            @endif
            @if ($expiredTickets > 0)
                <div
                    class="flex items-center gap-2 bg-gray-50 border border-gray-200
        text-gray-600 text-sm px-4 py-2.5 rounded-xl">
                    <i class="ti ti-ticket-off text-base text-gray-400"></i>
                    <span>
                        <strong>{{ $expiredTickets }}</strong> tiket expired hari ini
                    </span>
                </div>
            @endif
        </div>
    @endif

    {{-- Chart & Upcoming Events --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">

        {{-- Revenue Chart --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Pendapatan 6 Bulan Terakhir</p>
                    <p class="text-xs text-gray-400 mt-0.5">Berdasarkan pembayaran terverifikasi</p>
                </div>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        {{-- Upcoming Events --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-gray-900">Event Mendatang</p>
                <a href="{{ route('admin.events.index') }}" class="text-xs text-gray-400 hover:text-gray-700">Lihat
                    semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingEvents as $event)
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-gray-50 border border-gray-100 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-semibold text-gray-400 uppercase leading-none">
                                {{ $event->start_date->format('M') }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 leading-none">
                                {{ $event->start_date->format('d') }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $event->title }}</p>
                            <p class="text-xs text-gray-400 truncate mt-0.5">
                                <i class="ti ti-map-pin text-xs"></i> {{ $event->location }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="ti ti-calendar-off text-2xl text-gray-300 block mb-2"></i>
                        <p class="text-xs text-gray-400">Tidak ada event mendatang</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-sm font-semibold text-gray-900">Order Terbaru</p>
                <p class="text-xs text-gray-400 mt-0.5">10 transaksi terakhir</p>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="text-xs border border-gray-200 text-gray-500 hover:text-gray-900 hover:bg-gray-50 px-3 py-1.5 rounded-lg transition">
                Lihat semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-medium text-gray-400 pb-3">Order #</th>
                        <th class="text-left text-xs font-medium text-gray-400 pb-3">User</th>
                        <th class="text-left text-xs font-medium text-gray-400 pb-3">Event</th>
                        <th class="text-left text-xs font-medium text-gray-400 pb-3">Total</th>
                        <th class="text-left text-xs font-medium text-gray-400 pb-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 pb-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 font-mono text-xs text-gray-500">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                    </div>
                                    <span class="text-gray-700 truncate max-w-[120px]">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-gray-600 truncate max-w-[150px]">
                                {{ $order->orderItems->first()?->ticketType?->event?->title ?? '-' }}
                            </td>
                            <td class="py-3 font-medium text-gray-900">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3">
                                @php
                                    $statusConfig = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                        'expired' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        'refunded' => 'bg-blue-50 text-blue-600 border-blue-200',
                                    ];
                                    $class = $statusConfig[$order->status] ?? 'bg-gray-100 text-gray-500';
                                @endphp
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-lg border {{ $class }} capitalize">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 text-xs text-gray-400">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center">
                                <i class="ti ti-inbox text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-sm text-gray-400">Belum ada order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($revenues) !!},
                        backgroundColor: '#111827',
                        borderRadius: 8,
                        borderSkipped: false,
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
                                callback: (v) => 'Rp ' + (v / 1000000).toFixed(0) + 'jt'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
