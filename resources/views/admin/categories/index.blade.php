<x-app-layout>
    <div class="mb-6 mt-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Master Kategori Keluhan</h1>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm text-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
        </button>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 bg-gray-50 uppercase border-b border-gray-100">
                <tr><th class="px-6 py-4 w-16">ID</th><th class="px-6 py-4">Nama Kategori</th><th class="px-6 py-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($categories as $c)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-bold text-gray-500">{{ $c->id }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $c->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-orange-500 hover:underline font-bold text-xs mr-3">Edit</button>
                        <button class="text-red-500 hover:underline font-bold text-xs">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>