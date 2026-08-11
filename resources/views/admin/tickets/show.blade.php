<x-app-layout>
    <div class="mb-6 mt-4 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.tickets.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-600 mr-4 hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Tiket #{{ $ticket->ticket_number }}</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl flex items-center shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2 flex-shrink-0"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Kiri: Detail & Chat -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 border-l-4" style="border-left-color: {{ $ticket->status->color_code }}">
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $ticket->title }}</h2>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-black px-2.5 py-1 rounded-full uppercase tracking-wider border {{ $ticket->priority_color ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $ticket->priority_name ?? 'Biasa' }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-md" style="background-color: {{ $ticket->status->color_code }}20; color: {{ $ticket->status->color_code }}">
                        {{ $ticket->status->name }}
                    </span>
                </div>
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-2 font-semibold">Deskripsi Kendala:</p>
                    <p class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $ticket->description }}</p>
                </div>
                @if($ticket->attachments->count() > 0)
                    <div>
                        <p class="text-sm text-gray-500 mb-2 font-semibold">Foto Lampiran / Bukti:</p>
                        <div class="flex gap-4 flex-wrap">
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="block w-40 h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:opacity-90">
                                    <img src="{{ asset('storage/' . $attachment->file_path) }}" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- LIVE CHAT AREA -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: 500px;">
                <div class="p-3 bg-[#005c4b] text-white flex items-center gap-3 z-10 shadow-md">
                    <div class="w-10 h-10 bg-white text-[#005c4b] rounded-full flex items-center justify-center font-bold text-lg">
                        {{ substr($ticket->customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold leading-tight">{{ $ticket->customer->name }}</h3>
                        <p class="text-[11px] text-green-200">Pegawai (Pelapor)</p>
                    </div>
                </div>
                
                <div id="chat-box" class="p-4 flex-1 overflow-y-auto space-y-3 bg-[#efeae2]" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
                    @forelse($ticket->comments as $comment)
                        @if(str_contains($comment->body, 'Sistem:'))
                            <div class="flex justify-center my-2">
                                <span class="bg-yellow-100/90 text-yellow-800 text-[11px] px-3 py-1 rounded-lg font-medium text-center shadow-sm">
                                    {{ $comment->body }}
                                </span>
                            </div>
                        @elseif($comment->user_id === auth()->id())
                            <div class="flex justify-end">
                                <div class="bg-[#d9fdd3] text-gray-800 p-2.5 rounded-lg rounded-tr-none max-w-[80%] shadow-sm relative">
                                    <p class="text-sm whitespace-pre-wrap">{{ $comment->body }}</p>
                                    <div class="text-[10px] text-gray-500 text-right mt-1">
                                        {{ $comment->created_at->format('H:i') }} <i data-lucide="check-check" class="w-3 h-3 inline text-blue-500"></i>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start">
                                <div class="bg-white text-gray-800 p-2.5 rounded-lg rounded-tl-none max-w-[80%] shadow-sm relative">
                                    <span class="text-[11px] font-bold text-[#eb6161] block mb-0.5">
                                        {{ $comment->user->name }} 
                                        @if($comment->user->hasRole('Teknisi')) (Teknisi) @else (Admin) @endif
                                    </span>
                                    <p class="text-sm whitespace-pre-wrap">{{ $comment->body }}</p>
                                    <div class="text-[10px] text-gray-400 text-right mt-1">{{ $comment->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex justify-center items-center h-full">
                            <span class="bg-white/70 text-gray-600 text-xs px-4 py-2 rounded-full shadow-sm">Belum ada percakapan. Mulai chat dengan pegawai.</span>
                        </div>
                    @endforelse
                </div>

                <div class="p-3 bg-[#f0f2f5]">
                    <form id="chat-form" action="{{ route('admin.tickets.comment', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" name="body" id="chat-input" required placeholder="Ketik balasan untuk pegawai..." autocomplete="off" class="flex-1 bg-white border-none rounded-full px-4 py-2.5 text-sm focus:ring-0 shadow-sm">
                        <button type="submit" id="chat-submit" class="w-10 h-10 bg-[#00a884] rounded-full flex items-center justify-center text-white hover:bg-[#008f6f] transition shadow-sm shrink-0">
                            <svg class="w-5 h-5 ml-1 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- KANAN: Status & Delegasi Teknisi -->
        <div class="space-y-6">
            
            <!-- Update Status -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Update Status</h3>
                <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    <select name="status_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 mb-4">
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ $ticket->status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 font-bold rounded-xl text-sm px-5 py-3 transition shadow-sm">
                        Simpan Status
                    </button>
                </form>
            </div>

            <!-- KOTAK ORANGE: DELEGASI TEKNISI -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-200 bg-orange-50/30">
                <h3 class="font-bold text-orange-800 mb-4 border-b border-orange-200 pb-2 flex items-center gap-2">
                    <i data-lucide="hard-hat" class="w-5 h-5"></i> Delegasi Teknisi
                </h3>
                
                @if($ticket->technician_id)
                    <div class="mb-4 p-3 bg-white border border-orange-200 rounded-xl flex items-center gap-3 shadow-sm">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">
                            {{ substr($ticket->technician->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Ditugaskan Kepada:</p>
                            <p class="font-bold text-gray-900 text-sm">{{ $ticket->technician->name }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-red-500 font-bold mb-4">⚠️ Belum ada teknisi yang ditugaskan!</p>
                @endif

                <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST">
                    @csrf
                    <select name="technician_id" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block p-3 mb-4">
                        <option value="">-- Pilih Teknisi Lapangan --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full text-white bg-orange-600 hover:bg-orange-700 font-bold rounded-xl text-sm px-5 py-3 transition shadow-sm">
                        Tugaskan Teknisi
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Live Chat Auto Refresh Script -->
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
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(() => {
                    chatInput.value = '';
                    chatInput.disabled = false;
                    chatInput.focus();
                    updateChat();
                });
            });
        });
    </script>
</x-app-layout>