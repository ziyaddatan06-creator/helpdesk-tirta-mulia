<x-app-layout>
    <div class="mb-6 mt-4 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-600 mr-4 hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Tiket #{{ $ticket->ticket_number }}</h1>
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
                        IT
                    </div>
                    <div>
                        <h3 class="font-bold leading-tight">Tim Helpdesk IT</h3>
                        <p class="text-[11px] text-green-200">Online</p>
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
                                        {{ $comment->user->name }} (Tim IT)
                                    </span>
                                    <p class="text-sm whitespace-pre-wrap">{{ $comment->body }}</p>
                                    <div class="text-[10px] text-gray-400 text-right mt-1">{{ $comment->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex justify-center items-center h-full">
                            <span class="bg-white/70 text-gray-600 text-xs px-4 py-2 rounded-full shadow-sm">Belum ada percakapan.</span>
                        </div>
                    @endforelse
                </div>

                <div class="p-3 bg-[#f0f2f5]">
                    <form id="chat-form" action="{{ route('pelanggan.tickets.comment', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" name="body" id="chat-input" required placeholder="Ketik balasan untuk Tim IT..." autocomplete="off" class="flex-1 bg-white border-none rounded-full px-4 py-2.5 text-sm focus:ring-0 shadow-sm">
                        <button type="submit" id="chat-submit" class="w-10 h-10 bg-[#00a884] rounded-full flex items-center justify-center text-white hover:bg-[#008f6f] transition shadow-sm shrink-0">
                            <svg class="w-5 h-5 ml-1 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kanan: Status & Kotak Rating -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-2">Informasi Status</h3>
                <p class="text-sm text-gray-600 mb-4">Status saat ini: <strong style="color: {{ $ticket->status->color_code }}">{{ $ticket->status->name }}</strong></p>
                <p class="text-xs text-gray-500">Jika kendala telah selesai, Anda dapat memberikan penilaian di bawah ini.</p>
            </div>

            <!-- KOTAK RATING -->
            @if($ticket->status->name == 'Selesai' && !$ticket->rating)
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 shadow-sm border border-blue-100">
                <h3 class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <i data-lucide="star" class="w-5 h-5 text-yellow-500 fill-yellow-500"></i> Beri Penilaian Teknisi
                </h3>
                <p class="text-xs text-gray-600 mb-4">Mohon berikan penilaian agar pelayanan IT kami terus berkembang.</p>
                
                <form action="{{ route('pelanggan.tickets.rating', $ticket->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Rating Bintang:</label>
                        <select name="rating" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
                            <option value="5">⭐⭐⭐⭐⭐ 5 - Sangat Memuaskan</option>
                            <option value="4">⭐⭐⭐⭐ 4 - Baik</option>
                            <option value="3">⭐⭐⭐ 3 - Cukup</option>
                            <option value="2">⭐⭐ 2 - Kurang</option>
                            <option value="1">⭐ 1 - Buruk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Ulasan (Opsional):</label>
                        <textarea name="review" rows="2" placeholder="Tuliskan komentar..." class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm text-sm transition flex items-center justify-center gap-2">
                        Kirim Penilaian
                    </button>
                </form>
            </div>
            @elseif($ticket->rating)
            <div class="bg-green-50 rounded-2xl p-6 shadow-sm border border-green-100">
                <h3 class="font-bold text-green-900 mb-1 flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600"></i> Penilaian Dikirim
                </h3>
                <p class="text-xs text-green-700 mb-2">Rating Anda: <strong>{{ $ticket->rating }} / 5 Bintang</strong></p>
                @if($ticket->review)
                    <p class="text-xs text-gray-600 italic bg-white p-3 rounded-xl border border-green-200">"{{ $ticket->review }}"</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Script Auto Refresh Chat -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatBox = document.getElementById('chat-box');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
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