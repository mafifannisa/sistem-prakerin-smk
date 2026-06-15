@extends('layouts.admin')

@section('title', 'Cetak Surat Dispensasi')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.data.surat') }}" class="p-2.5 bg-white border border-gray-150 rounded-xl hover:bg-gray-50 text-gray-500 hover:text-gray-700 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Cetak Surat Dispensasi</h1>
            <p class="text-gray-500 text-xs mt-1">Isi formulir di bawah ini untuk menghasilkan Surat Dispensasi Siswa PDF.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-150 shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-8">
        <form action="{{ route('admin.cetak.dispensasi.pdf') }}" method="POST" target="_blank" class="space-y-6">
            @csrf

            <!-- Pilih Tipe Surat Dispensasi -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Pilih Template Surat Dispensasi</label>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Option 1: Kegiatan / Lomba -->
                    <label class="relative flex flex-col p-4 bg-gray-50 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition focus-within:ring-2 focus-within:ring-blue-500" id="label-kegiatan">
                        <input type="radio" name="tipe_surat" value="kegiatan" checked class="sr-only" onchange="switchTemplate('kegiatan')">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-800">Tipe 1: Kegiatan / Lomba</span>
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center" id="radio-indicator-kegiatan">
                                <div class="w-2 h-2 rounded-full bg-blue-600 hidden" id="radio-dot-kegiatan"></div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Dilengkapi tabel daftar nama siswa dispensasi (Contoh: Rektor Cup).</p>
                    </label>

                    <!-- Option 2: SAS / Ujian / KBM -->
                    <label class="relative flex flex-col p-4 bg-gray-50 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-100/60 transition focus-within:ring-2 focus-within:ring-blue-500" id="label-sas">
                        <input type="radio" name="tipe_surat" value="sas" class="sr-only" onchange="switchTemplate('sas')">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-800">Tipe 2: SAS / Ujian Sekolah</span>
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center" id="radio-indicator-sas">
                                <div class="w-2 h-2 rounded-full bg-blue-600 hidden" id="radio-dot-sas"></div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Berupa surat permohonan umum tanpa tabel siswa (Contoh: Sumatif Akhir Semester).</p>
                    </label>
                </div>
            </div>

            <!-- PILIH SISWA ATAU PENEMPATAN -->
            @php
                $uniqueIndustries = $placements->unique(function($place) {
                    return $place->industri_id . '_' . $place->periode_magang_id;
                });
            @endphp

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih</label>
                
                <!-- Toggle Mode Buttons -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <button type="button" id="btn-mode-siswa" onclick="setMode('siswa')" class="flex items-center justify-center gap-2 px-4 py-3 border-2 rounded-xl font-bold text-sm transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pilih Siswa
                    </button>
                    <button type="button" id="btn-mode-magang" onclick="setMode('magang')" class="flex items-center justify-center gap-2 px-4 py-3 border-2 rounded-xl font-bold text-sm transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Pilih Tempat Magang
                    </button>
                </div>

                <!-- Hidden Input for Form Submission -->
                <input type="hidden" name="penempatan_magang_id" id="hidden_penempatan_magang_id" required>

                <!-- Wrapper Siswa -->
                <div id="wrapper-siswa" class="space-y-3">
                    <!-- Search Field -->
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="search_siswa" oninput="filterSiswa()" placeholder="Cari nama siswa..." class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Dropdown Siswa -->
                    <div class="relative">
                        <select id="select_siswa" onchange="updateHiddenValue()" class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm outline-none text-gray-700 font-semibold cursor-pointer">
                            <option value="all" style="font-weight: bold; color: #2563eb;">-- PILIH SEMUA SISWA --</option>
                            @foreach($placements as $place)
                                <option value="{{ $place->id }}">
                                    {{ $place->siswa->nama }} ({{ $place->siswa->kelas->nama_kelas ?? '-' }} / {{ $place->siswa->jurusan->kode_jurusan ?? '' }}) - {{ $place->industri->nama_industri }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-550">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Wrapper Magang -->
                <div id="wrapper-magang" class="space-y-3 hidden">
                    <div class="relative">
                        <select id="select_magang" onchange="updateHiddenValue()" class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm outline-none text-gray-700 font-semibold cursor-pointer">
                            <option value="all" style="font-weight: bold; color: #2563eb;">-- PILIH SEMUA PENEMPATAN --</option>
                            @foreach($uniqueIndustries as $place)
                                <option value="industry_{{ $place->industri_id }}_{{ $place->periode_magang_id }}">
                                    {{ $place->industri->nama_industri }} ({{ $place->periodeMagang->nama_periode ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-550">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor Surat -->
                <div>
                    <label for="nomor_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nomor Surat Dispensasi</label>
                    <input type="text" name="nomor_surat" id="nomor_surat" value="421.5 / 377.02.027 / 101.6.21.23 / {{ date('Y') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label for="tanggal_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
            </div>

            <!-- DYNAMIC FORM FIELDS -->
            <!-- Tipe 1: Kegiatan / Lomba Fields -->
            <div id="fields-kegiatan" class="space-y-4">
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Detail Kegiatan / Lomba</h3>
                </div>
                <div>
                    <label for="nama_kegiatan" class="block text-xs font-semibold text-gray-600 mb-1">Nama Kegiatan / Lomba</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="REKTOR CUP UNIGORO KE V" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tempat_kegiatan" class="block text-xs font-semibold text-gray-600 mb-1">Tempat Kegiatan</label>
                        <input type="text" name="tempat_kegiatan" id="tempat_kegiatan" value="Bojonegoro" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <div>
                        <label for="tanggal_kegiatan" class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Kegiatan</label>
                        <input type="text" name="tanggal_kegiatan" id="tanggal_kegiatan" value="13, 15 April 2026 dan 20 April s/d 26 April 2026" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                </div>
            </div>

            <!-- Tipe 2: SAS / Ujian Fields -->
            <div id="fields-sas" class="space-y-4 hidden">
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Detail Ujian / SAS</h3>
                </div>
                <div>
                    <label for="nama_kegiatan_sas" class="block text-xs font-semibold text-gray-600 mb-1">Nama Kegiatan Ujian / KBM</label>
                    <input type="text" name="nama_kegiatan_sas" id="nama_kegiatan_sas" value="Sumatif Akhir Semester Genap TP. 2025/2026" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="tanggal_izin_sas" class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Izin</label>
                        <input type="text" name="tanggal_izin_sas" id="tanggal_izin_sas" value="2 – 9 Juni 2026" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <div>
                        <label for="kelas_sas" class="block text-xs font-semibold text-gray-600 mb-1">Kelas yang Diizinkan</label>
                        <input type="text" name="kelas_sas" id="kelas_sas" value="KELAS XI" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <div>
                        <label for="tanggal_penjemputan_sas" class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Penjemputan</label>
                        <input type="text" name="tanggal_penjemputan_sas" id="tanggal_penjemputan_sas" value="28 Oktober 2025" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                </div>
            </div>

            <!-- Signature Officials Section -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Informasi Pejabat Penandatangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Nama Pejabat -->
                    <div>
                        <label for="nama_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">Nama Pejabat</label>
                        <input type="text" name="nama_pejabat" id="nama_pejabat" value="SHOLAHUDDIN, ST., M.Si" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <!-- Jabatan Pejabat -->
                    <div>
                        <label for="jabatan_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">Jabatan Pejabat</label>
                        <input type="text" name="jabatan_pejabat" id="jabatan_pejabat" value="Kepala SMK Negeri 3 Tuban" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pangkat / Golongan -->
                    <div>
                        <label for="pangkat_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">Pangkat/Golongan</label>
                        <input type="text" name="pangkat_pejabat" id="pangkat_pejabat" value="Pembina" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <!-- NIP Pejabat -->
                    <div>
                        <label for="nip_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">NIP Pejabat</label>
                        <input type="text" name="nip_pejabat" id="nip_pejabat" value="19741014 200902 1 001" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTemplate(type) {
        const fieldsKegiatan = document.getElementById('fields-kegiatan');
        const fieldsSas = document.getElementById('fields-sas');
        const labelKegiatan = document.getElementById('label-kegiatan');
        const labelSas = document.getElementById('label-sas');
        const dotKegiatan = document.getElementById('radio-dot-kegiatan');
        const dotSas = document.getElementById('radio-dot-sas');
        const indKegiatan = document.getElementById('radio-indicator-kegiatan');
        const indSas = document.getElementById('radio-indicator-sas');
        
        if (type === 'kegiatan') {
            fieldsKegiatan.classList.remove('hidden');
            fieldsSas.classList.add('hidden');
            
            labelKegiatan.classList.add('border-blue-500', 'bg-blue-50/20');
            labelKegiatan.classList.remove('border-gray-200');
            labelSas.classList.remove('border-blue-500', 'bg-blue-50/20');
            labelSas.classList.add('border-gray-200');
            
            dotKegiatan.classList.remove('hidden');
            dotSas.classList.add('hidden');
            
            indKegiatan.classList.add('border-blue-500');
            indSas.classList.remove('border-blue-500');
            
            // Set required fields for validation
            document.getElementById('nama_kegiatan').setAttribute('required', 'required');
            document.getElementById('tempat_kegiatan').setAttribute('required', 'required');
            document.getElementById('tanggal_kegiatan').setAttribute('required', 'required');
            
            document.getElementById('nama_kegiatan_sas').removeAttribute('required');
            document.getElementById('tanggal_izin_sas').removeAttribute('required');
            document.getElementById('kelas_sas').removeAttribute('required');
            document.getElementById('tanggal_penjemputan_sas').removeAttribute('required');
        } else {
            fieldsKegiatan.classList.add('hidden');
            fieldsSas.classList.remove('hidden');
            
            labelSas.classList.add('border-blue-500', 'bg-blue-50/20');
            labelSas.classList.remove('border-gray-200');
            labelKegiatan.classList.remove('border-blue-500', 'bg-blue-50/20');
            labelKegiatan.classList.add('border-gray-200');
            
            dotSas.classList.remove('hidden');
            dotKegiatan.classList.add('hidden');
            
            indSas.classList.add('border-blue-500');
            indKegiatan.classList.remove('border-blue-500');
            
            // Set required fields for validation
            document.getElementById('nama_kegiatan_sas').setAttribute('required', 'required');
            document.getElementById('tanggal_izin_sas').setAttribute('required', 'required');
            document.getElementById('kelas_sas').setAttribute('required', 'required');
            document.getElementById('tanggal_penjemputan_sas').setAttribute('required', 'required');
            
            document.getElementById('nama_kegiatan').removeAttribute('required');
            document.getElementById('tempat_kegiatan').removeAttribute('required');
            document.getElementById('tanggal_kegiatan').removeAttribute('required');
        }
    }

    let originalSiswaOptions = [];
    let currentMode = 'siswa';

    function setMode(mode) {
        currentMode = mode;
        const btnSiswa = document.getElementById('btn-mode-siswa');
        const btnMagang = document.getElementById('btn-mode-magang');
        const wrapperSiswa = document.getElementById('wrapper-siswa');
        const wrapperMagang = document.getElementById('wrapper-magang');
        
        if (mode === 'siswa') {
            btnSiswa.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-blue-500 bg-blue-50 text-blue-600 rounded-xl font-bold text-sm transition-all shadow-sm";
            btnMagang.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-200 bg-gray-50 text-gray-550 hover:bg-gray-100 rounded-xl font-bold text-sm transition-all shadow-sm";
            
            wrapperSiswa.classList.remove('hidden');
            wrapperMagang.classList.add('hidden');
        } else {
            btnMagang.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-blue-500 bg-blue-50 text-blue-600 rounded-xl font-bold text-sm transition-all shadow-sm";
            btnSiswa.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-200 bg-gray-50 text-gray-550 hover:bg-gray-100 rounded-xl font-bold text-sm transition-all shadow-sm";
            
            wrapperMagang.classList.remove('hidden');
            wrapperSiswa.classList.add('hidden');
        }
        
        updateHiddenValue();
    }

    function updateHiddenValue() {
        const hiddenInput = document.getElementById('hidden_penempatan_magang_id');
        if (currentMode === 'siswa') {
            hiddenInput.value = document.getElementById('select_siswa').value;
        } else {
            hiddenInput.value = document.getElementById('select_magang').value;
        }
    }

    function filterSiswa() {
        const query = document.getElementById('search_siswa').value.toLowerCase().trim();
        const selectSiswa = document.getElementById('select_siswa');
        const currentValue = selectSiswa.value;
        
        selectSiswa.innerHTML = '';
        
        originalSiswaOptions.forEach(opt => {
            if (opt.value === 'all' || opt.text.toLowerCase().includes(query)) {
                const newOpt = document.createElement('option');
                newOpt.value = opt.value;
                newOpt.text = opt.text;
                if (opt.style) {
                    newOpt.setAttribute('style', opt.style);
                }
                selectSiswa.appendChild(newOpt);
            }
        });
        
        const optionExists = Array.from(selectSiswa.options).some(opt => opt.value === currentValue);
        if (optionExists) {
            selectSiswa.value = currentValue;
        } else {
            selectSiswa.value = 'all';
        }
        
        updateHiddenValue();
    }

    // Initialize layout on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Cache original siswa options
        const selectSiswa = document.getElementById('select_siswa');
        originalSiswaOptions = Array.from(selectSiswa.options).map(opt => ({
            value: opt.value,
            text: opt.text,
            style: opt.getAttribute('style') || ''
        }));
        
        setMode('siswa');

        // Find which one is checked
        const checkedRadio = document.querySelector('input[name="tipe_surat"]:checked');
        if (checkedRadio) {
            switchTemplate(checkedRadio.value);
        } else {
            switchTemplate('kegiatan');
        }
    });
</script>
@endsection
