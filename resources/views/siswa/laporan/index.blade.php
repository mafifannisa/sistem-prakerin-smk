@extends('layouts.siswa')

@section('title', 'Laporan Kegiatan')

@section('header_breadcrumb', 'Laporan')
@section('header_title', 'LAPORAN KEGIATAN ABSENSI, JURNAL HARIAN, DAN LAPORAN PKL')

@section('content')
<div class="p-0">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Hadir -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-1">Total Hadir</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['total_hadir'] }}</p>
            </div>
        </div>

        <!-- Card 2: Jurnal Diisi -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-1">Jurnal Diisi</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['jurnal_total'] }}</p>
            </div>
        </div>

        <!-- Card 3: Laporan PKL -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center gap-4">
            <div class="w-14 h-14 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-600 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-1">Laporan PKL</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['laporan_pkl'] ? 'Sudah' : 'Belum' }}</p>
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Absensi -->
        <a href="{{ route('siswa.laporan.absensi') }}" class="group bg-white/50 backdrop-blur-xl border border-white/35 rounded-3xl p-8 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between min-h-[220px]">
            <!-- Decorative gradient blur -->
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            
            <div>
                <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 transition-transform duration-300 group-hover:scale-110">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2 tracking-wide">Absensi Harian</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-semibold">Isi presensi kehadiran harian dengan upload foto bukti kehadiran</p>
            </div>
            
            <div class="mt-6 flex items-center gap-2 text-emerald-600 font-extrabold text-sm uppercase tracking-wider">
                <span>Buka Menu</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Jurnal Harian -->
        <a href="{{ route('siswa.laporan.jurnal') }}" class="group bg-white/50 backdrop-blur-xl border border-white/35 rounded-3xl p-8 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between min-h-[220px]">
            <!-- Decorative gradient blur -->
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            
            <div>
                <div class="w-16 h-16 bg-blue-500/10 border border-blue-500/25 rounded-2xl flex items-center justify-center mb-6 text-blue-600 transition-transform duration-300 group-hover:scale-110">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2 tracking-wide">Jurnal Harian</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-semibold">Catat ringkasan kegiatan harian magang dengan upload foto wajah</p>
            </div>
            
            <div class="mt-6 flex items-center gap-2 text-blue-600 font-extrabold text-sm uppercase tracking-wider">
                <span>Buka Menu</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Laporan PKL -->
        <a href="{{ route('siswa.laporan.pkl') }}" class="group bg-white/50 backdrop-blur-xl border border-white/35 rounded-3xl p-8 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between min-h-[220px]">
            <!-- Decorative gradient blur -->
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
            
            <div>
                <div class="w-16 h-16 bg-purple-500/10 border border-purple-500/25 rounded-2xl flex items-center justify-center mb-6 text-purple-600 transition-transform duration-300 group-hover:scale-110">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 25">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2 tracking-wide">Laporan PKL</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-semibold">Kirim & upload dokumen final Laporan PKL dalam format berkas PDF</p>
            </div>
            
            <div class="mt-6 flex items-center gap-2 text-purple-600 font-extrabold text-sm uppercase tracking-wider">
                <span>Buka Menu</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Input Nilai -->
        <a href="{{ route('siswa.laporan.nilai') }}" class="group bg-white/50 backdrop-blur-xl border border-white/35 rounded-3xl p-8 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between min-h-[220px]">
            <!-- Decorative gradient blur -->
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            
            <div>
                <div class="w-16 h-16 bg-amber-500/10 border border-amber-500/25 rounded-2xl flex items-center justify-center mb-6 text-amber-600 transition-transform duration-300 group-hover:scale-110">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9a2 2 0 100-4 2 2 0 000 4zm0 0v1a3 3 0 013 3h-6a3 3 0 013-3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2 tracking-wide">Input Nilai</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-semibold">Input nilai teknis magang beserta upload foto bukti lembar penilaian industri</p>
            </div>
            
            <div class="mt-6 flex items-center gap-2 text-amber-600 font-extrabold text-sm uppercase tracking-wider">
                <span>Buka Menu</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>
</div>
@endsection