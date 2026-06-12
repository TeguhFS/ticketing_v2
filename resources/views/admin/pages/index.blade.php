<x-app-layout>
    <x-slot name="title">Halaman</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-x text-base"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        @foreach ([['label' => 'Total', 'value' => $stats['total'], 'icon' => 'ti-file-text', 'color' => 'bg-gray-900'], ['label' => 'Aktif', 'value' => $stats['active'], 'icon' => 'ti-circle-check', 'color' => 'bg-emerald-500'], ['label' => 'Privacy', 'value' => $stats['privacy'], 'icon' => 'ti-shield-lock', 'color' => 'bg-blue-500'], ['label' => 'Terms', 'value' => $stats['terms'], 'icon' => 'ti-license', 'color' => 'bg-violet-500'], ['label' => 'Custom', 'value' => $stats['custom'], 'icon' => 'ti-layout', 'color' => 'bg-amber-500']] as $stat)
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <div class="w-9 h-9 {{ $stat['color'] }} rounded-xl flex items-center justify-center">
                        <i class="ti {{ $stat['icon'] }} text-white text-base"></i>
                    </div>
                </div>
                <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-900">Daftar Halaman</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    Kelola halaman statis website
                </p>
            </div>
            <a href="{{ route('admin.pages.create') }}"
                class="inline-flex items-center gap-2 h-9 px-4 bg-gray-900 text-white text-sm
                rounded-xl hover:bg-gray-700 transition">
                <i class="ti ti-plus text-base"></i> Tambah Halaman
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Halaman</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Tipe</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Slug</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Dipublikasikan</th>
                        <th class="text-left text-xs font-medium text-gray-400 px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @php
                                        $typeIcon = [
                                            'privacy' => [
                                                'icon' => 'ti-shield-lock',
                                                'bg' => 'bg-blue-100',
                                                'text' => 'text-blue-600',
                                            ],
                                            'terms' => [
                                                'icon' => 'ti-license',
                                                'bg' => 'bg-violet-100',
                                                'text' => 'text-violet-600',
                                            ],
                                            'custom' => [
                                                'icon' => 'ti-layout',
                                                'bg' => 'bg-amber-100',
                                                'text' => 'text-amber-600',
                                            ],
                                        ];
                                        $ti = $typeIcon[$page->type] ?? $typeIcon['custom'];
                                    @endphp
                                    <div
                                        class="w-9 h-9 {{ $ti['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="ti {{ $ti['icon'] }} {{ $ti['text'] }} text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $page->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ Str::limit(strip_tags($page->content), 50) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $typeBadge = [
                                        'privacy' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'terms' => 'bg-violet-50 text-violet-700 border-violet-200',
                                        'custom' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                @endphp
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            {{ $typeBadge[$page->type] ?? '' }} capitalize">
                                    {{ $page->type === 'privacy' ? 'Kebijakan Privasi' : ($page->type === 'terms' ? 'Syarat & Ketentuan' : 'Custom') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                    /{{ $page->slug }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-lg border
                            {{ $page->is_active
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                    {{ $page->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                {{ $page->published_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5">

                                    {{-- Preview --}}
                                    @if ($page->type === 'privacy')
                                        <a href="{{ route('pages.privacy') }}" target="_blank"
                                        @elseif($page->type === 'terms') <a href="{{ route('pages.terms') }}"
                                        target="_blank" @else <a href="{{ route('pages.show', $page->slug) }}"
                                            target="_blank" @endif
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center
                                    justify-center text-gray-400 hover:bg-blue-50 hover:text-blue-500
                                    hover:border-blue-200 transition">
                                            <i class="ti ti-external-link text-sm"></i>
                                        </a>

                                        <a href="{{ route('admin.pages.edit', $page) }}"
                                            class="w-8 h-8 border border-gray-100 rounded-lg flex items-center
                                    justify-center text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition">
                                            <i class="ti ti-edit text-sm"></i>
                                        </a>

                                        @if (!in_array($page->type, ['privacy', 'terms']))
                                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                                                onsubmit="return confirm('Hapus halaman ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 border border-gray-100 rounded-lg flex items-center
                                        justify-center text-gray-400 hover:bg-red-50 hover:text-red-500
                                        hover:border-red-200 transition">
                                                    <i class="ti ti-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @else
                                            <div class="w-8 h-8 flex items-center justify-center">
                                                <i class="ti ti-lock text-gray-200 text-sm"
                                                    title="Halaman ini dilindungi"></i>
                                            </div>
                                        @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <i class="ti ti-file-off text-4xl text-gray-200 block mb-3"></i>
                                <p class="text-sm text-gray-400">Belum ada halaman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
