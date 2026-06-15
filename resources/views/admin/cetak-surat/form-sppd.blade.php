@extends('layouts.admin')

@section('title', 'Cetak SPPD')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.data.surat') }}" class="p-2.5 bg-white border border-gray-150 rounded-xl hover:bg-gray-50 text-gray-500 hover:text-gray-700 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Cetak SPPD</h1>
            <p class="text-gray-500 text-xs mt-1">Surat Perintah Perjalanan Dinas untuk Guru Pembimbing.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-150 shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-8">
        <form action="{{ route('admin.cetak.sppd.pdf') }}" method="POST" target="_blank" class="space-y-6">
            @csrf

            <!-- Pilih Guru Pembimbing -->
            <div>
                <label for="guru_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Guru Pembimbing</label>
                <div class="relative">
                    <select name="guru_id" id="guru_id" required class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm outline-none text-gray-700 font-semibold cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}">
                                {{ $guru->nama }} (NIP. {{ $guru->nip ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-505">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Pilih Siswa / Penempatan (untuk Auto-fill Tujuan) -->
            <div>
                <label for="penempatan_magang_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Siswa / Lokasi PKL (Auto-Fill)</label>
                <div class="relative">
                    <select name="penempatan_magang_id" id="penempatan_magang_id" onchange="autoFillSppd()" required class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm outline-none text-gray-700 font-semibold cursor-pointer">
                        <option value="">-- Pilih Penempatan --</option>
                        @foreach($placements as $place)
                            <option value="{{ $place->id }}">
                                Siswa: {{ $place->siswa->nama }} - Industri: {{ $place->industri->nama_industri }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-505">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor SPPD -->
                <div>
                    <label for="nomor_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nomor SPPD</label>
                    <input type="text" name="nomor_surat" id="nomor_surat" value="094/SMK.3/{{ rand(100, 999) }}/{{ date('Y') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label for="tanggal_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
            </div>

            <!-- Tempat Berangkat & Tempat Tujuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tempat_berangkat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tempat Berangkat</label>
                    <input type="text" name="tempat_berangkat" id="tempat_berangkat" value="SMK Negeri 3 Tuban" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
                <div>
                    <label for="tempat_tujuan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tempat Tujuan</label>
                    <input type="text" name="tempat_tujuan" id="tempat_tujuan" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none" placeholder="Isi tempat tujuan / nama industri">
                </div>
            </div>

            <!-- Tanggal Perjalanan & Tanggal Kembali -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Berangkat</label>
                    <input type="date" name="tanggal_perjalanan" id="tanggal_perjalanan" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
                <div>
                    <label for="tanggal_kembali" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
            </div>

            <!-- Alat Angkutan & Pembebanan Anggaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="alat_angkutan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alat Angkutan</label>
                    <input type="text" name="alat_angkutan" id="alat_angkutan" value="Kendaraan Roda 2 / Roda 4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
                <div>
                    <label for="pembebanan_anggaran" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pembebanan Anggaran</label>
                    <input type="text" name="pembebanan_anggaran" id="pembebanan_anggaran" value="BOS / Komite Sekolah" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                </div>
            </div>

            <!-- Maksud Perjalanan Dinas -->
            <div>
                <label for="maksud_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Maksud Perjalanan Dinas</label>
                <input type="text" name="maksud_perjalanan" id="maksud_perjalanan" value="Monitoring Siswa Prakerin" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Informasi Kepala Sekolah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Kepala Sekolah -->
                    <div>
                        <label for="nama_pejabat" class="block text-xs font-semibold text-gray-500 mb-1">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_pejabat" id="nama_pejabat" value="SHOLAHUDDIN, ST., M.SI" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-gray-800 outline-none">
                    </div>
                    <!-- NIP Kepala Sekolah -->
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
    const placements = @json($placements);
    function autoFillSppd() {
        const selectedId = document.getElementById('penempatan_magang_id').value;
        const placement = placements.find(p => p.id == selectedId);
        if (placement) {
            document.getElementById('tempat_tujuan').value = placement.industri.nama_industri;
            document.getElementById('maksud_perjalanan').value = 'Monitoring Siswa Prakerin atas nama ' + placement.siswa.nama;
        }
    }
</script>
@endsection
