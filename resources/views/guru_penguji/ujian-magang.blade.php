@extends('layouts.guru_penguji')

@section('title', 'Ujian Magang Siswa')
@section('header_breadcrumb', 'Ujian Magang')
@section('header_title', 'PENILAIAN & CATATAN UJIAN')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Jadwal Ujian Anda</h2>
            <p class="text-xs text-gray-500 mt-1">Berikan penilaian berupa rekomendasi lulus / catatan penguji pada sidang laporan siswa.</p>
        </div>
    </div>

    <!-- Exams Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Perusahaan Mitra</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tanggal Magang</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Laporan PKL</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Nilai Penguji</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Catatan Penguji</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($placements as $index => $placement)
                        @php
                            $nilai = $placement->nilai;
                            $laporanDisetujui = $placement->laporanPkls->where('status', 'disetujui')->first();
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $placements->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $placement->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $placement->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $placement->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="font-semibold">{{ $placement->tanggal_mulai ? \Carbon\Carbon::parse($placement->tanggal_mulai)->format('d M Y') : '-' }}</div>
                                <div class="text-xs text-gray-400">s/d: {{ $placement->tanggal_selesai ? \Carbon\Carbon::parse($placement->tanggal_selesai)->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($laporanDisetujui)
                                    <a href="javascript:void(0)" onclick="openPdfModal('{{ asset('storage/' . $laporanDisetujui->file_path) }}')" class="text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Buka Laporan
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Belum Disetujui</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($nilai && $nilai->nilai_penguji !== null)
                                    <span class="text-emerald-700 font-black text-base">{{ round($nilai->nilai_penguji, 1) }}</span>
                                @else
                                    <span class="text-yellow-600 font-semibold text-xs">Belum Diinput</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $nilai->catatan_penguji ?? '' }}">
                                {{ $nilai->catatan_penguji ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openGradeModal({{ $placement->id }}, '{{ addslashes($placement->siswa->nama) }}', '{{ addslashes($nilai->catatan_penguji ?? '') }}', '{{ $nilai->nilai_penguji ?? '' }}')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition border border-emerald-700">
                                    Input Nilai
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada jadwal ujian magang ditugaskan kepada Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($placements->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $placements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Grade Exam -->
<div id="gradeModal" class="hidden fixed inset-0 w-screen h-screen bg-black/60 z-[9999] flex items-center justify-center backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 font-display">Penilaian & Catatan Penguji</h3>
            <button onclick="closeGradeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="gradeForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Siswa</label>
                <p id="modalSiswaName" class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"></p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai Penguji (0 - 100) *</label>
                <input type="number" name="nilai_penguji" id="modalNilaiPenguji" min="0" max="100" step="0.01" required placeholder="Masukkan nilai ujian magang (0 - 100)..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Penguji / Kelulusan Ujian Laporan *</label>
                <textarea name="catatan_penguji" id="modalCatatanPenguji" rows="5" placeholder="Tuliskan saran perbaikan laporan, kelebihan, atau kekurangan presentasi siswa..." required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
                <button type="button" onclick="closeGradeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-600 hover:from-yellow-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    Simpan Nilai & Catatan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview PDF -->
<div id="pdfModal" class="hidden fixed inset-0 w-screen h-screen bg-black/60 z-[9999] flex items-center justify-center backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[85vh] mx-4 p-6 flex flex-col transform transition-all">
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
    const gradeModal = document.getElementById('gradeModal');
    if (gradeModal) {
        document.body.appendChild(gradeModal);
    }
    const pdfModal = document.getElementById('pdfModal');
    if (pdfModal) {
        document.body.appendChild(pdfModal);
    }
});

function openGradeModal(id, siswaNama, catatan, nilaiPenguji) {
    document.getElementById('modalSiswaName').textContent = siswaNama;
    document.getElementById('modalCatatanPenguji').value = catatan;
    document.getElementById('modalNilaiPenguji').value = nilaiPenguji;
    document.getElementById('gradeForm').action = "{{ route('guru_penguji.ujian-magang.store', ':id') }}".replace(':id', id);
    document.getElementById('gradeModal').classList.remove('hidden');
}

function closeGradeModal() {
    document.getElementById('gradeModal').classList.add('hidden');
}

function openPdfModal(url) {
    const modal = document.getElementById('pdfModal');
    const frame = document.getElementById('pdfFrame');
    frame.src = url;
    modal.classList.remove('hidden');
}

function closePdfModal() {
    const modal = document.getElementById('pdfModal');
    const frame = document.getElementById('pdfFrame');
    modal.classList.add('hidden');
    frame.src = '';
}

// Close modals when clicking background
document.getElementById('gradeModal').addEventListener('click', function(e) {
    if (e.target === this) closeGradeModal();
});
document.getElementById('pdfModal').addEventListener('click', function(e) {
    if (e.target === this) closePdfModal();
});
</script>
@endsection
