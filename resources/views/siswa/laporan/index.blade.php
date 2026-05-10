@extends('layouts.siswa')

@section('title', 'Laporan Kegiatan')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Kegiatan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola absensi, jurnal harian, dan laporan PKL</p>
        </div>
        <div class="text-sm text-gray-600">{{ tanggal_indonesia() }}</div>
    </div>
</header>

<div class="p-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm mb-1">Total Hadir</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_hadir'] }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm mb-1">Jurnal Diisi</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['jurnal_total'] }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm mb-1">Jurnal Pending</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['jurnal_pending'] }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm mb-1">Laporan PKL</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['laporan_pkl'] ? 'Sudah' : 'Belum' }}</p>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Absensi -->
        <a href="{{ route('siswa.laporan.absensi') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-8 text-white hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Absensi Harian</h3>
            <p class="text-white/80 text-sm">Isi presensi kehadiran harian dengan foto bukti</p>
        </a>

        <!-- Jurnal Harian -->
        <a href="{{ route('siswa.laporan.jurnal') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-8 text-white hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Jurnal Harian</h3>
            <p class="text-white/80 text-sm">Catat kegiatan harian magang dengan foto wajah</p>
        </a>

        <!-- Laporan PKL -->
        <a href="{{ route('siswa.laporan.pkl') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-8 text-white hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Laporan PKL</h3>
            <p class="text-white/80 text-sm">Upload laporan PKL dalam format PDF</p>
        </a>
    </div>
</div>
@endsection