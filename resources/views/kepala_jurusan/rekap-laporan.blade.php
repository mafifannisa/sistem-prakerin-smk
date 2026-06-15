@extends('layouts.kepala_jurusan')

@section('title', 'Rekap Laporan PKL')
@section('header_breadcrumb', 'Rekap Laporan')
@section('header_title', 'REKAP LAPORAN PKL JURUSAN')

@section('content')
<div class="space-y-6">
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('kepala_jurusan.rekap-laporan') }}" method="GET" class="flex items-center gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama siswa..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 outline-none">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition">
                Filter
            </button>
            <a href="{{ route('kepala_jurusan.rekap-laporan') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Laporan Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Judul Laporan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Jenis Laporan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">File Laporan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($laporans as $index => $rep)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $laporans->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $rep->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $rep->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-md truncate" title="{{ $rep->judul_laporan }}">
                                {{ $rep->judul_laporan }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 capitalize">
                                {{ $rep->jenis ?? 'Laporan Akhir' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($rep->file_path)
                                    <a href="{{ asset('storage/' . $rep->file_path) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Buka Laporan
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Belum unggah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($rep->status == 'disetujui' || $rep->status == 'verified') bg-green-100 text-green-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ strtoupper($rep->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada rekap data laporan PKL siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($laporans->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
