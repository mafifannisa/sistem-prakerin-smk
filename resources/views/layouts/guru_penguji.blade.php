<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru Penguji') - Sistem Prakerin SMK N 3 Tuban</title>
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
        /* Custom scrollbar for HTML/Body */
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
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 60%, #f0fdf4 100%) no-repeat center center fixed;
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
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden bg-white/50 p-1 border border-orange-100">
                        <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-black text-gray-800 text-xs tracking-wider uppercase">SMK NEGERI 3</h1>
                        <p class="text-[10px] text-gray-500 font-semibold tracking-widest uppercase">Sistem Prakerin</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 flex-1 overflow-y-auto">
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Menu Utama</p>

                <a href="{{ route('guru_penguji.dashboard') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('guru_penguji.dashboard') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-semibold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('guru_penguji.ujian-magang') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-250 {{ request()->routeIs('guru_penguji.ujian-magang') ? 'sidebar-active' : 'text-gray-655 hover:bg-orange-50/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M15 11l-6 6m0-6l6 6"/>
                    </svg>
                    <span class="font-semibold text-sm">Ujian Magang</span>
                </a>
            </nav>

            <div class="mt-auto shrink-0 p-4 border-t border-gray-100/60">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-655 hover:bg-red-50 hover:text-red-600 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-semibold text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 lg:ml-72 min-w-0 w-full min-h-[calc(100vh-2rem)] p-4 lg:px-8 lg:pb-8 lg:pt-0 mt-0 lg:my-0">
            <div class="animate-fade-in-up">
                <!-- Premium Global Header -->
                <header class="relative z-30 bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl px-6 py-4 mb-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)]">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-0.5">
                                <span>{{ Auth::user()->nama_lengkap }}</span>
                                <span class="text-gray-300">/</span>
                                <span class="text-orange-500">@yield('header_breadcrumb', 'Guru Penguji')</span>
                            </div>
                            <h1 class="text-xl font-extrabold text-gray-800 leading-tight tracking-wider">@yield('header_title', 'SISTEM PRAKERIN')</h1>
                        </div>
                        <div class="flex items-center gap-4">
                            @yield('header_actions')
                            <div class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100 hidden md:block shrink-0">
                                {{ tanggal_indonesia() }}
                            </div>

                            <!-- User Avatar Dropdown -->
                            <div class="relative">
                                <button onclick="toggleUserDropdown()" class="w-10 h-10 bg-gradient-to-tr from-orange-500 to-amber-400 rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-sm border border-white hover:scale-105 active:scale-95 transition-all duration-200">
                                    {{ substr(Auth::user()->nama_lengkap ?? 'U', 0, 2) }}
                                </button>

                                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in-up">
                                    <div class="p-5 border-b border-gray-100 bg-gradient-to-br from-orange-50/30 to-orange-100/10">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-tr from-orange-500 to-amber-400 rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                                                {{ substr(Auth::user()->nama_lengkap ?? 'U', 0, 2) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-800 text-sm truncate">{{ Auth::user()->nama_lengkap }}</h4>
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase">Guru Penguji</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 text-xs">
                                        <div class="flex items-center gap-3 text-gray-650 mb-3">
                                            <span class="font-medium truncate">Email: {{ Auth::user()->email ?? '-' }}</span>
                                        </div>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold rounded-xl border border-orange-100 transition duration-200">
                                                Keluar Aplikasi
                                            </button>
                                        </form>
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

        document.addEventListener('click', function(event) {
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown && !event.target.closest('button[onclick*="toggleUserDropdown"]') && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
