@extends('layouts.kepala_jurusan')

@section('title', 'Import & Penilaian Magang')
@section('header_breadcrumb', 'Nilai Magang')
@section('header_title', 'PENILAIAN KERJA MAGANG')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('kepala_jurusan.import-nilai') }}" method="GET" class="flex items-center gap-3">
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
            <a href="{{ route('kepala_jurusan.import-nilai') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Placements / Grades Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap border-collapse">
                <thead class="bg-gray-50/50">
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left">Mitra Industri</th>
                        <th class="px-6 py-4 text-left">Nilai Teknis (Siswa)</th>
                        <th class="px-6 py-4 text-center">Nilai Sikap</th>
                        <th class="px-6 py-4 text-center">Nilai Keterampilan</th>
                        <th class="px-6 py-4 text-center">Nilai Pengetahuan</th>
                        <th class="px-6 py-4 text-center">Nilai Akhir</th>
                        <th class="px-6 py-4 text-center">Predikat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-semibold text-gray-750">
                    @forelse($placements as $index => $placement)
                        @php
                            $nilai = $placement->nilai;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600 font-normal">{{ $placements->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $placement->siswa->nama }}</div>
                                <div class="text-xs text-gray-500 font-normal">NISN: {{ $placement->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-655 font-bold">
                                {{ $placement->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                @if($nilai && $nilai->kegiatan_1)
                                    <div class="text-xs space-y-1 bg-gray-50/60 p-2.5 rounded-xl border border-gray-200/50 max-w-xs min-w-[220px]">
                                        <div class="flex justify-between items-start gap-3">
                                            <span class="text-gray-600 font-semibold truncate max-w-[150px]" title="{{ $nilai->kegiatan_1 }}">• {{ $nilai->kegiatan_1 }}</span>
                                            <span class="text-emerald-600 font-black shrink-0">{{ number_format($nilai->nilai_1) }}</span>
                                        </div>
                                        <div class="flex justify-between items-start gap-3">
                                            <span class="text-gray-600 font-semibold truncate max-w-[150px]" title="{{ $nilai->kegiatan_2 }}">• {{ $nilai->kegiatan_2 }}</span>
                                            <span class="text-emerald-600 font-black shrink-0">{{ number_format($nilai->nilai_2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-start gap-3">
                                            <span class="text-gray-600 font-semibold truncate max-w-[150px]" title="{{ $nilai->kegiatan_3 }}">• {{ $nilai->kegiatan_3 }}</span>
                                            <span class="text-emerald-600 font-black shrink-0">{{ number_format($nilai->nilai_3) }}</span>
                                        </div>
                                        @if($nilai->foto_nilai)
                                            <div class="pt-1.5 mt-1.5 border-t border-gray-200 flex items-center justify-between">
                                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Lembar Bukti</span>
                                                <a href="javascript:void(0)" onclick="openPhotoModal('{{ Storage::url($nilai->foto_nilai) }}')" class="text-[10px] text-emerald-600 hover:text-emerald-700 font-extrabold flex items-center gap-0.5">
                                                    🔍 Lihat Bukti
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 font-bold text-xs italic">Belum diisi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $nilai && $nilai->nilai_sikap !== null ? round($nilai->nilai_sikap) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $nilai && $nilai->nilai_keterampilan !== null ? round($nilai->nilai_keterampilan) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $nilai && $nilai->nilai_pengetahuan !== null ? round($nilai->nilai_pengetahuan) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($nilai && $nilai->nilai_akhir !== null)
                                    <span class="text-emerald-750 font-black text-base">{{ round($nilai->nilai_akhir, 1) }}</span>
                                @else
                                    <span class="text-gray-400 italic font-semibold">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($nilai && $nilai->predikat)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-black text-xs rounded-full">
                                        {{ $nilai->predikat }}
                                    </span>
                                @else
                                    <span class="text-gray-400 font-bold text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openGradeModal({{ $placement->id }}, '{{ addslashes($placement->siswa->nama) }}', '{{ $nilai ? $nilai->nilai_sikap : '' }}', '{{ $nilai ? $nilai->nilai_keterampilan : '' }}', '{{ $nilai ? $nilai->nilai_pengetahuan : '' }}')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                    {{ $nilai && $nilai->nilai_sikap !== null ? 'Edit Nilai' : 'Input Nilai' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data penempatan aktif.</p>
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
@endsection

@section('modals')
<!-- Modal Input/Edit Nilai -->
<div id="gradeModal" class="hidden fixed inset-0 bg-black/50 z-[999] flex items-center justify-center backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 font-display">Input Nilai Kompetensi Siswa</h3>
            <button onclick="closeGradeModal()" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai Sikap *</label>
                    <input type="number" name="nilai_sikap" id="modalNilaiSikap" min="0" max="100" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Keterampilan *</label>
                    <input type="number" name="nilai_keterampilan" id="modalNilaiKeterampilan" min="0" max="100" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pengetahuan *</label>
                    <input type="number" name="nilai_pengetahuan" id="modalNilaiPengetahuan" min="0" max="100" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
                <button type="button" onclick="closeGradeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-600 hover:from-yellow-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview Photo -->
<div id="photoModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/60 backdrop-blur-md" onclick="closePhotoModal()">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] p-4 shadow-2xl border border-gray-100 flex flex-col transform transition-all scale-95 opacity-0 duration-300 relative" id="photoModalContent" onclick="event.stopPropagation()">
        <button type="button" onclick="closePhotoModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition bg-gray-100 hover:bg-gray-200 p-2 rounded-full z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="flex-1 w-full bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 flex items-center justify-center p-2 min-h-0">
            <img id="previewImg" src="" class="max-w-full max-h-[75vh] object-contain rounded-xl">
        </div>
    </div>
</div>

<script>
function openGradeModal(id, siswaNama, sikap, keterampilan, pengetahuan) {
    document.getElementById('modalSiswaName').textContent = siswaNama;
    document.getElementById('modalNilaiSikap').value = sikap;
    document.getElementById('modalNilaiKeterampilan').value = keterampilan;
    document.getElementById('modalNilaiPengetahuan').value = pengetahuan;
    document.getElementById('gradeForm').action = "{{ route('kepala_jurusan.import-nilai.store', ':id') }}".replace(':id', id);
    document.getElementById('gradeModal').classList.remove('hidden');
}

function closeGradeModal() {
    document.getElementById('gradeModal').classList.add('hidden');
}

document.getElementById('gradeModal').addEventListener('click', function(e) {
    if (e.target === this) closeGradeModal();
});

function openPhotoModal(url) {
    const modal = document.getElementById('photoModal');
    const modalContent = document.getElementById('photoModalContent');
    const img = document.getElementById('previewImg');
    
    img.src = url;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    const modalContent = document.getElementById('photoModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
@endsection
