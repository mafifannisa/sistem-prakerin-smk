@extends('layouts.guru_pembimbing')

@section('title', 'Dashboard Guru Pembimbing')
@section('header_breadcrumb', 'Dashboard')
@section('header_title', 'RINGKASAN PEMBIMBING')

@section('content')
<div class="space-y-6">
    <!-- Greeting Card -->
    <div class="relative overflow-hidden bg-gradient-to-r from-yellow-500/90 via-emerald-600/90 to-emerald-700/90 rounded-3xl p-8 text-white shadow-lg">
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
        <div class="relative z-10 max-w-xl">
            <h2 class="text-3xl font-black mb-2">Selamat Datang, Bapak/Ibu Pembimbing!</h2>
            <p class="text-white/90 text-sm leading-relaxed">
                Portal Guru Pembimbing memudahkan Anda memantau dan memverifikasi absensi harian, jurnal kegiatan, serta laporan PKL dari siswa bimbingan Anda.
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa Bimbingan Anda</p>
                <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $totalSiswa }}</h3>
                <p class="text-xs text-gray-500 mt-1">Siswa magang yang Anda bimbing</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unverified Jurnal</p>
                <h3 class="text-3xl font-black text-yellow-600 mt-2">{{ $unverifiedJournal }}</h3>
                <p class="text-xs text-gray-500 mt-1">Jurnal butuh verifikasi segera</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laporan PKL Pending</p>
                <h3 class="text-3xl font-black text-emerald-600 mt-2">{{ $unverifiedLaporan }}</h3>
                <p class="text-xs text-gray-500 mt-1">Laporan butuh verifikasi segera</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
        </div>
    </div>

    <!-- Guided Students List -->
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm">
        <h3 class="font-bold text-gray-800 text-lg mb-4">Siswa Bimbingan Aktif</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase border-b border-gray-100 pb-3">
                        <th class="py-3">Nama Siswa</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Tempat Magang</th>
                        <th class="py-3">Periode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($bimbingans as $bim)
                        <tr>
                            <td class="py-3.5">
                                <div class="font-semibold text-gray-800">{{ $bim->siswa->nama }}</div>
                                <div class="text-[10px] text-gray-400">NISN: {{ $bim->siswa->nisn }}</div>
                            </td>
                            <td class="py-3.5 text-gray-600 font-medium">{{ $bim->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="py-3.5 text-gray-600 font-semibold">{{ $bim->industri->nama_industri }}</td>
                            <td class="py-3.5 text-gray-500">
                                {{ $bim->tanggal_mulai ? $bim->tanggal_mulai->format('d M Y') : '-' }} s/d {{ $bim->tanggal_selesai ? $bim->tanggal_selesai->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">Anda belum ditugaskan untuk membimbing siswa magang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
