<x-guest-layout>
    
    <!-- Teks Helpdesk_IT di Pojok Kiri Atas -->
    <div class="fixed top-6 left-6 sm:top-8 sm:left-10 z-50 select-none cursor-default hover:scale-105 transition-transform duration-300">
        <span class="text-xl sm:text-2xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-400 drop-shadow-sm">
            Helpdesk<span class="text-blue-900">_IT</span>
        </span>
    </div>

    <!-- Status Sesi (Bila ada pesan error) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Input Email -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-50 border-gray-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@tirtamulia.test" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Input Password dengan Fitur Mata -->
        <div class="mt-4" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda" class="border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm block mt-1 w-full pr-10">
                
                <!-- Tombol Ikon Mata -->
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                    
                    <!-- Ikon Mata Terbuka -->
                    <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    
                    <!-- Ikon Mata Tertutup Coret -->
                    <svg x-show="showPassword" style="display: none;" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                        <line x1="2" x2="22" y1="2" y2="22"></line>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Ingat Saya -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ml-2 text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-500 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif

            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                {{ __('MASUK (LOG IN)') }}
            </button>
        </div>
    </form>
</x-guest-layout>