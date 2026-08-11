<x-app-layout>
    <div class="mb-6 mt-4"><h1 class="text-2xl font-bold text-gray-900">Semua Laporan Keluhan</h1></div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 bg-gray-50 uppercase border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">No. Tiket & Judul</th>
                    <th class="px-6 py-4">Pelapor</th>
                    <th class="px-6 py-4 text-center">Prioritas</th>
                    <th class="px-6 py-4 text-center">Ditugaskan Ke</th>
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
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $t->customer->name }}</td>
                    
                    <!-- FITUR PRIORITAS TAMPIL DI SINI -->
                    <td class="px-6 py-4 text-center">
                        <span class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider border {{ $t->priority_color }}">
                            {{ $t->priority_name }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($t->technician_id)
                            <span class="text-xs font-bold text-orange-600 bg-orange-100 px-2 py-1 rounded-md">{{ $t->technician->name }}</span>
                        @else
                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-md" style="background-color: {{ $t->status->color_code }}20; color: {{ $t->status->color_code }}">{{ $t->status->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.tickets.show', $t->id) }}" class="text-blue-600 hover:underline font-bold text-xs bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">Buka</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500 font-medium">Belum ada laporan keluhan yang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>