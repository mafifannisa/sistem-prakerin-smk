@extends('layouts.guru_pembimbing')

@section('title', 'Rekap Laporan PKL')
@section('header_breadcrumb', 'Rekap Laporan')
@section('header_title', 'LAPORAN PKL SISWA')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('guru_pembimbing.rekap-laporan') }}" method="GET" class="flex items-center gap-3">
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
            <a href="{{ route('guru_pembimbing.rekap-laporan') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
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
                                    <a href="javascript:void(0)" onclick="openPdfModal('{{ asset('storage/' . $rep->file_path) }}')" class="text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1">
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
                                    @elseif($rep->status == 'perlu_revisi') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ strtoupper($rep->status == 'perlu_revisi' ? 'Revisi' : $rep->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $rep->catatan_pembimbing }}">
                                {{ $rep->catatan_pembimbing ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($rep->status == 'disetujui' || $rep->status == 'verified')
                                    <span class="text-green-600 font-bold text-xs bg-green-50 border border-green-200 px-2.5 py-1.5 rounded-lg shadow-sm">Terverifikasi</span>
                                @elseif($rep->status == 'perlu_revisi')
                                    <span class="text-orange-600 font-bold text-xs bg-orange-50 border border-orange-200 px-2.5 py-1.5 rounded-lg shadow-sm">Revisi</span>
                                @else
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openVerifikasiModal('{{ $rep->id }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition border border-emerald-700">
                                            Verifikasi
                                        </button>
                                        <button type="button" onclick="openRevisiModal('{{ $rep->id }}')" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg shadow-sm transition border border-orange-600">
                                            Revisi
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data laporan PKL.</p>
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

<!-- Modal Revisi -->
<div id="revisiModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl border border-gray-100 transform transition-all scale-95 opacity-0 duration-300" id="revisiModalContent">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Berikan Catatan Revisi</h3>
            <button type="button" onclick="closeRevisiModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="revisiForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Pembimbing <span class="text-red-500">*</span></label>
                <textarea name="catatan_pembimbing" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="Contoh: Tolong perbaiki Bab 2 pada bagian..."></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeRevisiModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition">Kirim Revisi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verifikasiModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl border border-gray-100 transform transition-all scale-95 opacity-0 duration-300" id="verifikasiModalContent">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Verifikasi Laporan PKL</h3>
            <button type="button" onclick="closeVerifikasiModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="verifikasiForm" method="POST" action="">
            @csrf
            <input type="hidden" name="status" value="disetujui">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Pembimbing (Opsional)</label>
                <textarea name="catatan_pembimbing" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="Contoh: Laporan sudah sangat baik, lanjutkan..."></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeVerifikasiModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition">Setujui & Verifikasi</button>
            </div>
        </form>
    </div>
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
        const revisiModal = document.getElementById('revisiModal');
        if (revisiModal) {
            document.body.appendChild(revisiModal);
        }
        const verifikasiModal = document.getElementById('verifikasiModal');
        if (verifikasiModal) {
            document.body.appendChild(verifikasiModal);
        }
        const pdfModal = document.getElementById('pdfModal');
        if (pdfModal) {
            document.body.appendChild(pdfModal);
        }
    });

    function openRevisiModal(id) {
        const modal = document.getElementById('revisiModal');
        const modalContent = document.getElementById('revisiModalContent');
        const form = document.getElementById('revisiForm');
        
        // Update form action url
        form.action = `/guru-pembimbing/rekap-laporan/${id}/revision`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRevisiModal() {
        const modal = document.getElementById('revisiModal');
        const modalContent = document.getElementById('revisiModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function openVerifikasiModal(id) {
        const modal = document.getElementById('verifikasiModal');
        const modalContent = document.getElementById('verifikasiModalContent');
        const form = document.getElementById('verifikasiForm');
        
        form.action = `/guru-pembimbing/rekap-laporan/${id}/verify`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeVerifikasiModal() {
        const modal = document.getElementById('verifikasiModal');
        const modalContent = document.getElementById('verifikasiModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

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
