@extends('layouts.siswa')

@section('title', 'Input Nilai PKL')

@section('header_breadcrumb', 'Laporan / Input Nilai')
@section('header_title', 'INPUT NILAI TEKNIS PKL')

@section('content')
<div class="p-0">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700 font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-red-700 font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Lock Check -->
    @if(!$bolehAbsen)
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Input Nilai Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat melakukan input nilai.' }}</p>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                        📍 Buka Cek Status Magang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Main Form Grid layout: Left is Form, Right is Real-time Average / Info Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
            <!-- Form Block -->
            <div class="lg:col-span-2 bg-white/65 backdrop-blur-md rounded-3xl shadow-sm border border-white/50 p-8">
                <h2 class="text-xl font-black text-gray-800 mb-6 flex items-center gap-3">
                    <span class="p-2 bg-amber-50 rounded-xl text-amber-600 shadow-sm shadow-amber-500/5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9a2 2 0 100-4 2 2 0 000 4zm0 0v1a3 3 0 013 3h-6a3 3 0 013-3z"/>
                        </svg>
                    </span>
                    Form Nilai Teknis
                </h2>

                <form id="nilaiForm" action="{{ route('siswa.laporan.nilai.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Notice Card & Progress Badge -->
                    <div id="notice-card" class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div class="flex items-start gap-3">
                            <span class="text-lg">⚠️</span>
                            <div>
                                <h4 class="text-sm font-bold text-amber-800">Wajib Mengisi 3 Baris Penilaian</h4>
                                <p class="text-xs text-amber-700 mt-0.5">Anda wajib menambahkan dan mengisi detail kegiatan beserta nilainya sampai dengan 3 baris sebelum menyimpan.</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span id="row-progress-badge" class="inline-flex items-center px-3 py-1 bg-amber-200/50 text-amber-800 text-xs font-black rounded-lg uppercase tracking-wider">
                                1 / 3 Baris
                            </span>
                        </div>
                    </div>

                    <!-- Dynamic Kegiatan & Nilai Container -->
                    <div id="kegiatan-container" class="space-y-4">
                        <!-- Row 1 (Always visible & required) -->
                        <div class="kegiatan-row p-4 bg-gray-50 border border-gray-200 rounded-2xl flex flex-col md:flex-row gap-4 items-end relative" id="row-1">
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    📝 Nama Kegiatan/Materi 1 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kegiatan_1" id="kegiatan_1" required
                                       value="{{ old('kegiatan_1', $nilai->kegiatan_1 ?? '') }}"
                                       placeholder="Contoh: Mempelajari React JS dasar..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 outline-none transition duration-200 text-sm font-semibold text-gray-800">
                            </div>
                            <div class="w-full md:w-36">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    📊 Nilai (0 - 100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="nilai_1" id="nilai_1" min="0" max="100" step="0.01" required
                                       value="{{ old('nilai_1', $nilai->nilai_1 ?? '') }}"
                                       placeholder="0"
                                       class="nilai-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 outline-none transition duration-200 text-sm font-semibold text-gray-800">
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="kegiatan-row p-4 bg-gray-50 border border-gray-200 rounded-2xl flex flex-col md:flex-row gap-4 items-end relative" id="row-2" style="display: none;">
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    📝 Nama Kegiatan/Materi 2 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kegiatan_2" id="kegiatan_2" required disabled
                                       value="{{ old('kegiatan_2', $nilai->kegiatan_2 ?? '') }}"
                                       placeholder="Contoh: Membuat rancangan database..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 outline-none transition duration-200 text-sm font-semibold text-gray-800">
                            </div>
                            <div class="w-full md:w-36">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    📊 Nilai (0 - 100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="nilai_2" id="nilai_2" min="0" max="100" step="0.01" required disabled
                                       value="{{ old('nilai_2', $nilai->nilai_2 ?? '') }}"
                                       placeholder="0"
                                       class="nilai-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 outline-none transition duration-200 text-sm font-semibold text-gray-800">
                            </div>
                            <div class="w-full md:w-auto self-center md:self-end">
                                <button type="button" onclick="removeRow(2)" class="px-3.5 py-3 text-red-600 hover:bg-red-50 rounded-xl transition font-bold text-xs uppercase tracking-wider border border-red-200 mt-2 md:mt-0 flex items-center gap-1.5">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="kegiatan-row p-4 bg-gray-50 border border-gray-200 rounded-2xl flex flex-col md:flex-row gap-4 items-end relative" id="row-3" style="display: none;">
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    📝 Nama Kegiatan/Materi 3 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kegiatan_3" id="kegiatan_3" required disabled
                                       value="{{ old('kegiatan_3', $nilai->kegiatan_3 ?? '') }}"
                                       placeholder="Contoh: Menguji fungsionalitas web..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 outline-none transition duration-200 text-sm font-semibold text-gray-800">
                            </div>
                            <div class="w-full md:w-36">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    📊 Nilai (0 - 100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="nilai_3" id="nilai_3" min="0" max="100" step="0.01" required disabled
                                       value="{{ old('nilai_3', $nilai->nilai_3 ?? '') }}"
                                       placeholder="0"
                                       class="nilai-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 outline-none transition duration-200 text-sm font-semibold text-gray-800">
                            </div>
                            <div class="w-full md:w-auto self-center md:self-end">
                                <button type="button" onclick="removeRow(3)" class="px-3.5 py-3 text-red-600 hover:bg-red-50 rounded-xl transition font-bold text-xs uppercase tracking-wider border border-red-200 mt-2 md:mt-0 flex items-center gap-1.5">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Row Button -->
                    <div class="mt-4" id="add-row-container">
                        <button type="button" id="btn-add-row" onclick="addRow()" class="px-5 py-2.5 bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-800 font-bold rounded-xl transition text-xs uppercase tracking-wider flex items-center gap-2 shadow-sm">
                            ➕ Tambah Baris Nilai
                        </button>
                    </div>

                    <!-- File Upload Foto Nilai -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            📸 Upload Foto Nilai / Lembar Penilaian <span class="text-red-500">*</span>
                        </label>
                        
                        @if($nilai && $nilai->foto_nilai)
                            <div class="mb-3 p-3 bg-amber-50/50 border border-amber-200 rounded-xl flex items-center gap-3">
                                <img src="{{ asset('storage/' . $nilai->foto_nilai) }}" alt="Foto Nilai" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-700 truncate">Foto Terupload</p>
                                    <a href="{{ asset('storage/' . $nilai->foto_nilai) }}" target="_blank" class="text-[11px] text-amber-600 hover:underline font-bold">Lihat Foto Ukuran Penuh</a>
                                </div>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-amber-200 bg-amber-50/10 rounded-2xl p-6 text-center hover:border-amber-400 transition duration-200 group">
                            <input type="file" name="foto_nilai" accept="image/*" 
                                   id="fotoPreview" 
                                   class="hidden" 
                                   onchange="previewImage(this)"
                                   {{ ($nilai && $nilai->foto_nilai) ? '' : 'required' }}>
                            <label for="fotoPreview" class="cursor-pointer block">
                                <svg class="w-12 h-12 mx-auto mb-3 text-amber-550 group-hover:scale-105 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs text-amber-700 font-bold mb-1">Klik untuk upload foto lembar penilaian</p>
                                <p class="text-[10px] text-amber-500/80 mb-2">Maksimal 2MB (Format: JPG, JPEG, PNG)</p>
                            </label>
                            
                            <div id="imageInfo" class="hidden mt-4 p-3 bg-white rounded-xl border border-amber-100 shadow-sm flex items-center gap-3 text-left">
                                <img id="imagePreviewImg" class="w-16 h-16 object-cover rounded-lg border border-gray-150" src="#" alt="Preview">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-gray-800 truncate" id="fileName"></p>
                                    <p class="text-[10px] text-gray-500 mt-0.5" id="fileSize"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3.5 px-4 rounded-2xl shadow-lg shadow-amber-500/10 transition duration-200 text-sm tracking-wide">
                        💾 Simpan Nilai Teknis
                    </button>
                </form>
            </div>

            <!-- Average and Guide Sidebar -->
            <div class="space-y-6">
                <!-- Live Calculation Card -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-8 text-white shadow-xl shadow-amber-500/10 flex flex-col justify-between min-h-[260px] relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/10 rounded-full blur-xl"></div>
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-white/80 mb-6">Rata-rata Nilai Teknis</h3>
                        <div class="flex items-baseline gap-2">
                            <span id="liveAverage" class="text-6xl font-black tracking-tight">0.00</span>
                            <span class="text-sm font-bold text-white/70">/100</span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <p class="text-xs text-white/80 font-bold uppercase tracking-wider mb-2">Predikat Performa</p>
                        <span id="liveCategory" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-sm font-black tracking-wide shadow-sm uppercase">
                            -
                        </span>
                    </div>
                </div>

                <!-- Info Guide Card -->
                <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 tracking-wide text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Skala Penilaian PKL
                    </h3>
                    <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between items-center p-2.5 bg-green-50 rounded-xl border border-green-100 text-green-700">
                            <span>Sangat Baik</span>
                            <span class="font-bold">86 - 100</span>
                        </div>
                        <div class="flex justify-between items-center p-2.5 bg-blue-50 rounded-xl border border-blue-100 text-blue-700">
                            <span>Baik</span>
                            <span class="font-bold">70 - 85</span>
                        </div>
                        <div class="flex justify-between items-center p-2.5 bg-amber-50 rounded-xl border border-amber-100 text-amber-700">
                            <span>Cukup</span>
                            <span class="font-bold">56 - 69</span>
                        </div>
                        <div class="flex justify-between items-center p-2.5 bg-red-50 rounded-xl border border-red-100 text-red-700">
                            <span>Kurang</span>
                            <span class="font-bold">0 - 55</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>

<!-- Scripts for Image Preview & Realtime Math -->
<script>
    // Preview image before upload
    function previewImage(input) {
        const imageInfo = document.getElementById('imageInfo');
        const imgPreview = document.getElementById('imagePreviewImg');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const file = input.files[0];
        
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Hanya file Gambar (JPG, JPEG, PNG) yang diperbolehkan!');
                input.value = '';
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
            
            imageInfo.classList.remove('hidden');
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        } else {
            imageInfo.classList.add('hidden');
        }
    }

    // Dynamic row management
    function addRow() {
        const row2 = document.getElementById('row-2');
        const row3 = document.getElementById('row-3');
        
        if (row2.style.display === 'none') {
            row2.style.display = 'flex';
            document.getElementById('kegiatan_2').disabled = false;
            document.getElementById('nilai_2').disabled = false;
        } else if (row3.style.display === 'none') {
            row3.style.display = 'flex';
            document.getElementById('kegiatan_3').disabled = false;
            document.getElementById('nilai_3').disabled = false;
            document.getElementById('add-row-container').style.display = 'none';
        }
        updateBadge();
        calculateAverage();
    }

    function removeRow(index) {
        if (index === 3) {
            const row = document.getElementById('row-3');
            row.style.display = 'none';
            document.getElementById('kegiatan_3').disabled = true;
            document.getElementById('nilai_3').disabled = true;
            document.getElementById('kegiatan_3').value = '';
            document.getElementById('nilai_3').value = '';
            document.getElementById('add-row-container').style.display = 'block';
        } else if (index === 2) {
            const row3Visible = document.getElementById('row-3').style.display !== 'none';
            if (row3Visible) {
                // Shift values from row 3 to row 2
                document.getElementById('kegiatan_2').value = document.getElementById('kegiatan_3').value;
                document.getElementById('nilai_2').value = document.getElementById('nilai_3').value;
                removeRow(3);
            } else {
                const row = document.getElementById('row-2');
                row.style.display = 'none';
                document.getElementById('kegiatan_2').disabled = true;
                document.getElementById('nilai_2').disabled = true;
                document.getElementById('kegiatan_2').value = '';
                document.getElementById('nilai_2').value = '';
                document.getElementById('add-row-container').style.display = 'block';
            }
        }
        updateBadge();
        calculateAverage();
    }

    function updateBadge() {
        const r2 = document.getElementById('row-2').style.display !== 'none';
        const r3 = document.getElementById('row-3').style.display !== 'none';
        let count = 1;
        if (r2) count++;
        if (r3) count++;
        
        const badge = document.getElementById('row-progress-badge');
        if (badge) {
            badge.textContent = count + ' / 3 Baris';
            if (count === 3) {
                badge.className = 'inline-flex items-center px-3 py-1 bg-emerald-500 text-white text-xs font-black rounded-lg uppercase tracking-wider';
            } else {
                badge.className = 'inline-flex items-center px-3 py-1 bg-amber-200/50 text-amber-800 text-xs font-black rounded-lg uppercase tracking-wider';
            }
        }
    }

    // Realtime Average & Performance calculations
    const liveAverage = document.getElementById('liveAverage');
    const liveCategory = document.getElementById('liveCategory');

    function calculateAverage() {
        let total = 0;
        let count = 0;
        const activeInputs = document.querySelectorAll('.nilai-input:not(:disabled)');
        
        activeInputs.forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) {
                total += val;
                count++;
            }
        });
        
        if (count > 0) {
            const avg = total / count;
            liveAverage.textContent = avg.toFixed(2);
            
            // Set Category
            if (avg >= 86) {
                liveCategory.textContent = '🌟 Sangat Baik';
                liveCategory.className = 'inline-flex items-center px-4 py-2 bg-emerald-500/25 border border-emerald-400/30 rounded-xl text-sm font-black tracking-wide shadow-sm uppercase text-white';
            } else if (avg >= 70) {
                liveCategory.textContent = '👍 Baik';
                liveCategory.className = 'inline-flex items-center px-4 py-2 bg-blue-500/25 border border-blue-400/30 rounded-xl text-sm font-black tracking-wide shadow-sm uppercase text-white';
            } else if (avg >= 56) {
                liveCategory.textContent = '😐 Cukup';
                liveCategory.className = 'inline-flex items-center px-4 py-2 bg-yellow-500/25 border border-yellow-400/30 rounded-xl text-sm font-black tracking-wide shadow-sm uppercase text-white';
            } else {
                liveCategory.textContent = '⚠️ Kurang';
                liveCategory.className = 'inline-flex items-center px-4 py-2 bg-red-500/25 border border-red-400/30 rounded-xl text-sm font-black tracking-wide shadow-sm uppercase text-white';
            }
        } else {
            liveAverage.textContent = '0.00';
            liveCategory.textContent = '-';
            liveCategory.className = 'inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-sm font-black tracking-wide shadow-sm uppercase';
        }
    }

    // Attach dynamic input listeners
    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('nilai-input')) {
            calculateAverage();
        }
    });

    // Form submission validation
    document.getElementById('nilaiForm').addEventListener('submit', function(e) {
        const r2 = document.getElementById('row-2').style.display !== 'none';
        const r3 = document.getElementById('row-3').style.display !== 'none';
        if (!r2 || !r3) {
            e.preventDefault();
            alert('Anda wajib menambahkan dan mengisi sampai dengan 3 baris kegiatan!');
            return false;
        }
    });

    // Run once on load to populate if edit/old mode
    window.addEventListener('DOMContentLoaded', () => {
        // Check if values exist for Row 2 or Row 3
        const val2 = document.getElementById('kegiatan_2').value;
        const val3 = document.getElementById('kegiatan_3').value;
        const old2 = "{{ old('kegiatan_2') }}";
        const old3 = "{{ old('kegiatan_3') }}";

        if (val2 || old2) {
            document.getElementById('row-2').style.display = 'flex';
            document.getElementById('kegiatan_2').disabled = false;
            document.getElementById('nilai_2').disabled = false;
        }
        if (val3 || old3) {
            document.getElementById('row-3').style.display = 'flex';
            document.getElementById('kegiatan_3').disabled = false;
            document.getElementById('nilai_3').disabled = false;
            document.getElementById('add-row-container').style.display = 'none';
        }

        updateBadge();
        calculateAverage();
    });
</script>
@endsection
