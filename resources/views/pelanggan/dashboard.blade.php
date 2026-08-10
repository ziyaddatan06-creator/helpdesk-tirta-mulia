<x-app-layout>
    <div class="md:hidden flex items-center justify-between mb-6 mt-2">
        <div>
            <p class="text-sm text-gray-500">Selamat datang,</p>
            <h1 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h1>
            <p class="text-xs text-blue-600 font-semibold mt-1">Pegawai Internal</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg border-2 border-white shadow-sm">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl flex items-start">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center mb-3">
                <i data-lucide="activity" class="text-blue-600 w-5 h-5"></i>
            </div>
            <h3 class="text-gray-500 text-xs font-medium">Tiket Aktif</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeTickets }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="bg-green-50 w-10 h-10 rounded-full flex items-center justify-center mb-3">
                <i data-lucide="check-circle" class="text-green-600 w-5 h-5"></i>
            </div>
            <h3 class="text-gray-500 text-xs font-medium">Selesai</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $completedTickets }}</p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/30 mb-8 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-lg font-bold mb-1">Ada kendala di kantor?</h2>
            <p class="text-sm text-blue-100 mb-4 opacity-90">Laporkan masalah seperti printer error, PC mati, internet putus, atau AC bocor.</p>
            <a href="{{ route('pelanggan.tickets.create') }}" class="inline-flex items-center bg-white text-blue-700 text-sm font-semibold px-4 py-2 rounded-full hover:bg-gray-50 transition">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Buat Laporan Baru
            </a>
        </div>
        <i data-lucide="monitor-x" class="absolute -bottom-4 -right-4 w-32 h-32 text-white opacity-10"></i>
    </div>

    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Tiket Terbaru</h3>
            <a href="#" class="text-sm text-blue-600 font-medium hover:underline">Lihat Semua</a>
        </div>
        
        @if($tickets->isEmpty())
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="inbox" class="text-gray-400 w-8 h-8"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-1">Belum ada laporan</h4>
                <p class="text-sm text-gray-500">Laporan yang Anda buat akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($tickets as $ticket)
                    <!-- KODE DI BAWAH INI MENGUBAH HREF AGAR TIKET BISA DIKLIK -->
                    <a href="{{ route('pelanggan.tickets.show', $ticket->id) }}" class="block bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md" style="background-color: {{ $ticket->status->color_code }}20; color: {{ $ticket->status->color_code }}">
                                {{ $ticket->status->name }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1 truncate">{{ $ticket->title }}</h4>
                        <div class="flex items-center text-xs text-gray-500 font-medium mt-2">
                            <i data-lucide="tag" class="w-3.5 h-3.5 mr-1.5"></i> {{ $ticket->category->name }}
                        </div>
                        <div class="flex items-center text-xs text-gray-400 font-medium mt-1">
                            <i data-lucide="hash" class="w-3.5 h-3.5 mr-1.5"></i> {{ $ticket->ticket_number }}
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>