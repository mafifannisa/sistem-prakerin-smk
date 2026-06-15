@extends('layouts.siswa')

@section('title', 'Jurnal Harian')

@section('header_breadcrumb', 'Laporan / Jurnal')
@section('header_title', 'JURNAL HARIAN')

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
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Jurnal Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses jurnal harian.' }}</p>
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
                    <h3 class="font-bold text-gray-800 mb-2">Panduan Pengisian Jurnal</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Isi jurnal <strong>setiap hari</strong> setelah menyelesaikan kegiatan magang</li>
                        <li>• <strong class="text-red-600">Foto wajah WAJIB</strong> sebagai bukti Anda benar-benar melakukan kegiatan</li>
                        <li>• Deskripsi kegiatan minimal <strong>15 karakter</strong></li>
                        <li>• Pilih minggu ke- sesuai dengan durasi magang Anda</li>
                        <li>• Durasi kerja normal: <strong>6-8 jam per hari</strong></li>
                        <li>• Jurnal akan diverifikasi oleh pembimbing lapangan</li>
                    </ul>
                </div>
            </div        <!-- Centered Layout -->
        <div class="max-w-2xl mx-auto mb-8">
            <!-- Form Jurnal -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">📝 Tulis Jurnal Hari Ini</h2>
                
                @if($sudahIsiJurnalHariIni)
                    <!-- Sudah Isi Jurnal -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Jurnal Sudah Diisi</h3>
                        <p class="text-sm text-gray-500">Anda sudah mengisi jurnal untuk hari ini</p>
                        <p class="text-xs text-gray-400 mt-2">Tunggu approval dari pembimbing</p>
                    </div>
                @else
                    <!-- Form Jurnal -->
                    <form action="{{ route('siswa.laporan.jurnal') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Tanggal (Read-Only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                📅 Tanggal Jurnal *
                            </label>
                            <div class="relative">
                                <input type="date" name="tanggal" required 
                                       value="{{ date('Y-m-d') }}"
                                       readonly
                                       class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-500 cursor-not-allowed">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Tanggal otomatis hari ini</p>
                        </div>
                        
                        <!-- Minggu Ke- -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                📆 Minggu Ke- *
                            </label>
                            <select name="minggu_ke" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Minggu --</option>
                                @for($i = 1; $i <= 24; $i++)
                                    <option value="{{ $i }}">Minggu {{ $i }}</option>
                                @endfor
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Sesuaikan dengan minggu magang Anda</p>
                        </div>
                        
                        <!-- Deskripsi Kegiatan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                📖 Deskripsi Kegiatan *
                            </label>
                            <textarea name="kegiatan" rows="5" required minlength="15" maxlength="1000"
                                      placeholder="Contoh:&#10;- Melakukan input data customer ke sistem&#10;- Membuat laporan harian penjualan&#10;- Belajar menggunakan software akuntansi&#10;- Meeting dengan tim marketing"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">Minimal 15 karakter</p>
                                <p class="text-xs text-gray-400"><span id="charCount">0</span>/1000</p>
                            </div>
                        </div>
                        
                        <!-- Durasi Jam -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                ⏱️ Durasi Kerja (Jam) *
                            </label>
                            <select name="durasi_jam" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Durasi --</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }} Jam</option>
                                @endfor
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Normal: 6-8 jam per hari</p>
                        </div>
                        
                        <!-- Foto Wajah (WAJIB) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                📸 Foto Wajah (WAJIB) *
                            </label>
                            <div class="border-2 border-dashed border-red-300 bg-red-50 rounded-xl p-6 text-center hover:border-red-500 transition">
                                <input type="file" name="bukti_foto" accept="image/*" required 
                                       id="fotoPreview" 
                                       class="hidden" 
                                       onchange="previewImage(this)">
                                <label for="fotoPreview" class="cursor-pointer">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="text-sm text-red-700 font-semibold mb-1">🔴 WAJIB Upload Foto Wajah</p>
                                    <p class="text-xs text-red-600 mb-2">Foto selfie untuk bukti kegiatan</p>
                                    <p class="text-xs text-gray-500">Max 2MB (JPG, PNG)</p>
                                </label>
                                <img id="preview" class="hidden mt-4 max-h-48 mx-auto rounded-lg shadow-md border-2 border-green-500">
                            </div>
                            <p class="text-xs text-red-600 mt-2">⚠️ Foto wajib menampilkan wajah Anda!</p>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                            📤 Kirim Jurnal
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center">
                            Jurnal akan diverifikasi oleh pembimbing lapangan
                        </p>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Script Preview Image & Char Count -->
<script>
// Preview Image
function previewImage(input) {
    const preview = document.getElementById('preview');
    const file = input.files[0];
    
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2MB!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
}

// Character Counter
// Character Counter
const textarea = document.querySelector('textarea[name="kegiatan"]');
const charCount = document.getElementById('charCount');

if (textarea) {
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        
        // 👇 UBAH ANGKA 50 MENJADI 15 DI SINI 👇
        if (this.value.length < 15) { 
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-green-500');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-green-500');
        }
    });
}
</script>
@endsection