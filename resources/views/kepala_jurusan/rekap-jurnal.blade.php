@extends('layouts.kepala_jurusan')

@section('title', 'Rekap Jurnal Kegiatan')
@section('header_breadcrumb', 'Rekap Jurnal')
@section('header_title', 'REKAP JURNAL JURUSAN')

@section('content')
<div class="space-y-6">
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('kepala_jurusan.rekap-jurnal') }}" method="GET" class="flex items-center gap-3">
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
            <a href="{{ route('kepala_jurusan.rekap-jurnal') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Jurnal Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Aktivitas Pekerjaan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Catatan Pembimbing</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Foto</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status Verifikasi</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($jurnals as $index => $jrn)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $jurnals->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $jrn->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $jrn->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $jrn->tanggal ? \Carbon\Carbon::parse($jrn->tanggal)->format('d F Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $jrn->kegiatan }}">
                                {{ $jrn->kegiatan }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $jrn->catatan_pembimbing }}">
                                {{ $jrn->catatan_pembimbing ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($jrn->bukti_foto)
                                    <img src="{{ asset('storage/' . $jrn->bukti_foto) }}" class="w-12 h-12 object-cover rounded-xl border border-gray-200 mx-auto">
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada foto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($jrn->status == 'disetujui' || $jrn->status == 'verified') bg-green-100 text-green-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ $jrn->status == 'disetujui' || $jrn->status == 'verified' ? 'Berhasil' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                                <button type="button" 
                                        data-nama="{{ $jrn->siswa->nama }}"
                                        data-nisn="{{ $jrn->siswa->nisn }}"
                                        data-tanggal="{{ $jrn->tanggal ? \Carbon\Carbon::parse($jrn->tanggal)->format('d M Y') : '-' }}"
                                        data-minggu="Minggu ke-{{ $jrn->minggu_ke }}"
                                        data-kegiatan="{{ $jrn->kegiatan }}"
                                        data-durasi="{{ $jrn->durasi_jam }} Jam Kerja"
                                        data-catatan="{{ $jrn->catatan_pembimbing ?? '-' }}"
                                        data-foto="{{ $jrn->bukti_foto ? asset('storage/' . $jrn->bukti_foto) : '' }}"
                                        onclick="showJurnalDetail(this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-lg text-xs transition duration-200 shadow-sm shadow-emerald-500/10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat
                                </button>
                                <form action="{{ route('kepala_jurusan.rekap-jurnal.destroy', $jrn->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurnal harian ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-650 text-xs font-bold rounded-lg transition border border-red-200">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada rekap data jurnal kegiatan siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($jurnals->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $jurnals->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Detail Jurnal (Read Only) -->
<div id="detailJurnalModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeJurnalDetail()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 z-10" id="detailJurnalContent">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-150 flex items-center justify-between bg-gradient-to-r from-emerald-500 to-teal-500 text-white">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Detail Jurnal Kegiatan
            </h3>
            <button type="button" onclick="closeJurnalDetail()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
            <!-- Siswa Info -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-400 font-medium block">Siswa</span>
                <span class="text-sm font-bold text-gray-800" id="modalSiswaNama">-</span>
                <span class="text-xs text-gray-500 block" id="modalSiswaNisn">NISN: -</span>
            </div>

            <!-- Tanggal & Minggu -->
            <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
                <div>
                    <span class="text-xs text-gray-400 font-medium block">Tanggal</span>
                    <span class="text-sm font-bold text-gray-800" id="modalTanggal">-</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 font-medium block">Periode</span>
                    <span class="text-sm font-bold text-gray-800" id="modalMinggu">-</span>
                </div>
            </div>
            
            <!-- Durasi Kerja -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-400 font-medium block mb-1">Durasi Kerja</span>
                <span class="inline-flex items-center gap-1 text-xs text-emerald-650 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full" id="modalDurasi">
                    -
                </span>
            </div>
            
            <!-- Kegiatan -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-400 font-medium block mb-1">Aktivitas Pekerjaan</span>
                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 whitespace-pre-wrap leading-relaxed" id="modalKegiatan">-</p>
            </div>

            <!-- Catatan Pembimbing (Read Only) -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-400 font-medium block mb-1">Catatan Pembimbing</span>
                <p class="text-sm text-gray-700 bg-amber-50/50 p-3 rounded-lg border border-amber-200 whitespace-pre-wrap leading-relaxed italic" id="modalCatatan">-</p>
            </div>
            
            <!-- Bukti Foto -->
            <div class="py-2">
                <span class="text-xs text-gray-400 font-medium block mb-2">Foto Bukti</span>
                <div id="modalFotoContainer" class="relative group overflow-hidden rounded-xl border border-gray-200 shadow-sm bg-gray-50 max-h-60 flex items-center justify-center">
                    <img id="modalFoto" src="" alt="Foto Bukti" class="w-full h-full object-contain max-h-60">
                    <div id="modalFotoPlaceholder" class="text-gray-400 text-sm italic py-8 flex flex-col items-center gap-2">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Tidak ada foto bukti
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeJurnalDetail()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl text-sm transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailJurnalModal');
    if (modal) {
        document.body.appendChild(modal);
    }
});

function showJurnalDetail(btn) {
    const data = btn.dataset;
    
    // Set text values
    document.getElementById('modalSiswaNama').textContent = data.nama;
    document.getElementById('modalSiswaNisn').textContent = 'NISN: ' + data.nisn;
    document.getElementById('modalTanggal').textContent = data.tanggal;
    document.getElementById('modalMinggu').textContent = data.minggu;
    document.getElementById('modalDurasi').textContent = '⏱️ ' + data.durasi;
    document.getElementById('modalKegiatan').textContent = data.kegiatan;
    document.getElementById('modalCatatan').textContent = data.catatan ? data.catatan : '-';
    
    // Set photo
    const modalFoto = document.getElementById('modalFoto');
    const modalFotoPlaceholder = document.getElementById('modalFotoPlaceholder');
    
    if (data.foto) {
        modalFoto.src = data.foto;
        modalFoto.classList.remove('hidden');
        modalFotoPlaceholder.classList.add('hidden');
    } else {
        modalFoto.src = '';
        modalFoto.classList.add('hidden');
        modalFotoPlaceholder.classList.remove('hidden');
    }
    
    // Show modal with animation
    const modal = document.getElementById('detailJurnalModal');
    const content = document.getElementById('detailJurnalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeJurnalDetail() {
    const modal = document.getElementById('detailJurnalModal');
    const content = document.getElementById('detailJurnalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
@endsection
