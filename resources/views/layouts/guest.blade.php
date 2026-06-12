<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <meta name="description" content="{{ setting('seo_description') }}">
    <meta name="keywords" content="{{ setting('seo_keywords') }}">
    <meta name="author" content="{{ setting('app_name', 'TicketIn') }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ setting('seo_title', setting('app_name', 'TicketIn')) }}">
    <meta property="og:description" content="{{ setting('seo_description') }}">
    @if (setting('seo_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @else
        <meta property="og:image" content="{{ asset('favicon/Logo.png') }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ setting('seo_title', setting('app_name', 'TicketIn')) }}">
    <meta name="twitter:description" content="{{ setting('seo_description') }}">

    {{-- Title --}}
    <title>{{ $title ?? setting('seo_title', config('app.name')) }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png"
        href="{{ setting('app_favicon') ? Storage::url(setting('app_favicon')) : asset('favicon/Logo.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="{{ config('app.url') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="font-sans text-gray-900 antialiased bg-white">
    <div class="min-h-screen">
        {{ $slot }}
    </div>
    @stack('scripts')
</body>

</html>
