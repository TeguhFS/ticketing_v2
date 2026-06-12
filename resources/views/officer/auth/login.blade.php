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

    <title>Officer Login — {{ setting('app_name', config('app.name')) }}</title>

    {{-- Favicon Dinamis Berbasis Setting --}}
    <link rel="icon" type="image/png"
        href="{{ setting('app_favicon') ? Storage::url(setting('app_favicon')) : asset('favicon/Logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="{{ config('app.url') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div
                class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-4 flex-shrink-0 overflow-hidden">
                @if (setting('app_logo'))
                    <img src="{{ Storage::url(setting('app_logo')) }}" alt="{{ setting('app_name', 'TicketIn') }}"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                @endif
            </div>
            {{-- Menggunakan Nama Aplikasi Dinamis dari Setting --}}
            <h1 class="text-xl font-bold text-gray-900">{{ setting('app_name', 'TicketIn') }} Officer</h1>
            <p class="text-sm text-gray-400 mt-1">Login sebagai Field Officer</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">

            @if ($errors->any())
                <div
                    class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-center gap-2 text-sm text-red-600">
                    <i class="ti ti-alert-circle text-base flex-shrink-0"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('officer.login.post') }}" class="space-y-4">
                @csrf

                {{-- Email Input --}}
                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Email</label>
                    <div class="relative">
                        <i class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="email@example.com" autofocus required
                            class="w-full h-11 pl-9 pr-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white @error('email') @enderror">
                    </div>
                </div>

                {{-- Password Input --}}
                <div>
                    <label class="text-xs font-medium text-gray-500 block mb-1.5">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" required
                            class="w-full h-11 pl-9 pr-10 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="ti text-base" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <label class="flex items-center gap-2 cursor-pointer py-0.5">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 rounded text-gray-900 focus:ring-0 accent-gray-900 cursor-pointer">
                    <span class="text-sm text-gray-600 selection:bg-transparent">Ingat saya</span>
                </label>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2 shadow-sm">
                    <i class="ti ti-login text-base"></i> Login
                </button>
            </form>
        </div>

        {{-- Footer Link --}}
        <p class="text-center text-xs text-gray-400 mt-5">
            Bukan Field Officer?
            <a href="{{ route('login') }}" class="text-gray-700 font-medium hover:underline transition">Login Admin</a>
        </p>

    </div>

</body>

</html>
