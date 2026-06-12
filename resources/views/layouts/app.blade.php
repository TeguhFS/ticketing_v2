<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags Baku --}}
    <meta name="description" content="{{ setting('seo_description') }}">
    <meta name="keywords" content="{{ setting('seo_keywords') }}">
    <meta name="author" content="{{ setting('app_name', 'TicketIn') }}">

    {{-- Open Graph (OG) Meta Tags untuk Optimalisasi Share Media Sosial (WA, Telegram, FB) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ setting('seo_title', setting('app_name', 'TicketIn')) }}">
    <meta property="og:description" content="{{ setting('seo_description') }}">
    @if (setting('seo_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @else
        <meta property="og:image" content="{{ asset('favicon/Logo.png') }}">
    @endif

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ setting('seo_title', setting('app_name', 'TicketIn')) }}">
    <meta name="twitter:description" content="{{ setting('seo_description') }}">

    {{-- Title --}}
    <title>{{ setting('seo_title', config('app.name')) }}</title>

    {{-- Favicon Dinamis Berbasis Setting --}}
    <link rel="icon" type="image/png"
        href="{{ setting('app_favicon') ? Storage::url(setting('app_favicon')) : asset('favicon/Logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="{{ config('app.url') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    {{-- Kustom Global CSS --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        .tox-tinymce {
            border: 1px solid #F3F4F6 !important;
            border-radius: 0.75rem !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.partials.admin-sidebar')

        {{-- Main Area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Topbar --}}
            @include('layouts.partials.admin-topbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- Rich Text Editor Asset --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tiny.cloud/1/wrfpceqzzwpscmo22iq176ivyidwj2pn3xaxy22trgbj1vwp/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    @stack('scripts')
</body>

</html>
