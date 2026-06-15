@extends('layouts.siswa')

@section('title', 'Riwayat Absensi')

@section('header_breadcrumb', 'Riwayat / Absensi')
@section('header_title', 'RIWAYAT ABSENSI')

@section('content')
<div class="p-0">
    <!-- LOCK MESSAGE: Jika Belum Boleh Absen -->
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
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses riwayat absensi.' }}</p>
                    
                    @if(!$penempatan)
                        <div class="bg-white rounded-xl p-4 border border-red-200">
                            <p class="text-sm text-red-600 font-semibold mb-2">📌 Yang Perlu Dilakukan:</p>
                            <ol class="text-sm text-red-700 space-y-1 list-decimal list-inside">
                                <li>Buka menu <strong>Cek Status Magang</strong></li>
                                <li>Pilih mitra magang yang diinginkan</li>
                                <li>Ajukan pengajuan tempat magang</li>
                                <li>Tunggu approval dari TU dan Pimpinan</li>
                                <li>Setelah disetujui, riwayat absensi akan terbuka</li>
                            </ol>
                            <a href="{{ route('siswa.cek-status') }}" 
                               class="inline-block mt-4 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                                📍 Buka Cek Status Magang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total Kehadiran</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $absensis->total() }} Hari</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                    K
                </div>
            </div>
            
            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Hadir</p>
                    <h3 class="text-2xl font-black text-green-600 mt-1">
                        {{ $absensis->where('status', 'hadir')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold text-lg">
                    H
                </div>
            </div>

            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Izin / Sakit</p>
                    <h3 class="text-2xl font-black text-blue-600 mt-1">
                        {{ $absensis->where('status', 'izin')->count() + $absensis->where('status', 'sakit')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                    I/S
                </div>
            </div>

            <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Alpha</p>
                    <h3 class="text-2xl font-black text-red-500 mt-1">
                        {{ $absensis->where('status', 'alpha')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-650 rounded-xl flex items-center justify-center font-bold text-lg">
                    A
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100/50 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">📊 Rekapitulasi Absensi Harian</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar lengkap kehadiran Anda selama masa Prakerin</p>
                </div>
                <a href="{{ route('siswa.laporan.absensi') }}" 
                   class="px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-500 text-white font-semibold rounded-xl shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:scale-[1.02] transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Isi Absen Hari Ini
                </a>
            </div>

            @if($absensis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bukti Foto</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">View</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($absensis as $absen)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                        {{ $absen->tanggal->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3.5 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1.5
                                            @if($absen->status === 'hadir') bg-green-50 text-green-700 border border-green-200
                                            @elseif($absen->status === 'izin') bg-yellow-50 text-yellow-700 border border-yellow-200
                                            @elseif($absen->status === 'sakit') bg-blue-50 text-blue-700 border border-blue-200
                                            @else bg-red-50 text-red-700 border border-red-200 @endif">
                                            <span class="w-1.5 h-1.5 rounded-full 
                                                @if($absen->status === 'hadir') bg-green-500
                                                @elseif($absen->status === 'izin') bg-yellow-500
                                                @elseif($absen->status === 'sakit') bg-blue-500
                                                @else bg-red-500 @endif"></span>
                                            {{ ucfirst($absen->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                        {{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                        {{ $absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                        {{ $absen->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($absen->bukti_foto)
                                            <img src="{{ asset('storage/' . $absen->bukti_foto) }}" alt="Bukti Absen" 
                                                 class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm">
                                        @else
                                            <span class="text-gray-400 text-xs italic bg-gray-50 px-2.5 py-1 rounded border border-gray-100">Kosong</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" 
                                                data-tanggal="{{ $absen->tanggal->format('d M Y') }}"
                                                data-status="{{ $absen->status }}"
                                                data-status-label="{{ ucfirst($absen->status) }}"
                                                data-jam-masuk="{{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-' }}"
                                                data-jam-pulang="{{ $absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') : '-' }}"
                                                data-keterangan="{{ $absen->keterangan ?? '-' }}"
                                                data-foto="{{ $absen->bukti_foto ? asset('storage/' . $absen->bukti_foto) : '' }}"
                                                onclick="showAbsenDetail(this)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold rounded-lg text-xs transition duration-200 shadow-sm shadow-orange-500/10">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Section -->
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $absensis->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Belum ada riwayat absensi</h3>
                    <p class="text-sm text-gray-400 mt-1 max-w-sm mx-auto">Silakan isi absensi harian Anda terlebih dahulu melalui tombol di atas.</p>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Modal Detail Absensi -->
<div id="detailAbsenModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeAbsenDetail()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 z-10" id="detailAbsenContent">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-150 flex items-center justify-between bg-gradient-to-r from-orange-500 to-amber-500 text-white">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Detail Absensi
            </h3>
            <button onclick="closeAbsenDetail()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <!-- Tanggal -->
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm text-gray-500 font-medium">Tanggal</span>
                <span class="text-sm font-bold text-gray-800" id="modalTanggal">-</span>
            </div>
            
            <!-- Status -->
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm text-gray-500 font-medium">Status Kehadiran</span>
                <span id="modalStatusBadge" class="px-3 py-1 text-xs font-bold rounded-full inline-flex items-center gap-1.5 border">
                    <span class="w-1.5 h-1.5 rounded-full" id="modalStatusDot"></span>
                    <span id="modalStatusLabel">-</span>
                </span>
            </div>
            
            <!-- Jam -->
            <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
                <div>
                    <span class="text-xs text-gray-400 font-medium block">Jam Masuk</span>
                    <span class="text-sm font-bold text-gray-700 font-mono" id="modalJamMasuk">-</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 font-medium block">Jam Pulang</span>
                    <span class="text-sm font-bold text-gray-700 font-mono" id="modalJamPulang">-</span>
                </div>
            </div>
            
            <!-- Keterangan -->
            <div class="py-2 border-b border-gray-100">
                <span class="text-xs text-gray-400 font-medium block mb-1">Keterangan</span>
                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 whitespace-pre-wrap leading-relaxed" id="modalKeterangan">-</p>
            </div>
            
            <!-- Bukti Foto -->
            <div class="py-2">
                <span class="text-xs text-gray-400 font-medium block mb-2">Bukti Foto</span>
                <div id="modalFotoContainer" class="relative group overflow-hidden rounded-xl border border-gray-200 shadow-sm bg-gray-50 max-h-60 flex items-center justify-center">
                    <img id="modalFoto" src="" alt="Bukti Foto" class="w-full h-full object-contain max-h-60">
                    <div id="modalFotoPlaceholder" class="text-gray-450 text-sm italic py-8 flex flex-col items-center gap-2">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Tidak ada bukti foto
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button onclick="closeAbsenDetail()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl text-sm transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailAbsenModal');
    if (modal) {
        document.body.appendChild(modal);
    }
});

function showAbsenDetail(btn) {
    const data = btn.dataset;
    
    // Set text values
    document.getElementById('modalTanggal').textContent = data.tanggal;
    document.getElementById('modalJamMasuk').textContent = data.jamMasuk;
    document.getElementById('modalJamPulang').textContent = data.jamPulang;
    document.getElementById('modalKeterangan').textContent = data.keterangan;
    document.getElementById('modalStatusLabel').textContent = data.statusLabel;
    
    // Reset status badge classes
    const badge = document.getElementById('modalStatusBadge');
    const dot = document.getElementById('modalStatusDot');
    
    badge.className = 'px-3 py-1 text-xs font-bold rounded-full inline-flex items-center gap-1.5 border ';
    dot.className = 'w-1.5 h-1.5 rounded-full ';
    
    const status = data.status;
    if (status === 'hadir') {
        badge.className += 'bg-green-50 text-green-700 border-green-200';
        dot.className += 'bg-green-500';
    } else if (status === 'izin') {
        badge.className += 'bg-yellow-50 text-yellow-700 border-yellow-200';
        dot.className += 'bg-yellow-500';
    } else if (status === 'sakit') {
        badge.className += 'bg-blue-50 text-blue-700 border-blue-200';
        dot.className += 'bg-blue-500';
    } else {
        badge.className += 'bg-red-50 text-red-700 border-red-200';
        dot.className += 'bg-red-500';
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
    const modal = document.getElementById('detailAbsenModal');
    const content = document.getElementById('detailAbsenContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeAbsenDetail() {
    const modal = document.getElementById('detailAbsenModal');
    const content = document.getElementById('detailAbsenContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
@endsection
