@extends('layouts.siswa')

@section('title', 'Laporan PKL')

@section('header_breadcrumb', 'Laporan / PKL')
@section('header_title', 'LAPORAN PKL')

@section('content')
<div class="p-0">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-red-700">{{ session('error') }}</span>
        </div>
    @endif

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
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Upload Laporan Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses upload laporan.' }}</p>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                        📍 Buka Cek Status Magang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Info Card -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl p-6 mb-8 shadow-sm max-w-3xl mx-auto">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2 tracking-wide text-base">Panduan Upload Laporan PKL</h3>
                    <ul class="text-sm text-gray-600 space-y-1.5 font-semibold">
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-500">•</span> Upload laporan PKL dalam format <strong class="text-gray-850">PDF</strong>
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-500">•</span> Ukuran file maksimal <strong class="text-gray-855">10MB</strong>
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-red-500">•</span> <strong class="text-red-650">Hanya bisa upload 1 kali</strong>, pastikan file sudah benar!
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-500">•</span> Laporan akan diverifikasi oleh pembimbing dan guru pamong
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-500">•</span> Jika perlu revisi, Anda akan mendapat notifikasi
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-500">•</span> Setelah disetujui, sertifikat dapat diunduh
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Single Column Layout centered -->
        <div class="max-w-3xl mx-auto mb-8">
            <div class="bg-white/65 backdrop-blur-md rounded-2xl shadow-sm border border-white/50 p-8">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="p-1.5 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </span>
                    Upload Laporan
                </h2>
                
                @if($laporans->count() > 0 && $laporans->first()->status !== 'perlu_revisi')
                    <!-- Sudah Upload Laporan -->
                    <div class="text-center py-6">
                        <div class="w-16 h-16 bg-gradient-to-tr from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md shadow-emerald-500/10 text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1">Laporan Sudah Diupload</h3>
                        <p class="text-xs text-gray-500 mb-6">Anda sudah mengupload laporan PKL</p>
                        
                        @php
                            $laporan = $laporans->first();
                        @endphp
                        
                        <div class="bg-gray-50/80 border border-gray-150 rounded-xl p-4 mb-5 max-w-md mx-auto">
                            <p class="text-xs text-gray-400 font-bold tracking-wide uppercase mb-2">Status Laporan</p>
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold rounded-full
                                @if($laporan->status === 'disetujui') bg-green-50 text-green-700
                                @elseif($laporan->status === 'pending') bg-yellow-50 text-yellow-755
                                @else bg-red-50 text-red-700 @endif">
                                @if($laporan->status === 'disetujui')
                                    ✅ Berhasil (Disetujui)
                                @elseif($laporan->status === 'pending')
                                    ⏳ Menunggu Verifikasi
                                @else
                                    ❌ Gagal (Perlu Revisi)
                                @endif
                            </span>
                        </div>
                        
                        @if($laporan->file_path)
                            <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" 
                               class="inline-flex items-center justify-center gap-2 max-w-md w-full px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-xl shadow-md shadow-emerald-500/10 transition duration-200 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download Laporan
                            </a>
                        @endif
                        
                        @if($laporan->catatan_pembimbing)
                            <div class="bg-yellow-50 border border-yellow-150 rounded-xl p-4 mt-4 text-left max-w-md mx-auto">
                                <p class="text-xs text-yellow-800 font-bold mb-1 flex items-center gap-1">
                                    💬 Catatan Pembimbing:
                                </p>
                                <p class="text-xs text-yellow-750 leading-relaxed font-semibold">{{ $laporan->catatan_pembimbing }}</p>
                            </div>
                        @endif
                        
                        <p class="text-[10px] text-gray-400 mt-5 font-semibold">
                            📅 Upload: {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                @else
                    @if($laporans->count() > 0 && $laporans->first()->status === 'perlu_revisi')
                        <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6 text-left">
                            <p class="text-sm text-red-800 font-bold mb-2 flex items-center gap-1">
                                ❌ Laporan Anda Perlu Revisi!
                            </p>
                            <p class="text-sm text-red-700 font-semibold mb-2">Catatan Pembimbing: {{ $laporans->first()->catatan_pembimbing }}</p>
                            <p class="text-xs text-red-600 mt-2 italic">Silakan perbaiki laporan Anda dan upload ulang formulir di bawah ini.</p>
                        </div>
                    @endif
                    <!-- Form Upload Laporan -->
                    <form action="{{ route('siswa.laporan.pkl') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <!-- Judul Laporan -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                📖 Judul Laporan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul_laporan" required maxlength="255"
                                   placeholder="Contoh: Implementasi Sistem Informasi Manajemen di PT. XYZ"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 outline-none transition duration-200 text-sm">
                        </div>
                        
                        <!-- Abstrak -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                📝 Abstrak (Opsional)
                            </label>
                            <textarea name="abstrak" rows="4" maxlength="1000"
                                      placeholder="Ringkasan singkat tentang laporan PKL Anda..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 outline-none resize-none transition duration-200 text-sm"></textarea>
                            <p class="text-[10px] text-gray-400 mt-1 text-right font-semibold"><span id="abstrakCount">0</span>/1000</p>
                        </div>
                        
                        <!-- File PDF -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                📄 File Laporan (PDF) <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-emerald-200 bg-emerald-50/20 rounded-2xl p-6 text-center hover:border-emerald-400 transition duration-200 group">
                                <input type="file" name="file_path" accept=".pdf,application/pdf" required 
                                       id="filePreview" 
                                       class="hidden" 
                                       onchange="previewFile(this)">
                                <label for="filePreview" class="cursor-pointer block">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-emerald-450 group-hover:scale-105 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V4a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-xs text-emerald-700 font-bold mb-1">Klik untuk upload PDF</p>
                                    <p class="text-[10px] text-emerald-500/80 mb-2">Maksimal 10MB</p>
                                    <p class="text-[9px] text-gray-400">Format: PDF only</p>
                                </label>
                                <div id="fileInfo" class="hidden mt-4 p-3 bg-white rounded-xl border border-emerald-100 shadow-sm text-left">
                                    <p class="text-xs text-gray-800 font-bold truncate" id="fileName"></p>
                                    <p class="text-[10px] text-gray-500 mt-0.5" id="fileSize"></p>
                                </div>
                            </div>
                            <p class="text-[10px] text-red-650 font-bold mt-2.5">⚠️ Pastikan file sudah benar, hanya bisa upload 1 kali!</p>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3 px-4 rounded-xl shadow-md shadow-emerald-500/10 transition duration-200 text-sm">
                            📤 Upload Laporan
                        </button>
                        
                        <p class="text-[10px] text-gray-500 text-center font-medium">
                            Laporan akan diverifikasi oleh pembimbing & pamong
                        </p>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Script Preview File & Char Count -->
<script>
// Preview File
function previewFile(input) {
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const file = input.files[0];
    
    if (file) {
        // Validasi tipe file
        if (file.type !== 'application/pdf') {
            alert('Hanya file PDF yang diperbolehkan!');
            input.value = '';
            return;
        }
        
        // Validasi ukuran file (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file maksimal 10MB!');
            input.value = '';
            return;
        }
        
        fileInfo.classList.remove('hidden');
        fileName.textContent = '📄 ' + file.name;
        fileSize.textContent = '📊 ' + (file.size / 1024 / 1024).toFixed(2) + ' MB';
    } else {
        fileInfo.classList.add('hidden');
    }
}

// Abstrak Character Counter
const abstrakTextarea = document.querySelector('textarea[name="abstrak"]');
const abstrakCount = document.getElementById('abstrakCount');

if (abstrakTextarea) {
    abstrakTextarea.addEventListener('input', function() {
        abstrakCount.textContent = this.value.length;
        
        if (this.value.length > 1000) {
            abstrakCount.classList.add('text-red-500');
            abstrakCount.classList.remove('text-green-500');
        } else {
            abstrakCount.classList.remove('text-red-500');
            abstrakCount.classList.add('text-green-500');
        }
    });
}
</script>
@endsection