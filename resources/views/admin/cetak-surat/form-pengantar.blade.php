@extends('layouts.admin')

@section('title', 'Cetak Surat Pengantar')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.data.surat') }}" class="p-2.5 bg-white border border-gray-150 rounded-xl hover:bg-gray-50 text-gray-500 hover:text-gray-700 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Cetak Surat Pengantar</h1>
            <p class="text-gray-500 text-xs mt-1">Isi formulir di bawah ini untuk menghasilkan Surat Pengantar PDF.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-150 shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-8">
        <form action="{{ route('admin.cetak.pengantar.pdf') }}" method="POST" target="_blank" class="space-y-6">
            @csrf

            <!-- PILIH SISWA ATAU JURUSAN / KELAS -->
            @php
                $jurusans = $placements->map(fn($p) => $p->siswa->jurusan)->filter()->unique('id')->sortBy('nama_jurusan');
                $kelas = $placements->map(fn($p) => $p->siswa->kelas)->filter()->unique('id')->sortBy('nama_kelas');
            @endphp

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih</label>
                
                <!-- Toggle Mode Buttons -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <button type="button" id="btn-mode-siswa" onclick="setMode('siswa')" class="flex items-center justify-center gap-2 px-4 py-3 border-2 rounded-xl font-bold text-sm transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pilih Siswa
                    </button>
                    <button type="button" id="btn-mode-jurusan-kelas" onclick="setMode('jurusan-kelas')" class="flex items-center justify-center gap-2 px-4 py-3 border-2 rounded-xl font-bold text-sm transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Pilih Jurusan / Kelas
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

                <!-- Wrapper Jurusan / Kelas -->
                <div id="wrapper-jurusan-kelas" class="space-y-3 hidden">
                    <div class="relative">
                        <select id="select_jurusan_kelas" onchange="updateHiddenValue()" class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm outline-none text-gray-700 font-semibold cursor-pointer">
                            <option value="all" style="font-weight: bold; color: #2563eb;">-- PILIH SEMUA JURUSAN & KELAS --</option>
                            <optgroup label="Berdasarkan Jurusan">
                                @foreach($jurusans as $j)
                                    <option value="jurusan_{{ $j->id }}">
                                        Jurusan: {{ $j->nama_jurusan }} ({{ $j->kode_jurusan ?? '' }})
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Berdasarkan Kelas">
                                @foreach($kelas as $k)
                                    <option value="kelas_{{ $k->id }}">
                                        Kelas: {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-550">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nomor & Tanggal Surat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor Surat -->
                <div>
                    <label for="nomor_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nomor Surat</label>
                    <input type="text" name="nomor_surat" id="nomor_surat" value="421.3/SMK.3/{{ rand(100, 999) }}/{{ date('m') }}/{{ date('Y') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label for="tanggal_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
            </div>

            <!-- Signature Officials Section -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Informasi Kepala Sekolah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Nama Pejabat -->
                    <div>
                        <label for="nama_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_pejabat" id="nama_pejabat" value="SHOLAHUDDIN, ST., M.SI" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <!-- Jabatan Pejabat -->
                    <div>
                        <label for="jabatan_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">Jabatan</label>
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
                        <label for="nip_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">NIP Kepala Sekolah</label>
                        <input type="text" name="nip_pejabat" id="nip_pejabat" value="19680101 199003 1 001" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
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
    let originalSiswaOptions = [];
    let currentMode = 'siswa';

    function setMode(mode) {
        currentMode = mode;
        const btnSiswa = document.getElementById('btn-mode-siswa');
        const btnJurusanKelas = document.getElementById('btn-mode-jurusan-kelas');
        const wrapperSiswa = document.getElementById('wrapper-siswa');
        const wrapperJurusanKelas = document.getElementById('wrapper-jurusan-kelas');
        
        if (mode === 'siswa') {
            btnSiswa.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-blue-500 bg-blue-50 text-blue-600 rounded-xl font-bold text-sm transition-all shadow-sm";
            btnJurusanKelas.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-200 bg-gray-50 text-gray-550 hover:bg-gray-100 rounded-xl font-bold text-sm transition-all shadow-sm";
            
            wrapperSiswa.classList.remove('hidden');
            wrapperJurusanKelas.classList.add('hidden');
        } else {
            btnJurusanKelas.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-blue-500 bg-blue-50 text-blue-600 rounded-xl font-bold text-sm transition-all shadow-sm";
            btnSiswa.className = "flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-200 bg-gray-50 text-gray-550 hover:bg-gray-100 rounded-xl font-bold text-sm transition-all shadow-sm";
            
            wrapperJurusanKelas.classList.remove('hidden');
            wrapperSiswa.classList.add('hidden');
        }
        
        updateHiddenValue();
    }

    function updateHiddenValue() {
        const hiddenInput = document.getElementById('hidden_penempatan_magang_id');
        if (currentMode === 'siswa') {
            hiddenInput.value = document.getElementById('select_siswa').value;
        } else {
            hiddenInput.value = document.getElementById('select_jurusan_kelas').value;
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
    });
</script>
@endsection
