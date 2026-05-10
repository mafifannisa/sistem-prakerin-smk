@extends('layouts.siswa')

@section('title', 'Laporan PKL')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan PKL</h1>
            <p class="text-sm text-gray-500 mt-1">Upload laporan PKL dalam format PDF</p>
        </div>
        <div class="text-sm text-gray-600">{{ tanggal_indonesia() }}</div>
    </div>
</header>

<div class="p-8">
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
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Panduan Upload Laporan PKL</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Upload laporan PKL dalam format <strong>PDF</strong></li>
                        <li>• Ukuran file maksimal <strong>10MB</strong></li>
                        <li>• <strong class="text-red-600">Hanya bisa upload 1 kali</strong>, pastikan file sudah benar!</li>
                        <li>• Laporan akan diverifikasi oleh pembimbing dan guru pamong</li>
                        <li>• Jika perlu revisi, Anda akan mendapat notifikasi</li>
                        <li>• Setelah disetujui, sertifikat dapat diunduh</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Form Upload (Kiri) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">📤 Upload Laporan</h2>
                    
                    @if($laporans->count() > 0)
                        <!-- Sudah Upload Laporan -->
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-2">Laporan Sudah Diupload</h3>
                            <p class="text-sm text-gray-500 mb-4">Anda sudah mengupload laporan PKL</p>
                            
                            @php
                                $laporan = $laporans->first();
                            @endphp
                            
                            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                <p class="text-xs text-gray-500 mb-2">Status Laporan:</p>
                                <span class="px-4 py-2 text-sm font-bold rounded-full
                                    @if($laporan->status === 'disetujui') bg-green-100 text-green-700
                                    @elseif($laporan->status === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    @if($laporan->status === 'disetujui')
                                        ✅ Disetujui
                                    @elseif($laporan->status === 'pending')
                                        ⏳ Menunggu Verifikasi
                                    @else
                                        ❌ Perlu Revisi
                                    @endif
                                </span>
                            </div>
                            
                            @if($laporan->file_path)
                                <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download Laporan
                                </a>
                            @endif
                            
                            @if($laporan->catatan_pembimbing)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-4">
                                    <p class="text-xs text-yellow-700 font-semibold mb-2">💬 Catatan Pembimbing:</p>
                                    <p class="text-sm text-yellow-600">{{ $laporan->catatan_pembimbing }}</p>
                                </div>
                            @endif
                            
                            <p class="text-xs text-gray-400 mt-4">
                                📅 Upload: {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                    @else
                        <!-- Form Upload Laporan -->
                        <form action="{{ route('siswa.laporan.pkl') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <!-- Judul Laporan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    📖 Judul Laporan *
                                </label>
                                <input type="text" name="judul_laporan" required maxlength="255"
                                       placeholder="Contoh: Implementasi Sistem Informasi Manajemen di PT. XYZ"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <!-- Abstrak -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    📝 Abstrak (Opsional)
                                </label>
                                <textarea name="abstrak" rows="4" maxlength="1000"
                                          placeholder="Ringkasan singkat tentang laporan PKL Anda (maksimal 1000 karakter)..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                                <p class="text-xs text-gray-500 mt-1 text-right"><span id="abstrakCount">0</span>/1000</p>
                            </div>
                            
                            <!-- File PDF -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    📄 File Laporan (PDF) *
                                </label>
                                <div class="border-2 border-dashed border-blue-300 bg-blue-50 rounded-xl p-6 text-center hover:border-blue-500 transition">
                                    <input type="file" name="file_path" accept=".pdf,application/pdf" required 
                                           id="filePreview" 
                                           class="hidden" 
                                           onchange="previewFile(this)">
                                    <label for="filePreview" class="cursor-pointer">
                                        <svg class="w-16 h-16 mx-auto mb-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-sm text-blue-700 font-semibold mb-1">Klik untuk upload PDF</p>
                                        <p class="text-xs text-blue-600 mb-2">Max 10MB</p>
                                        <p class="text-xs text-gray-500">Format: PDF only</p>
                                    </label>
                                    <div id="fileInfo" class="hidden mt-4 p-3 bg-white rounded-lg border border-blue-200">
                                        <p class="text-sm text-gray-700 font-medium" id="fileName"></p>
                                        <p class="text-xs text-gray-500" id="fileSize"></p>
                                    </div>
                                </div>
                                <p class="text-xs text-red-600 mt-2">⚠️ Pastikan file sudah benar, hanya bisa upload 1 kali!</p>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                                📤 Upload Laporan
                            </button>
                            
                            <p class="text-xs text-gray-500 text-center">
                                Laporan akan diverifikasi oleh pembimbing dan guru pamong
                            </p>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info & Riwayat (Kanan) -->
            <div class="lg:col-span-2">
                <!-- Status Progress -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">📊 Status Progress Laporan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Step 1: Upload -->
                        <div class="text-center p-4 rounded-xl {{ $laporans->count() > 0 ? 'bg-green-50 border-2 border-green-200' : 'bg-gray-50 border-2 border-gray-200' }}">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                                {{ $laporans->count() > 0 ? 'bg-green-500' : 'bg-gray-300' }}">
                                @if($laporans->count() > 0)
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <span class="text-white font-bold">1</span>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-800">Upload Laporan</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $laporans->count() > 0 ? 'Selesai' : 'Belum' }}</p>
                        </div>
                        
                        <!-- Step 2: Verifikasi -->
                        <div class="text-center p-4 rounded-xl {{ $laporans->count() > 0 && $laporans->first()->status !== 'pending' ? 'bg-green-50 border-2 border-green-200' : 'bg-gray-50 border-2 border-gray-200' }}">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                                {{ $laporans->count() > 0 && $laporans->first()->status !== 'pending' ? 'bg-green-500' : 'bg-gray-300' }}">
                                @if($laporans->count() > 0 && $laporans->first()->status !== 'pending')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <span class="text-white font-bold">2</span>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-800">Verifikasi</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $laporans->count() > 0 && $laporans->first()->status !== 'pending' ? 'Selesai' : 'Pending' }}</p>
                        </div>
                        
                        <!-- Step 3: Sertifikat -->
                        <div class="text-center p-4 rounded-xl {{ $laporans->count() > 0 && $laporans->first()->status === 'disetujui' ? 'bg-green-50 border-2 border-green-200' : 'bg-gray-50 border-2 border-gray-200' }}">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                                {{ $laporans->count() > 0 && $laporans->first()->status === 'disetujui' ? 'bg-green-500' : 'bg-gray-300' }}">
                                @if($laporans->count() > 0 && $laporans->first()->status === 'disetujui')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                @else
                                    <span class="text-white font-bold">3</span>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-800">Sertifikat</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $laporans->count() > 0 && $laporans->first()->status === 'disetujui' ? 'Tersedia' : 'Terkunci' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Upload -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">📁 Riwayat Upload</h2>

                    @if($laporans->count() > 0)
                        <div class="space-y-4">
                            @foreach($laporans as $laporan)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">{{ $laporan->judul_laporan }}</h3>
                                                <p class="text-sm text-gray-500">
                                                    📅 Upload: {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-4 py-2 text-sm font-bold rounded-full
                                            @if($laporan->status === 'disetujui') bg-green-100 text-green-700
                                            @elseif($laporan->status === 'pending') bg-yellow-100 text-yellow-700
                                            @else bg-red-100 text-red-700 @endif">
                                            @if($laporan->status === 'disetujui')
                                                ✅ Disetujui
                                            @elseif($laporan->status === 'pending')
                                                ⏳ Pending
                                            @else
                                                ❌ Perlu Revisi
                                            @endif
                                        </span>
                                    </div>
                                    
                                    @if($laporan->abstrak)
                                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                            <p class="text-xs text-gray-500 font-semibold mb-2">📝 Abstrak:</p>
                                            <p class="text-sm text-gray-700">{{ Str::limit($laporan->abstrak, 200) }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($laporan->catatan_pembimbing)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                                            <p class="text-xs text-yellow-700 font-semibold mb-2">💬 Catatan Pembimbing:</p>
                                            <p class="text-sm text-yellow-600">{{ $laporan->catatan_pembimbing }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center gap-4">
                                        @if($laporan->file_path)
                                            <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" 
                                               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Download PDF
                                            </a>
                                        @endif
                                        
                                        @if($laporan->status === 'disetujui')
                                            <a href="{{ route('siswa.download.sertifikat') }}" 
                                               class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                </svg>
                                                Download Sertifikat
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada laporan yang diupload</p>
                            <p class="text-sm text-gray-400 mt-2">Silakan upload laporan PKL Anda</p>
                        </div>
                    @endif
                </div>
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