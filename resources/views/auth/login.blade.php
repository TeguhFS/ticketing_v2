<x-guest-layout>
    {{-- Header Logo Atas --}}
    <div class="bg-white w-full py-5 px-10 border-b border-gray-100 shadow-sm">
        <h1 class="text-xl font-bold tracking-tight">
            <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 group w-fit mx-auto">
                {{-- Logo Container --}}
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden bg-gray-900">

                    @if (setting('app_logo'))
                        <img src="{{ Storage::url(setting('app_logo')) }}" alt="{{ setting('app_name', 'TicketIn') }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    @endif

                </div>

                {{-- Nama Aplikasi --}}
                <span class="text-gray-900 uppercase transition-colors duration-200 group-hover:text-gray-700">
                    {{ setting('app_name', 'TicketIn') }}
                </span>
            </a>
        </h1>
    </div>

    <div class="min-h-[calc(100vh-80px)] flex flex-col items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

            {{-- Sisi Kiri: Ilustrasi & Teks Branding --}}
            <div class="hidden md:flex flex-col items-center text-center">
                <div class="w-full max-w-sm mb-8">
                    <img src="{{ asset('images/gambar-login-register.png') }}" alt="Halo" class="w-full h-auto">
                </div>
                <h2 class="text-xl font-extrabold text-gray-900 mb-4 leading-tight">
                    Tidak lagi ketinggalan event dan film favoritmu
                </h2>
                <p class="text-gray-400 text-sm px-6 leading-relaxed">
                    Gabung dan rasakan kemudahan beli tiket dan mengelola event di
                    {{ setting('app_name', 'TicketIn') }}.
                </p>
            </div>

            {{-- Sisi Kanan: Form Card Login --}}
            <div class="flex justify-center">
                <div
                    class="w-full max-w-md bg-white p-10 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100">

                    <h3 class="text-xl font-bold text-gray-900 mb-1 text-center">Masuk ke akunmu</h3>
                    <p class="text-sm text-gray-400 mb-8 text-center">
                        Belum punya akun?
                        <a href="{{ route('register') }}"
                            class="text-gray-900 font-bold hover:underline ml-1 transition-colors duration-150">
                            Daftar
                        </a>
                    </p>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Input --}}
                        <div class="mb-5">
                            <label for="email" class="block text-xs font-medium text-gray-500 mb-2">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus placeholder="email@example.com"
                                class="w-full h-11 px-4 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-300 outline-none focus:border-gray-400 transition-all bg-white focus:ring-0" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Password Input --}}
                        <div class="mb-5">
                            <label for="password" class="block text-xs font-medium text-gray-500 mb-2">Password</label>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full h-11 px-4 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-300 outline-none focus:border-gray-400 transition-all bg-white focus:ring-0" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8">
                            <button type="submit"
                                class="w-full h-12 bg-gray-900 hover:bg-gray-700 text-white font-medium text-sm rounded-xl transition-all shadow-sm active:scale-[0.99]">
                                Masuk
                            </button>
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="mt-6 flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="w-4 h-4 rounded border-gray-300 text-gray-900 shadow-sm focus:ring-0 accent-gray-900 cursor-pointer">
                                <span class="ms-2 text-xs text-gray-500 font-medium select-none">Ingat Saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-xs text-gray-500 hover:text-gray-900 font-medium hover:underline transition-colors duration-150"
                                    href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
