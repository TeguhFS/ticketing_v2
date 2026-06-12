<aside x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false"
    class="w-64 min-w-[256px] bg-white border-r border-gray-100 flex flex-col h-screen sticky top-0 z-40">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden bg-gray-900">

            @if (setting('app_logo'))
                <img src="{{ Storage::url(setting('app_logo')) }}" alt="{{ setting('app_name', 'TicketIn') }}"
                    class="w-full h-full object-cover">
            @else
                <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            @endif

        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ setting('app_name', 'TicketIn') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">User Panel</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5">

        {{-- Main --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Main</p>
            <x-user-nav-link route="user.dashboard" icon="ti-layout-dashboard" label="Dashboard" />
        </div>

        {{-- Management --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Management</p>
            <x-user-nav-link route="user.tickets.index" :active="request()->routeIs('user.tickets*')" icon="ti-calendar-event" label="Ticket" />
            <x-user-nav-link route="user.orders.index" :active="request()->routeIs('user.orders*')" icon="ti-tag" label="Order" />
            <x-user-nav-link route="user.refunds.index" :active="request()->routeIs('user.refunds*')" icon="ti-refresh" label="Refund" />
        </div>

        {{-- Profile --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Profile</p>
            <x-user-nav-link route="user.profile.edit" :active="request()->routeIs('user.profile.*')" icon="ti-users" label="Informasi Diri" />
        </div>


        {{-- Website --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Website</p>
            <x-user-nav-link route="welcome" icon="ti-arrow-left" label="Kembali ke Beranda" />
        </div>
    </nav>

    {{-- Footer with Dropdown Logout --}}
    <div class="px-3 py-3 border-t border-gray-100 relative">

        {{-- Dropdown Menu (Flat Modern Style) --}}
        <div x-show="userMenuOpen" x-transition x-cloak
            class="absolute bottom-[calc(100%-4px)] left-3 right-3 bg-white border border-gray-200 rounded-xl overflow-hidden z-50 mb-2">
            <div class="p-1.5 bg-gray-50/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 transition-all duration-150 group/btn">
                        <i class="ti ti-logout text-base text-red-400 group-hover/btn:text-red-600"></i>
                        <span>Logout / Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Trigger Button --}}
        <button @click="userMenuOpen = !userMenuOpen" type="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors duration-150 text-left group"
            :class="userMenuOpen ? 'bg-gray-50' : ''">
            <div
                class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                @if (auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}"
                        class="w-8 h-8 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-100" alt="">
                @else
                    <div
                        class="w-6 h-6 rounded-full bg-gray-900 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0 tracking-tight">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->email }}</p>
            </div>
            <i class="ti ti-chevron-up text-gray-400 text-sm transition-transform duration-200"
                :class="userMenuOpen ? 'rotate-180 text-gray-600' : ''"></i>
        </button>

    </div>

</aside>
