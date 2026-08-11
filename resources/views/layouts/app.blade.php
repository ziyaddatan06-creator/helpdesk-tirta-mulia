<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Helpdesk IT - PDAM Tirta Mulia</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-hidden">
        
        <!-- Wrapper Utama -->
        <div class="flex h-screen w-full" x-data="{ sidebarOpen: false }">
            
            <!-- Panggil File Sidebar -->
            @include('layouts.navigation')

            <!-- Area Konten Kanan -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                <!-- Topbar (Header Atas) -->
                <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 shadow-sm shrink-0">
                    
                    <div class="flex items-center gap-4">
                        <!-- Tombol Hamburger (Untuk HP) -->
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-blue-600 focus:outline-none">
                            <i data-lucide="menu" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Profil User di Kanan Atas -->
                    <div class="flex items-center gap-4">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-3 hover:bg-gray-50 p-1.5 rounded-lg transition-colors border border-transparent hover:border-gray-200 focus:outline-none">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full object-cover border border-gray-200 shadow-sm" alt="Avatar">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-bold text-sm text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 hidden sm:block"></i>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 font-medium">
                                    <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                                </x-dropdown-link>
                                <hr class="border-gray-100 my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 font-bold text-red-600 hover:text-red-700 hover:bg-red-50">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Halaman Konten Utama yang bisa di-scroll -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50 custom-scrollbar">
                    {{ $slot }}
                </main>

            </div>
        </div>

        <!-- Render Ikon Lucide -->
        <script> lucide.createIcons(); </script>
        
        <style>
            /* Script untuk mempercantik scrollbar */
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
        </style>
    </body>
</html>