<x-app-layout>
    <div class="mb-6 mt-4">
        <h1 class="text-2xl font-bold text-gray-900">Tugas Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar perbaikan dan keluhan yang ditugaskan kepada Anda oleh Admin IT.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Kolom: Tugas Aktif -->
        <div class="bg-white rounded-2xl shadow-sm border border-orange-200 overflow-hidden">
            <div class="p-4 bg-orange-50 border-b border-orange-200 flex justify-between items-center">
                <h2 class="font-bold text-orange-800 flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5"></i> Sedang Dikerjakan
                </h2>
                <span class="bg-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $activeTickets->count() }}</span>
            </div>
            <div class="p-4 space-y-4">
                @forelse($activeTickets as $ticket)
                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition bg-gray-50/50 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full" style="background-color: {{ $ticket->status->color_code }}"></div>
                        <div class="flex justify-between items-start mb-2 pl-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md" style="background-color: {{ $ticket->status->color_code }}20; color: {{ $ticket->status->color_code }}">{{ $ticket->status->name }}</span>
                            <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 pl-3">{{ $ticket->title }}</h3>
                        <p class="text-sm text-gray-600 mb-4 pl-3 line-clamp-2">{{ $ticket->description }}</p>
                        
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 pl-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">{{ substr($ticket->customer->name, 0, 1) }}</div>
                                <span class="text-xs font-medium text-gray-600">Pelapor: {{ $ticket->customer->name }}</span>
                            </div>
                            <a href="{{ route('teknisi.tickets.show', $ticket->id) }}" class="text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-lg transition shadow-sm">Buka Tiket</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 font-medium text-sm flex flex-col items-center">
                        <i data-lucide="coffee" class="w-12 h-12 mb-3 text-gray-300"></i>
                        Tidak ada tugas aktif. Waktunya istirahat!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom: Tugas Selesai -->
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden h-fit">
            <div class="p-4 bg-green-50 border-b border-green-200 flex justify-between items-center">
                <h2 class="font-bold text-green-800 flex items-center gap-2">
                    <i data-lucide="check-square" class="w-5 h-5"></i> Riwayat Selesai
                </h2>
                <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $completedTickets->count() }}</span>
            </div>
            <div class="p-4 space-y-3">
                @forelse($completedTickets as $ticket)
                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 opacity-80 hover:opacity-100 transition">
                        <h3 class="font-bold text-gray-900 mb-1 line-through">{{ $ticket->title }}</h3>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-green-100 text-green-700">Selesai</span>
                            <a href="{{ route('teknisi.tickets.show', $ticket->id) }}" class="text-xs font-bold text-blue-600 hover:underline">Detail History</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400 font-medium text-sm">Belum ada riwayat perbaikan.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>