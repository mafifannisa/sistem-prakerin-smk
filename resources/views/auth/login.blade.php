<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Prakerin SMK N 3 Tuban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/bg.jpg') }}" 
             alt="Logo SMK N 3 Tuban" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>

    <div class="relative z-10 min-h-screen flex">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="w-24 h-24 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMK N 3 Tuban" class="w-full h-full object-contain drop-shadow-xl">
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-white">SMK Negeri 3 Tuban</h1>
                    <p class="text-white/80 text-sm mt-2">Sistem Administrasi Prakerin</p>
                </div>

                <div class="glass-effect rounded-3xl shadow-2xl p-8">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-500/20 border border-red-400/50 text-red-100 px-4 py-3 rounded-xl">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="identity" class="block text-white/90 text-sm font-semibold mb-2">
                                Username atau NISN
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <input type="text" 
                                       id="identity" 
                                       name="identity" 
                                       value="{{ old('identity') }}"
                                       class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent backdrop-blur-sm"
                                       placeholder="Masukkan NISN atau ID"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-white/90 text-sm font-semibold mb-2">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent backdrop-blur-sm"
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>



                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-white/30 rounded focus:ring-blue-500 bg-white/10">
                                <span class="ml-2 text-sm text-white/70">Ingat saya</span>
                            </label>
                            <a href="{{ route('contact.admin') }}" class="text-sm text-white/80 hover:text-white transition">Lupa kata sandi?</a>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 shadow-lg hover:shadow-xl">
                            Masuk Ke Sistem
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-white/70 text-sm">
                            Belum punya akun? 
                            <a href="{{ route('contact.admin') }}" class="text-blue-300 hover:text-blue-200 font-semibold">
                                Hubungi Admin Sekolah
                            </a>
                        </p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10 text-center text-xs text-white/50">
                        <p>&copy; {{ date('Y') }} SMK NEGERI 3 TUBAN. ALL RIGHTS RESERVED.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 flex-col justify-center items-center p-12 text-white relative">
            <div class="absolute top-8 right-8">
                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                    PORTAL PRAKERIN
                </span>
            </div>

            <div class="text-center mb-12">
                <h2 class="text-5xl font-bold mb-6 leading-tight">Mempersiapkan<br>Generasi Unggul di<br>Dunia Industri</h2>
                <p class="text-lg text-white/80 max-w-xl">
                    Sistem Administrasi Praktik Kerja Industri mempermudah siswa dan guru dalam memonitoring kegiatan magang secara digital, transparan, dan efisien.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-12 mb-12">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">150+</div>
                    <div class="text-sm text-white/70">Mitra Industri</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">1,200+</div>
                    <div class="text-sm text-white/70">Siswa Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-sm text-white/70">Akses Digital</div>
                </div>
            </div>

            <div class="absolute bottom-8 right-8">
                <div class="bg-white/20 backdrop-blur-sm px-6 py-4 rounded-2xl flex items-center gap-3">
                    <svg class="w-8 h-8 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <div>
                        <div class="font-bold text-sm">TERAKREDITASI A</div>
                        <div class="text-xs text-white/70">Unggul & Berprestasi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>