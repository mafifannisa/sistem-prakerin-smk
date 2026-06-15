<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Sistem Prakerin SMK N 3 Tuban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif !important;
        }
        .sidebar-active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 8px 20px -2px rgba(29, 78, 216, 0.3);
            transform: translateY(-2px);
        }
        /* Custom scrollbar */
        #sidebar::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.1);
            border-radius: 4px;
        }
        /* Nav items dynamic styling */
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-item:hover:not(.sidebar-active) {
            background-color: rgba(219, 234, 254, 0.4);
            color: #1d4ed8;
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
        /* Custom scrollbar for HTML/Body */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 99px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
            border: 1px solid transparent;
            background-clip: content-box;
        }
        /* Root background fixed */
        html {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 60%, #e0f2fe 100%) no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-transparent min-h-screen overflow-x-hidden">
    
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
        <aside id="sidebar" class="w-64 bg-white/85 backdrop-blur-md border-r border-gray-150 lg:border lg:border-white/40 shadow-xl lg:shadow-[0_10px_30px_rgba(0,0,0,0.04)] fixed inset-y-0 left-0 lg:left-4 lg:top-4 lg:bottom-4 z-50 h-full lg:h-[calc(100vh-2rem)] overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out flex flex-col rounded-none lg:rounded-2xl">
            
            <button onclick="toggleSidebar()" class="lg:hidden absolute top-4 right-4 p-2 text-gray-400 hover:bg-gray-100 rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-6 border-b border-gray-100/60 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden bg-white/50 p-1 border border-blue-100">
                        <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-black text-gray-800 text-xs tracking-wider uppercase">SMK NEGERI 3</h1>
                        <p class="text-[10px] text-gray-500 font-semibold tracking-widest uppercase">Sistem Prakerin</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-semibold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.data-siswa') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.data-siswa') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-semibold text-sm">Data Siswa</span>
                </a>

                <a href="{{ route('admin.data-industri') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.data-industri') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-semibold text-sm">Data Industri</span>
                </a>

                <a href="{{ route('admin.data-jurusan') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.data-jurusan') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="font-semibold text-sm">Data Jurusan</span>
                </a>

                <a href="{{ route('admin.data-guru') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.data-guru') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-semibold text-sm">Data Guru</span>
                </a>

                <a href="{{ route('admin.data-magang-all') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.data-magang-all') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-semibold text-sm">Data Magang (Semua)</span>
                </a>

                <a href="{{ route('admin.laporan-masalah-all') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.laporan-masalah-all') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-semibold text-sm">Laporan Masalah</span>
                </a>

                <a href="{{ route('admin.data.surat') }}" 
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.data.surat') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-semibold text-sm">Cetak Surat</span>
                </a>

                <a href="{{ route('admin.import-nilai.view') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.import-nilai*') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span class="font-semibold text-sm">Import Nilai</span>
                </a>

                <a href="{{ route('admin.generate-sertifikat.view') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.generate-sertifikat*') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span class="font-semibold text-sm">Generate Sertifikat</span>
                </a>

                <a href="{{ route('admin.wa-blast') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.wa-blast') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span class="font-semibold text-sm">WhatsApp Blast</span>
                </a>

                <a href="{{ route('admin.laporan-cetak') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.laporan-cetak') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-semibold text-sm">Laporan & Cetak</span>
                </a>

                <div class="pt-2">
                    <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Informasi</p>
                    
                    <a href="{{ route('admin.pengumuman') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('admin.pengumuman') ? 'sidebar-active' : 'text-gray-655 hover:bg-blue-50/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="font-semibold text-sm">Pengumuman</span>
                    </a>


                </div>
            </nav>

            <div class="mt-auto shrink-0 p-4 border-t border-gray-100/60">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600 hover:bg-red-50 hover:text-red-600 w-full">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <header class="relative z-30 bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl px-6 py-4 mb-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)] flex flex-col gap-3">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-0.5">
                                <span>{{ Auth::user()->username ?? 'ADMIN' }}</span>
                                <span class="text-gray-300">/</span>
                                <span class="text-blue-500 font-extrabold flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                                    @yield('header_breadcrumb', 'Dashboard')
                                </span>
                            </div>
                            <h1 class="text-lg md:text-xl font-black text-gray-800 leading-tight tracking-wider uppercase">@yield('header_title', 'SISTEM PRAKERIN')</h1>
                        </div>
                        
                        <div class="flex items-center gap-3 shrink-0">
                            <!-- Tanggal (Date Display) -->
                            <div class="text-xs font-semibold text-gray-500 bg-white/50 backdrop-blur-sm px-3 py-2 rounded-xl border border-gray-150 hidden sm:block shrink-0">
                                {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('l, d M Y') }}
                            </div>

                            <!-- Notifikasi (Bell Dropdown) -->
                            <div class="relative">
                                <button onclick="toggleNotifikasi()" class="w-10 h-10 rounded-xl bg-white/50 backdrop-blur-sm hover:bg-gray-105 flex items-center justify-center text-gray-500 hover:text-blue-600 border border-gray-150 transition relative">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if(isset($globalSuratPending) && $globalSuratPending > 0)
                                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-bounce">
                                            {{ $globalSuratPending }}
                                        </span>
                                    @endif
                                </button>
                                
                                <div id="notifikasiDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in-up">
                                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                                        <h3 class="font-bold text-gray-800">Pemberitahuan Sistem</h3>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        @if(isset($globalSuratPending) && $globalSuratPending > 0)
                                            <a href="{{ route('admin.data.surat') }}" class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-red-50 transition border border-red-100 bg-red-50/20">
                                                <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-red-500 shrink-0"></div>
                                                <div>
                                                    <p class="font-bold text-xs text-red-800">Persetujuan Surat Tertunda</p>
                                                    <p class="text-[11px] text-red-650 mt-0.5">Ada {{ $globalSuratPending }} surat permohonan magang yang perlu segera ditinjau dan divalidasi.</p>
                                                </div>
                                            </a>
                                        @else
                                            <div class="p-4 text-center text-gray-500">
                                                <p class="text-xs font-semibold">Tidak ada pemberitahuan baru.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Ikon Profil Bulat dengan Dropdown -->
                            <div class="relative">
                                <button onclick="toggleUserDropdown()" class="w-10 h-10 bg-gradient-to-tr from-blue-500 to-indigo-400 rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-sm border border-white hover:scale-105 active:scale-95 transition-all duration-200">
                                    {{ substr(Auth::user()->nama_lengkap ?? 'A', 0, 2) }}
                                </button>

                                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in-up">
                                    <div class="p-5 border-b border-gray-100 bg-gradient-to-br from-blue-50/30 to-blue-100/10">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-tr from-blue-500 to-indigo-400 rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                                                {{ substr(Auth::user()->nama_lengkap ?? 'A', 0, 2) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-800 text-sm truncate">{{ Auth::user()->nama_lengkap ?? 'Administrator' }}</h4>
                                                <p class="text-[10px] text-gray-400 font-medium">Role: Admin Utama</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 space-y-3 text-xs">
                                        <div class="flex items-center gap-3 text-gray-600">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium truncate">{{ Auth::user()->email ?? 'admin@smk3tuban.sch.id' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @hasSection('header_actions')
                        <div class="border-t border-gray-150/50 pt-3 w-full">
                            @yield('header_actions')
                        </div>
                    @endif
                </header>

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function toggleNotifikasi() {
            const dropdown = document.getElementById('notifikasiDropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const btn = event.target.closest('button[onclick="toggleUserDropdown()"]');
            if (dropdown && !dropdown.contains(event.target) && !btn) {
                dropdown.classList.add('hidden');
            }

            const notifDropdown = document.getElementById('notifikasiDropdown');
            const notifBtn = event.target.closest('button[onclick="toggleNotifikasi()"]');
            if (notifDropdown && !notifDropdown.contains(event.target) && !notifBtn) {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>