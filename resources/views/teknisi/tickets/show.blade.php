<x-app-layout>
    <div class="mb-6 mt-4 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('teknisi.dashboard') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-600 mr-4 hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Pengerjaan Tiket #{{ $ticket->ticket_number }}</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl flex items-start shadow-sm hidden" id="success-alert">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Kiri: Detail & Chat -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 border-l-4" style="border-left-color: {{ $ticket->status->color_code }}">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $ticket->title }}</h2>
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-2 font-semibold">Deskripsi Kerusakan:</p>
                    <p class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $ticket->description }}</p>
                </div>
                @if($ticket->attachments->count() > 0)
                    <div>
                        <p class="text-sm text-gray-500 mb-2 font-semibold">Foto Lampiran:</p>
                        <div class="flex gap-4">
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="block w-40 h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200"><img src="{{ asset('storage/' . $attachment->file_path) }}" class="w-full h-full object-cover"></a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- LIVE CHAT AREA -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: 500px;">
                <div class="p-3 bg-[#005c4b] text-white flex items-center gap-3 z-10 shadow-md">
                    <div class="w-10 h-10 bg-white text-[#005c4b] rounded-full flex items-center justify-center font-bold text-lg">{{ substr($ticket->customer->name, 0, 1) }}</div>
                    <div>
                        <h3 class="font-bold leading-tight">{{ $ticket->customer->name }}</h3>
                        <p class="text-[11px] text-green-200">Pegawai (Pelapor)</p>
                    </div>
                </div>
                
                <div id="chat-box" class="p-4 flex-1 overflow-y-auto space-y-3 bg-[#efeae2]" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
                    @forelse($ticket->comments as $comment)
                        @if(str_contains($comment->body, 'Sistem:'))
                            <div class="flex justify-center my-2"><span class="bg-yellow-100/90 text-yellow-800 text-[11px] px-3 py-1 rounded-lg font-medium text-center shadow-sm">{{ $comment->body }}</span></div>
                        @elseif($comment->user_id === auth()->id())
                            <div class="flex justify-end">
                                <div class="bg-[#d9fdd3] text-gray-800 p-2.5 rounded-lg rounded-tr-none max-w-[80%] shadow-sm relative">
                                    <p class="text-sm whitespace-pre-wrap">{{ $comment->body }}</p>
                                    <div class="text-[10px] text-gray-500 text-right mt-1">{{ $comment->created_at->format('H:i') }} <i data-lucide="check-check" class="w-3 h-3 inline text-blue-500"></i></div>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start">
                                <div class="bg-white text-gray-800 p-2.5 rounded-lg rounded-tl-none max-w-[80%] shadow-sm relative">
                                    <span class="text-[11px] font-bold text-[#eb6161] block mb-0.5">{{ $comment->user->name }} ({{ $comment->user->roles->pluck('name')->first() ?? 'Pegawai' }})</span>
                                    <p class="text-sm whitespace-pre-wrap">{{ $comment->body }}</p>
                                    <div class="text-[10px] text-gray-400 text-right mt-1">{{ $comment->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex justify-center items-center h-full"><span class="bg-white/70 text-gray-600 text-xs px-4 py-2 rounded-full shadow-sm">Tidak ada percakapan. Mulai update laporan.</span></div>
                    @endforelse
                </div>

                <div class="p-3 bg-[#f0f2f5]">
                    <form id="chat-form" action="{{ route('teknisi.tickets.comment', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" name="body" id="chat-input" required placeholder="Ketik laporan atau balasan..." autocomplete="off" class="flex-1 bg-white border-none rounded-full px-4 py-2.5 text-sm focus:ring-0 shadow-sm">
                        <button type="submit" id="chat-submit" class="w-10 h-10 bg-[#00a884] rounded-full flex items-center justify-center text-white hover:bg-[#008f6f] transition shadow-sm shrink-0">
                            <svg class="w-5 h-5 ml-1 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kanan: Update Status Eksekusi -->
        <div class="space-y-6">
            
            <!-- PESAN ERROR (Jika teknisi bandel tidak upload foto) -->
            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-100 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Progress Lapangan</h3>
                
                <!-- Tambahan x-data Alpine.js untuk memantau perubahan pilihan Dropdown -->
                <form action="{{ route('teknisi.tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" x-data="{ statusPilihan: '{{ $ticket->status_id }}' }">
                    @csrf
                    <p class="text-xs text-gray-500 font-medium mb-2">Ubah Status Perbaikan:</p>
                    
                    <!-- x-model="statusPilihan" untuk mendeteksi ID yang dipilih -->
                    <select name="status_id" x-model="statusPilihan" class="w-full bg-blue-50 border border-blue-200 text-blue-900 font-bold text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 mb-4">
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>

                    <!-- KOTAK UPLOAD: Hanya muncul jika status yang dipilih adalah '3' (Selesai) -->
                    <div x-show="statusPilihan == 3" style="display: none;" class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-xl border-dashed">
                        <p class="text-xs text-red-500 font-bold mb-2">⚠️ Wajib Upload Foto Bukti Selesai</p>
                        <input type="file" name="bukti_perbaikan" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm px-5 py-3 transition shadow-sm flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Progress
                    </button>
                </form>
            </div>
        </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatBox = document.getElementById('chat-box');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatSubmit = document.getElementById('chat-submit');

            chatBox.scrollTop = chatBox.scrollHeight;

            function updateChat() {
                fetch(window.location.href)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newChatBox = doc.getElementById('chat-box');
                    if(chatBox.innerHTML !== newChatBox.innerHTML) {
                        chatBox.innerHTML = newChatBox.innerHTML;
                        chatBox.scrollTop = chatBox.scrollHeight;
                        if(typeof lucide !== 'undefined') { lucide.createIcons(); }
                    }
                });
            }
            setInterval(updateChat, 3000);

            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                
                chatInput.disabled = true;
                chatSubmit.innerHTML = '<span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(() => {
                    chatInput.value = '';
                    chatInput.disabled = false;
                    chatSubmit.innerHTML = '<svg class="w-5 h-5 ml-1 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>';
                    chatInput.focus();
                    updateChat();
                });
            });
        });
    </script>
</x-app-layout>