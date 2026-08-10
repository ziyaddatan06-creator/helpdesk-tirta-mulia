<x-app-layout>
    <div class="mb-6 mt-4 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-600 mr-4 hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Akun</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl flex items-start shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <!-- KOLOM KIRI: Form Tambah Akun -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-6">
                <div class="flex items-center mb-4 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 mr-3">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Buat Akun Baru</h2>
                </div>
                
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" required placeholder="budi@tirtamulia.test" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3">
                    </div>

                    <!-- BAGIAN PASSWORD DENGAN FITUR SHOW/HIDE (MATA) -->
                    <div class="mb-4" x-data="{ showPassword: false }">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi (Password)</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 pr-10">
                            
                            <!-- Tombol Mata -->
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none">
                                
                                <!-- Ikon Mata Terbuka (Muncul saat password disembunyikan) -->
                                <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                
                                <!-- Ikon Mata Tertutup Coret (Muncul saat password terlihat) -->
                                <svg x-show="showPassword" style="display: none;" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                                
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hak Akses (Role)</label>
                        <select name="role" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3">
                            <option value="Pelanggan">Pegawai Kantor (User Biasa)</option>
                            <option value="Teknisi">Teknisi Lapangan</option>
                            <option value="Admin">Admin IT</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm px-5 py-3 transition shadow-sm flex justify-center items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Akun
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Tabel Daftar Akun -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-900">Daftar Akun Terdaftar</h2>
                    <span class="text-xs font-bold bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">{{ $users->count() }} Pengguna</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 bg-gray-50 uppercase border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Pengguna</th>
                                <th class="px-6 py-4 text-center">Hak Akses</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $u)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($u->avatar)
                                            <img src="{{ asset('storage/' . $u->avatar) }}" class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-white shadow-sm" alt="Foto">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold mr-3 border-2 border-white shadow-sm">
                                                {{ substr($u->name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <span class="font-bold text-gray-900 block">{{ $u->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $u->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @foreach($u->roles as $role)
                                        @if($role->name == 'Super Admin' || $role->name == 'Admin')
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-purple-100 text-purple-700">{{ $role->name }}</span>
                                        @elseif($role->name == 'Teknisi')
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-orange-100 text-orange-700">{{ $role->name }}</span>
                                        @else
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-blue-100 text-blue-700">Pegawai</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <!-- TOMBOL HAPUS AKUN -->
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-white hover:bg-red-500 font-medium rounded-lg text-xs px-3 py-1.5 border border-red-500 transition shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>