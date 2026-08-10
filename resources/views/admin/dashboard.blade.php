<x-app-layout>
   <div class="mb-8 mt-4 md:flex md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard IT Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau dan kelola semua laporan kendala kantor dari seluruh pegawai.</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            <!-- Tombol Manajemen Akun Baru -->
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-indigo-700 transition">
                <i data-lucide="users" class="w-4 h-4 mr-2"></i> Kelola Akun
            </a>
            
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-gray-700 transition">
                <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Laporan
            </a>
            
            <span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-bold rounded-lg border border-blue-200">
                <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i> {{ Auth::user()->name }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-blue-500">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Tiket Baru (Open)</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ $openTickets }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-yellow-500">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Sedang Diproses</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ $processTickets }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-green-500">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Selesai</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ $completedTickets }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-gray-700">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Laporan</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ $totalTickets }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-bold text-gray-900">Daftar Laporan Masuk</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-gray-50 uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID & Tanggal</th>
                        <th class="px-6 py-4">Pelapor (Pegawai)</th>
                        <th class="px-6 py-4">Judul Kendala</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-blue-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900 block">{{ $ticket->ticket_number }}</span>
                            <span class="text-xs text-gray-500">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $ticket->customer->name }}</td>
                        <td class="px-6 py-4"><span class="font-bold text-gray-900 block truncate w-48">{{ $ticket->title }}</span></td>
                        <td class="px-6 py-4"><span class="text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-md">{{ $ticket->category->name }}</span></td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2.5 py-1.5 rounded-md" style="background-color: {{ $ticket->status->color_code }}20; color: {{ $ticket->status->color_code }}">
                                {{ $ticket->status->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <!-- Link ini sudah diperbaiki dan ditambah whitespace-nowrap agar tidak tergencet -->
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="whitespace-nowrap inline-block text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-4 py-2 transition shadow-sm">
                                Proses Laporan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="shield-check" class="w-12 h-12 text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">Belum ada laporan masuk dari pegawai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>