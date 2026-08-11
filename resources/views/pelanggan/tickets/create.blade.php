<x-app-layout>
    <div class="mb-6 mt-4">
        <h1 class="text-2xl font-bold text-gray-900">Buat Laporan Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Ceritakan detail kendala IT yang Anda alami agar tim kami bisa membantu.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 max-w-4xl">
        <form action="{{ route('pelanggan.tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Kendala (Singkat & Jelas)</label>
                <input type="text" name="title" required placeholder="Contoh: Printer Divisi Keuangan Rusak" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Kategori -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Hardware/Software</label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Prioritas Baru -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tingkat Prioritas (Seberapa Urgent?)</label>
                    <select name="priority_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
                        <option value="1">🟢 BIASA - Pekerjaan masih bisa dilanjutkan</option>
                        <option value="2">🔴 DARURAT - Pekerjaan/Pelayanan terhenti total!</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Detail Kronologi</label>
                <textarea name="description" required rows="4" placeholder="Jelaskan secara detail, kapan mulai error, pesan error yang muncul, dll..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3"></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Upload Foto Bukti/Error (Opsional)</label>
                <input type="file" name="attachment" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl bg-gray-50">
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition flex items-center gap-2">
                    <i data-lucide="send" class="w-5 h-5"></i> Kirim Laporan ke IT
                </button>
            </div>
        </form>
    </div>
</x-app-layout>