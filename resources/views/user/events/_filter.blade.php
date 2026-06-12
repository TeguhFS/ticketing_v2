<div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
        <p class="text-sm font-semibold text-gray-900 flex items-center gap-2">
            <i class="ti ti-filter text-base text-gray-400"></i> Filter
        </p>
        @if (request()->anyFilled(['category', 'price', 'date', 'featured']))
            <a href="{{ route('events.index', request()->only('search', 'sort')) }}"
                class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                Reset
            </a>
        @endif
    </div>

    <div class="p-4 space-y-5">

        {{-- Kategori --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kategori</p>
            <div class="space-y-1">
                <a href="{{ route('events.index', request()->except('category', 'page')) }}"
                    class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-all duration-150
                        {{ !request('category')
                            ? 'bg-gray-900 text-white font-medium'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>Semua Kategori</span>
                    <span class="text-xs {{ !request('category') ? 'text-gray-400' : 'text-gray-400' }}">
                        {{ number_format($totalEvents ?? 0) }}
                    </span>
                </a>
                @foreach ($categories as $category)
                    @if ($category->events_count > 0)
                        <a href="{{ route('events.index', array_merge(request()->except('category', 'page'), ['category' => $category->id])) }}"
                            class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-all duration-150
                        {{ request('category') == $category->id
                            ? 'bg-gray-900 text-white font-medium'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="flex items-center gap-2">
                                @if ($category->image)
                                    <img src="{{ Storage::url($category->image) }}"
                                        class="w-5 h-5 rounded-md object-cover flex-shrink-0" alt="">
                                @endif
                                {{ $category->name }}
                            </span>
                            <span
                                class="text-xs {{ request('category') == $category->id ? 'text-gray-400' : 'text-gray-400' }}">
                                {{ $category->events_count }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Harga --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Harga</p>
            <div class="space-y-1">
                @foreach ([
        '' => 'Semua Harga',
        'free' => 'Gratis',
        'paid' => 'Berbayar',
        'under100' => 'Di bawah Rp 100rb',
        'under500' => 'Di bawah Rp 500rb',
    ] as $val => $label)
                    <a href="{{ route('events.index', array_merge(request()->except('price', 'page'), $val ? ['price' => $val] : [])) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-150
                        {{ request('price', '') === $val
                            ? 'bg-gray-900 text-white font-medium'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div
                            class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                        {{ request('price', '') === $val ? 'border-white' : 'border-gray-300' }}">
                            @if (request('price', '') === $val)
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </div>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Tanggal --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tanggal</p>
            <div class="space-y-1">
                @foreach ([
        '' => 'Semua Tanggal',
        'today' => 'Hari Ini',
        'tomorrow' => 'Besok',
        'week' => 'Minggu Ini',
        'month' => 'Bulan Ini',
    ] as $val => $label)
                    <a href="{{ route('events.index', array_merge(request()->except('date', 'page'), $val ? ['date' => $val] : [])) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-150
                        {{ request('date', '') === $val
                            ? 'bg-gray-900 text-white font-medium'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div
                            class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                        {{ request('date', '') === $val ? 'border-white' : 'border-gray-300' }}">
                            @if (request('date', '') === $val)
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </div>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Featured Only --}}
        <div class="pt-3 border-t border-gray-50">
            <a href="{{ route(
                'events.index',
                array_merge(request()->except('featured', 'page'), request('featured') ? [] : ['featured' => 1]),
            ) }}"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl border transition-all duration-150
                    {{ request('featured')
                        ? 'bg-amber-50 border-amber-200 text-amber-700'
                        : 'border-gray-100 text-gray-600 hover:bg-gray-50 hover:border-gray-200' }}">
                <div class="flex items-center gap-2 text-sm font-medium">
                    <i class="ti ti-star-filled text-amber-400 text-base"></i>
                    Event Unggulan
                </div>
                <div
                    class="w-5 h-5 rounded-md border-2 flex items-center justify-center
                    {{ request('featured') ? 'bg-amber-400 border-amber-400' : 'border-gray-300' }}">
                    @if (request('featured'))
                        <i class="ti ti-check text-white text-xs"></i>
                    @endif
                </div>
            </a>
        </div>

    </div>
</div>
