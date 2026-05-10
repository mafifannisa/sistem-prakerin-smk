<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Siswa') - Sistem Prakerin SMK N 3 Tuban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-active {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden">
    
    <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
        </div>
        <h1 class="font-bold text-gray-800 text-sm">SMKN 3 TUBAN</h1>
    </div>
        <button onclick="toggleSidebar()" class="p-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity opacity-0 duration-300"></div>

    <div class="flex min-h-screen relative">
        <aside id="sidebar" class="w-64 bg-white shadow-xl fixed inset-y-0 left-0 z-50 h-full overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
            
            <button onclick="toggleSidebar()" class="lg:hidden absolute top-4 right-4 p-2 text-gray-400 hover:bg-gray-100 rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-6 border-b border-gray-100 shrink-0">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="font-bold text-gray-800 text-sm">SMK NEGERI 3 TUBAN</h1>
            <p class="text-xs text-gray-500">Sistem Prakerin</p>
        </div>
    </div>
</div>

            <div class="p-4 border-b border-gray-100 shrink-0">
                <div onclick="openProfilModal()" class="bg-orange-50 hover:bg-orange-100 border border-orange-100 rounded-xl p-4 cursor-pointer transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold shrink-0">
                            {{ substr(session('siswa_nama') ?? 'S', 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ session('siswa_nama') }}</p>
                            <p class="text-xs text-gray-500">NISN: {{ session('siswa_nisn') }}</p>
                            
                            @php
                                $siswaAktif = \App\Models\Siswa::find(session('siswa_id'));
                            @endphp
                            @if($siswaAktif && $siswaAktif->no_wa)
                                <p class="text-xs text-green-600 font-semibold mt-1 flex items-center gap-1 truncate">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                                    {{ $siswaAktif->no_wa }}
                                </p>
                            @else
                                <p class="text-xs text-red-500 mt-1 italic">Belum ada No. WA</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                <a href="{{ route('siswa.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('siswa.dashboard') ? 'sidebar-active' : 'text-gray-600 hover:bg-orange-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <a href="{{ route('siswa.cek-status') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('siswa.cek-status') ? 'sidebar-active' : 'text-gray-600 hover:bg-orange-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="font-medium text-sm">Cek Status Magang</span>
                </a>

                <a href="{{ route('siswa.laporan') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('siswa.laporan*') ? 'sidebar-active' : 'text-gray-600 hover:bg-orange-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-sm">Laporan</span>
                </a>

                <a href="{{ route('siswa.download.surat') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('siswa/download-surat*') ? 'sidebar-active' : 'text-gray-600 hover:bg-orange-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-sm">Download Surat</span>
                </a>

                <a href="{{ route('siswa.download.sertifikat') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('siswa/download-sertifikat*') ? 'sidebar-active' : 'text-gray-600 hover:bg-orange-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span class="font-medium text-sm">Download Sertifikat</span>
                </a>

                <a href="{{ route('siswa.bantuan') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('siswa.bantuan') ? 'sidebar-active' : 'text-gray-600 hover:bg-orange-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-sm">Bantuan</span>
                </a>
            </nav>

            <div class="mt-auto shrink-0 p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600 hover:bg-red-50 hover:text-red-600 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-medium text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 w-full min-h-screen">
            @yield('content')
        </main>
    </div>

    <div id="profilModal" class="fixed inset-0 z-[100] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all scale-95 opacity-0 duration-200" id="profilModalContent">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-800">Update Profil Siswa</h3>
                <button onclick="closeProfilModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('siswa.update.profil') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp Aktif</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                        </span>
                        @php
                            $siswaData = \App\Models\Siswa::find(session('siswa_id'));
                        @endphp
                        <input type="text" name="no_wa" value="{{ $siswaData->no_wa ?? '' }}" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" placeholder="Contoh: 081234567890" required>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Nomor ini wajib diisi untuk menerima info dan balasan bot.
                    </p>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeProfilModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-orange-600 text-white rounded-xl font-medium hover:bg-orange-700 transition shadow-sm hover:shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logika Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Buka Sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                // Tutup Sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300); // Menunggu transisi selesai
            }
        }

        // Logika Popup Modal Profil
        function openProfilModal() {
            const modal = document.getElementById('profilModal');
            const content = document.getElementById('profilModalContent');
            modal.classList.remove('hidden');
            // Animasi masuk
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeProfilModal() {
            const modal = document.getElementById('profilModal');
            const content = document.getElementById('profilModalContent');
            // Animasi keluar
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Tutup modal jika mengklik area luar modal
        document.getElementById('profilModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfilModal();
            }
        });
    </script>
</body>
</html>