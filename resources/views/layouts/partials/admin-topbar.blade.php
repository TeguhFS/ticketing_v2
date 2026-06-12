<header class="h-[60px] bg-white border-b border-gray-100 flex items-center px-6 gap-4 sticky top-0 z-10">

    {{-- Page Title & Breadcrumb --}}
    <div class="flex-1">
        <h1 class="text-sm font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
        <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-600">Home</a>
            <span>›</span>
            <span>{{ $title ?? 'Dashboard' }}</span>
        </p>
    </div>

    {{-- Search --}}
    <div class="relative" x-data="adminSearch()" @click.away="close()">

        {{-- Search Input --}}
        <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg
        px-3 h-9 w-56 transition-all duration-200"
            :class="focused ? 'border-gray-300 bg-white shadow-sm w-72' : ''">
            <i class="ti ti-search text-gray-400 text-sm flex-shrink-0" :class="loading ? 'animate-pulse' : ''"></i>
            <input type="text" x-model="query" x-ref="searchInput"
                @focus="focused = true; open = true; if(query.length >= 2) search()" @input.debounce.400ms="search()"
                @keydown.escape="close()" @keydown.arrow-down.prevent="moveDown()" @keydown.arrow-up.prevent="moveUp()"
                @keydown.enter.prevent="selectActive()" placeholder="Cari order, event, user..."
                class="bg-transparent border-none outline-none text-sm text-gray-700
                w-full placeholder-gray-400">

            {{-- Clear --}}
            <button x-show="query.length > 0" @click="clearSearch()" x-transition
                class="text-gray-300 hover:text-gray-500 transition flex-shrink-0">
                <i class="ti ti-x text-xs"></i>
            </button>

            {{-- Keyboard shortcut hint --}}
            <span x-show="!focused && query.length === 0"
                class="text-[16px] text-gray-300 bg-gray-100 px-1.5 py-0.5 rounded
                font-mono flex-shrink-0">
                K
            </span>
        </div>

        {{-- Dropdown Results --}}
        <div x-show="open && focused" x-cloak x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute top-full right-0 mt-2 w-80 bg-white border border-gray-100
            rounded-2xl shadow-xl shadow-gray-100/50 z-50 overflow-hidden">

            {{-- Loading --}}
            <div x-show="loading" class="flex items-center justify-center py-8">
                <svg class="animate-spin w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>

            {{-- Empty --}}
            <div x-show="!loading && results.total === 0 && query.length >= 2" class="text-center py-8 px-4">
                <i class="ti ti-search text-3xl text-gray-200 block mb-2"></i>
                <p class="text-sm font-medium text-gray-600">Tidak ditemukan</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    Tidak ada hasil untuk "<span x-text="query"></span>"
                </p>
            </div>

            {{-- Min chars hint --}}
            <div x-show="!loading && query.length > 0 && query.length < 2" class="text-center py-6 px-4">
                <p class="text-xs text-gray-400">Ketik minimal 2 karakter...</p>
            </div>

            {{-- Quick shortcuts (when empty) --}}
            <div x-show="open && focused && !loading && query.trim().length === 0" x-transition x-cloak class="p-3">
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-2 mb-2">
                    Akses Cepat
                </p>
                <div class="space-y-0.5">
                    @foreach ([['href' => route('admin.orders.index'), 'icon' => 'ti-shopping-cart', 'label' => 'Semua Orders', 'shortcut' => ''], ['href' => route('admin.tickets.index'), 'icon' => 'ti-ticket', 'label' => 'Semua Tiket', 'shortcut' => ''], ['href' => route('admin.payments.index'), 'icon' => 'ti-credit-card', 'label' => 'Pembayaran', 'shortcut' => ''], ['href' => route('admin.refunds.index'), 'icon' => 'ti-refresh', 'label' => 'Refund', 'shortcut' => ''], ['href' => route('admin.events.index'), 'icon' => 'ti-calendar-event', 'label' => 'Events', 'shortcut' => ''], ['href' => route('admin.users.index'), 'icon' => 'ti-users', 'label' => 'Users', 'shortcut' => '']] as $shortcut)
                        <a href="{{ $shortcut['href'] }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm
                        text-gray-600 hover:bg-gray-50 hover:text-gray-900
                        transition-all duration-150">
                            <i class="ti {{ $shortcut['icon'] }} text-gray-400 text-base w-5 text-center"></i>
                            {{ $shortcut['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Results --}}
            <div x-show="!loading && results.total > 0" class="p-2">

                {{-- Orders --}}
                <template x-if="results.orders && results.orders.length > 0">
                    <div class="mb-2">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-3 py-1.5">
                            Orders (<span x-text="results.orders.length"></span>)
                        </p>
                        <template x-for="(item, i) in results.orders" :key="'order-' + i">
                            <a :href="item.url"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                                transition-all duration-150 group"
                                :class="activeIndex === getIndex('orders', i) ?
                                    'bg-gray-900 text-white' :
                                    'text-gray-700 hover:bg-gray-50'">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    :class="activeIndex === getIndex('orders', i) ? 'bg-white/10' : 'bg-gray-100'">
                                    <i class="ti ti-shopping-cart text-sm"
                                        :class="activeIndex === getIndex('orders', i) ? 'text-white' : 'text-gray-500'"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold truncate" x-text="item.title"></p>
                                    <p class="text-[10px] truncate mt-0.5"
                                        :class="activeIndex === getIndex('orders', i) ? 'text-gray-300' : 'text-gray-400'"
                                        x-text="item.subtitle"></p>
                                </div>
                                <span class="text-[10px] font-semibold flex-shrink-0 px-2 py-0.5 rounded-full"
                                    :class="getBadgeClass(item.status, activeIndex === getIndex('orders', i))"
                                    x-text="item.status">
                                </span>
                            </a>
                        </template>
                    </div>
                </template>

                {{-- Payments --}}
                <template x-if="results.payments && results.payments.length > 0">
                    <div class="mb-2">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-3 py-1.5">
                            Pembayaran (<span x-text="results.payments.length"></span>)
                        </p>
                        <template x-for="(item, i) in results.payments" :key="'payment-' + i">
                            <a :href="item.url"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                                transition-all duration-150"
                                :class="activeIndex === getIndex('payments', i) ?
                                    'bg-gray-900 text-white' :
                                    'text-gray-700 hover:bg-gray-50'">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    :class="activeIndex === getIndex('payments', i) ? 'bg-white/10' : 'bg-gray-100'">
                                    <i class="ti ti-credit-card text-sm"
                                        :class="activeIndex === getIndex('payments', i) ? 'text-white' : 'text-gray-500'"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold truncate" x-text="item.title"></p>
                                    <p class="text-[10px] truncate mt-0.5"
                                        :class="activeIndex === getIndex('payments', i) ? 'text-gray-300' : 'text-gray-400'"
                                        x-text="item.subtitle"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>

                {{-- Tickets --}}
                <template x-if="results.tickets && results.tickets.length > 0">
                    <div class="mb-2">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-3 py-1.5">
                            Tiket (<span x-text="results.tickets.length"></span>)
                        </p>
                        <template x-for="(item, i) in results.tickets" :key="'ticket-' + i">
                            <a :href="item.url"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                                transition-all duration-150"
                                :class="activeIndex === getIndex('tickets', i) ?
                                    'bg-gray-900 text-white' :
                                    'text-gray-700 hover:bg-gray-50'">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    :class="activeIndex === getIndex('tickets', i) ? 'bg-white/10' : 'bg-gray-100'">
                                    <i class="ti ti-ticket text-sm"
                                        :class="activeIndex === getIndex('tickets', i) ? 'text-white' : 'text-gray-500'"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold truncate font-mono" x-text="item.title"></p>
                                    <p class="text-[10px] truncate mt-0.5"
                                        :class="activeIndex === getIndex('tickets', i) ? 'text-gray-300' : 'text-gray-400'"
                                        x-text="item.subtitle"></p>
                                </div>
                                <span class="text-[10px] font-semibold flex-shrink-0 px-2 py-0.5 rounded-full"
                                    :class="getBadgeClass(item.status, activeIndex === getIndex('tickets', i))"
                                    x-text="item.status">
                                </span>
                            </a>
                        </template>
                    </div>
                </template>

                {{-- Events --}}
                <template x-if="results.events && results.events.length > 0">
                    <div class="mb-2">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-3 py-1.5">
                            Event (<span x-text="results.events.length"></span>)
                        </p>
                        <template x-for="(item, i) in results.events" :key="'event-' + i">
                            <a :href="item.url"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                                transition-all duration-150"
                                :class="activeIndex === getIndex('events', i) ?
                                    'bg-gray-900 text-white' :
                                    'text-gray-700 hover:bg-gray-50'">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    :class="activeIndex === getIndex('events', i) ? 'bg-white/10' : 'bg-gray-100'">
                                    <i class="ti ti-calendar-event text-sm"
                                        :class="activeIndex === getIndex('events', i) ? 'text-white' : 'text-gray-500'"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold truncate" x-text="item.title"></p>
                                    <p class="text-[10px] truncate mt-0.5"
                                        :class="activeIndex === getIndex('events', i) ? 'text-gray-300' : 'text-gray-400'"
                                        x-text="item.subtitle"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>

                {{-- Users --}}
                <template x-if="results.users && results.users.length > 0">
                    <div class="mb-2">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-3 py-1.5">
                            Users (<span x-text="results.users.length"></span>)
                        </p>
                        <template x-for="(item, i) in results.users" :key="'user-' + i">
                            <a :href="item.url"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                                transition-all duration-150"
                                :class="activeIndex === getIndex('users', i) ?
                                    'bg-gray-900 text-white' :
                                    'text-gray-700 hover:bg-gray-50'">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                text-[10px] font-bold"
                                    :class="activeIndex === getIndex('users', i) ?
                                        'bg-white/20 text-white' :
                                        'bg-gray-900 text-white'"
                                    x-text="item.avatar">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold truncate" x-text="item.title"></p>
                                    <p class="text-[10px] truncate mt-0.5"
                                        :class="activeIndex === getIndex('users', i) ? 'text-gray-300' : 'text-gray-400'"
                                        x-text="item.subtitle"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>

                {{-- View all results --}}
                <div class="border-t border-gray-50 mt-1 pt-2 px-2">
                    <a :href="'{{ route('admin.orders.index') }}?search=' + encodeURIComponent(query)"
                        class="flex items-center justify-center gap-2 h-9 text-xs font-medium
                        text-gray-500 hover:text-gray-900 hover:bg-gray-50
                        rounded-xl transition-all duration-150">
                        <i class="ti ti-search text-sm"></i>
                        Lihat semua hasil untuk "<span x-text="query" class="font-semibold"></span>"
                    </a>
                </div>

            </div>

        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-2">

        {{-- ── Notification Bell ─────────────────────────────────────── --}}
        <div class="relative" x-data="notificationBell()" x-init="init()" @click.away="open = false">

            {{-- ── Bell Trigger Button ── --}}
            <button @click="toggle()"
                class="w-9 h-9 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition relative">
                <i class="ti ti-bell text-base"></i>

                {{-- Badge Counter --}}
                <span x-show="count > 0" x-cloak x-transition
                    class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 border-2 border-white"
                    x-text="count > 99 ? '99+' : count">
                </span>
            </button>

            {{-- ── Dropdown Panel ── --}}
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-full mt-2 w-80 bg-white border border-gray-100 rounded-2xl shadow-xl shadow-gray-100/50 z-50 overflow-hidden">

                {{-- Panel Header (Sudah Dirapikan & Responsif) --}}
                <div class="px-4 py-3.5 border-b border-gray-50 bg-white">
                    {{-- Baris Atas: Judul & Badge Jumlah --}}
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-gray-900">Notifikasi</p>
                            <span x-show="count > 0" x-cloak
                                class="text-[10px] font-bold bg-red-50 text-red-500 px-2 py-0.5 rounded-full border border-red-100">
                                <span x-text="count"></span> baru
                            </span>
                        </div>
                    </div>

                    {{-- Baris Bawah: Tombol Aksi Kanan-Kiri --}}
                    <div class="flex items-center justify-between pt-1">
                        <button @click="markAllRead()" x-show="count > 0" x-cloak
                            class="text-xs text-blue-500 hover:text-blue-600 font-medium transition flex items-center gap-1 active:scale-95 transform duration-150">
                            <i class="ti ti-checks text-sm"></i>
                            Tandai semua dibaca
                        </button>
                        <div x-show="count === 0" class="text-xs text-gray-400">
                            Aktivitas terbaru
                        </div>

                        <a href="{{ route('admin.notifications.index') }}"
                            class="text-xs text-gray-400 hover:text-gray-600 transition flex items-center gap-0.5 font-medium ml-auto">
                            Lihat semua
                            <i class="ti ti-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Panel Body (List Area) --}}
                <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 no-scrollbar">

                    {{-- State: Loading --}}
                    <div x-show="loading" class="flex items-center justify-center py-8">
                        <svg class="animate-spin w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>

                    {{-- State: Empty --}}
                    <div x-show="!loading && notifications.length === 0" class="text-center py-10 px-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="ti ti-bell-off text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600">Tidak ada notifikasi</p>
                        <p class="text-xs text-gray-400 mt-0.5">Semua aktivitas akan muncul di sini</p>
                    </div>

                    {{-- State: Render Items --}}
                    <template x-for="notif in notifications" :key="notif.id">
                        <div class="flex items-start gap-3 px-4 py-3.5 hover:bg-gray-50/50 transition-colors duration-150 cursor-pointer group relative"
                            :class="!notif.read_at ? 'bg-blue-50/30' : ''" @click="openNotif(notif)">

                            {{-- Item Icon --}}
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                                :class="notif.bg">
                                <i class="ti text-base" :class="[notif.icon, notif.color]"></i>
                            </div>

                            {{-- Item Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900" x-text="notif.title"></p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 leading-relaxed"
                                    x-text="notif.message"></p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <p class="text-[10px] text-gray-400" x-text="notif.created_at"></p>
                                    <template x-if="notif.amount">
                                        <span class="text-[10px] font-semibold text-gray-600"
                                            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(notif.amount)">
                                        </span>
                                    </template>
                                </div>
                            </div>

                            {{-- Item Unread Dot Indicator --}}
                            <div x-show="!notif.read_at"
                                class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></div>

                            {{-- Item Action: Delete Button --}}
                            <button @click.stop="deleteNotif(notif.id)"
                                class="absolute top-3 right-3 w-5 h-5 flex items-center justify-center text-gray-300 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-150">
                                <i class="ti ti-x text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Panel Footer --}}
                <div x-show="!loading && notifications.length > 0"
                    class="border-t border-gray-50 px-4 py-3 flex items-center justify-between">
                    <a href="{{ route('admin.notifications.index') }}"
                        class="text-xs text-gray-500 hover:text-gray-900 transition font-medium flex items-center gap-1">
                        Lihat semua notifikasi
                        <i class="ti ti-arrow-right text-xs"></i>
                    </a>
                    <button @click="clearAll()"
                        class="text-xs text-red-400 hover:text-red-600 transition font-medium">
                        Hapus semua
                    </button>
                </div>

            </div>
        </div>

        <div class="w-px h-5 bg-gray-100"></div>

        {{-- User Dropdown --}}
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center
                    text-white text-xs font-semibold hover:bg-gray-700 transition">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </button>
            </x-slot>
            <x-slot name="content">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="ti ti-logout mr-2"></i> Log Out
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>

    </div>
</header>

@push('scripts')
    <script>
        function adminSearch() {
            return {
                query: '',
                open: false,
                focused: false,
                loading: false,
                results: {
                    orders: [],
                    payments: [],
                    tickets: [],
                    events: [],
                    users: [],
                    total: 0
                },
                activeIndex: -1,
                allItems: [],

                async search() {
                    if (this.query.length < 2) {
                        this.open = false;
                        this.results = {
                            orders: [],
                            payments: [],
                            tickets: [],
                            events: [],
                            users: [],
                            total: 0
                        };
                        return;
                    }

                    this.loading = true;
                    this.open = true;

                    try {
                        const res = await fetch(
                            `{{ route('admin.search') }}?q=${encodeURIComponent(this.query)}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }
                        );
                        const data = await res.json();
                        this.results = data;
                        this.activeIndex = -1;
                        this.buildAllItems();
                    } catch (e) {
                        console.error('Search error:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                buildAllItems() {
                    this.allItems = [
                        ...(this.results.orders || []).map((item, i) => ({
                            group: 'orders',
                            index: i,
                            url: item.url
                        })),
                        ...(this.results.payments || []).map((item, i) => ({
                            group: 'payments',
                            index: i,
                            url: item.url
                        })),
                        ...(this.results.tickets || []).map((item, i) => ({
                            group: 'tickets',
                            index: i,
                            url: item.url
                        })),
                        ...(this.results.events || []).map((item, i) => ({
                            group: 'events',
                            index: i,
                            url: item.url
                        })),
                        ...(this.results.users || []).map((item, i) => ({
                            group: 'users',
                            index: i,
                            url: item.url
                        })),
                    ];
                },

                getIndex(group, itemIndex) {
                    return this.allItems.findIndex(item => item.group === group && item.index === itemIndex);
                },

                moveDown() {
                    if (!this.open) return;
                    this.activeIndex = Math.min(this.activeIndex + 1, this.allItems.length - 1);
                },

                moveUp() {
                    if (!this.open) return;
                    this.activeIndex = Math.max(this.activeIndex - 1, -1);
                },

                selectActive() {
                    if (this.activeIndex >= 0 && this.allItems[this.activeIndex]) {
                        window.location.href = this.allItems[this.activeIndex].url;
                    } else if (this.query.length >= 2) {
                        window.location.href =
                            `{{ route('admin.orders.index') }}?search=${encodeURIComponent(this.query)}`;
                    }
                },

                getBadgeClass(status, isActive) {
                    if (isActive) return 'bg-white/20 text-white';
                    const map = {
                        'pending': 'bg-amber-100 text-amber-700',
                        'paid': 'bg-emerald-100 text-emerald-700',
                        'cancelled': 'bg-red-100 text-red-600',
                        'expired': 'bg-gray-100 text-gray-500',
                        'active': 'bg-emerald-100 text-emerald-700',
                        'used': 'bg-blue-100 text-blue-600',
                        'published': 'bg-emerald-100 text-emerald-700',
                        'draft': 'bg-amber-100 text-amber-700',
                    };
                    return map[status] || 'bg-gray-100 text-gray-500';
                },

                clearSearch() {
                    this.query = '';
                    this.open = false;
                    this.results = {
                        orders: [],
                        payments: [],
                        tickets: [],
                        events: [],
                        users: [],
                        total: 0
                    };
                    this.activeIndex = -1;
                    this.$refs.searchInput.focus();
                },

                close() {
                    this.open = false;
                    this.focused = false;
                },
            }
        }

        // Global keyboard shortcut ⌘K / Ctrl+K
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                const input = document.querySelector('[x-ref="searchInput"]');
                if (input) {
                    input.focus();
                    input._x_model && input.dispatchEvent(new Event('focus'));
                }
            }
        });

        function notificationBell() {
            return {
                open: false,
                loading: false,
                notifications: [],
                count: 0,
                pollInterval: null,

                init() {
                    this.fetchNotifications();
                    // Auto-refresh every 30 seconds
                    this.pollInterval = setInterval(() => {
                        this.fetchNotifications(false);
                    }, 30000);
                },

                async fetchNotifications(showLoading = true) {
                    if (showLoading) this.loading = true;
                    try {
                        const res = await fetch('{{ route('admin.notifications.unread') }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await res.json();
                        this.notifications = data.notifications;
                        this.count = data.count;
                    } catch (e) {
                        console.error('Failed to fetch notifications:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                async toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        await this.fetchNotifications();
                    }
                },

                async markAllRead() {
                    await fetch('{{ route('admin.notifications.read') }}', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    this.notifications = this.notifications.map(n => ({
                        ...n,
                        read_at: new Date().toISOString()
                    }));
                    this.count = 0;
                },

                async openNotif(notif) {
                    // Mark as read
                    if (!notif.read_at) {
                        await fetch('{{ route('admin.notifications.read') }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                id: notif.id
                            }),
                        });
                        notif.read_at = new Date().toISOString();
                        this.count = Math.max(0, this.count - 1);
                    }
                    // Navigate
                    if (notif.url && notif.url !== '#') {
                        window.location.href = notif.url;
                    }
                    this.open = false;
                },

                async deleteNotif(id) {
                    const wasUnread = this.notifications.find(n => n.id === id && !n.read_at);
                    await fetch(`{{ url('admin/notifications') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    this.notifications = this.notifications.filter(n => n.id !== id);
                    if (wasUnread) this.count = Math.max(0, this.count - 1);
                },

                async clearAll() {
                    if (!confirm('Hapus semua notifikasi?')) return;
                    await fetch('{{ route('admin.notifications.destroyAll') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    this.notifications = [];
                    this.count = 0;
                    this.open = false;
                },
            }
        }
    </script>
@endpush
