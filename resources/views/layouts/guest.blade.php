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

    {{-- Open Graph (OG) Meta Tags Lengkap --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ setting('seo_title', setting('app_name', 'TicketIn')) }}">
    <meta property="og:description" content="{{ setting('seo_description') }}">
    @if (setting('seo_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @else
        <meta property="og:image" content="{{ asset('favicon/Logo.png') }}"> {{-- Fallback gambar jika OG Image kosong --}}
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
    {{-- preconnect ke storage jika menggunakan link eksternal/S3 nantinya --}}
    <link rel="preconnect" href="{{ config('app.url') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-white-50">
    <div class="min-h-screen">
        {{ $slot }}
    </div>
</body>

</html>
