@extends('layouts.kepala_jurusan')

@section('title', 'Dashboard Kepala Jurusan')
@section('header_breadcrumb', 'Dashboard')
@section('header_title', 'RINGKASAN JURUSAN')

@section('content')
<div class="space-y-6">
    <!-- Top Greeting Card with Glassmorphism -->
    <div class="relative overflow-hidden bg-gradient-to-r from-yellow-500/90 via-emerald-600/90 to-emerald-700/90 rounded-3xl p-8 text-white shadow-lg">
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
        <div class="absolute right-20 bottom-0 w-24 h-24 rounded-full bg-emerald-500/20 blur-lg"></div>
        <div class="relative z-10 max-w-xl">
            <h2 class="text-3xl font-black mb-2">Selamat Datang, Bapak/Ibu Kajur!</h2>
            <p class="text-white/90 text-sm leading-relaxed">
                Melalui portal Kepala Jurusan, Anda dapat mengelola seluruh administrasi magang siswa secara terpusat, mulai dari penempatan mitra, pemantauan kegiatan jurnal/absen, hingga rekap penilaian akhir.
            </p>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Siswa Jurusan</p>
                <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $totalSiswa }}</h3>
                <p class="text-xs text-gray-500 mt-1">Siswa terdaftar di departemen Anda</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa Magang</p>
                <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $totalMagang }}</h3>
                <p class="text-xs text-gray-500 mt-1">Telah ditempatkan di mitra industri</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kendala / Masalah Aktif</p>
                <h3 class="text-3xl font-black text-red-600 mt-2">{{ $totalMasalah }}</h3>
                <p class="text-xs text-gray-500 mt-1">Laporan kendala butuh penyelesaian</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
        <h3 class="font-bold text-gray-800 text-lg mb-4">Aktivitas Penempatan Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase border-b border-gray-100 pb-3">
                        <th class="py-3">Siswa</th>
                        <th class="py-3">Perusahaan</th>
                        <th class="py-3">Posisi</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($aktivitasTerbaru as $placement)
                        <tr>
                            <td class="py-3.5">
                                <div class="font-semibold text-gray-800">{{ $placement->siswa->nama }}</div>
                                <div class="text-[10px] text-gray-400">NISN: {{ $placement->siswa->nisn }}</div>
                            </td>
                            <td class="py-3.5 text-gray-600">{{ $placement->industri->nama_industri }}</td>
                            <td class="py-3.5 text-gray-600">{{ $placement->posisi_magang ?? '-' }}</td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                    @if($placement->status == 'approved') bg-green-100 text-green-700
                                    @elseif($placement->status == 'ongoing') bg-blue-100 text-blue-700
                                    @elseif($placement->status == 'completed') bg-teal-100 text-teal-700
                                    @elseif($placement->status == 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($placement->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">Belum ada penempatan magang terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
