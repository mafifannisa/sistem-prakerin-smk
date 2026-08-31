@extends('layouts.admin')

@section('title', 'Data Guru')

@section('header_breadcrumb', 'Data Guru')
@section('header_title', 'DATA GURU')

@section('content')
<div class="p-0">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-green-800 font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-red-800 font-bold">Terjadi Kesalahan Validasi:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <form action="{{ route('admin.data-guru') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau NIP..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.data-guru') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
            
            <div class="flex items-center gap-2.5">
                <button onclick="openImportModal()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Import Excel
                </button>
                <button onclick="openModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Guru
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama / NIP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Username / Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No. Telp</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan / Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jabatan / Role</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gurus as $index => $guru)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $gurus->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 text-sm">{{ $guru->nama }}</p>
                                <p class="text-xs text-gray-500">NIP: {{ $guru->nip ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">{{ $guru->user->username ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $guru->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $guru->no_telp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <p>{{ $guru->jurusan->nama_jurusan ?? 'Semua Jurusan / Umum' }}</p>
                                @if($guru->kelas)
                                    <p class="text-xs text-gray-400 mt-1 font-semibold">Kelas: {{ $guru->kelas->nama_kelas }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($guru->jabatan == 'kepala_jurusan') bg-purple-100 text-purple-700
                                    @elseif($guru->jabatan == 'guru_pembimbing') bg-blue-100 text-blue-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ ucwords(str_replace('_', ' ', $guru->jabatan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editGuru(
                                        {{ $guru->id }}, 
                                        '{{ $guru->nama }}', 
                                        '{{ $guru->nip }}', 
                                        '{{ $guru->user->username ?? '' }}', 
                                        '{{ $guru->user->email ?? '' }}', 
                                        '{{ $guru->no_telp }}', 
                                        '{{ $guru->jurusan_id }}', 
                                        '{{ $guru->kelas_id }}', 
                                        '{{ $guru->jabatan }}'
                                    )" 
                                    class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteGuru({{ $guru->id }}, '{{ $guru->nama }}')" 
                                            class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data guru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($gurus->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Import Excel Data Guru (Centered & Elevated UI/UX) -->
<div id="importModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div id="importModalContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100 flex flex-col my-auto">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-green-50/70 via-emerald-50/40 to-white flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-green-600 flex items-center justify-center text-white shadow-md shadow-green-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-800 tracking-tight">Import Data Guru</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Upload spreadsheet (.xlsx / .xls / .csv)</p>
                </div>
            </div>
            <button type="button" onclick="closeImportModal()" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition border border-gray-200/60 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.data-guru.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 m-0">
            @csrf
            <!-- Download Template Banner -->
            <div class="flex items-center justify-between bg-emerald-50/70 p-3.5 rounded-2xl border border-emerald-200/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Template Format Excel</p>
                        <p class="text-[10px] text-gray-500 font-medium">Unduh format resmi dengan contoh data</p>
                    </div>
                </div>
                <a href="{{ route('admin.data-guru.template') }}" class="px-3 py-1.5 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200 shadow-sm transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download
                </a>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Upload File Excel / CSV yang Sudah Diisi <span class="text-red-500">*</span></label>
                <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none text-sm file:mr-4 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 cursor-pointer">
                
                <div class="mt-3.5 bg-gray-50/80 p-4 rounded-2xl border border-gray-200/80 space-y-2 text-[11px] text-gray-600 leading-relaxed">
                    <p class="font-bold text-gray-800 flex items-center gap-1.5">
                        <span>📋</span> Kolom Header Excel:
                    </p>
                    <div class="font-mono text-[10px] bg-white p-2.5 rounded-xl border border-gray-200 text-gray-700 font-semibold break-words">
                        nama, nip, jabatan, username, email, password, no_telp, jurusan, kelas
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-gray-500 pt-0.5">
                        <li><span class="font-bold text-gray-700">Pilihan Jabatan:</span> <code>guru_pembimbing</code>, <code>kepala_jurusan</code>, <code>guru_penguji</code></li>
                        <li><span class="font-bold text-gray-700">Password default:</span> NIP (atau <code>guru123</code>)</li>
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white border border-gray-250 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition shadow-sm">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-md shadow-green-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Mulai Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Form Guru (Centered & Elevated UI/UX) -->
<div id="guruModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div id="guruModalContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100 flex flex-col max-h-[92vh] my-auto">
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 id="modalTitle" class="text-lg font-black text-gray-800 tracking-tight">Tambah Data Guru</h3>
                        <span id="modalModeBadge" class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-blue-100 text-blue-700 tracking-wider">Baru</span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Kelola akun, hak akses, dan penugasan guru</p>
                </div>
            </div>
            <button type="button" onclick="closeModal()" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition border border-gray-200/60 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Modal Form Body (Scrollable) -->
        <form id="guruForm" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            <input type="hidden" id="guruMethod" name="_method" value="POST">
            
            <div class="p-6 overflow-y-auto space-y-5 flex-1 text-sm">
                
                <!-- Section 1: Identitas Guru -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2 pb-1 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">👤 Data Diri & Kontak</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" required placeholder="Contoh: Drs. Budi Santoso, M.Pd"
                                   class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-semibold transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                            <input type="text" name="nip" id="nip" placeholder="Opsional / Misal: 1980..."
                                   class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">No. Telp / WhatsApp</label>
                            <input type="text" name="no_telp" id="no_telp" placeholder="Contoh: 08123456789"
                                   class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Kredensial Login -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-2 pb-1 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">🔐 Akun Login</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" id="username" required placeholder="Username unik"
                                   class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-semibold transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email Aktif <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required placeholder="email@smk3tuban.sch.id"
                                   class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-semibold transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                   class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                            <p id="passwordHelp" class="text-[11px] text-amber-600 font-semibold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kosongkan password jika tidak ingin mengubah password saat Edit.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Jabatan & Penugasan -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-2 pb-1 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">🎓 Jabatan & Penugasan</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jabatan / Role <span class="text-red-500">*</span></label>
                            <select name="jabatan" id="jabatan" required class="w-full px-3 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs font-bold text-gray-800 transition">
                                <option value="guru_pembimbing">Guru Pembimbing</option>
                                <option value="kepala_jurusan">Kepala Jurusan</option>
                                <option value="guru_penguji">Guru Penguji</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jurusan</label>
                            <select name="jurusan_id" id="jurusan_id" class="w-full px-3 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs font-bold text-gray-800 transition">
                                <option value="">Semua / Tidak Ada</option>
                                @foreach($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kelas Bimbingan</label>
                            <select name="kelas_id" id="kelas_id" class="w-full px-3 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs font-bold text-gray-800 transition">
                                <option value="">Tidak Ada</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-white border border-gray-250 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data Guru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('guruModal');
    const content = document.getElementById('guruModalContent');
    
    document.getElementById('modalTitle').textContent = 'Tambah Guru Baru';
    document.getElementById('modalModeBadge').textContent = 'Baru';
    document.getElementById('modalModeBadge').className = 'px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-blue-100 text-blue-700 tracking-wider';
    document.getElementById('guruForm').reset();
    document.getElementById('password').required = true;
    document.getElementById('passwordHelp').classList.add('hidden');
    document.getElementById('guruMethod').value = 'POST';
    document.getElementById('guruForm').action = "{{ route('admin.data-guru.store') }}";
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function editGuru(id, nama, nip, username, email, noTelp, jurusanId, kelasId, jabatan) {
    const modal = document.getElementById('guruModal');
    const content = document.getElementById('guruModalContent');
    
    document.getElementById('modalTitle').textContent = 'Edit Data Guru';
    document.getElementById('modalModeBadge').textContent = 'Mode Edit';
    document.getElementById('modalModeBadge').className = 'px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-amber-100 text-amber-700 tracking-wider';
    
    document.getElementById('nama').value = nama;
    document.getElementById('nip').value = nip || '';
    document.getElementById('username').value = username || '';
    document.getElementById('email').value = email || '';
    document.getElementById('no_telp').value = noTelp || '';
    document.getElementById('jurusan_id').value = jurusanId || '';
    document.getElementById('kelas_id').value = kelasId || '';
    document.getElementById('jabatan').value = jabatan;
    
    document.getElementById('password').value = '';
    document.getElementById('password').required = false;
    document.getElementById('passwordHelp').classList.remove('hidden');
    
    document.getElementById('guruMethod').value = 'PUT';
    document.getElementById('guruForm').action = "{{ route('admin.data-guru.update', ':id') }}".replace(':id', id);
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('guruModal');
    const content = document.getElementById('guruModalContent');
    if (!modal || modal.classList.contains('hidden')) return;
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 200);
}

function openImportModal() {
    const modal = document.getElementById('importModal');
    const content = document.getElementById('importModalContent');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    const content = document.getElementById('importModalContent');
    if (!modal || modal.classList.contains('hidden')) return;
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 200);
}

function deleteGuru(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus data guru "${nama}" beserta akun loginnya?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.data-guru.delete', ':id') }}".replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('guruModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) closeImportModal();
});
</script>
@endsection
