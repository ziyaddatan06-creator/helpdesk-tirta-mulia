<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <!-- Meta viewport khusus aplikasi mobile -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Helpdesk PDAM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Tailwind Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 pb-20 md:pb-0">
        <div class="min-h-screen bg-gray-50">
            
            <!-- Navbar Atas (Akan otomatis disesuaikan untuk Desktop) -->
            @include('layouts.navigation')

            <!-- Header Halaman (Opsional) -->
            @isset($header)
                <header class="bg-white shadow-sm md:block">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Konten Utama Halaman -->
            <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

        <!-- Panggil Bottom Navigation Khusus Mobile -->
        <x-mobile-bottom-nav />

        <!-- Initialize Lucide Icons -->
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>