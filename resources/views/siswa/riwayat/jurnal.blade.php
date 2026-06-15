@extends('layouts.siswa')

@section('title', 'Riwayat Jurnal')

@section('header_breadcrumb', 'Riwayat / Jurnal')
@section('header_title', 'RIWAYAT JURNAL')

@section('content')
<div class="p-0">
    <!-- LOCK MESSAGE: Jika Belum Boleh Isi Jurnal -->
    @if(!isset($bolehAbsen) || !$bolehAbsen)
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Riwayat Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses riwayat jurnal harian.' }}</p>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                        📍 Buka Cek Status Magang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total Jurnal</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $jurnals->total() }} Entries</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                    T
                </div>
            </div>

            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Berhasil</p>
                    <h3 class="text-2xl font-black text-green-600 mt-1">
                        {{ $jurnals->whereIn('status', ['disetujui', 'pending'])->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold text-lg">
                    B
                </div>
            </div>

            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Gagal</p>
                    <h3 class="text-2xl font-black text-red-500 mt-1">
                        {{ $jurnals->where('status', 'ditolak')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-650 rounded-xl flex items-center justify-center font-bold text-lg">
                    G
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100/50 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">📊 Rekapitulasi Jurnal Harian Magang</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar lengkap laporan aktivitas harian Anda</p>
                </div>
                <a href="{{ route('siswa.laporan.jurnal') }}" 
                   class="px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-500 text-white font-semibold rounded-xl shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:scale-[1.02] transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tulis Jurnal Baru
                </a>
            </div>

            @if($jurnals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktivitas & Kegiatan</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Foto Bukti</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">View</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan Pembimbing</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($jurnals as $jurnal)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800">Minggu ke-{{ $jurnal->minggu_ke }}</span>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700 max-w-sm whitespace-pre-line leading-relaxed">{{ $jurnal->kegiatan }}</p>
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full mt-2">
                                            ⏱️ {{ $jurnal->durasi_jam }} Jam Kerja
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($jurnal->bukti_foto)
                                            <img src="{{ asset('storage/' . $jurnal->bukti_foto) }}" alt="Bukti Jurnal" 
                                                 class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm mx-auto">
                                        @else
                                            <span class="text-gray-400 text-xs italic bg-gray-50 px-2.5 py-1 rounded border border-gray-100">Kosong</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" 
                                                data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}"
                                                data-minggu="Minggu ke-{{ $jurnal->minggu_ke }}"
                                                data-kegiatan="{{ $jurnal->kegiatan }}"
                                                data-durasi="{{ $jurnal->durasi_jam }} Jam Kerja"
                                                data-status="{{ $jurnal->status }}"
                                                data-status-label="{{ in_array($jurnal->status, ['disetujui', 'pending']) ? 'Berhasil' : 'Gagal' }}"
                                                data-catatan="{{ $jurnal->catatan_pembimbing ?? '-' }}"
                                                data-foto="{{ $jurnal->bukti_foto ? asset('storage/' . $jurnal->bukti_foto) : '' }}"
                                                onclick="showJurnalDetail(this)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold rounded-lg text-xs transition duration-200 shadow-sm shadow-orange-500/10">
                                            Lihat
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3.5 py-1.5 text-[10px] font-black uppercase rounded-full tracking-wider inline-block
                                            @if(in_array($jurnal->status, ['disetujui', 'pending'])) bg-green-50 text-green-700 border border-green-200
                                             @else bg-red-50 text-red-700 border border-red-200 @endif">
                                            @if(in_array($jurnal->status, ['disetujui', 'pending']))
                                                 Berhasil
                                             @else
                                                 Gagal
                                             @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($jurnal->catatan_pembimbing)
                                            <div class="bg-amber-50 border border-amber-200 p-2.5 rounded-lg text-xs text-amber-800 italic max-w-xs">
                                                {{ $jurnal->catatan_pembimbing }}
                                            </div>
                                        @else
                                            <span class="italic text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Section -->
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $jurnals->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Belum ada riwayat jurnal</h3>
                    <p class="text-sm text-gray-400 mt-1 max-w-sm mx-auto">Silakan tulis jurnal harian magang Anda terlebih dahulu melalui tombol di atas.</p>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Modal Detail Jurnal -->
<div id="detailJurnalModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeJurnalDetail()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 z-10" id="detailJurnalContent">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-150 flex items-center justify-between bg-gradient-to-r from-orange-500 to-amber-500 text-white">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Detail Jurnal Harian
            </h3>
            <button onclick="closeJurnalDetail()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <!-- Tanggal & Minggu -->
            <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
                <div>
                    <span class="text-xs text-gray-405 font-medium block">Tanggal</span>
                    <span class="text-sm font-bold text-gray-800" id="modalTanggal">-</span>
                </div>
                <div>
                    <span class="text-xs text-gray-405 font-medium block">Periode</span>
                    <span class="text-sm font-bold text-gray-800" id="modalMinggu">-</span>
                </div>
            </div>
            
            <!-- Durasi & Status -->
            <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
                <div>
                    <span class="text-xs text-gray-405 font-medium block mb-1">Durasi Kerja</span>
                    <span class="inline-flex items-center gap-1 text-xs text-emerald-650 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full" id="modalDurasi">
                        -
                    </span>
                </div>
                <div>
                    <span class="text-xs text-gray-405 font-medium block mb-1">Status</span>
                    <span id="modalStatusBadge" class="px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider inline-block border">
                        -
                    </span>
                </div>
            </div>
            
            <!-- Kegiatan -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-405 font-medium block mb-1">Aktivitas & Kegiatan</span>
                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 whitespace-pre-wrap leading-relaxed" id="modalKegiatan">-</p>
            </div>

            <!-- Catatan Pembimbing -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-405 font-medium block mb-1">Catatan Pembimbing</span>
                <p class="text-xs text-amber-800 bg-amber-50 p-3 rounded-lg border border-amber-200 italic" id="modalCatatan">-</p>
            </div>
            
            <!-- Bukti Foto -->
            <div class="py-2">
                <span class="text-xs text-gray-405 font-medium block mb-2">Foto Bukti</span>
                <div id="modalFotoContainer" class="relative group overflow-hidden rounded-xl border border-gray-200 shadow-sm bg-gray-50 max-h-60 flex items-center justify-center">
                    <img id="modalFoto" src="" alt="Foto Bukti" class="w-full h-full object-contain max-h-60">
                    <div id="modalFotoPlaceholder" class="text-gray-450 text-sm italic py-8 flex flex-col items-center gap-2">
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
            <button onclick="closeJurnalDetail()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl text-sm transition">
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
    document.getElementById('modalTanggal').textContent = data.tanggal;
    document.getElementById('modalMinggu').textContent = data.minggu;
    document.getElementById('modalDurasi').textContent = '⏱️ ' + data.durasi;
    document.getElementById('modalKegiatan').textContent = data.kegiatan;
    document.getElementById('modalCatatan').textContent = data.catatan;
    
    // Reset status badge classes
    const badge = document.getElementById('modalStatusBadge');
    badge.textContent = data.statusLabel;
    
    const status = data.status;
    if (status === 'disetujui') {
        badge.className = 'px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider inline-block bg-green-50 text-green-700 border border-green-200';
    } else if (status === 'pending') {
        badge.className = 'px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider inline-block bg-yellow-50 text-yellow-700 border border-yellow-200';
    } else {
        badge.className = 'px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-wider inline-block bg-red-50 text-red-700 border border-red-200';
    }
    
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
