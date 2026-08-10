<x-app-layout>
    <div class="mb-6 mt-4 flex items-center">
        <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-600 mr-4 hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Pusat Laporan IT</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10 max-w-3xl">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-bold text-gray-900">Pilih Periode Laporan</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-500 text-sm mb-6">Silakan pilih rentang waktu laporan kendala yang ingin Anda cetak. Laporan akan disajikan dalam format siap cetak (Bisa disimpan sebagai PDF).</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Harian -->
                <a href="{{ route('admin.reports.print', ['type' => 'harian']) }}" target="_blank" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i data-lucide="sun" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Laporan Harian</h3>
                        <p class="text-xs text-gray-500">Cetak data hari ini saja.</p>
                    </div>
                </a>

                <!-- Mingguan -->
                <a href="{{ route('admin.reports.print', ['type' => 'mingguan']) }}" target="_blank" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-yellow-500 hover:bg-yellow-50 transition group">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-4 group-hover:bg-yellow-500 group-hover:text-white transition">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Laporan Mingguan</h3>
                        <p class="text-xs text-gray-500">Cetak data minggu ini.</p>
                    </div>
                </a>

                <!-- Bulanan -->
                <a href="{{ route('admin.reports.print', ['type' => 'bulanan']) }}" target="_blank" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition group">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-4 group-hover:bg-green-600 group-hover:text-white transition">
                        <i data-lucide="calendar-days" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Laporan Bulanan</h3>
                        <p class="text-xs text-gray-500">Cetak data bulan berjalan.</p>
                    </div>
                </a>

                <!-- Semua Data -->
                <a href="{{ route('admin.reports.print', ['type' => 'semua']) }}" target="_blank" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-gray-500 hover:bg-gray-50 transition group">
                    <div class="w-12 h-12 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center mr-4 group-hover:bg-gray-600 group-hover:text-white transition">
                        <i data-lucide="database" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Semua Data</h3>
                        <p class="text-xs text-gray-500">Cetak seluruh riwayat tiket.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>