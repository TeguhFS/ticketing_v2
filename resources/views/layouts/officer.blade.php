<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags Dinamis --}}
    <meta name="description" content="{{ setting('seo_description') }}">
    <meta name="keywords" content="{{ setting('seo_keywords') }}">
    <meta name="author" content="{{ setting('app_name', 'TicketIn') }}">

    <title>Officer Panel — {{ setting('app_name', config('app.name')) }}</title>

    {{-- Favicon Dinamis Berbasis Setting --}}
    <link class="w-5 h-5" rel="icon" type="image/png"
        href="{{ setting('app_favicon') ? Storage::url(setting('app_favicon')) : asset('favicon/Logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="{{ config('app.url') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">

    <div class="min-h-screen flex flex-col">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-lg mx-auto px-4 h-14 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-900 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-none">Officer Panel</p>
                        <p class="text-xs text-gray-400 mt-1">{{ auth()->user()->name }}</p>
                    </div>
                </div>

                {{-- Logout Form --}}
                <form method="POST" action="{{ route('officer.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-9 h-9 border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition">
                        <i class="ti ti-logout text-base"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- Content Area --}}
        <main class="flex-1 max-w-lg mx-auto w-full px-4 py-5">
            {{ $slot }}
        </main>

        {{-- Bottom Navigation --}}
        <nav class="bg-white border-t border-gray-100 sticky bottom-0 z-50">
            <div class="max-w-lg mx-auto px-4">
                <div class="grid grid-cols-4 h-16">
                    @foreach ([['route' => 'officer.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'], ['route' => 'officer.scan', 'icon' => 'ti-scan', 'label' => 'Scan'], ['route' => 'officer.history', 'icon' => 'ti-history', 'label' => 'Riwayat'], ['route' => 'officer.profile', 'icon' => 'ti-user', 'label' => 'Profil']] as $nav)
                        @php
                            $isActive = request()->routeIs($nav['route']);
                        @endphp

                        {{-- Menggunakan relative agar garis indikator aktif terkunci di dalam box menu masing-masing --}}
                        <a href="{{ route($nav['route']) }}"
                            class="relative flex flex-col items-center justify-center gap-1 transition
                            {{ $isActive ? 'text-gray-900 font-semibold' : 'text-gray-400 hover:text-gray-600' }}">

                            <i class="ti {{ $nav['icon'] }} text-xl"></i>
                            <span class="text-[10px]">{{ $nav['label'] }}</span>

                            @if ($isActive)
                                <div class="absolute bottom-0 w-8 h-0.5 bg-gray-900 rounded-full"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>

    </div>

    @stack('scripts')
</body>

</html>
