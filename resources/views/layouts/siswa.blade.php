<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Siswa') - Sistem Prakerin SMK N 3 Tuban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#eab308',
                            500: '#eab308',
                            600: '#059669',
                            650: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        amber: {
                            50: '#feffec',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#10b981',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .sidebar-active {
            background: linear-gradient(135deg, #eab308 0%, #10b981 100%);
            color: white;
            box-shadow: 0 8px 20px -2px rgba(16, 185, 129, 0.3);
            transform: translateY(-2px);
        }
        /* Custom scrollbar */
        #sidebar::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.1);
            border-radius: 4px;
        }
        /* Nav items dynamic styling */
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-item:hover:not(.sidebar-active) {
            background-color: rgba(240, 253, 244, 0.5);
            color: #059669;
            transform: translateX(4px);
        }
        /* Page transition animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        /* Custom scrollbar for HTML/Body (elegant floating capsule) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #eab308 0%, #10b981 100%);
            border-radius: 99px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #10b981;
            border: 1px solid transparent;
            background-clip: content-box;
        }
        /* Root background fixed */
        html {
            background: linear-gradient(135deg, #fffbeb 0%, #f0fdf4 50%, #ecfdf5 100%) no-repeat center center fixed;
            background-size: cover;
        }

        /* Animated Blobs */
        @keyframes float-blob-1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(60px, 40px) scale(1.15); }
            66% { transform: translate(-40px, 60px) scale(0.9); }
        }
        @keyframes float-blob-2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-50px, -60px) scale(0.85); }
            66% { transform: translate(40px, -30px) scale(1.1); }
        }
        @keyframes float-blob-3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-30px, 50px) scale(1.05); }
        }
        .animate-blob-1 {
            animation: float-blob-1 25s infinite ease-in-out;
        }
        .animate-blob-2 {
            animation: float-blob-2 30s infinite ease-in-out;
        }
        .animate-blob-3 {
            animation: float-blob-3 28s infinite ease-in-out;
        }

    </style>
</head>
<body class="bg-transparent min-h-screen overflow-x-hidden">
    <!-- Animated Ambient Blobs Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1]">
        <div class="absolute top-[-10%] left-[-10%] w-[60vw] h-[60vh] rounded-full bg-yellow-100/40 blur-[130px] animate-blob-1"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[60vw] h-[60vh] rounded-full bg-emerald-200/30 blur-[130px] animate-blob-2"></div>
        <div class="absolute top-[30%] right-[20%] w-[50vw] h-[50vh] rounded-full bg-teal-200/25 blur-[130px] animate-blob-3"></div>
    </div>
    
    <div class="lg:hidden bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
            </div>
            <h1 class="font-bold text-gray-800 text-sm">SMKN 3 TUBAN</h1>
        </div>
        <button onclick="toggleSidebar()" class="p-2 bg-gray-50/80 hover:bg-gray-100 rounded-lg text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-950/40 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity opacity-0 duration-300"></div>

    <div class="flex min-h-screen relative p-0 lg:p-4">
        <!-- Floating Sidebar with Glassmorphism -->
        <aside id="sidebar" class="w-64 bg-white/45 backdrop-blur-xl border-r border-white/20 lg:border lg:border-white/40 shadow-xl lg:shadow-[0_10px_30px_rgba(0,0,0,0.02)] fixed inset-y-0 left-0 lg:left-4 lg:top-4 lg:bottom-4 z-50 h-full lg:h-[calc(100vh-2rem)] overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out flex flex-col rounded-none lg:rounded-2xl">
            
            <button onclick="toggleSidebar()" class="lg:hidden absolute top-4 right-4 p-2 text-gray-400 hover:bg-gray-100 rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="p-6 border-b border-gray-100/30 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden bg-white/40 p-1 border border-white/30">
                        <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-black text-gray-800 text-xs tracking-wider uppercase">SMK NEGERI 3</h1>
                        <p class="text-[10px] text-gray-500 font-semibold tracking-widest uppercase">Sistem Prakerin</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 flex-1 overflow-y-auto">
                <a href="{{ route('siswa.dashboard') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('siswa.dashboard') ? 'sidebar-active' : 'text-gray-650 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-semibold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('siswa.cek-status') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('siswa.cek-status') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="font-semibold text-sm">Cek Status Magang</span>
                </a>

                <a href="{{ route('siswa.laporan') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('siswa.laporan*') && !request()->routeIs('siswa.riwayat*') && !request()->routeIs('siswa.laporan.pkl') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-semibold text-sm">Laporan</span>
                </a>

                <!-- Dropdown Riwayat -->
                <div class="space-y-1">
                    <button onclick="toggleRiwayatDropdown()" 
                        class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('siswa.riwayat*') ? 'sidebar-active text-white' : 'text-gray-655 hover:bg-orange-50/50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-semibold text-sm">Riwayat</span>
                        </div>
                        <svg id="riwayatArrow" class="w-4 h-4 transform transition-transform duration-200 {{ request()->routeIs('siswa.riwayat*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="riwayatDropdownContent" class="{{ request()->routeIs('siswa.riwayat*') ? '' : 'hidden' }} pl-11 pr-2 mt-1 space-y-1">
                        <a href="{{ route('siswa.riwayat.absensi') }}" 
                           class="flex items-center gap-2 py-2 px-3 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('siswa.riwayat.absensi') ? 'text-emerald-600 bg-green-50/50' : 'text-gray-600 hover:text-emerald-600 hover:bg-green-50/30' }}">
                            Riwayat Absensi
                        </a>
                        <a href="{{ route('siswa.riwayat.jurnal') }}" 
                           class="flex items-center gap-2 py-2 px-3 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('siswa.riwayat.jurnal') ? 'text-emerald-600 bg-green-50/50' : 'text-gray-600 hover:text-emerald-600 hover:bg-green-50/30' }}">
                            Riwayat Jurnal
                        </a>
                        <a href="{{ route('siswa.riwayat.laporan') }}" 
                           class="flex items-center gap-2 py-2 px-3 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('siswa.riwayat.laporan') ? 'text-emerald-600 bg-green-50/50' : 'text-gray-600 hover:text-emerald-600 hover:bg-green-50/30' }}">
                            Riwayat Laporan PKL
                        </a>
                        <a href="{{ route('siswa.riwayat.nilai') }}" 
                           class="flex items-center gap-2 py-2 px-3 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('siswa.riwayat.nilai') ? 'text-emerald-600 bg-green-50/50' : 'text-gray-655 hover:text-emerald-600 hover:bg-green-50/30' }}">
                            Riwayat Input Nilai
                        </a>
                    </div>
                </div>

                <a href="{{ route('siswa.download.surat') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->is('siswa/download-surat*') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-semibold text-sm">Download Surat</span>
                </a>

                <a href="{{ route('siswa.download.sertifikat') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->is('siswa/download-sertifikat*') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span class="font-semibold text-sm">Download Sertifikat</span>
                </a>

                <a href="{{ route('siswa.bantuan') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('siswa.bantuan') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold text-sm">Bantuan</span>
                </a>
            </nav>

            @php
                $loggedSiswa = \App\Models\Siswa::with(['jurusan', 'kelas'])->find(session('siswa_id'));
            @endphp
            <div class="mt-auto shrink-0 p-4 border-t border-gray-100/60 space-y-3">
                @if($loggedSiswa)
                <button onclick="openProfilModal()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50/50 transition duration-200 text-left w-full border border-transparent hover:border-green-100/60 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#10b981] to-[#047857] flex items-center justify-center text-white font-extrabold text-xs shadow-sm group-hover:scale-105 transition-transform duration-200 uppercase shrink-0">
                        {{ substr($loggedSiswa->nama, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-xs truncate group-hover:text-emerald-600 transition-colors">{{ $loggedSiswa->nama }}</p>
                        <p class="text-[9px] text-gray-450 font-bold tracking-wider truncate">NISN: {{ $loggedSiswa->nisn }}</p>
                    </div>
                    <div class="text-gray-400 group-hover:text-emerald-500 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </button>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600 hover:bg-red-50 hover:text-red-600 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-semibold text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area adjusted for floating sidebar -->
        <main class="flex-1 lg:ml-72 min-w-0 w-full min-h-[calc(100vh-2rem)] p-4 lg:px-8 lg:pb-8 lg:pt-0 mt-0 lg:my-0">
            <div class="animate-fade-in-up">
                <!-- Premium Global Header -->
                <header class="relative z-30 bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl px-6 py-4 mb-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)]">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-0.5">
                                <span>{{ session('siswa_nama') }}</span>
                                <span class="text-gray-300">/</span>
                                <span class="text-orange-500">@yield('header_breadcrumb', 'Dashboard')</span>
                            </div>
                            <h1 class="text-xl font-extrabold text-gray-800 leading-tight tracking-wider">@yield('header_title', 'SISTEM PRAKERIN')</h1>
                        </div>
                        <div class="flex items-center gap-4">
                            @yield('header_actions')
                            <!-- Tanggal (Di samping kiri notifikasi) -->
                            <div class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100 hidden md:block shrink-0">
                                {{ tanggal_indonesia() }}
                            </div>

                            <!-- Notifikasi -->
                            <div class="relative">
                                <button onclick="toggleNotifikasi()" class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-500 hover:text-orange-600 border border-gray-100 transition relative">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if($globalNotifikasiUnread > 0)
                                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-bounce">
                                            {{ $globalNotifikasiUnread }}
                                        </span>
                                    @endif
                                </button>
                                
                                <div id="notifikasiDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in-up">
                                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-800">Notifikasi</h3>
                                            <a href="#" class="text-xs text-blue-600 hover:underline font-medium">Tandai semua dibaca</a>
                                        </div>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @forelse($globalNotifikasis as $notifikasi)
                                            <div class="p-4 border-b border-gray-100 hover:bg-orange-50/30 transition {{ !$notifikasi->is_read ? 'bg-orange-50/10' : '' }}">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-2 h-2 mt-2 rounded-full 
                                                        @if($notifikasi->jenis === 'success') bg-green-500
                                                        @elseif($notifikasi->jenis === 'warning') bg-yellow-500
                                                        @elseif($notifikasi->jenis === 'error') bg-red-500
                                                        @else bg-blue-500 @endif">
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-sm text-gray-800">{{ $notifikasi->judul }}</p>
                                                        <p class="text-xs text-gray-650 mt-1">{{ $notifikasi->pesan }}</p>
                                                        <p class="text-xs text-gray-400 mt-2">{{ $notifikasi->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-8 text-center text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                                <p class="text-sm">Belum ada notifikasi</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="p-3 border-t border-gray-100 bg-gray-50/50 text-center">
                                        <a href="#" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua Notifikasi</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Ikon Profil Bulat dengan Dropdown -->
                            <div class="relative">
                                <button onclick="toggleUserDropdown()" class="w-10 h-10 bg-gradient-to-tr from-[#10b981] to-[#047857] rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-sm border border-white hover:scale-105 active:scale-95 transition-all duration-200">
                                    {{ substr(session('siswa_nama') ?? 'S', 0, 2) }}
                                </button>

                                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in-up">
                                    <div class="p-5 border-b border-gray-100 bg-gradient-to-br from-orange-50/30 to-orange-100/10">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-tr from-[#10b981] to-[#047857] rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                                                {{ substr(session('siswa_nama') ?? 'S', 0, 2) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-800 text-sm truncate">{{ session('siswa_nama') }}</h4>
                                                <p class="text-[10px] text-gray-400 font-medium">NISN: {{ session('siswa_nisn') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $siswaDetail = \App\Models\Siswa::find(session('siswa_id'));
                                    @endphp
                                    <div class="p-4 space-y-3 text-xs">
                                        <div class="flex items-center gap-3 text-gray-650">
                                            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span class="font-medium truncate">{{ $siswaDetail && $siswaDetail->no_wa ? $siswaDetail->no_wa : 'Belum Atur No. WA' }}</span>
                                        </div>
                                        
                                        <button onclick="openProfilModal()" class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold rounded-xl border border-orange-100 transition duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Edit Profil
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                @yield('content')
            </div>
        </main>
    </div>

    <div id="profilModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-95 opacity-0 duration-300 border border-gray-100/80 flex flex-col max-h-[90vh]" id="profilModalContent">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50/50 to-emerald-50/50 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#10b981] to-[#047857] flex items-center justify-center text-white font-extrabold text-sm shadow-sm uppercase shrink-0">
                        {{ substr($loggedSiswa->nama ?? 'S', 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-800 tracking-wide">Profil Siswa</h3>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Detail Informasi & Pengaturan</p>
                    </div>
                </div>
                <button onclick="closeProfilModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-6 flex-1 text-sm">
                <!-- Status Badge -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Status Akun</p>
                        <p class="text-gray-700 font-semibold text-xs">Aktif sebagai siswa prakerin</p>
                    </div>
                    <span class="px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 font-extrabold text-xs uppercase rounded-full tracking-wider">
                        ● {{ $loggedSiswa && $loggedSiswa->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </div>

                <!-- Personal Info Grid -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-gray-800 uppercase tracking-wider text-xs border-b border-gray-100 pb-2">📋 Biodata Diri</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Lengkap</p>
                            <p class="text-gray-800 font-bold">{{ $loggedSiswa->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">NISN</p>
                            <p class="text-gray-800 font-bold tracking-wider">{{ $loggedSiswa->nisn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Kelas</p>
                            <p class="text-gray-800 font-semibold">{{ $loggedSiswa->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Jurusan</p>
                            <p class="text-gray-800 font-semibold">{{ $loggedSiswa->jurusan->nama_jurusan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tempat Lahir</p>
                            <p class="text-gray-800 font-semibold">{{ $loggedSiswa->tempat_lahir ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Lahir</p>
                            <p class="text-gray-800 font-semibold">
                                {{ $loggedSiswa && $loggedSiswa->tanggal_lahir ? $loggedSiswa->tanggal_lahir->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email</p>
                            <p class="text-gray-800 font-semibold">{{ $loggedSiswa->email ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Lengkap</p>
                            <p class="text-gray-800 font-semibold leading-relaxed">{{ $loggedSiswa->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Guardian Info Grid -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-gray-800 uppercase tracking-wider text-xs border-b border-gray-100 pb-2">👥 Kontak Orang Tua / Wali</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Wali</p>
                            <p class="text-gray-800 font-bold">{{ $loggedSiswa->nama_wali ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">No. WA Wali</p>
                            <p class="text-gray-800 font-semibold">{{ $loggedSiswa->no_wa_wali ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Edit WA Form -->
                <div class="border-t border-gray-100 pt-5 space-y-4">
                    <h4 class="font-extrabold text-gray-800 uppercase tracking-wider text-xs">📞 Update Kontak WhatsApp Anda</h4>
                    <form action="{{ route('siswa.update.profil') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nomor WhatsApp Aktif</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                                </span>
                                <input type="text" name="no_wa" value="{{ $loggedSiswa->no_wa ?? '' }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm font-semibold" placeholder="Contoh: 081234567890" required>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Nomor ini wajib diisi untuk menerima info dan balasan bot.
                            </p>
                        </div>
                        <div class="flex justify-end gap-3 pt-1">
                            <button type="button" onclick="closeProfilModal()" class="px-4 py-2.5 bg-gray-105 text-gray-700 rounded-xl font-bold text-xs hover:bg-gray-200 transition">Batal</button>
                            <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-sm">Simpan WhatsApp</button>
                        </div>
                    </form>
                </div>
            </div>
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

        // Dropdown Riwayat Toggle
        function toggleRiwayatDropdown() {
            const dropdown = document.getElementById('riwayatDropdownContent');
            const arrow = document.getElementById('riwayatArrow');
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                dropdown.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        // Dropdown Header
        function toggleNotifikasi() {
            const dropdown = document.getElementById('notifikasiDropdown');
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) userDropdown.classList.add('hidden');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const notifDropdown = document.getElementById('notifikasiDropdown');
            if (notifDropdown) notifDropdown.classList.add('hidden');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notifDropdown = document.getElementById('notifikasiDropdown');
            const userDropdown = document.getElementById('userDropdown');
            
            if (notifDropdown && !event.target.closest('button[onclick*="toggleNotifikasi"]') && !notifDropdown.contains(event.target)) {
                notifDropdown.classList.add('hidden');
            }
            if (userDropdown && !event.target.closest('button[onclick*="toggleUserDropdown"]') && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id$="Modal"], [id^="modal-"], [id^="modal_"], .modal-container').forEach(function(el) {
                if (el.parentElement !== document.body && !el.closest('#sidebar') && el.id !== 'sidebarOverlay') {
                    document.body.appendChild(el);
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (typeof closeModal === 'function') closeModal();
                if (typeof closeProfilModal === 'function') closeProfilModal();
            }
        });
    </script>
    @yield('modals')
    @stack('modals')
    @stack('scripts')
</body>
</html>