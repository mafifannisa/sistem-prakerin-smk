@extends('layouts.guru_penguji')

@section('title', 'Dashboard Guru Penguji')
@section('header_breadcrumb', 'Dashboard')
@section('header_title', 'DASHBOARD GURU PENGUJI')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner with Glassmorphism -->
    <div class="relative bg-gradient-to-r from-yellow-400 via-amber-400 to-emerald-500 rounded-3xl p-6 lg:p-8 text-white overflow-hidden shadow-lg border border-white/20">
        <div class="absolute right-0 bottom-0 opacity-15 transform translate-y-1/4 translate-x-1/4 scale-150">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h-2v-2h4v8zm0-10h-2V5h2v2z"/></svg>
        </div>
        <div class="relative z-10 max-w-2xl">
            <span class="px-3.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider">Prakerin SMKN 3 Tuban</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold mt-3 tracking-tight font-display">
                Selamat Datang, {{ auth()->user()->nama_lengkap }}!
            </h2>
            <p class="mt-2 text-sm text-yellow-50/90 leading-relaxed font-semibold">
                Gunakan dashboard ini untuk menguji laporan akhir, memberikan feedback, dan memverifikasi kelulusan sidang magang siswa.
            </p>
        </div>
    </div>

    <!-- Metric Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Ujian Magang</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalSiswaUjian }} Siswa</h3>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sudah Dinilai / Catat</p>
                <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $totalDinilai }} Siswa</h3>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum Dinilai</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ max(0, $totalSiswaUjian - $totalDinilai) }} Siswa</h3>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Student Lists -->
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white/40 shadow-sm space-y-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800 font-display">Aktivitas Ujian Terbaru</h3>
            <p class="text-xs text-gray-500">Log aktivitas penempatan dan ujian yang sedang aktif saat ini.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-150/60 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3 font-semibold">Siswa</th>
                        <th class="pb-3 font-semibold">Perusahaan</th>
                        <th class="pb-3 font-semibold">Nilai Akhir (Kajur)</th>
                        <th class="pb-3 font-semibold">Catatan Penguji</th>
                        <th class="pb-3 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150/40">
                    @forelse($aktivitasTerbaru as $act)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="py-4">
                                <span class="font-bold text-gray-800">{{ $act->siswa->nama }}</span>
                                <span class="block text-[10px] text-gray-400">NISN: {{ $act->siswa->nisn }}</span>
                            </td>
                            <td class="py-4 font-semibold text-gray-700">
                                {{ $act->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="py-4">
                                @if($act->nilai)
                                    <span class="text-emerald-600 font-black text-sm">{{ round($act->nilai->nilai_akhir, 1) }}</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Belum diinput</span>
                                @endif
                            </td>
                            <td class="py-4 text-gray-550 max-w-xs truncate" title="{{ $act->nilai->catatan_penguji ?? '' }}">
                                {{ $act->nilai->catatan_penguji ?? '-' }}
                            </td>
                            <td class="py-4 text-center">
                                @if($act->nilai && $act->nilai->catatan_penguji)
                                    <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-200">Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-200">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Belum ada aktivitas ujian terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
