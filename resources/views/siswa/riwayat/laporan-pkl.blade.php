@extends('layouts.siswa')

@section('title', 'Riwayat Laporan PKL')

@section('header_breadcrumb', 'Riwayat / Laporan PKL')
@section('header_title', 'RIWAYAT LAPORAN PKL')

@section('content')
@php
    $latestLaporan = $laporans->first();
    $hasActiveLaporan = $latestLaporan && $latestLaporan->status !== 'perlu_revisi';
    $isVerified = $latestLaporan && ($latestLaporan->status === 'disetujui' || $latestLaporan->status === 'verified');
@endphp
<div class="p-0">
    <!-- LOCK MESSAGE: Jika Belum Boleh Upload -->
    @if(!isset($bolehAbsen) || !$bolehAbsen)
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Riwayat Laporan Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses riwayat laporan.' }}</p>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                        📍 Buka Cek Status Magang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Grid Layout -->
        <div class="space-y-6">
            <!-- Status Progress -->
            <div class="bg-white/50 backdrop-blur-xl rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 border border-white/35 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="p-1.5 bg-purple-500/10 border border-purple-500/20 rounded-lg text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                    Status Progress Laporan
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Step 1: Upload -->
                    <div class="text-center p-5 rounded-2xl border duration-300 transition-all cursor-pointer
                        {{ $hasActiveLaporan 
                            ? 'bg-indigo-500/10 border-indigo-500/25 hover:border-indigo-500/40 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-500/5' 
                            : 'bg-gray-50/50 border-gray-200 hover:border-gray-300 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-lg' }}">
                        <div class="w-10 h-10 mx-auto mb-3 rounded-full flex items-center justify-center font-bold text-sm transition duration-350
                            {{ $hasActiveLaporan ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-500/20' : 'bg-gray-100 border border-gray-200 text-gray-400' }}">
                            @if($hasActiveLaporan)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                1
                            @endif
                        </div>
                        <p class="font-extrabold text-sm text-gray-800">Upload Laporan</p>
                        <p class="text-[10px] text-gray-500 mt-0.5 font-bold uppercase tracking-wider">{{ $hasActiveLaporan ? 'Selesai' : ($latestLaporan && $latestLaporan->status === 'perlu_revisi' ? 'Perlu Revisi' : 'Belum') }}</p>
                    </div>
                    
                    <!-- Step 2: Verifikasi -->
                    <div class="text-center p-5 rounded-2xl border duration-300 transition-all cursor-pointer
                        {{ $isVerified 
                            ? 'bg-purple-500/10 border-purple-500/25 hover:border-purple-500/40 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/5' 
                            : 'bg-gray-50/50 border-gray-200 hover:border-gray-300 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-lg' }}">
                        <div class="w-10 h-10 mx-auto mb-3 rounded-full flex items-center justify-center font-bold text-sm transition duration-350
                            {{ $isVerified ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-md shadow-purple-500/20' : 'bg-gray-100 border border-gray-200 text-gray-400' }}">
                            @if($isVerified)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                2
                            @endif
                        </div>
                        <p class="font-extrabold text-sm text-gray-800">Verifikasi</p>
                        <p class="text-[10px] text-gray-500 mt-0.5 font-bold uppercase tracking-wider">
                            @if($isVerified)
                                Selesai
                            @elseif($latestLaporan && $latestLaporan->status === 'perlu_revisi')
                                Pending
                            @else
                                Pending
                            @endif
                        </p>
                    </div>
                    
                    <!-- Step 3: Sertifikat -->
                    <div class="text-center p-5 rounded-2xl border duration-300 transition-all cursor-pointer
                        {{ $isVerified 
                            ? 'bg-emerald-500/10 border-emerald-500/25 hover:border-emerald-500/40 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5' 
                            : 'bg-gray-50/50 border-gray-200 hover:border-gray-300 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-lg' }}">
                        <div class="w-10 h-10 mx-auto mb-3 rounded-full flex items-center justify-center font-bold text-sm transition duration-350
                            {{ $isVerified ? 'bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-md shadow-emerald-500/20' : 'bg-gray-100 border border-gray-200 text-gray-400' }}">
                            @if($isVerified)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            @else
                                3
                            @endif
                        </div>
                        <p class="font-extrabold text-sm text-gray-800">Sertifikat</p>
                        <p class="text-[10px] text-gray-500 mt-0.5 font-bold uppercase tracking-wider">{{ $isVerified ? 'Tersedia' : 'Terkunci' }}</p>
                    </div>
                </div>
            </div>

            <!-- Riwayat Upload -->
            <div class="bg-white/50 backdrop-blur-xl rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 border border-white/35 overflow-hidden">
                <div class="p-6 border-b border-gray-100/50 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 bg-purple-500/10 border border-purple-500/20 rounded-lg text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Riwayat Upload Laporan PKL</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Daftar lengkap riwayat pengumpulan laporan PKL Anda</p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.laporan.pkl') }}" 
                       class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold rounded-xl shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 hover:scale-[1.02] transition flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Upload Laporan PKL
                    </a>
                </div>
                
                <div class="p-6">

                @if($laporans->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <th class="p-4">Tanggal & Waktu</th>
                                    <th class="p-4">Judul Laporan</th>
                                    <th class="p-4">Catatan Pembimbing</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($laporans as $laporan)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="p-4 text-xs text-gray-600 whitespace-nowrap">
                                            {{ $laporan->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="p-4 text-sm font-bold text-gray-800">
                                            {{ $laporan->judul_laporan }}
                                            @if($laporan->abstrak)
                                                <div class="mt-1 text-[10px] text-gray-400 font-normal truncate max-w-xs" title="{{ $laporan->abstrak }}">
                                                    Abstrak: {{ $laporan->abstrak }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $laporan->catatan_pembimbing }}">
                                            {{ $laporan->catatan_pembimbing ?? '-' }}
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-full border
                                                @if($laporan->status === 'disetujui') bg-green-50 text-green-700 border-green-200
                                                @elseif($laporan->status === 'pending') bg-yellow-50 text-yellow-700 border-yellow-200
                                                @else bg-red-50 text-red-700 border-red-200 @endif">
                                                @if($laporan->status === 'disetujui')
                                                    Disetujui
                                                @elseif($laporan->status === 'pending')
                                                    Pending
                                                @else
                                                    Revisi
                                                @endif
                                            </span>
                                        </td>
                                        <td class="p-4 text-center">
                                            @if($laporan->file_path)
                                                <a href="javascript:void(0)" onclick="openPdfModal('{{ Storage::url($laporan->file_path) }}')" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs rounded-lg transition border border-purple-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    File
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        
                    @if($laporans->hasPages())
                        <div class="mt-4">
                            {{ $laporans->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl bg-white/50">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Belum ada riwayat upload laporan PKL.</p>
                    </div>
                @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Preview PDF -->
<div id="pdfModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-5xl h-[85vh] p-6 shadow-2xl border border-gray-100 flex flex-col transform transition-all scale-95 opacity-0 duration-300" id="pdfModalContent">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Preview File Laporan PKL
            </h3>
            <button type="button" onclick="closePdfModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 w-full bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
            <iframe id="pdfFrame" src="" class="w-full h-full" frameborder="0"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfModal = document.getElementById('pdfModal');
        if (pdfModal) {
            document.body.appendChild(pdfModal);
        }
    });

    function openPdfModal(url) {
        const modal = document.getElementById('pdfModal');
        const modalContent = document.getElementById('pdfModalContent');
        const frame = document.getElementById('pdfFrame');
        
        frame.src = url;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePdfModal() {
        const modal = document.getElementById('pdfModal');
        const modalContent = document.getElementById('pdfModalContent');
        const frame = document.getElementById('pdfFrame');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            frame.src = '';
        }, 300);
    }
</script>
@endsection
