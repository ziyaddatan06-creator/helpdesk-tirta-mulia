<x-app-layout>
    <!-- Banner Sambutan -->
    <div class="mb-6 mt-4 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-blue-100 text-sm mt-1">Selamat datang di Sistem Helpdesk IT PDAM Tirta Mulia. Ada kendala perangkat atau jaringan hari ini?</p>
        </div>
        <a href="{{ route('pelanggan.tickets.create') }}" class="bg-white text-blue-600 hover:bg-blue-50 font-bold px-6 py-3 rounded-2xl shadow-sm transition flex items-center gap-2 text-sm shrink-0">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> Buat Laporan Baru
        </a>
    </div>

    <!-- Widget Statistik Personal -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shrink-0">
                <i data-lucide="ticket" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Laporan</p>
                <p class="text-3xl font-black text-gray-900">{{ $totalTickets }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Sedang Diproses</p>
                <p class="text-3xl font-black text-gray-900">{{ $activeTickets }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
                <p class="text-3xl font-black text-gray-900">{{ $completedTickets }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan Terbaru Pegawai -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-blue-500"></i> Laporan Kendala Terbaru Anda
            </h2>
            <a href="{{ route('pelanggan.tickets.index') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua Riwayat</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 bg-gray-50 uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">No. Tiket & Judul</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tickets as $t)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900 block">{{ $t->ticket_number }}</span>
                            <span class="text-xs text-gray-500">{{ $t->title }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $t->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold px-3 py-1 rounded-md" style="background-color: {{ $t->status->color_code ?? '#ccc' }}20; color: {{ $t->status->color_code ?? '#333' }}">
                                {{ $t->status->name ?? 'Open' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('pelanggan.tickets.show', $t->id) }}" class="text-blue-600 hover:underline font-bold text-xs bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">Buka Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">
                            Belum ada laporan kendala yang dibuat. Silakan klik tombol <strong>"Buat Laporan Baru"</strong> di atas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>