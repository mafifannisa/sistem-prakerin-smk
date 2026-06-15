@extends('layouts.siswa')
@section('title', 'Cek Status Magang')

@section('header_breadcrumb', 'CEK STATUS MAGANG')
@section('header_title', 'STATUS MAGANG')

@section('content')

@php
    $statusMagang = $penempatan ? $penempatan->status : 'belum_ada';
    if ($statusMagang === 'ongoing') {
        $statusIconBg = 'from-[#facc15] to-[#ca8a04] shadow-yellow-500/25';
        $statusIconColor = 'text-white';
        $statusBadgeBg = 'bg-yellow-100 text-yellow-800 border border-yellow-200/50';
    } elseif ($statusMagang === 'completed') {
        $statusIconBg = 'from-[#10b981] to-[#064e3b] shadow-emerald-500/25';
        $statusIconColor = 'text-white';
        $statusBadgeBg = 'bg-emerald-100 text-emerald-800 border border-emerald-250/50';
    } elseif ($statusMagang === 'approved') {
        $statusIconBg = 'from-[#7c3aed] to-[#4c1d95] shadow-purple-500/25';
        $statusIconColor = 'text-white';
        $statusBadgeBg = 'bg-purple-100 text-purple-800 border border-purple-200/50';
    } else {
        $statusIconBg = 'from-[#ef4444] to-[#991b1b] shadow-red-500/25';
        $statusIconColor = 'text-white';
        $statusBadgeBg = 'bg-red-100 text-red-755 border border-red-200/50';
    }
@endphp

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50/50 backdrop-blur-md border border-red-200/50 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-650 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-red-850 font-bold tracking-wide">Validasi Gagal!</h3>
                    <ul class="text-red-700 text-sm mt-1 list-disc list-inside font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

<!-- Main Content -->
<div class="p-0">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50/50 backdrop-blur-md border border-green-200/50 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-green-655 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-800 text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50/50 backdrop-blur-md border border-red-200/50 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-655 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-red-800 text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Info Card: Status Magang -->
    <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 mb-8 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-tr {{ $statusIconBg }} rounded-xl flex items-center justify-center flex-shrink-0 {{ $statusIconColor }} shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-extrabold text-gray-800 tracking-wide text-base">Status Magang Anda</h3>
                
                @if(!$penempatan)
                    <p class="text-sm text-gray-550 font-semibold mt-1">
                        Anda belum mengajukan tempat magang. Silakan pilih opsi di bawah untuk mengajukan.
                    </p>
                @else
                    <p class="text-sm text-gray-655 mb-2 font-semibold mt-1 leading-relaxed">
                        Saat ini Anda ditempatkan di <strong class="text-gray-900 font-extrabold">{{ $penempatan->industri->nama_industri ?? '-' }}</strong> 
                        dengan status: 
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider ml-1 {{ $statusBadgeBg }}">
                            @if($penempatan->status == 'approved') Menunggu Periode Magang
                            @elseif($penempatan->status == 'ongoing') Magang Aktif
                            @elseif($penempatan->status == 'completed') Selesai
                            @else Ditolak @endif
                        </span>
                    </p>
                    
                    @if($penempatan->tanggal_mulai && $penempatan->tanggal_selesai)
                        <div class="flex flex-wrap gap-4 text-xs font-bold text-gray-400 mt-2">
                            <span class="flex items-center gap-1.5 bg-white/40 border border-white/20 px-2.5 py-1 rounded-lg">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                MULAI: {{ $penempatan->tanggal_mulai->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/40 border border-white/20 px-2.5 py-1 rounded-lg">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                SELESAI: {{ $penempatan->tanggal_selesai->format('d M Y') }}
                            </span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Single Column Layout: Status Only -->
    <div class="space-y-6 mb-8">
        
        <!-- Timeline Pengajuan -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Timeline Pengajuan</h3>
            
            <div class="space-y-6">
                <!-- Step 1: Penempatan oleh Kepala Jurusan -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                        @if($penempatan) bg-gradient-to-tr from-[#10b981] to-[#047857] shadow-lg shadow-emerald-500/20 @else bg-gray-300 @endif">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">1. Penempatan</h4>
                        <p class="text-sm text-gray-500">Kepala Jurusan menempatkan siswa ke industri</p>
                        @if($penempatan)
                            <p class="text-xs text-gray-400 mt-1">📅 {{ $penempatan->created_at->format('d M Y, H:i') }} WIB</p>
                        @endif
                    </div>
                </div>

                <!-- Step 2: Menunggu Periode Magang -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                        @if($penempatan && $penempatan->status == 'approved') 
                            bg-gradient-to-tr from-[#7c3aed] to-[#4c1d95] shadow-lg shadow-purple-500/20 animate-pulse
                        @elseif($penempatan && in_array($penempatan->status, ['ongoing', 'completed'])) 
                            bg-gradient-to-tr from-[#10b981] to-[#047857] shadow-lg shadow-emerald-500/20
                        @else 
                            bg-gray-300 
                        @endif">
                        @if($penempatan && in_array($penempatan->status, ['ongoing', 'completed']))
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($penempatan && $penempatan->status == 'approved')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <span class="text-white font-bold text-sm">2</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">2. Menunggu Periode Magang</h4>
                        <p class="text-sm text-gray-500">Menunggu dimulainya tanggal magang yang telah ditentukan</p>
                        @if($penempatan && in_array($penempatan->status, ['ongoing', 'completed']))
                            <p class="text-xs text-green-600 mt-1">Periode Magang Telah Dimulai</p>
                        @elseif($penempatan && $penempatan->status == 'approved')
                            <p class="text-xs text-purple-600 mt-1 font-semibold">⏳ Menunggu dimulai pada {{ $penempatan->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y') : '-' }}</p>
                        @endif
                    </div>
                </div>

                <!-- Step 3: Magang Aktif -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                        @if($penempatan && $penempatan->status == 'ongoing') 
                            bg-gradient-to-tr from-[#10b981] to-[#047857] shadow-lg shadow-emerald-500/20 animate-pulse
                        @elseif($penempatan && $penempatan->status == 'completed') 
                            bg-gradient-to-tr from-[#10b981] to-[#047857] shadow-lg shadow-emerald-500/20
                        @else 
                            bg-gray-300 
                        @endif">
                        @if($penempatan && $penempatan->status == 'completed')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-white font-bold text-sm">3</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">3. Magang Aktif</h4>
                        <p class="text-sm text-gray-500">Siswa ditempatkan di industri</p>
                        @if($penempatan && $penempatan->status == 'ongoing')
                            <p class="text-xs text-emerald-600 mt-1 font-semibold">🟢 Sedang Berlangsung di {{ $penempatan->industri->nama_industri ?? '-' }}</p>
                        @elseif($penempatan && $penempatan->status == 'completed')
                            <p class="text-xs text-emerald-600 mt-1">Selesai Magang</p>
                        @endif
                    </div>
                </div>

                <!-- Step 4: Selesai -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                        @if($penempatan && $penempatan->status == 'completed') bg-gradient-to-tr from-[#10b981] to-[#047857] shadow-lg shadow-emerald-500/20 @else bg-gray-300 @endif">
                        <span class="text-white font-bold text-sm">4</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">4. Selesai</h4>
                        <p class="text-sm text-gray-500">Program magang selesai</p>
                        @if($penempatan && $penempatan->status == 'completed')
                            <p class="text-xs text-emerald-600 mt-1">Selesai pada {{ $penempatan->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '-' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Penempatan -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-8 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300">
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
                        Penempatan magang Anda ke <strong>{{ $penempatan->industri->nama_industri ?? 'industri tersebut' }}</strong> ditolak.
                    </p>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 max-w-md mx-auto text-left">
                        <p class="text-sm text-red-800 font-semibold mb-1">📝 Alasan Penolakan:</p>
                        <p class="text-sm text-red-700 whitespace-pre-line">
                            {{ $penempatan->alasan_penolakan ?? 'Tidak ada alasan yang diberikan.' }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-500">Silakan hubungi Kepala Jurusan Anda untuk informasi lebih lanjut.</p>
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
                        <div class="flex justify-between py-3 border-b border-gray-100/50">
                            <span class="text-gray-500 font-medium">Industri</span>
                            <span class="font-bold text-gray-800 text-right">{{ $penempatan->industri->nama_industri }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100/50">
                            <span class="text-gray-500 font-medium">Alamat</span>
                            <span class="font-bold text-gray-800 text-right">{{ $penempatan->industri->alamat }}, {{ $penempatan->industri->kota }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100/50">
                            <span class="text-gray-500 font-medium">Posisi</span>
                            <span class="font-bold text-gray-800">{{ $penempatan->posisi_magang ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100/50">
                            <span class="text-gray-500 font-medium">Kontak Industri</span>
                            <span class="font-bold text-gray-800">{{ $penempatan->industri->no_telp ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-gray-500 font-medium">Status</span>
                            <span class="px-4 py-1.5 {{ $statusBadgeBg }} text-sm font-extrabold rounded-full">
                                @if($penempatan->status === 'approved') Menunggu Periode Magang
                                @elseif($penempatan->status === 'ongoing') Magang Aktif
                                @elseif($penempatan->status === 'completed') Selesai
                                @else Ditolak @endif
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <!-- KASUS: BELUM ADA PENEMPATAN -->
                <div class="text-center py-12">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Belum Ada Penempatan Magang</p>
                    <p class="text-sm text-gray-400 mt-2 max-w-lg mx-auto">Penempatan magang dikelola oleh Kepala Jurusan. Silakan hubungi Kepala Jurusan Anda jika ada pertanyaan.</p>
                    <div class="mt-6 bg-amber-50/50 border border-amber-200/50 rounded-xl p-4 max-w-md mx-auto">
                        <p class="text-sm text-amber-700 font-semibold">💡 Info:</p>
                        <p class="text-sm text-amber-600 mt-1">Kepala Jurusan akan menempatkan Anda ke industri yang sesuai dengan jurusan dan kemampuan Anda.</p>
                    </div>
                </div>
            @endif
        </div>
</div>
@endsection