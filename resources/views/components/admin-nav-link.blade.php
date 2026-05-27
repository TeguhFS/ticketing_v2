@php
    $isActive = $active ?? request()->routeIs($route);
    $badgeClass = $badgeColor === 'yellow' ? 'bg-amber-400 text-amber-900' : 'bg-red-500 text-white';
@endphp

<a href="{{ route($route) }}"
    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm mb-0.5 transition-all
          {{ $isActive ? 'bg-gray-900 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
    <i
        class="ti {{ $icon }} text-base w-5 text-center
               {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
    <span class="flex-1">{{ $label }}</span>
    @if ($badge > 0)
        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full {{ $badgeClass }}">
            {{ $badge }}
        </span>
    @endif
</a>
