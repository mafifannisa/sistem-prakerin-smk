@extends('layouts.siswa')
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

@section('title', 'Cek Status Magang')

@section('content')
<!-- Top Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cek Status Magang</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau progress pengajuan dan pilih tempat magang Anda</p>
        </div>
        <div class="text-sm text-gray-600">
            {{ tanggal_indonesia() }}
        </div>
    </div>
</header>

<!-- Main Content -->
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

    <!-- Info Card: Status Magang -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800 mb-2">Status Magang Anda</h3>
                
                @if(!$penempatan)
                    <p class="text-sm text-gray-600">
                        Anda belum mengajukan tempat magang. Silakan pilih opsi di bawah untuk mengajukan.
                    </p>
                @else
                    <p class="text-sm text-gray-600 mb-3">
                        Saat ini Anda ditempatkan di <strong>{{ $penempatan->industri->nama_industri ?? '-' }}</strong> 
                        dengan status 
                        <span class="font-bold 
                            @if($penempatan->status == 'pending') text-yellow-600
                            @elseif($penempatan->status == 'verified') text-blue-600
                            @elseif($penempatan->status == 'approved') text-green-600
                            @elseif($penempatan->status == 'ongoing') text-green-600
                            @elseif($penempatan->status == 'completed') text-gray-600
                            @else text-red-600 @endif">
                            @if($penempatan->status == 'pending') Menunggu Verifikasi TU
                            @elseif($penempatan->status == 'verified') Menunggu Approval Pimpinan
                            @elseif($penempatan->status == 'approved') Disetujui - Siap Magang
                            @elseif($penempatan->status == 'ongoing') Sedang Berlangsung
                            @elseif($penempatan->status == 'completed') Selesai
                            @else Ditolak @endif
                        </span>
                    </p>
                    
                    @if($penempatan->tanggal_mulai && $penempatan->tanggal_selesai)
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Mulai: {{ $penempatan->tanggal_mulai->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Selesai: {{ $penempatan->tanggal_selesai->format('d M Y') }}
                            </span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Grid Layout: 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- KOLOM KIRI: Timeline & Form Mitra Sekolah -->
        <div class="space-y-6">
            
            <!-- Timeline Pengajuan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Timeline Pengajuan</h3>
                
                <div class="space-y-6">
                    <!-- Step 1: Pengajuan -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($penempatan) bg-green-500 @else bg-gray-300 @endif">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">1. Pengajuan</h4>
                            <p class="text-sm text-gray-500">Siswa mengajukan tempat magang</p>
                            @if($penempatan)
                                <p class="text-xs text-gray-400 mt-1">📅 {{ $penempatan->created_at->format('d M Y, H:i') }} WIB</p>
                            @endif
                        </div>
                    </div>

                    <!-- Step 2: Verifikasi TU (Admin) -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($penempatan && in_array($penempatan->status, ['verified', 'approved', 'ongoing', 'completed'])) 
                                bg-green-500 
                            @elseif($penempatan && $penempatan->status == 'pending') 
                                bg-blue-500 animate-pulse
                            @else 
                                bg-gray-300 
                            @endif">
                            @if($penempatan && in_array($penempatan->status, ['verified', 'approved', 'ongoing', 'completed']))
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif($penempatan && $penempatan->status == 'pending')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <span class="text-white font-bold text-sm">2</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">2. Verifikasi TU</h4>
                            <p class="text-sm text-gray-500">Tata Usaha memverifikasi data</p>
                            @if($penempatan && in_array($penempatan->status, ['verified', 'approved', 'ongoing', 'completed']))
                                <p class="text-xs text-green-600 mt-1">✅ Terverifikasi</p>
                            @elseif($penempatan && $penempatan->status == 'pending')
                                <p class="text-xs text-blue-600 mt-1 font-semibold">⏳ Sedang diverifikasi TU...</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($penempatan && in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) 
                                bg-green-500 
                            @elseif($penempatan && $penempatan->status == 'verified') 
                                bg-blue-500 animate-pulse
                            @else 
                                bg-gray-300 
                            @endif">
                            @if($penempatan && in_array($penempatan->status, ['approved', 'ongoing', 'completed']))
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif($penempatan && $penempatan->status == 'verified')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <span class="text-white font-bold text-sm">3</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">3. Approval Pimpinan</h4>
                            <p class="text-sm text-gray-500">Kepala sekolah menyetujui</p>
                            @if($penempatan && in_array($penempatan->status, ['approved', 'ongoing', 'completed']))
                                <p class="text-xs text-green-600 mt-1">✅ Disetujui</p>
                            @elseif($penempatan && $penempatan->status == 'verified')
                                <p class="text-xs text-blue-600 mt-1 font-semibold">⏳ Menunggu approval pimpinan...</p>
                            @elseif($penempatan && $penempatan->status == 'pending')
                                <p class="text-xs text-gray-500 mt-1">⏳ Dalam antrian</p>
                            @endif
                        </div>
                    </div>

                    <!-- Step 4: Penempatan -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($penempatan && in_array($penempatan->status, ['ongoing', 'completed'])) 
                                bg-green-500 
                            @elseif($penempatan && $penempatan->status == 'approved') 
                                bg-yellow-500 
                            @else 
                                bg-gray-300 
                            @endif">
                            @if($penempatan && in_array($penempatan->status, ['ongoing', 'completed']))
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif($penempatan && $penempatan->status == 'approved')
                                <span class="text-white font-bold text-sm">4</span>
                            @else
                                <span class="text-white font-bold text-sm">4</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">4. Penempatan</h4>
                            <p class="text-sm text-gray-500">Siswa ditempatkan di industri</p>
                            @if($penempatan && in_array($penempatan->status, ['ongoing', 'completed']))
                                <p class="text-xs text-green-600 mt-1">✅ {{ $penempatan->industri->nama_industri ?? '-' }}</p>
                            @elseif($penempatan && $penempatan->status == 'approved')
                                <p class="text-xs text-yellow-600 mt-1">⏳ Siap dimulai</p>
                            @endif
                        </div>
                    </div>

                    <!-- Step 5: Selesai -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($penempatan && $penempatan->status == 'completed') 
                                bg-green-500 
                            @else 
                                bg-gray-300 
                            @endif">
                            @if($penempatan && $penempatan->status == 'completed')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <span class="text-white font-bold text-sm">5</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">5. Selesai</h4>
                            <p class="text-sm text-gray-500">Program magang selesai</p>
                            @if($penempatan && $penempatan->status == 'completed')
                                <p class="text-xs text-green-600 mt-1">✅ Selesai pada {{ $penempatan->tanggal_selesai ? $penempatan->tanggal_selesai->format('d M Y') : '-' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengajuan Mitra Sekolah -->
            @if(!$penempatan)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Pilih Mitra Magang</h3>
                <p class="text-sm text-gray-600 mb-4">Pilih tempat magang dari daftar mitra sekolah yang tersedia</p>
                
                <form action="{{ route('siswa.ajukan-mitra') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Industri / Perusahaan *</label>
                        <select name="industri_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">-- Pilih Industri --</option>
                            @foreach($industris as $industri)
                                <option value="{{ $industri->id }}" {{ $industri->sisa_kuota <= 0 ? 'disabled' : '' }}>
                                    {{ $industri->nama_industri }} ({{ $industri->kota }}) 
                                    - {{ $industri->sisa_kuota > 0 ? 'Sisa Kuota: ' . $industri->sisa_kuota : 'KUOTA PENUH' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih dari daftar mitra sekolah yang sudah bekerja sama</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi yang Diinginkan *</label>
                        <input type="text" name="posisi_magang" required placeholder="Contoh: Staff IT, Admin, Teknisi" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Memilih (Opsional)</label>
                        <textarea name="alasan" rows="3" placeholder="Jelaskan mengapa Anda memilih industri ini..." 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Ajukan Pengajuan
                    </button>
                    <p class="text-xs text-center text-gray-500">Pengajuan akan diverifikasi oleh TU dan disetujui oleh Pimpinan</p>
                </form>
            </div>
            @endif
        </div>

        <!-- KOLOM KANAN: Informasi Penempatan & Form Mandiri -->
        <div class="space-y-6">
            
            <!-- Informasi Penempatan -->
                <!-- Informasi Penempatan / Status Pengajuan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Informasi Penempatan</h3>
                    
                    @if($penempatan && $penempatan->status == 'rejected')
                        <!-- KASUS: DITOLAK -->
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-xl mb-2">Pengajuan Ditolak</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                Maaf, pengajuan magang Anda ke <strong>{{ $penempatan->industri->nama_industri ?? 'industri tersebut' }}</strong> tidak disetujui.
                            </p>
                            
                            <!-- Kotak Alasan (Opsional, jika Admin nanti menambahkan fitur input alasan) -->
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 max-w-md mx-auto text-left">
                                <p class="text-sm text-red-800 font-semibold mb-1">📝 Alasan Penolakan:</p>
                                <p class="text-sm text-red-700 whitespace-pre-line">
                                    {{ $penempatan->alasan_penolakan ?? 'Tidak ada alasan yang diberikan.' }}
                                </p>
                            </div>

                            <!-- Tombol Ajukan Ulang -->
                            <form action="{{ route('siswa.ajukan-ulang') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Ajukan Ulang Magang
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-4">Tombol ini akan menghapus pengajuan sebelumnya dan mengaktifkan form pengajuan baru.</p>
                        </div>

                        @elseif($penempatan && $penempatan->industri)
                            <!-- KASUS: SEDANG PROSES / DISETUJUI / MAGANG -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <img src="https://images.unsplash.com/photo-1562774053-701939374585?w=600" 
                                        alt="Industri" 
                                        class="w-full h-48 object-cover rounded-xl mb-4">
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-500">Industri</span>
                                        <span class="font-semibold text-gray-800 text-right">{{ $penempatan->industri->nama_industri }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-500">Alamat</span>
                                        <span class="font-semibold text-gray-800 text-right">{{ $penempatan->industri->alamat }}, {{ $penempatan->industri->kota }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-500">Posisi</span>
                                        <span class="font-semibold text-gray-800">{{ $penempatan->posisi_magang ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-500">Kontak Industri</span>
                                        <span class="font-semibold text-gray-800">{{ $penempatan->industri->no_telp ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between py-3">
                                        <span class="text-gray-500">Status</span>
                                        <span class="px-4 py-2 
                                            @if($penempatan->status === 'ongoing') bg-green-100 text-green-700
                                            @elseif($penempatan->status === 'approved') bg-blue-100 text-blue-700
                                            @elseif($penempatan->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($penempatan->status === 'verified') bg-blue-100 text-blue-700
                                            @else bg-gray-100 text-gray-700 @endif 
                                            text-sm font-semibold rounded-full">
                                            @if($penempatan->status === 'pending') Menunggu Verifikasi
                                            @elseif($penempatan->status === 'verified') Menunggu Approval
                                            @else {{ ucfirst($penempatan->status) }} @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                        <!-- KASUS: BELUM ADA PENGAJUAN -->
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada informasi penempatan</p>
                            <p class="text-sm text-gray-400 mt-2">Silakan pilih mitra magang atau ajukan perusahaan mandiri di samping.</p>
                        </div>
                    @endif
                </div>

            <!-- Form Pengajuan Perusahaan Mandiri -->
            @if(!$penempatan)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🏢 Form Pengajuan Perusahaan Mandiri</h3>
                <p class="text-sm text-gray-600 mb-4">Ajukan perusahaan yang Anda cari sendiri (di luar mitra sekolah)</p>
                
                <form action="{{ route('siswa.ajukan-mandiri') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Data Perusahaan</h4>
                        
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Industri / Perusahaan *</label>
                                <input type="text" name="nama_industri" required placeholder="Nama lengkap perusahaan" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NIB (Opsional)</label>
                                <input type="text" name="nib" placeholder="Nomor Induk Berusaha" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap *</label>
                                <textarea name="alamat" required rows="2" placeholder="Jalan, nomor, dll" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kelurahan</label>
                                    <input type="text" name="kelurahan" placeholder="Kelurahan" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kecamatan</label>
                                    <input type="text" name="kecamatan" placeholder="Kecamatan" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kota/Kabupaten *</label>
                                <input type="text" name="kota" required placeholder="Kota atau Kabupaten" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Provinsi *</label>
                                <input type="text" name="provinsi" required placeholder="Provinsi" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Pos</label>
                                <input type="text" name="kode_pos" placeholder="Kode pos" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">No. Telepon Perusahaan *</label>
                                    <input type="text" name="no_telp" required placeholder="021-12345678" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" placeholder="email@perusahaan.com" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Website (Opsional)</label>
                                <input type="text" name="website" placeholder="https://www.perusahaan.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Industri</label>
                                <input type="text" name="kategori" placeholder="Contoh: Manufaktur, Jasa, IT" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Kontak HRD/Personalia</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama HR</label>
                                <input type="text" name="nama_hr" placeholder="Nama kontak person" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">No. WA HR</label>
                                <input type="text" name="no_wa_hr" placeholder="0812-3456-7890" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Posisi yang Diinginkan *</label>
                        <input type="text" name="posisi_magang" required placeholder="Contoh: Staff IT, Admin, Teknisi" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Memilih (Opsional)</label>
                        <textarea name="alasan" rows="2" placeholder="Jelaskan mengapa Anda memilih perusahaan ini..." 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Ajukan Pengajuan Mandiri
                    </button>
                    <p class="text-xs text-center text-gray-500">Pastikan perusahaan sudah menyetujui penerimaan Anda</p>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection