<x-guest-layout>
    <div class="bg-white w-full py-6 px-10 shadow-sm">
        <h1 class="text-2xl font-bold tracking-tighter text-center">
            <a href="{{ '/' }}"><span class="text-[#002d72]">TIKETKU</span></a>
        </h1>
    </div>

    <div class="min-h-[calc(100vh-80px)] flex flex-col items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

            <div class="hidden md:flex flex-col items-center text-center">
                <div class="w-full max-w-sm mb-8">
                    <img src="{{ asset('images/gambar-login-register.png') }}" alt="Halo" class="w-full h-auto">
                </div>
                <h2 class="text-xl font-extrabold text-gray-900 mb-4 leading-tight">
                    Buat akun untuk mulai memesan tiket
                </h2>
                <p class="text-gray-500 text-sm px-6">
                    Lengkapi data diri Anda untuk menikmati kemudahan akses event dan film favorit.
                </p>
            </div>

            <div class="flex justify-center">
                <div
                    class="w-full max-w-md bg-white p-10 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.06)] border border-gray-50">

                    <h3 class="text-xl font-bold text-gray-900 mb-1 text-center">Buat Akun Anda</h3>
                    <p class="text-sm text-gray-500 mb-8 text-center">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline ml-1">Masuk</a>
                    </p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="w-full bg-[#0049cc] hover:bg-[#003da8] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-100 transition-all transform active:scale-[0.98]">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
