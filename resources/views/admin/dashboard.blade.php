<x-app-layout>
    <!-- Memanggil Library Chart.js dari Internet -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="mb-6 mt-4 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Beranda Admin IT</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau dan kelola semua laporan kendala dari pegawai PDAM Tirta Mulia.</p>
        </div>
    </div>

    <!-- Widget Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 flex items-center gap-5 hover:shadow-md transition">
            <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center shrink-0"><i data-lucide="alert-circle" class="w-7 h-7"></i></div>
            <div><p class="text-sm font-bold text-gray-500">Tiket Aktif / Proses</p><p class="text-3xl font-black text-gray-900">{{ $activeTickets }}</p></div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 flex items-center gap-5 hover:shadow-md transition">
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0"><i data-lucide="check-circle" class="w-7 h-7"></i></div>
            <div><p class="text-sm font-bold text-gray-500">Tiket Selesai</p><p class="text-3xl font-black text-gray-900">{{ $completedTickets }}</p></div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-blue-100 flex items-center gap-5 hover:shadow-md transition">
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0"><i data-lucide="ticket" class="w-7 h-7"></i></div>
            <div><p class="text-sm font-bold text-gray-500">Total Semua Laporan</p><p class="text-3xl font-black text-gray-900">{{ $activeTickets + $completedTickets }}</p></div>
        </div>
    </div>

    <!-- Layout 2 Kolom: Kiri Grafik, Kanan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- KOLOM KIRI: Grafik Chart.js (Porsi 1/3) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden col-span-1 p-5">
            <h2 class="font-bold text-gray-900 flex items-center gap-2 mb-4 border-b border-gray-100 pb-3">
                <i data-lucide="pie-chart" class="w-5 h-5 text-purple-500"></i> Statistik Kategori
            </h2>
            <div class="relative w-full aspect-square flex items-center justify-center">
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>

        <!-- KOLOM KANAN: Tabel 5 Laporan Terbaru (Porsi 2/3) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2 flex flex-col">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="font-bold text-gray-900 flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5 text-blue-500"></i> Laporan Terbaru</h2>
                <a href="{{ route('admin.tickets.index') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 bg-white uppercase border-b border-gray-100">
                        <tr><th class="px-6 py-4">No. Tiket</th><th class="px-6 py-4">Pelapor</th><th class="px-6 py-4 text-center">Status</th><th class="px-6 py-4 text-center">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($tickets as $t)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4"><span class="font-bold text-gray-900 block">{{ $t->ticket_number }}</span><span class="text-xs text-gray-500 line-clamp-1">{{ $t->title }}</span></td>
                            <td class="px-6 py-4 font-medium">{{ $t->customer->name }}</td>
                            <td class="px-6 py-4 text-center"><span class="text-[10px] font-bold px-2 py-1 rounded-md" style="background-color: {{ $t->status->color_code }}20; color: {{ $t->status->color_code }}">{{ $t->status->name }}</span></td>
                            <td class="px-6 py-4 text-center"><a href="{{ route('admin.tickets.show', $t->id) }}" class="text-blue-600 hover:underline font-bold text-xs bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">Buka</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 font-medium">Belum ada laporan terbaru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Penggerak Grafik -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('kategoriChart').getContext('2d');
            
            // Ambil data dari Controller
            const labels = {!! json_encode($chartLabels) !!};
            const data = {!! json_encode($chartData) !!};

            new Chart(ctx, {
                type: 'doughnut', // Tipe grafik kue berlubang
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ef4444'], // Warna warni
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11, family: "'Figtree', sans-serif" } } }
                    },
                    cutout: '70%' // Ukuran lubang di tengah
                }
            });
        });
    </script>
</x-app-layout>