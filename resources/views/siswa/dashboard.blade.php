@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="p-0 space-y-8 animate-fade-in-up">
    <!-- Welcome Card - Soft glass gradient card -->
    <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 text-gray-800 relative overflow-hidden shadow-[0_15px_35px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgba(0,0,0,0.05)] transition-all duration-300">
        <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-tr from-amber-400/15 to-emerald-500/15 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black text-emerald-800 mb-2">Halo, {{ session('siswa_nama') }}! 👋</h2>
            <p class="text-gray-650 font-semibold text-sm">Selamat datang di Dashboard Siswa Prakerin. Pantau progress magangmu dan unduh dokumen yang diperlukan di sini.</p>
        </div>
    </div>

@php
    $statusMagang = $stats['status_magang'] ?? '';
    if ($statusMagang === 'ongoing') {
        $statusIconBg = 'from-[#facc15] to-[#ca8a04] shadow-yellow-500/25';
        $statusBadgeBg = 'bg-yellow-100 text-yellow-800 border-yellow-200';
        $statusCardGlow = 'hover:shadow-[0_25px_50px_rgba(234,179,8,0.15)] hover:border-yellow-500/30';
        $statusLabelText = 'text-yellow-800/60';
    } elseif ($statusMagang === 'completed') {
        $statusIconBg = 'from-[#10b981] to-[#064e3b] shadow-emerald-500/25';
        $statusBadgeBg = 'bg-emerald-100 text-emerald-850 border-emerald-200';
        $statusCardGlow = 'hover:shadow-[0_25px_50px_rgba(16,185,129,0.15)] hover:border-emerald-500/30';
        $statusLabelText = 'text-emerald-805/65';
    } else {
        $statusIconBg = 'from-[#ef4444] to-[#991b1b] shadow-red-500/25';
        $statusBadgeBg = 'bg-red-100 text-red-800 border-red-200';
        $statusCardGlow = 'hover:shadow-[0_25px_50px_rgba(239,68,68,0.15)] hover:border-red-500/30';
        $statusLabelText = 'text-red-800/60';
    }
@endphp

    <!-- Stats Grid (Glassmorphism with elegant drop shadows) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Status Magang (Dynamic Theme) -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] {{ $statusCardGlow }} transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-tr {{ $statusIconBg }} text-white shadow-md rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 {{ $statusBadgeBg }} text-xs font-black rounded-full">
                    @if($statusMagang == 'ongoing') AKTIF
                    @elseif($statusMagang == 'approved') MENUNGGU PERIODE
                    @elseif($statusMagang == 'completed') SELESAI
                    @else BELUM ADA @endif
                </span>
            </div>
            <p class="{{ $statusLabelText }} text-[10px] font-black uppercase tracking-wider mb-1">Status Magang</p>
            <p class="text-xl font-black text-gray-800">
                @if($statusMagang == 'ongoing') Sedang Magang
                @elseif($statusMagang == 'completed') Selesai Magang
                @elseif($statusMagang == 'approved') Menunggu Periode Magang
                @else Belum Ada Penempatan
                @endif
            </p>
        </div>

        <!-- Durasi Magang (Vibrant Smooth Dark Purple theme) -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_25px_50px_rgba(124,58,237,0.15)] hover:border-purple-500/30 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-tr from-[#7c3aed] to-[#4c1d95] text-white shadow-md shadow-purple-500/25 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($penempatan && $penempatan->status == 'ongoing' && $stats['sisa_hari'] > 0)
                    <span class="px-3 py-1 bg-purple-100 text-purple-755 border border-purple-200 text-xs font-black rounded-full">{{ $stats['sisa_hari'] }} Hari Lagi</span>
                @elseif($penempatan && $penempatan->status == 'ongoing' && $stats['sisa_hari'] == 0)
                    <span class="px-3 py-1 bg-red-100 text-red-755 border border-red-200 text-xs font-black rounded-full">Hari Terakhir</span>
                @elseif($penempatan && $penempatan->status == 'approved')
                    <span class="px-3 py-1 bg-amber-100 text-amber-755 border border-amber-200 text-xs font-black rounded-full">Menunggu Dimulai</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 border border-gray-250 text-xs font-black rounded-full">Selesai / Kosong</span>
                @endif
            </div>
            <div>
                <p class="text-purple-800/60 text-[10px] font-black uppercase tracking-wider mb-1">Durasi Magang</p>
                @if($penempatan && $penempatan->status == 'ongoing')
                    <p class="text-xl font-black text-gray-800">Sedang Magang</p>
                    <p class="text-[10px] text-gray-450 mt-1 italic font-bold">
                        Berakhir: {{ $penempatan->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y, H:i') : 'Belum diatur Admin' }} WIB
                    </p>
                @elseif($penempatan && $penempatan->status == 'completed')
                    <p class="text-xl font-black text-purple-700">Telah Selesai</p>
                    <p class="text-[10px] text-gray-450 mt-1 italic font-bold">
                        Selesai pd: {{ $penempatan->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '-' }}
                    </p>
                @elseif($penempatan && $penempatan->status == 'approved')
                    <p class="text-xl font-black text-gray-800">Siap Dimulai</p>
                    <p class="text-[10px] text-gray-450 mt-1 italic font-bold">
                        Mulai: {{ $penempatan->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y, H:i') : 'Menunggu Admin' }} WIB
                    </p>
                @else
                    <p class="text-xl font-black text-gray-400">Belum Ada</p>
                @endif
            </div>
        </div>

        <!-- Kehadiran (Vibrant Cyan/Blue theme) -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_25px_50px_rgba(6,182,212,0.15)] hover:border-cyan-500/30 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-tr from-cyan-500 to-blue-500 text-white shadow-md shadow-cyan-500/25 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['kehadiran'] >= 90)
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-black rounded-full">Excellent</span>
                @elseif($stats['kehadiran'] >= 75)
                    <span class="px-3 py-1 bg-cyan-100 text-cyan-800 border border-cyan-200 text-xs font-black rounded-full">Baik</span>
                @else
                    <span class="px-3 py-1 bg-amber-100 text-amber-800 border border-amber-200 text-xs font-black rounded-full">Kurang</span>
                @endif
            </div>
            <p class="text-cyan-850/65 text-[10px] font-black uppercase tracking-wider mb-1">Kehadiran</p>
            <p class="text-xl font-black text-gray-800">{{ $stats['kehadiran'] }}%</p>
            <p class="text-[10px] text-gray-450 mt-1 italic font-bold">
                Hadir: {{ $stats['total_hadir'] }} | Izin: {{ $stats['total_izin'] }} | Sakit: {{ $stats['total_sakit'] }} | <span class="text-red-500 font-bold">Alpha: {{ $stats['total_alpha'] }}</span>
            </p>
        </div>
    </div>

    <!-- Rows Grid Layout (Glassmorphism with deep shadows) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Penempatan -->
        <div class="lg:col-span-2 bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_25px_50px_rgba(59,130,246,0.1)] hover:border-blue-500/25 transition-all duration-300 overflow-hidden">
            <div class="p-6 border-b border-white/30">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        Informasi Penempatan Industri
                    </h3>
                    @if($penempatan && $penempatan->industri)
                        <a href="{{ route('siswa.industri.detail', $penempatan->industri->id) }}" class="text-blue-600 hover:text-blue-700 text-xs font-bold transition flex items-center gap-1">
                            Detail Lokasi
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
            <div class="p-6">
                @if($penempatan && $penempatan->industri)
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-48 h-32 bg-gradient-to-tr from-pink-500 to-rose-500 text-white shadow-md shadow-rose-500/20 flex items-center justify-center text-5xl font-black rounded-xl border border-white/40">
                        {{ substr($penempatan->industri->nama_industri, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-blue-650 mb-2">{{ $penempatan->industri->nama_industri }}</h4>
                        <p class="text-gray-650 text-sm mb-4 font-semibold">
                            <svg class="w-4 h-4 inline mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $penempatan->industri->alamat }}, {{ $penempatan->industri->kota }}
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-blue-800/60 font-bold uppercase tracking-wider mb-1">PEMBIMBING LAPANGAN</p>
                                <p class="text-sm text-gray-800 font-bold">{{ $penempatan->industri->nama_hr ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-blue-800/60 font-bold uppercase tracking-wider mb-1">KONTAK INDUSTRI</p>
                                <p class="text-sm text-gray-800 font-bold">{{ $penempatan->industri->no_telp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 text-blue-350/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-sm font-bold text-blue-800/80">Belum ada data penempatan industri.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Pengumuman Penting (Vibrant Rose/Pink theme) -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_25px_50px_rgba(244,63,94,0.1)] hover:border-rose-500/25 transition-all duration-300 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-rose-100 text-rose-600 border border-rose-200 rounded-full flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Pengumuman Penting</h3>
            </div>
            
            <div class="relative flex-1 flex flex-col justify-center overflow-hidden">
                @if($pengumumans->count() > 0)
                    <div class="overflow-hidden w-full relative">
                        <div id="slider-pengumuman" class="flex transition-transform duration-500 ease-in-out">
                            @foreach($pengumumans as $p)
                                @php
                                    $bg = 'bg-rose-500/5'; $border = 'border-rose-500/10'; $titleColor = 'text-rose-800'; $bodyColor = 'text-gray-600';
                                    if($p->prioritas == 'tinggi') { 
                                        $bg = 'bg-red-500/5'; $border = 'border-red-500/10'; $titleColor = 'text-red-800'; $bodyColor = 'text-gray-600'; 
                                    } elseif($p->prioritas == 'sedang') { 
                                        $bg = 'bg-amber-500/5'; $border = 'border-amber-500/10'; $titleColor = 'text-amber-850'; $bodyColor = 'text-gray-600'; 
                                    }
                                @endphp
                                
                                <div class="w-full flex-shrink-0 px-1">
                                    <div class="p-5 rounded-xl border {{ $border }} {{ $bg }} h-full flex flex-col justify-center">
                                        <div class="flex justify-between items-start mb-3 gap-2">
                                            <h4 class="font-bold {{ $titleColor }} leading-tight text-sm">{{ $p->judul }}</h4>
                                            <span class="text-[9px] font-bold {{ $titleColor }} bg-white/70 px-2 py-0.5 rounded-md shrink-0">
                                                {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M') : $p->created_at->format('d M') }}
                                            </span>
                                        </div>
                                        <p class="text-xs {{ $bodyColor }} leading-relaxed font-semibold">{{ $p->isi }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center gap-1.5 mt-5">
                        @foreach($pengumumans as $index => $p)
                            <button onclick="pindahSlide({{ $index }})" class="dot-btn w-2 h-2 rounded-full transition-all duration-300 {{ $index == 0 ? 'bg-rose-550 w-5' : 'bg-rose-200 hover:bg-rose-350' }}"></button>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 my-auto">
                        <svg class="w-12 h-12 text-rose-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-xs font-bold text-rose-600/80">Tidak ada pengumuman.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- Outer Lower Row (Glassmorphism with deep shadows) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <!-- Aktivitas Terakhir (Vibrant Violet theme) -->
        <div class="lg:col-span-2 bg-white/50 backdrop-blur-xl border border-white/35 rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_25px_50px_rgba(139,92,246,0.1)] hover:border-violet-500/25 transition-all duration-300 overflow-hidden">
            <div class="p-6 border-b border-white/30">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-1.5 bg-violet-100 rounded-lg text-violet-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Aktivitas Terakhir
                    </h3>
                    <a href="{{ route('siswa.riwayat.jurnal') }}" class="text-violet-605 hover:text-violet-750 text-xs font-bold transition flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-100/40">
                @forelse($stats['aktivitas_terakhir'] as $aktivitas)
                    <div class="p-4 hover:bg-violet-50/10 transition-all duration-200">
                        <div class="flex items-start gap-4">
                            <div class="w-2 h-2 mt-2 rounded-full 
                                @if($aktivitas['status'] === 'success') bg-green-500
                                @elseif($aktivitas['status'] === 'warning') bg-yellow-500
                                @else bg-red-500 @endif">
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-sm leading-snug">{{ $aktivitas['judul'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 font-medium">{{ $aktivitas['deskripsi'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 font-semibold">
                                    {{ $aktivitas['waktu']->diffForHumans() }}, {{ $aktivitas['waktu']->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 font-bold text-sm">Belum ada aktivitas</p>
                        <p class="text-xs text-gray-400 mt-1">Aktivitas akan muncul setelah kamu mengisi presensi, jurnal, atau download surat</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Progres Kelengkapan (Vibrant Gradient Progress bars) -->
        <div class="bg-white/50 backdrop-blur-xl border border-white/30 rounded-2xl p-6 shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-[0_25px_50px_rgba(0,0,0,0.08)] transition-all duration-300">
            <h3 class="text-base font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="p-1.5 bg-emerald-100 rounded-lg text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
                Progres Kelengkapan
            </h3>
            
            <div class="space-y-6">
                <!-- Jurnal Harian (Purple/Pink Gradient) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-700 font-extrabold">Jurnal Harian</span>
                        <span class="text-xs font-black text-purple-700 bg-purple-100 border border-purple-200 px-2 py-0.5 rounded-md">{{ $stats['progres_jurnal'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200/50 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $stats['progres_jurnal'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1.5 font-bold">
                        📋 {{ $stats['jurnal_count'] }} / {{ $stats['target_jurnal'] ?? 60 }} Hari Kerja
                    </p>
                </div>
                
                <!-- Laporan PKL (Blue/Cyan Gradient) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-700 font-extrabold">Laporan PKL</span>
                        <span class="text-xs font-black text-blue-700 bg-blue-100 border border-blue-200 px-2 py-0.5 rounded-md">{{ $stats['progres_laporan'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200/50 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $stats['progres_laporan'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1.5 font-bold">
                        @if($stats['laporan_pkl'])
                            Status: <span class="text-blue-700 font-extrabold uppercase">{{ $stats['laporan_pkl']->status }}</span>
                        @else
                            <span class="text-red-500 font-extrabold italic">Belum upload</span>
                        @endif
                    </p>
                </div>
                
                <!-- Presensi (Emerald/Lime Gradient) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-700 font-extrabold">Presensi</span>
                        <span class="text-xs font-black text-emerald-700 bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded-md">{{ $stats['progres_presensi'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200/50 rounded-full h-2">
                        <div class="bg-gradient-to-r from-emerald-500 to-lime-500 h-2 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $stats['progres_presensi'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1.5 font-bold">
                        💼 Hadir: {{ $stats['total_hadir'] }} | Izin: {{ $stats['total_izin'] }} | Sakit: {{ $stats['total_sakit'] }} | <span class="text-red-550 font-extrabold">Alpha: {{ $stats['total_alpha'] }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleNotifikasi() {
        const dropdown = document.getElementById('notifikasiDropdown');
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) userDropdown.classList.add('hidden');
        if (dropdown) dropdown.classList.toggle('hidden');
    }

    // ====== SCRIPT SLIDER PENGUMUMAN ======
    let slideSaatIni = 0;
    const totalSlide = {{ $pengumumans->count() }};

    function pindahSlide(index) {
        if (totalSlide === 0) return;
        slideSaatIni = index;
        const slider = document.getElementById('slider-pengumuman');
        if(slider) {
            slider.style.transform = `translateX(-${slideSaatIni * 100}%)`;
        }
        
        // Animasi Titik Geser
        document.querySelectorAll('.dot-btn').forEach((dot, i) => {
            if (i === slideSaatIni) {
                dot.classList.remove('bg-rose-200');
                dot.classList.add('bg-rose-550', 'w-5');
            } else {
                dot.classList.remove('bg-rose-550', 'w-5');
                dot.classList.add('bg-rose-200');
            }
        });
    }

    // Slider Berjalan Otomatis setiap 4 Detik
    if(totalSlide > 1) {
        setInterval(() => {
            let selanjutnya = (slideSaatIni + 1) % totalSlide;
            pindahSlide(selanjutnya);
        }, 4000);
    }
</script>
@endsection