<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags Baku --}}
    <title>{{ $title ?? setting('seo_title', config('app.name')) }}</title>
    <meta name="description" content="{{ $metaDescription ?? setting('seo_description', '') }}">
    <meta name="keywords" content="{{ setting('seo_keywords', '') }}">
    <meta name="author" content="{{ setting('app_name', 'TicketIn') }}">

    {{-- Open Graph (OG) Tags — Optimalisasi Share Media Sosial (WA, Telegram, FB) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? setting('seo_title', config('app.name')) }}">
    <meta property="og:description" content="{{ $metaDescription ?? setting('seo_description', '') }}">
    @if (setting('seo_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @else
        <meta property="og:image" content="{{ asset('favicon/Logo.png') }}"> {{-- Fallback gambar jika OG Image kosong --}}
    @endif

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? setting('seo_title', config('app.name')) }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? setting('seo_description', '') }}">

    {{-- Favicon Dinamis Berbasis Setting --}}
    <link rel="icon" type="image/png"
        href="{{ setting('app_favicon') ? Storage::url(setting('app_favicon')) : asset('favicon/Logo.png') }}">

    {{-- Fonts & Preconnect --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="{{ config('app.url') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- Extra head --}}
    @stack('head')
</head>

<body class="font-sans antialiased bg-white text-gray-900">

    {{-- Navbar --}}
    @include('layouts.partials.user-navbar')

    {{-- Page Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('layouts.partials.user-footer')

    {{-- Scripts --}}
    @stack('scripts')

</body>

</html>
