<!-- Layer Hitam Transparan untuk Mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/60 z-20 md:hidden backdrop-blur-sm transition-opacity" style="display: none;" x-transition></div>

<!-- Sidebar Kiri -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 z-30 w-72 bg-[#0a192f] text-white transition-transform duration-300 ease-in-out md:translate-x-0 flex flex-col border-r border-[#112240] shadow-2xl shrink-0">
    
    <!-- Logo & Judul Aplikasi -->
    <div class="h-16 flex items-center px-6 border-b border-[#112240] bg-[#0d213b] shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 w-full">
            <img src="{{ asset('maskot.png') }}" alt="Logo" class="h-10 w-auto drop-shadow-md hover:scale-110 transition-transform" onerror="this.src='{{ asset('logo.png') }}'">
            <span class="font-black text-xl tracking-tight text-white drop-shadow-sm">Helpdesk<span class="text-cyan-400">_IT</span></span>
        </a>
    </div>

    <!-- Area Daftar Menu -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        
        <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Menu Utama</p>
        
        <!-- DASHBOARD UMUM (Selain Teknisi) -->
        @if(!Auth::user()->hasRole('Teknisi'))
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard', '*.dashboard') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-bold text-sm">Beranda (Dashboard)</span>
            </a>
        @endif

        <!-- ========================= -->
        <!-- MENU KHUSUS PELANGGAN     -->
        <!-- ========================= -->
        @if(Auth::user()->hasRole('Pelanggan'))
            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 mt-8">Layanan IT</p>
            
            <a href="{{ route('pelanggan.tickets.create') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('pelanggan.tickets.create') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span class="font-bold text-sm">Buat Laporan Baru</span>
            </a>
            
            <a href="{{ route('pelanggan.tickets.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('pelanggan.tickets.index') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="history" class="w-5 h-5"></i>
                <span class="font-bold text-sm">Riwayat Laporan</span>
            </a>

            <a href="{{ route('pelanggan.faq') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('pelanggan.faq') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white group' }}">
                <i data-lucide="book-open" class="w-5 h-5 {{ request()->routeIs('pelanggan.faq') ? 'text-white' : 'text-yellow-500 group-hover:scale-110' }} transition-transform"></i>
                <span class="font-bold text-sm">FAQ & Panduan IT</span>
            </a>
        @endif

        <!-- ========================= -->
        <!-- MENU KHUSUS TEKNISI       -->
        <!-- ========================= -->
        @if(Auth::user()->hasRole('Teknisi'))
            @php
                $activeTasks = \App\Models\Ticket::where('technician_id', Auth::id())->whereHas('status', function($q){ $q->whereNotIn('name', ['Selesai', 'Ditutup']); })->count();
            @endphp
            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 mt-4">Manajemen Tugas</p>
            
            <a href="{{ route('teknisi.dashboard') }}" class="flex items-center justify-between px-3 py-3 rounded-xl transition-all {{ request()->routeIs('teknisi.dashboard') || (request()->routeIs('teknisi.tickets.show') && !request()->routeIs('teknisi.tickets.history')) ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white group' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="clipboard-list" class="w-5 h-5 {{ request()->routeIs('teknisi.dashboard') || (request()->routeIs('teknisi.tickets.show') && !request()->routeIs('teknisi.tickets.history')) ? 'text-white' : 'text-orange-400 group-hover:scale-110' }} transition-transform"></i>
                    <span class="font-bold text-sm">Tugas Saya</span>
                </div>
                @if($activeTasks > 0)
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $activeTasks }}</span>
                @endif
            </a>
            
            <a href="{{ route('teknisi.tickets.history') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('teknisi.tickets.history') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="check-circle" class="w-5 h-5 {{ request()->routeIs('teknisi.tickets.history') ? 'text-white' : 'text-green-400' }}"></i>
                <span class="font-bold text-sm">Tugas Selesai</span>
            </a>
        @endif

        <!-- ========================= -->
        <!-- MENU KHUSUS ADMIN         -->
        <!-- ========================= -->
        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin'))
            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 mt-8">Operasional IT</p>
            
            <a href="{{ route('admin.tickets.index') }}" class="flex items-center justify-between px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tickets.index') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white group' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="inbox" class="w-5 h-5 {{ request()->routeIs('admin.tickets.index') ? 'text-white' : 'text-cyan-400 group-hover:scale-110' }} transition-transform"></i>
                    <span class="font-bold text-sm">Semua Laporan (Tiket)</span>
                </div>
            </a>
            
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="printer" class="w-5 h-5"></i>
                <span class="font-bold text-sm">Cetak Laporan PDF</span>
            </a>

            <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 mt-8">Master Data</p>
            
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-bold text-sm">Manajemen Akun</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="tags" class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-purple-400' }}"></i>
                <span class="font-bold text-sm">Kategori Keluhan</span>
            </a>
            
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-[#112240] hover:text-white' }}">
                <i data-lucide="settings" class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400' }}"></i>
                <span class="font-bold text-sm">Pengaturan Sistem</span>
            </a>
        @endif
    </div>

    <!-- Profil Identitas di Bawah Sidebar -->
    <div class="p-4 border-t border-[#112240] bg-[#0a1526] shrink-0">
        <div class="flex items-center gap-3">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-10 w-10 rounded-full object-cover border-2 border-[#112240]" alt="Avatar">
            @else
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center font-black text-sm shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[11px] font-bold text-cyan-400 truncate">{{ Auth::user()->roles->pluck('name')->first() ?? 'Pegawai' }}</p>
            </div>
        </div>
    </div>
</aside>