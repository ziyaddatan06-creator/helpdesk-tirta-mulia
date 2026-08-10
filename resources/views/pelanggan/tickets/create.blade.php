<x-app-layout>
    <!-- Header Navigasi -->
    <div class="flex items-center mb-6 mt-2">
        <a href="{{ route('pelanggan.dashboard') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-600 mr-4">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Buat Pengaduan</h1>
    </div>

    <!-- Form Pengaduan -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('pelanggan.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Kendala</label>
                <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Laporan</label>
                <input type="text" name="title" required placeholder="Contoh: Printer di Ruang Keuangan Kertas Nyangkut" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Detail Kendala & Lokasi Ruangan</label>
                <textarea name="description" required rows="4" placeholder="Jelaskan masalahnya dan sebutkan nama ruangan / bagian Anda..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Bukti (Opsional)</label>
                <input type="file" name="attachment" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG. Maks 5MB.</p>
            </div>

            <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg shadow-blue-500/30 transform transition active:scale-95">
                Kirim Laporan
            </button>
        </form>
    </div>
</x-app-layout>