@extends('layouts.admin')

@section('title', 'Cetak Surat')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <!-- Breadcrumbs / Top Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Cetak Surat</h1>
        <p class="text-gray-500 mt-2 text-sm">Pilih dan cetak surat administrasi Prakerin menggunakan template resmi sekolah.</p>
    </div>

    <!-- Empty State Content Card -->
    <div class="bg-white rounded-3xl border border-gray-150 shadow-[0_10px_30px_rgba(0,0,0,0.02)] p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
        <!-- Premium Animated Printer Icon -->
        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mb-6 border border-blue-100/60 shadow-inner">
            <svg class="w-12 h-12 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.25 2.25H8.59A2.25 2.25 0 016.34 18m11.32-9.742a2.25 2.25 0 00-2.25-2.25H8.59a2.25 2.25 0 00-2.25 2.25M6.34 18c-1.22 0-2.22-.917-2.25-2.136V11.93c0-1.22.98-2.22 2.25-2.25M17.66 18c1.22 0 2.22-.917 2.25-2.136V11.93c0-1.22-.98-2.22-2.25-2.25" />
            </svg>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-2">Cetak Dokumen Baru</h3>
        <p class="text-gray-500 text-sm max-w-md mb-8 leading-relaxed">
            Belum ada template surat yang dipilih. Klik tombol di bawah ini untuk memilih jenis surat yang ingin dicetak dari template yang tersedia.
        </p>

        <!-- Dropdown Button Container -->
        <div class="relative inline-block text-left" id="dropdownContainer">
            <button onclick="toggleTemplateDropdown()" type="button" class="inline-flex items-center gap-2.5 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-blue-500/20 active:scale-95 transition-all duration-200" id="menu-button" aria-expanded="false" aria-haspopup="true">
                <span>Pilih Template Surat</span>
                <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="templateDropdown" class="hidden absolute left-1/2 -translate-x-1/2 mt-2 w-72 origin-top bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in-up">
                <div class="py-1.5" role="none">
                    <a href="{{ route('admin.cetak.pengantar') }}" class="group flex items-center px-4 py-3 text-xs text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 transition" role="menuitem">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center text-blue-600 mr-3 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-800 group-hover:text-blue-600 text-sm">Surat Pengantar</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Surat permohonan PKL ke Industri</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.cetak.tugas') }}" class="group flex items-center px-4 py-3 text-xs text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 transition" role="menuitem">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center text-purple-600 mr-3 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-800 group-hover:text-blue-600 text-sm">Surat Tugas</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Surat tugas resmi untuk siswa magang</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.cetak.dispensasi') }}" class="group flex items-center px-4 py-3 text-xs text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 transition" role="menuitem">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center text-amber-600 mr-3 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-800 group-hover:text-blue-600 text-sm">Surat Dispensasi</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Izin tidak mengikuti pelajaran</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.cetak.sppd') }}" class="group flex items-center px-4 py-3 text-xs text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 transition" role="menuitem">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 mr-3 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-800 group-hover:text-blue-600 text-sm">Surat SPPD</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Surat Perintah Perjalanan Dinas</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTemplateDropdown() {
        const dropdown = document.getElementById('templateDropdown');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Close dropdown on click outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('dropdownContainer');
        const dropdown = document.getElementById('templateDropdown');
        if (container && dropdown && !container.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endsection