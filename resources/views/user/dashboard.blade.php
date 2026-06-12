<x-auth-layout>
    <x-slot name="title">Dashboard — {{ setting('app_name') }}</x-slot>

    {{-- Page Header --}}
    <section class="bg-gray-950 pt-5 pb-5 relative overflow-hidden rounded-2xl">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/3 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.02]"
                style="background-image: linear-gradient(rgba(255,255,255,.3) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.3) 1px, transparent 1px);
            background-size: 40px 40px;">
            </div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                        class="w-12 h-12 rounded-xl object-cover border border-white/10 flex-shrink-0"
                        alt="{{ $user->name }}">
                @else
                    <div
                        class="w-12 h-12 rounded-xl bg-white/10 border border-white/10 flex items-center
                    justify-center text-white text-base font-bold flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif

                {{-- Greeting Info --}}
                <div>
                    <p class="text-[11px] text-gray-500 mb-0.5">Selamat datang kembali 👋</p>
                    <h1 class="text-xl font-bold text-white leading-tight">{{ $user->name }}</h1>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                </div>

                {{-- Action Header Links --}}
                <div class="ml-auto hidden sm:flex items-center gap-2">
                    <a href="{{ route('user.profile.edit') }}"
                        class="inline-flex items-center gap-2 h-8 px-3.5 bg-white/10 border border-white/10
                    text-white text-xs font-medium rounded-xl hover:bg-white/20
                    transition-all duration-200">
                        <i class="ti ti-edit text-sm"></i> Edit Profil
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 h-8 px-3.5 bg-white text-gray-900 text-xs
                    font-semibold rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <i class="ti ti-ticket text-sm"></i> Beli Tiket
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Container Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 pb-12">

        {{-- Stats Cards Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-8 mt-10">
            @foreach ([['label' => 'Total Order', 'value' => $stats['total_orders'], 'icon' => 'ti-shopping-cart', 'color' => 'bg-gray-900', 'text' => 'text-white'], ['label' => 'Total Tiket', 'value' => $stats['total_tickets'], 'icon' => 'ti-ticket', 'color' => 'bg-blue-500', 'text' => 'text-white'], ['label' => 'Tiket Aktif', 'value' => $stats['active_tickets'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500', 'text' => 'text-white'], ['label' => 'Sudah Digunakan', 'value' => $stats['used_tickets'], 'icon' => 'ti-scan', 'color' => 'bg-violet-500', 'text' => 'text-white'], ['label' => 'Total Pengeluaran', 'value' => 'Rp ' . number_format($stats['total_spent'], 0, ',', '.'), 'icon' => 'ti-currency-dollar', 'color' => 'bg-amber-500', 'text' => 'text-white']] as $stat)
                <div
                    class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-gray-400 leading-tight">{{ $stat['label'] }}</p>
                        <div
                            class="w-8 h-8 {{ $stat['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ti {{ $stat['icon'] }} {{ $stat['text'] }} text-sm"></i>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-900 leading-tight">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Columns Split Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left Content Grid (Main Data) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Upcoming Tickets Card --}}
                @if ($upcomingTickets->count() > 0)
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Tiket Mendatang</p>
                                <p class="text-xs text-gray-400 mt-0.5">Event yang akan segera berlangsung</p>
                            </div>
                            <a href="{{ route('user.tickets.index') }}"
                                class="text-xs font-medium text-gray-500 hover:text-gray-900 transition flex items-center gap-1">
                                Lihat semua <i class="ti ti-arrow-right text-sm"></i>
                            </a>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach ($upcomingTickets as $ticket)
                                <a href="{{ route('user.tickets.show', $ticket) }}"
                                    class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50/50 transition-colors duration-150 group">

                                    {{-- Event Thumbnail --}}
                                    <div class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                        @if ($ticket->ticketType->event->thumbnail)
                                            <img src="{{ Storage::url($ticket->ticketType->event->thumbnail) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                alt="">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                <i class="ti ti-calendar-event text-xl text-gray-300"></i>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Ticket Title Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate leading-snug">
                                            {{ $ticket->ticketType->event->title }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-3">
                                            <span class="flex items-center gap-1">
                                                <i class="ti ti-calendar text-gray-300 text-xs"></i>
                                                {{ $ticket->ticketType->event->start_date->translatedFormat('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="ti ti-map-pin text-gray-300 text-xs"></i>
                                                {{ Str::limit($ticket->ticketType->event->location, 20) }}
                                            </span>
                                        </p>
                                    </div>

                                    {{-- Dynamic Countdown Status --}}
                                    <div class="text-right flex-shrink-0">
                                        @php
                                            $daysLeft = (int) today()->diffInDays(
                                                $ticket->ticketType->event->start_date,
                                                false,
                                            );
                                        @endphp
                                        @if ($daysLeft < 0)
                                            <span
                                                class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                                                Event Selesai
                                            </span>
                                        @elseif ($daysLeft === 0)
                                            <span
                                                class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                                                Hari Ini
                                            </span>
                                        @elseif ($daysLeft === 1)
                                            <span
                                                class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">
                                                Besok
                                            </span>
                                        @else
                                            <span
                                                class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                                                {{ $daysLeft }} hari lagi
                                            </span>
                                        @endif
                                        <p class="text-xs font-mono text-gray-400 mt-1">
                                            {{ $ticket->ticket_code }}
                                        </p>
                                    </div>

                                    <i
                                        class="ti ti-chevron-right text-gray-300 text-sm flex-shrink-0 group-hover:text-gray-500 group-hover:translate-x-0.5 transition-all duration-200"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Recent Orders Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                        <div>
                            <p class="text-sm font-bold text-gray-900">Order Terbaru</p>
                            <p class="text-xs text-gray-400 mt-0.5">Riwayat pembelian tiket Anda</p>
                        </div>
                        <a href="{{ route('user.orders.index') }}"
                            class="text-xs font-medium text-gray-500 hover:text-gray-900 transition flex items-center gap-1">
                            Lihat semua <i class="ti ti-arrow-right text-sm"></i>
                        </a>
                    </div>

                    @if ($recentOrders->isEmpty())
                        <div class="text-center py-12">
                            <i class="ti ti-shopping-cart-off text-4xl text-gray-200 block mb-3"></i>
                            <p class="text-sm text-gray-400 mb-4">Belum ada order</p>
                            <a href="{{ route('events.index') }}"
                                class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-xs font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                                <i class="ti ti-ticket text-sm"></i> Beli Tiket Pertama
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach ($recentOrders as $order)
                                <a href="{{ route('user.orders.show', $order) }}"
                                    class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50/50 transition-colors duration-150 group">

                                    {{-- Mapping Order Status Icon --}}
                                    @php
                                        $statusIcon = [
                                            'pending' => [
                                                'icon' => 'ti-clock',
                                                'bg' => 'bg-amber-100',
                                                'text' => 'text-amber-600',
                                            ],
                                            'paid' => [
                                                'icon' => 'ti-circle-check',
                                                'bg' => 'bg-emerald-100',
                                                'text' => 'text-emerald-600',
                                            ],
                                            'cancelled' => [
                                                'icon' => 'ti-circle-x',
                                                'bg' => 'bg-red-100',
                                                'text' => 'text-red-500',
                                            ],
                                            'expired' => [
                                                'icon' => 'ti-clock-off',
                                                'bg' => 'bg-gray-100',
                                                'text' => 'text-gray-400',
                                            ],
                                            'refunded' => [
                                                'icon' => 'ti-refresh',
                                                'bg' => 'bg-blue-100',
                                                'text' => 'text-blue-500',
                                            ],
                                        ];
                                        $si = $statusIcon[$order->status] ?? $statusIcon['pending'];
                                    @endphp
                                    <div
                                        class="w-10 h-10 {{ $si['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="ti {{ $si['icon'] }} {{ $si['text'] }} text-base"></i>
                                    </div>

                                    {{-- Order Data Details --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $order->orderItems->first()?->ticketType?->event?->title ?? 'Event' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                                            <span class="font-mono">{{ $order->order_number }}</span>
                                            <span class="text-gray-200">·</span>
                                            <span>{{ $order->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>

                                    {{-- Total Spent & Status Badge --}}
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-sm font-bold text-gray-900">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </p>
                                        @php
                                            $statusBadge = [
                                                'pending' => 'bg-amber-50 text-amber-700',
                                                'paid' => 'bg-emerald-50 text-emerald-700',
                                                'cancelled' => 'bg-red-50 text-red-600',
                                                'expired' => 'bg-gray-100 text-gray-500',
                                                'refunded' => 'bg-blue-50 text-blue-600',
                                            ];
                                        @endphp
                                        <span
                                            class="text-xs font-medium px-2 py-0.5 rounded-lg mt-1 inline-block {{ $statusBadge[$order->status] ?? '' }} capitalize">
                                            {{ $order->status }}
                                        </span>
                                    </div>

                                    <i
                                        class="ti ti-chevron-right text-gray-300 text-sm flex-shrink-0 group-hover:text-gray-500 group-hover:translate-x-0.5 transition-all duration-200"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Content Grid --}}
            <div class="space-y-5">

                {{-- Quick Actions Menu --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">Menu Utama</p>
                    <div class="space-y-2">
                        @foreach ([['route' => 'events.index', 'icon' => 'ti-calendar-event', 'label' => 'Jelajahi Event', 'desc' => 'Temukan event seru', 'color' => 'bg-gray-900'], ['route' => 'user.tickets.index', 'icon' => 'ti-ticket', 'label' => 'Tiket Saya', 'desc' => $stats['active_tickets'] . ' tiket aktif', 'color' => 'bg-blue-500'], ['route' => 'user.orders.index', 'icon' => 'ti-shopping-cart', 'label' => 'Pesanan Saya', 'desc' => $stats['total_orders'] . ' total order', 'color' => 'bg-violet-500'], ['route' => 'user.profile.edit', 'icon' => 'ti-user', 'label' => 'Edit Profil', 'desc' => 'Perbarui data diri', 'color' => 'bg-amber-500']] as $action)
                            <a href="{{ route($action['route']) }}"
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-all duration-150 group">
                                <div
                                    class="w-9 h-9 {{ $action['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="ti {{ $action['icon'] }} text-white text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $action['label'] }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $action['desc'] }}</p>
                                </div>
                                <i
                                    class="ti ti-arrow-right text-gray-300 text-sm group-hover:text-gray-500 group-hover:translate-x-0.5 transition-all duration-200"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Profile Completion Card --}}
                @php
                    $fields = ['name', 'email', 'phone', 'address', 'birth_date', 'id_card_number'];
                    $filled = collect($fields)->filter(fn($f) => !empty($user->$f))->count();
                    $percentage = round(($filled / count($fields)) * 100);
                @endphp
                @if ($percentage < 100)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-bold text-gray-900">Kelengkapan Profil</p>
                            <span class="text-sm font-bold text-gray-900">{{ $percentage }}%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden mb-3">
                            <div class="h-full rounded-full transition-all duration-500 {{ $percentage >= 80 ? 'bg-emerald-400' : ($percentage >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">Lengkapi profil Anda untuk pengalaman yang lebih baik.
                        </p>
                        <div class="space-y-1.5">
                            @foreach ([['field' => 'phone', 'label' => 'Nomor HP'], ['field' => 'address', 'label' => 'Alamat'], ['field' => 'birth_date', 'label' => 'Tanggal Lahir'], ['field' => 'id_card_number', 'label' => 'NIK KTP']] as $item)
                                @if (empty($user->{$item['field']}))
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <i class="ti ti-circle text-gray-200 text-xs flex-shrink-0"></i>
                                        {{ $item['label'] }} belum diisi
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('user.profile.edit') }}"
                            class="flex items-center justify-center gap-2 h-9 bg-gray-900 text-white text-xs font-medium rounded-xl hover:bg-gray-700 transition-all duration-200 mt-4">
                            <i class="ti ti-edit text-sm"></i> Lengkapi Sekarang
                        </a>
                    </div>
                @endif

                {{-- Recommended Events Card --}}
                @if ($recommendedEvents->count() > 0)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-bold text-gray-900">Event Rekomendasi</p>
                            <a href="{{ route('events.index') }}"
                                class="text-xs text-gray-400 hover:text-gray-700 transition">
                                Lihat semua →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($recommendedEvents as $event)
                                <a href="{{ route('events.show', $event->slug) }}" class="flex gap-3 group">
                                    <div class="w-14 h-12 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                        @if ($event->thumbnail)
                                            <img src="{{ Storage::url($event->thumbnail) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="ti ti-calendar-event text-xl text-gray-300"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-xs font-semibold text-gray-900 line-clamp-2 leading-snug group-hover:text-gray-700 transition-colors duration-200">
                                            {{ $event->title }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $event->start_date->format('d M Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-auth-layout>
