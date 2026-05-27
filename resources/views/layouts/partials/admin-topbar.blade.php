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
    <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg px-3 h-9 w-56">
        <i class="ti ti-search text-gray-400 text-sm"></i>
        <input type="text" placeholder="Search anything..."
            class="bg-transparent border-none outline-none focus:outline-none focus:ring-0 text-sm text-gray-700 w-full placeholder-gray-400">
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-2">

        {{-- Notifikasi --}}
        <div class="relative">
            <button
                class="w-9 h-9 border border-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                <i class="ti ti-bell text-base"></i>
            </button>
            <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full border-2 border-white"></span>
        </div>

        <div class="w-px h-5 bg-gray-100"></div>

        {{-- User Dropdown --}}
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold hover:bg-gray-700 transition">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </button>
            </x-slot>
            <x-slot name="content">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="ti ti-logout mr-2"></i> Log Out
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>

    </div>
</header>
