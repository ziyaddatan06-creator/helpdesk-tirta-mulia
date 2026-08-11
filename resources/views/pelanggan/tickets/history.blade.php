<x-app-layout>
    <div class="mb-6 mt-4">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Tugas Selesai</h1>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 bg-gray-50 uppercase border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">No. Tiket & Judul</th>
                    <th class="px-6 py-4">Pelapor</th>
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
                    <td class="px-6 py-4 font-medium">{{ $t->customer->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-green-100 text-green-700">Selesai</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('teknisi.tickets.show', $t->id) }}" class="text-blue-600 hover:underline font-bold text-xs">Lihat Log Chat</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 font-medium">Belum ada tugas yang diselesaikan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>