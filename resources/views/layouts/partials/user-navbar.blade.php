<header x-data="navbar()" x-init="init()" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18">

            {{-- ── Left: Logo --}}
            <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 flex-shrink-0 group">
                <div
                    class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center
                    group-hover:bg-gray-700 transition-colors duration-200">
                    @if (setting('app_logo'))
                        <img src="{{ Storage::url(setting('app_logo')) }}" class="w-6 h-6 object-contain" alt="Logo">
                    @else
                        <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    @endif
                </div>
                <span
                    class="text-base font-bold text-gray-900 tracking-tight
                    group-hover:text-gray-700 transition-colors duration-200">
                    {{ setting('app_name', config('app.name')) }}
                </span>
            </a>

            {{-- Menggunakan global state 'searchExpanded' untuk mengatur max-width secara halus --}}
            <div class="hidden lg:flex items-center justify-center flex-1 transition-all duration-300 overflow-hidden"
                :class="searchExpanded ? 'max-w-xs xl:max-w-md' : 'max-w-2xl'">
                <nav class="flex items-center gap-1 w-full justify-center">
                    @foreach ([['route' => 'welcome', 'label' => 'Home'], ['route' => 'events.index', 'label' => 'Jelajahi Tiket'], ['route' => 'blogs.index', 'label' => 'Blog'], ['route' => 'faqs.index', 'label' => 'FAQ']] as $nav)
                        <a href="{{ route($nav['route']) }}"
                            class="relative px-4 py-2 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap
                            {{ request()->routeIs($nav['route'])
                                ? 'text-gray-900 bg-gray-100'
                                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                            {{ $nav['label'] }}
                            @if (request()->routeIs($nav['route']))
                                <span
                                    class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-gray-900 rounded-full"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- ── Right: Search + Auth ─────────────────────────── --}}
            <div class="flex items-center gap-2 flex-shrink-0 justify-end">

                {{-- Search Bar (Desktop) --}}
                <div class="hidden lg:flex items-center">

                    {{-- Collapsed state --}}
                    <div x-show="!searchExpanded" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95">
                        <button @click="expandSearch()"
                            class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center
                            text-gray-400 hover:bg-gray-50 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                            <i class="ti ti-search text-base"></i>
                        </button>
                    </div>

                    {{-- Expanded state dengan transisi lebar CSS asli untuk kelancaran performa --}}
                    <div x-show="searchExpanded" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="relative" @click.away="collapseSearch()">
                        <form action="{{ route('events.index') }}" method="GET">
                            <div
                                class="flex items-center gap-2 bg-gray-50 border border-gray-200
                                focus-within:border-gray-400 focus-within:bg-white
                                rounded-xl px-3 h-10 w-72 transition-all duration-300">
                                <i class="ti ti-search text-gray-400 text-sm flex-shrink-0"></i>
                                <input type="text" name="search" x-ref="searchInput"
                                    placeholder="Cari event, konser, seminar..."
                                    class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700
                                    placeholder-gray-400 w-full">
                                <button type="button" @click="collapseSearch()"
                                    class="text-gray-300 hover:text-gray-500 transition flex-shrink-0">
                                    <i class="ti ti-x text-sm"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- Divider --}}
                <div class="hidden lg:block w-px h-5 bg-gray-200"></div>

                {{-- Auth Buttons --}}
                @guest
                    <div class="hidden lg:flex items-center gap-2">
                        <a href="{{ route('login') }}"
                            class="h-9 px-4 text-sm font-medium text-gray-600 rounded-xl
                            flex items-center justify-center hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="h-9 px-4 bg-gray-900 text-white text-sm font-medium rounded-xl
                            flex items-center justify-center hover:bg-gray-700 transition-all duration-200">
                            Daftar
                        </a>
                    </div>
                @endguest

                @auth
                    {{-- Profile Dropdown --}}
                    <div class="hidden lg:block relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2.5 h-9 pl-2 pr-3 border border-gray-200 rounded-xl
                            hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                            @if (auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                    class="w-6 h-6 rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <div
                                    class="w-6 h-6 rounded-full bg-gray-900 flex items-center justify-center
                                    text-white text-[10px] font-semibold flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-700 max-w-[100px] truncate">
                                {{ auth()->user()->name }}
                            </span>
                            <i class="ti ti-chevron-down text-gray-400 text-sm transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 top-full mt-2 w-52 bg-white border border-gray-100
                            rounded-2xl shadow-lg shadow-gray-100/50 overflow-hidden z-50">

                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="p-1.5">
                                @foreach ([['route' => 'user.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'], ['route' => 'user.tickets', 'icon' => 'ti-ticket', 'label' => 'Tiket Saya'], ['route' => 'user.orders', 'icon' => 'ti-shopping-cart', 'label' => 'Pesanan Saya'], ['route' => 'profile.edit', 'icon' => 'ti-user', 'label' => 'Profil']] as $item)
                                    <a href="{{ route($item['route']) }}"
                                        class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150
                                        {{ request()->routeIs($item['route'])
                                            ? 'bg-gray-100 text-gray-900 font-medium'
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i
                                            class="ti {{ $item['icon'] }} text-base {{ request()->routeIs($item['route']) ? 'text-gray-900' : 'text-gray-400' }}"></i>
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>

                            <div class="p-1.5 border-t border-gray-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm
                                        text-red-500 hover:bg-red-50 hover:text-red-600 transition-all duration-150">
                                        <i class="ti ti-logout text-base"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Mobile: Search Icon --}}
                <button @click="mobileSearch = !mobileSearch"
                    class="lg:hidden w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center
                    text-gray-500 hover:bg-gray-50 transition-all duration-200">
                    <i class="ti ti-search text-base"></i>
                </button>

                {{-- Mobile: Hamburger --}}
                <button @click="mobileMenu = !mobileMenu"
                    class="lg:hidden w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center
                    text-gray-500 hover:bg-gray-50 transition-all duration-200">
                    <i class="ti text-base transition-all duration-200" :class="mobileMenu ? 'ti-x' : 'ti-menu-2'"></i>
                </button>

            </div>
        </div>

        {{-- Mobile Search Bar --}}
        <div x-show="mobileSearch" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="lg:hidden pb-3">
            <form action="{{ route('events.index') }}" method="GET">
                <div
                    class="flex items-center gap-2 bg-gray-50 border border-gray-200 focus-within:border-gray-400 focus-within:bg-white rounded-xl px-3 h-11 transition-all duration-200">
                    <i class="ti ti-search text-gray-400 text-sm flex-shrink-0"></i>
                    <input type="text" name="search" autofocus placeholder="Cari event, konser, seminar..."
                        class="bg-transparent border-none outline-none text-sm text-gray-700 placeholder-gray-400 w-full">
                </div>
            </form>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-gray-100 bg-white/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 py-3 space-y-0.5">
            @foreach ([['route' => 'welcome', 'icon' => 'ti-home', 'label' => 'Home'], ['route' => 'events.index', 'icon' => 'ti-ticket', 'label' => 'Jelajahi Tiket'], ['route' => 'blogs.index', 'icon' => 'ti-news', 'label' => 'Blog'], ['route' => 'faqs.index', 'icon' => 'ti-help-circle', 'label' => 'FAQ']] as $nav)
                <a href="{{ route($nav['route']) }}" @click="mobileMenu = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
                    {{ request()->routeIs($nav['route']) ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i
                        class="ti {{ $nav['icon'] }} text-base {{ request()->routeIs($nav['route']) ? 'text-gray-900' : 'text-gray-400' }}"></i>
                    {{ $nav['label'] }}
                </a>
            @endforeach

            <div class="pt-2 border-t border-gray-100 mt-2">
                @guest
                    <div class="grid grid-cols-2 gap-2 w-full">
                        <a href="{{ route('login') }}"
                            class="h-10 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl flex items-center justify-center hover:bg-gray-50 transition-all duration-200">Login</a>
                        <a href="{{ route('register') }}"
                            class="h-10 bg-gray-900 text-white text-sm font-medium rounded-xl flex items-center justify-center hover:bg-gray-700 transition-all duration-200">Daftar</a>
                    </div>
                @endguest

                @auth
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl mb-2">
                            @if (auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                    class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        @foreach ([['route' => 'user.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'], ['route' => 'user.tickets', 'icon' => 'ti-ticket', 'label' => 'Tiket Saya'], ['route' => 'user.orders', 'icon' => 'ti-shopping-cart', 'label' => 'Pesanan Saya'], ['route' => 'profile.edit', 'icon' => 'ti-user', 'label' => 'Profil']] as $item)
                            <a href="{{ route($item['route']) }}" @click="mobileMenu = false"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all duration-150">
                                <i class="ti {{ $item['icon'] }} text-base text-gray-400"></i>
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50 transition-all duration-150">
                                <i class="ti ti-logout text-base"></i> Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

<div class="h-16 lg:h-18"></div>

@push('scripts')
    <script>
        function navbar() {
            return {
                scrolled: false,
                mobileMenu: false,
                mobileSearch: false,
                searchExpanded: false,

                init() {
                    this.scrolled = window.scrollY > 10;
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 10;
                    });
                },

                expandSearch() {
                    this.searchExpanded = true;
                    this.$nextTick(() => {
                        const input = document.querySelector('input[name="search"]');
                        input?.focus();
                    });
                },

                collapseSearch() {
                    this.searchExpanded = false;
                }
            }
        }
    </script>
@endpush
