<aside class="w-64 min-w-[256px] bg-white border-r border-gray-100 flex flex-col h-screen sticky top-0">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ setting('app_name', 'TicketIn') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Admin Panel</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5">

        {{-- Main --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Main</p>
            <x-admin-nav-link route="admin.dashboard" icon="ti-layout-dashboard" label="Dashboard" />
        </div>

        {{-- Management --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Management</p>
            <x-admin-nav-link route="admin.events.index" :active="request()->routeIs('admin.events.*')" icon="ti-calendar-event" label="Event" />
            <x-admin-nav-link route="admin.categories.index" :active="request()->routeIs('admin.categories.*')" icon="ti-tag" label="Kategori" />
            <x-admin-nav-link route="admin.orders.index" :active="request()->routeIs('admin.orders.*')" icon="ti-shopping-cart" label="Order"
                :badge="$pendingOrders ?? 0" />
            <x-admin-nav-link route="admin.tickets.index" :active="request()->routeIs('admin.tickets.*')" icon="ti-ticket" label="Ticket" />
            <x-admin-nav-link route="admin.transactions.index" :active="request()->routeIs('admin.transactions.*')" icon="ti-exchange"
                label="Transaction" />
            <x-admin-nav-link route="admin.payments.index" icon="ti-credit-card" label="Payment" :badge="$pendingPayments ?? 0"
                badgeColor="yellow" />
        </div>

        {{-- Content --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Content</p>
            <x-admin-nav-link route="admin.blogs.index" :active="request()->routeIs('admin.blogs.*')" icon="ti-news" label="Blog" />
            <x-admin-nav-link route="admin.faqs.index" :active="request()->routeIs('admin.faqs.*')" icon="ti-help-circle" label="FAQ" />
        </div>

        {{-- People --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">People</p>
            <x-admin-nav-link route="admin.users.index" :active="request()->routeIs('admin.users.*')" icon="ti-users" label="Users" />
            <x-admin-nav-link route="admin.officers.index" :active="request()->routeIs('admin.officers.*')" icon="ti-id-badge"
                label="Field Officers" />
        </div>

        {{-- System --}}
        <div>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">System</p>
            <x-admin-nav-link route="admin.payments.methods" icon="ti-building-bank" label="Payment Methods" />
            <x-admin-nav-link route="admin.settings.index" :active="request()->routeIs('admin.settings.*')" icon="ti-settings" label="Settings" />
        </div>

    </nav>

    {{-- Footer --}}
    <div class="px-3 py-3 border-t border-gray-100">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 cursor-pointer group">
            <div
                class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>

</aside>
