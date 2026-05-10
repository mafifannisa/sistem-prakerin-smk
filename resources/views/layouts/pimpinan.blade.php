<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Pimpinan') - Sistem Prakerin SMK N 3 Tuban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
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
            <h1 class="font-bold text-gray-800 text-sm leading-tight">Administrasi Prakerin</h1>
        </div>
        <button onclick="toggleSidebar()" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
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
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-black text-gray-800 text-sm leading-tight">Administrasi</h1>
                        <p class="text-blue-600 font-extrabold text-[10px] uppercase tracking-widest">Prakerin</p>
                    </div>
                </div>
            </div>

            <div class="p-4 border-b border-gray-100 bg-blue-50/30 shrink-0">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_lengkap ?? 'Pimpinan') }}&background=3b82f6&color=fff&rounded=true" 
                         alt="Profile" 
                         class="w-11 h-11 rounded-full shadow-sm shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm truncate">{{ Auth::user()->nama_lengkap ?? 'Kepala SMK' }}</p>
                        <p class="text-[11px] font-medium text-gray-500 truncate">
                            {{ (isset(Auth::user()->role) && Auth::user()->role == 'pimpinan') ? 'Kepala Sekolah' : 'Administrator' }}
                        </p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2 mt-2 flex-1 overflow-y-auto">
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Menu Utama</p>
                
                <a href="{{ route('pimpinan.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition group {{ request()->routeIs('pimpinan.dashboard') ? 'sidebar-active text-white' : 'text-gray-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('pimpinan.dashboard') ? 'text-white' : 'group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('pimpinan.approval.surat') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition group {{ request()->routeIs('pimpinan.approval.surat') ? 'sidebar-active text-white' : 'text-gray-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('pimpinan.approval.surat') ? 'text-white' : 'group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-bold text-sm">Approval Surat</span>
                </a>

                <a href="{{ route('pimpinan.statistik') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition group {{ request()->routeIs('pimpinan.statistik') ? 'sidebar-active text-white' : 'text-gray-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('pimpinan.statistik') ? 'text-white' : 'group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-bold text-sm">Statistik</span>
                </a>

                <a href="{{ route('pimpinan.laporan') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition group {{ request()->routeIs('pimpinan.laporan') ? 'sidebar-active text-white' : 'text-gray-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('pimpinan.laporan') ? 'text-white' : 'group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-bold text-sm">Pusat Laporan</span>
                </a>
            </nav>

            <div class="mt-auto shrink-0 p-4 border-t border-gray-100 bg-white">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition text-gray-500 hover:bg-red-50 hover:text-red-600 w-full font-bold">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 w-full min-h-screen pb-10 transition-all duration-300">
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
                document.body.classList.add('overflow-hidden');
            } else {
                // Tutup Sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 300);
            }
        }

        // Tutup otomatis jika layar kembali besar
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden', 'opacity-0');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
</body>
</html>