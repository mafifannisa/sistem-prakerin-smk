<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Sistem Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
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
                        <h1 class="font-bold text-gray-800 text-sm">Sistem Prakerin</h1>
                        <p class="text-xs text-gray-500">SMKN 3 TUBAN</p>
                    </div>
                </div>
            </div>

            <div class="p-4 border-b border-gray-100 bg-blue-50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 shadow-sm">
                        {{ substr(Auth::user()->nama_lengkap ?? 'AD', 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ Auth::user()->nama_lengkap ?? 'Administrator' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@smk3tuban.sch.id' }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.data-siswa') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.data-siswa') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium text-sm">Data Siswa</span>
                </a>

                <a href="{{ route('admin.data-industri') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.data-industri') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-medium text-sm">Data Industri</span>
                </a>

                <a href="{{ route('admin.data-jurusan') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.data-jurusan') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="font-medium text-sm">Data Jurusan</span>
                </a>

                <a href="{{ route('admin.data.surat') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.data.surat') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-sm">Data Surat</span>
                </a>

                <a href="{{ route('admin.import-nilai.view') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.import-nilai*') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span class="font-medium text-sm">Import Nilai</span>
                </a>

                <a href="{{ route('admin.generate-sertifikat.view') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.generate-sertifikat*') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span class="font-medium text-sm">Generate Sertifikat</span>
                </a>

                <a href="{{ route('admin.wa-blast') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.wa-blast') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span class="font-medium text-sm">WhatsApp Blast</span>
                </a>

                <a href="{{ route('admin.laporan-cetak') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.laporan-cetak') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-sm">Laporan & Cetak</span>
                </a>

                <div class="mb-4">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi</p>
                    <a href="{{ route('admin.pengumuman') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition group {{ request()->routeIs('admin.pengumuman') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="text-sm font-medium">Pengumuman</span>
                    </a>
                </div>
                <a href="{{ route('admin.kontrol-magang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.kontrol-magang') ? 'sidebar-active' : 'text-gray-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-sm">Kontrol Magang</span>
                </a>
            </nav>

            <div class="mt-auto shrink-0 p-4 border-t border-gray-100 bg-white">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600 hover:bg-red-50 hover:text-red-600 w-full font-medium">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 w-full min-h-screen pb-10">
            @yield('content')
        </main>
    </div>

    <script>
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
    </script>

    @stack('scripts')
</body>
</html>