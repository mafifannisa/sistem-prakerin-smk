@extends('layouts.admin')

@section('title', 'Data Industri')

@section('header_breadcrumb', 'Data Industri')
@section('header_title', 'DATA INDUSTRI')

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

    <!-- Error Validation -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-semibold">Validasi Gagal!</h3>
                    <ul class="text-red-700 text-sm mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <!-- Search & Filter -->
            <form action="{{ route('admin.data-industri') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama industri..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <input type="text" name="kota" value="{{ request('kota') }}" 
                       placeholder="Filter kota..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.data-industri') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
            
            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="openModal()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Industri
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">NIB</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Industri</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Alamat</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kontak & HR</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kapasitas</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($industris as $index => $industri)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $industris->firstItem() + $index }}</td>
                            
                            <!-- ✅ TAMPILKAN NIB DI KOLOM KODE -->
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @if($industri->nib)
                                    <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded" title="{{ $industri->nib }}">
                                        {{ \Str::limit($industri->nib, 15) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Kolom Nama Industri -->
                            <td class="px-4 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $industri->nama_industri }}</p>
                                    @if($industri->kategori)
                                        <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ $industri->kategori }}</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600 max-w-xs">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $industri->alamat }}</span>
                                    @if($industri->kelurahan)
                                        <span class="text-xs text-gray-500">Kel. {{ $industri->kelurahan }}</span>
                                    @endif
                                    @if($industri->kecamatan)
                                        <span class="text-xs text-gray-500">Kec. {{ $industri->kecamatan }}</span>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ $industri->kota }}, {{ $industri->provinsi }} {{ $industri->kode_pos }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium">{{ $industri->no_telp }}</span>
                                    @if($industri->email)
                                        <span class="text-xs text-gray-400">{{ $industri->email }}</span>
                                    @endif
                                    @if($industri->nama_hr)
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <span class="text-xs font-semibold text-gray-700">HR: {{ $industri->nama_hr }}</span>
                                            @if($industri->no_wa_hr)
                                                <span class="text-xs text-gray-500 block">{{ $industri->no_wa_hr }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($industri->pembimbing_magang)
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <span class="text-xs font-semibold text-gray-700">Pembimbing: {{ $industri->pembimbing_magang }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-center">
                                @if($industri->kapasitas_magang)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold">{{ $industri->kapasitas_magang }} Siswa</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- ✅ PERBAIKAN: Parameter dikirim sesuai urutan database -->
                                    <button onclick="editIndustri(
                                        {{ $industri->id }}, 
                                        '{{ $industri->nib ?? '' }}', 
                                        '{{ addslashes($industri->nama_industri) }}', 
                                        '{{ addslashes($industri->alamat) }}', 
                                        '{{ addslashes($industri->kelurahan ?? '') }}',
                                        '{{ addslashes($industri->kecamatan ?? '') }}',
                                        '{{ addslashes($industri->kota ?? '') }}', 
                                        '{{ addslashes($industri->provinsi ?? '') }}',
                                        '{{ $industri->kode_pos ?? '' }}',
                                        '{{ $industri->no_telp }}', 
                                        '{{ $industri->email ?? '' }}', 
                                        '{{ $industri->website ?? '' }}', 
                                        '{{ addslashes($industri->nama_hr ?? '') }}',
                                        '{{ $industri->no_wa_hr ?? '' }}',
                                        '{{ addslashes($industri->pembimbing_magang ?? '') }}',
                                        '{{ $industri->kategori ?? '' }}',
                                        {{ $industri->kapasitas_magang ?? 0 }},
                                        '{{ $industri->latitude ?? '' }}',
                                        '{{ $industri->longitude ?? '' }}',
                                        '{{ $industri->radius_toleransi_meter ?? 300 }}',
                                        '{{ $industri->jam_masuk ? substr($industri->jam_masuk, 0, 5) : '08:00' }}',
                                        '{{ $industri->jam_pulang ? substr($industri->jam_pulang, 0, 5) : '16:00' }}'
                                    )" 
                                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteIndustri({{ $industri->id }}, '{{ $industri->nama_industri }}')" 
                                            class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-lg font-semibold text-gray-600">Belum ada data industri</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($industris->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $industris->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah/Edit Industri (Centered & Elevated UI/UX) -->
<div id="industriModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div id="industriModalContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100 flex flex-col max-h-[92vh] my-auto">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 id="modalTitle" class="text-lg font-black text-gray-800 tracking-tight">Tambah Industri Baru</h3>
                        <span id="modalModeBadge" class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-blue-100 text-blue-700 tracking-wider">Baru</span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Kelola data mitra DU/DI, lokasi GPS absensi, dan kontak HR</p>
                </div>
            </div>
            <button type="button" onclick="closeModal()" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition border border-gray-200/60 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
             <!-- Modal Form Body (Scrollable with proper padding) -->
        <form id="industriForm" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            <input type="hidden" id="industriId" name="_method" value="POST">
            
            <div class="p-6 md:p-8 overflow-y-auto space-y-6 flex-1 text-sm">
                <!-- 3-Column Card Layout -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kolom 1: Informasi Dasar -->
                    <div class="space-y-3.5 bg-gray-50/60 p-5 rounded-2xl border border-gray-200/70">
                        <h4 class="font-black text-gray-800 text-xs uppercase tracking-wider pb-2 border-b border-gray-200 flex items-center gap-2">
                            <span>🏢</span> Informasi Dasar
                        </h4>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">NIB (Nomor Induk Berusaha) <span class="text-red-500">*</span></label>
                            <input type="text" name="nib" id="nib" required placeholder="123456789..."
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-semibold transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Industri <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_industri" id="nama_industri" required placeholder="PT. Suka Makmur"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-bold text-gray-800 transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Bidang</label>
                            <input type="text" name="kategori" id="kategori" placeholder="Contoh: Manufaktur, IT, Jasa"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kapasitas Kuota Magang</label>
                            <input type="number" name="kapasitas_magang" id="kapasitas_magang" placeholder="Jumlah siswa"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>
                    </div>

                    <!-- Kolom 2: Alamat Lengkap -->
                    <div class="space-y-3.5 bg-gray-50/60 p-5 rounded-2xl border border-gray-200/70">
                        <h4 class="font-black text-gray-800 text-xs uppercase tracking-wider pb-2 border-b border-gray-200 flex items-center gap-2">
                            <span>📍</span> Lokasi & Domisili
                        </h4>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Jalan <span class="text-red-500">*</span></label>
                            <textarea name="alamat" id="alamat" rows="2" required placeholder="Jl. Raya Industri No. 1..."
                                      class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kelurahan</label>
                                <input type="text" name="kelurahan" id="kelurahan" placeholder="Karangrejo"
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kecamatan</label>
                                <input type="text" name="kecamatan" id="kecamatan" placeholder="Kerek"
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kota/Kab. <span class="text-red-500">*</span></label>
                                <input type="text" name="kota" id="kota" required placeholder="Tuban"
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                                <input type="text" name="provinsi" id="provinsi" required placeholder="Jawa Timur"
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos" placeholder="62354"
                                   class="w-full px-3.5 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                        </div>
                    </div>

                    <!-- Kolom 3: Kontak & HR -->
                    <div class="space-y-3.5 bg-gray-50/60 p-5 rounded-2xl border border-gray-200/70">
                        <h4 class="font-black text-gray-800 text-xs uppercase tracking-wider pb-2 border-b border-gray-200 flex items-center gap-2">
                            <span>📞</span> Kontak & Personalia
                        </h4>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">No. Telp Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="no_telp" id="no_telp" required placeholder="0356-123456"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email Resmi</label>
                            <input type="email" name="email" id="email" placeholder="hrd@sukamakmur.co.id"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Website</label>
                            <input type="text" name="website" id="website" placeholder="www.sukamakmur.co.id"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>
                        
                        <div class="pt-2 border-t border-gray-200/80 space-y-2.5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama HRD / PIC</label>
                                <input type="text" name="nama_hr" id="nama_hr" placeholder="Bapak Hendra"
                                       class="w-full px-3.5 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">No. WhatsApp HR</label>
                                <input type="text" name="no_wa_hr" id="no_wa_hr" placeholder="081234567901"
                                       class="w-full px-3.5 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Pembimbing DU/DI</label>
                                <input type="text" name="pembimbing_magang" id="pembimbing_magang" placeholder="Pembimbing Industri"
                                       class="w-full px-3.5 py-2 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 4: Titik Koordinat Geofencing & Jam Kerja (Presensi Mobile) -->
                <div class="bg-emerald-50/40 p-5 rounded-2xl border border-emerald-200/60 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Pengaturan Geofencing & Jam Presensi Mobile</h4>
                                <p class="text-[11px] text-gray-500 font-medium">Validasi radius GPS dan jam kehadiran siswa di tempat magang</p>
                            </div>
                        </div>
                        <button type="button" onclick="getBrowserGps()" class="px-3.5 py-2 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200 shadow-sm transition flex items-center gap-1.5">
                            📍 Ambil Lokasi Saat Ini (GPS)
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" placeholder="-6.894520"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-xs font-mono transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" placeholder="112.058340"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-xs font-mono transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Radius Toleransi (Meter)</label>
                            <input type="number" name="radius_toleransi_meter" id="radius_toleransi_meter" placeholder="300"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-xs transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jam Masuk</label>
                            <input type="time" name="jam_masuk" id="jam_masuk" value="08:00"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-xs font-semibold transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jam Pulang</label>
                            <input type="time" name="jam_pulang" id="jam_pulang" value="16:00"
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-250 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-xs font-semibold transition">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal()" 
                        class="px-5 py-2.5 bg-white border border-gray-250 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data Industri
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('industriModal');
    const content = document.getElementById('industriModalContent');
    
    document.getElementById('modalTitle').textContent = 'Tambah Industri Baru';
    const badge = document.getElementById('modalModeBadge');
    if (badge) {
        badge.textContent = 'Baru';
        badge.className = 'px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-blue-100 text-blue-700 tracking-wider';
    }
    
    document.getElementById('industriForm').reset();
    document.getElementById('industriId').value = 'POST';
    document.getElementById('industriForm').action = "{{ route('admin.data-industri') }}";
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function editIndustri(id, nib, nama, alamat, kelurahan, kecamatan, kota, provinsi, kodePos, noTelp, email, website, namaHr, noWaHr, pembimbingMagang, kategori, kapasitasMagang, lat, lng, radius, jamMasuk, jamPulang) {
    const modal = document.getElementById('industriModal');
    const content = document.getElementById('industriModalContent');
    
    document.getElementById('modalTitle').textContent = 'Edit Data Industri';
    const badge = document.getElementById('modalModeBadge');
    if (badge) {
        badge.textContent = 'Mode Edit';
        badge.className = 'px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-amber-100 text-amber-700 tracking-wider';
    }
    
    // Isi semua field
    document.getElementById('nib').value = nib || '';
    document.getElementById('nama_industri').value = nama;
    document.getElementById('alamat').value = alamat;
    document.getElementById('kelurahan').value = kelurahan || '';
    document.getElementById('kecamatan').value = kecamatan || '';
    document.getElementById('kota').value = kota;
    document.getElementById('provinsi').value = provinsi;
    document.getElementById('kode_pos').value = kodePos || '';
    document.getElementById('no_telp').value = noTelp;
    document.getElementById('email').value = email || '';
    document.getElementById('website').value = website || '';
    document.getElementById('nama_hr').value = namaHr || '';
    document.getElementById('no_wa_hr').value = noWaHr || '';
    document.getElementById('pembimbing_magang').value = pembimbingMagang || '';
    document.getElementById('kategori').value = kategori || '';
    document.getElementById('kapasitas_magang').value = kapasitasMagang || '';
    document.getElementById('latitude').value = lat || '';
    document.getElementById('longitude').value = lng || '';
    document.getElementById('radius_toleransi_meter').value = radius || 300;
    document.getElementById('jam_masuk').value = jamMasuk || '08:00';
    document.getElementById('jam_pulang').value = jamPulang || '16:00';
    
    // Set method dan action
    const form = document.getElementById('industriForm');
    const hiddenMethod = document.getElementById('industriId');
    
    hiddenMethod.value = 'PUT';
    form.action = "{{ route('admin.data-industri.update', ':id') }}".replace(':id', id);
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function getBrowserGps() {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung Geolocation.');
        return;
    }
    
    const btn = event.target;
    const origText = btn.innerHTML;
    btn.innerHTML = '⏳ Mengambil Lokasi...';
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
            document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
            btn.innerHTML = '✅ Lokasi Terpasang!';
            setTimeout(() => { btn.innerHTML = origText; btn.disabled = false; }, 2000);
        },
        function(error) {
            alert('Gagal mengambil lokasi GPS: ' + error.message);
            btn.innerHTML = origText;
            btn.disabled = false;
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function closeModal() {
    const modal = document.getElementById('industriModal');
    const content = document.getElementById('industriModalContent');
    if (!modal || modal.classList.contains('hidden')) return;
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 200);
}

function deleteIndustri(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus industri "${nama}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.data-industri.delete', ':id') }}".replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('industriModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Debug form submit
document.getElementById('industriForm').addEventListener('submit', function(e) {
    console.log('Form submitting...');
    console.log('Action:', this.action);
    console.log('Method:', document.getElementById('industriId').value);
});
</script>
@endsection