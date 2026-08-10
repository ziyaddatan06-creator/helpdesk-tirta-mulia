<div class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 md:hidden pb-safe">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
        
        <!-- Dashboard -->
        <a href="{{ route('pelanggan.dashboard') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group {{ request()->routeIs('pelanggan.dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
            <i data-lucide="home" class="w-6 h-6 mb-1 {{ request()->routeIs('pelanggan.dashboard') ? 'fill-blue-100' : '' }}"></i>
            <span class="text-[10px]">Beranda</span>
        </a>

        <!-- Tiket -->
        <a href="#" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group text-gray-500">
            <i data-lucide="file-text" class="w-6 h-6 mb-1"></i>
            <span class="text-[10px]">Tiket</span>
        </a>

        <!-- Tombol Lapor Tengah -->
        <div class="flex items-center justify-center">
            <a href="#" class="flex items-center justify-center w-12 h-12 bg-blue-600 rounded-full hover:bg-blue-700 text-white shadow-lg shadow-blue-500/30 transform transition active:scale-95">
                <i data-lucide="plus" class="w-6 h-6"></i>
            </a>
        </div>

        <!-- Notifikasi -->
        <a href="#" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group text-gray-500">
            <i data-lucide="bell" class="w-6 h-6 mb-1"></i>
            <span class="text-[10px]">Notif</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('profile.edit') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group {{ request()->routeIs('profile.edit') ? 'text-blue-600' : 'text-gray-500' }}">
            <i data-lucide="user" class="w-6 h-6 mb-1 {{ request()->routeIs('profile.edit') ? 'fill-blue-100' : '' }}"></i>
            <span class="text-[10px]">Profil</span>
        </a>
    </div>
</div>