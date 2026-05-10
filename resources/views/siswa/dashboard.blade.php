@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800">Sistem Administrasi Prakerin</h1>
        <div class="flex items-center gap-6">
    <div class="relative">
        <button onclick="toggleNotifikasi()" class="relative text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            @if($stats['notifikasi_unread'] > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ $stats['notifikasi_unread'] }}
                </span>
            @endif
        </button>
        
        <div id="notifikasiDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Notifikasi</h3>
                    <a href="#" class="text-xs text-blue-600 hover:underline">Tandai semua dibaca</a>
                </div>
            </div>
            <div class="max-h-96 overflow-y-auto">
                @forelse($stats['notifikasis'] as $notifikasi)
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition {{ !$notifikasi->is_read ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full 
                                @if($notifikasi->jenis === 'success') bg-green-500
                                @elseif($notifikasi->jenis === 'warning') bg-yellow-500
                                @elseif($notifikasi->jenis === 'error') bg-red-500
                                @else bg-blue-500 @endif">
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-gray-800">{{ $notifikasi->judul }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $notifikasi->pesan }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $notifikasi->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm">Belum ada notifikasi</p>
                    </div>
                @endforelse
            </div>
            <div class="p-3 border-t border-gray-100 bg-gray-50 text-center">
                <a href="#" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua Notifikasi</a>
            </div>
        </div>
    </div>
    
    <div class="text-sm text-gray-600">
        {{ tanggal_indonesia() }}
    </div>
</div>
</header>

<div class="p-8">
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Halo, {{ session('siswa_nama') }}! 👋</h2>
            <p class="text-blue-100">Selamat datang di Dashboard Siswa Prakerin. Pantau progress magangmu dan unduh dokumen yang diperlukan di sini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 
                    @if($stats['status_magang'] == 'ongoing') bg-green-100
                    @elseif($stats['status_magang'] == 'pending') bg-yellow-100
                    @elseif($stats['status_magang'] == 'completed') bg-blue-100
                    @else bg-gray-100 @endif 
                    rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 
                        @if($stats['status_magang'] == 'ongoing') text-green-600
                        @elseif($stats['status_magang'] == 'pending') text-yellow-600
                        @elseif($stats['status_magang'] == 'completed') text-blue-600
                        @else text-gray-600 @endif" 
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 
                    @if($stats['status_magang'] == 'ongoing') bg-green-100 text-green-700
                    @elseif($stats['status_magang'] == 'pending') bg-yellow-100 text-yellow-700
                    @elseif($stats['status_magang'] == 'completed') bg-blue-100 text-blue-700
                    @else bg-gray-100 text-gray-700 @endif 
                    text-xs font-semibold rounded-full">
                    @if($stats['status_magang'] == 'ongoing') AKTIF
                    @elseif($stats['status_magang'] == 'pending') PENDING
                    @elseif($stats['status_magang'] == 'completed') SELESAI
                    @elseif($stats['status_magang'] == 'approved') DISETUJUI
                    @else BELUM ADA @endif
                </span>
            </div>
            <p class="text-gray-500 text-sm mb-1">Status Magang</p>
            <p class="text-2xl font-bold text-gray-800">
                @if($stats['status_magang'] == 'ongoing') Sedang Magang
                @elseif($stats['status_magang'] == 'pending') Menunggu Approval
                @elseif($stats['status_magang'] == 'completed') Selesai Magang
                @elseif($stats['status_magang'] == 'approved') Siap Magang
                @else Belum Ada Penempatan
                @endif
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                @if($penempatan && $penempatan->status == 'ongoing' && $stats['sisa_hari'] > 0)
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">{{ $stats['sisa_hari'] }} Hari Lagi</span>
                @elseif($penempatan && $penempatan->status == 'ongoing' && $stats['sisa_hari'] == 0)
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Hari Terakhir</span>
                @elseif($penempatan && $penempatan->status == 'approved')
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Menunggu Dimulai</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">Selesai / Kosong</span>
                @endif
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Durasi Magang</p>
                @if($penempatan && $penempatan->status == 'ongoing')
                    <p class="text-2xl font-bold text-gray-800 mb-1">Sedang Magang</p>
                    <p class="text-[10px] text-gray-400 mt-1 italic">
                        Berakhir: {{ $penempatan->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y, H:i') : 'Belum diatur Admin' }} WIB
                    </p>
                @elseif($penempatan && $penempatan->status == 'completed')
                    <p class="text-2xl font-bold text-green-600 mb-1">Telah Selesai</p>
                    <p class="text-[10px] text-gray-400 mt-1 italic">
                        Selesai pd: {{ $penempatan->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d M Y') : '-' }}
                    </p>
                @elseif($penempatan && $penempatan->status == 'approved')
                    <p class="text-xl font-bold text-gray-800 mb-1">Siap Dimulai</p>
                    <p class="text-[10px] text-gray-400 mt-1 italic">
                        Mulai: {{ $penempatan->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d M Y, H:i') : 'Menunggu Admin' }} WIB
                    </p>
                @else
                    <p class="text-xl font-bold text-gray-400">Belum Ada</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['kehadiran'] >= 90)
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Excellent</span>
                @elseif($stats['kehadiran'] >= 75)
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Baik</span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Kurang</span>
                @endif
            </div>
            <p class="text-gray-500 text-sm mb-1">Kehadiran</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['kehadiran'] }}%</p>
            <p class="text-[10px] text-gray-400 mt-1 italic">
                Hadir: {{ $stats['total_hadir'] }} | Izin: {{ $stats['total_izin'] }} | Sakit: {{ $stats['total_sakit'] }} | <span class="text-red-500 font-bold">Alpha: {{ $stats['total_alpha'] }}</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Informasi Penempatan Industri</h3>
                    @if($penempatan && $penempatan->industri)
                        <a href="{{ route('siswa.industri.detail', $penempatan->industri->id) }}" class="text-orange-600 hover:text-orange-700 text-sm font-semibold transition">Detail Lokasi</a>
                    @endif
                </div>
            </div>
            <div class="p-6">
                @if($penempatan && $penempatan->industri)
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-48 h-32 bg-orange-100 flex items-center justify-center text-orange-500 text-4xl font-bold rounded-xl">
                        {{ substr($penempatan->industri->nama_industri, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-orange-600 mb-2">{{ $penempatan->industri->nama_industri }}</h4>
                        <p class="text-gray-600 text-sm mb-4">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $penempatan->industri->alamat }}, {{ $penempatan->industri->kota }}
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">PEMBIMBING LAPANGAN</p>
                                <p class="text-sm text-gray-800">{{ $penempatan->industri->nama_hr ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">KONTAK INDUSTRI</p>
                                <p class="text-sm text-gray-800">{{ $penempatan->industri->no_telp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-6 text-gray-500">
                    <p>Belum ada data penempatan industri.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-orange-50 rounded-2xl p-6 border border-orange-100 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Pengumuman Penting</h3>
            </div>
            
            <div class="relative flex-1 flex flex-col justify-center overflow-hidden">
                @if($pengumumans->count() > 0)
                    <div class="overflow-hidden w-full relative">
                        <div id="slider-pengumuman" class="flex transition-transform duration-500 ease-in-out">
                            @foreach($pengumumans as $p)
                                @php
                                    // Logika Warna berdasarkan PRIORITAS database terbaru
                                    $bg = 'bg-blue-100/50'; $border = 'border-blue-200'; $titleColor = 'text-blue-800'; $bodyColor = 'text-blue-700';
                                    if($p->prioritas == 'tinggi') { 
                                        $bg = 'bg-red-100/50'; $border = 'border-red-200'; $titleColor = 'text-red-800'; $bodyColor = 'text-red-700'; 
                                    } elseif($p->prioritas == 'sedang') { 
                                        $bg = 'bg-yellow-100/50'; $border = 'border-yellow-300'; $titleColor = 'text-yellow-800'; $bodyColor = 'text-yellow-700'; 
                                    }
                                @endphp
                                
                                <div class="w-full flex-shrink-0 px-1">
                                    <div class="p-5 rounded-xl border {{ $border }} {{ $bg }} h-full flex flex-col justify-center">
                                        <div class="flex justify-between items-start mb-3 gap-2">
                                            <h4 class="font-bold {{ $titleColor }} leading-tight">{{ $p->judul }}</h4>
                                            <span class="text-[10px] font-bold {{ $titleColor }} bg-white/60 px-2 py-1 rounded-lg shrink-0">
                                                {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M') : $p->created_at->format('d M') }}
                                            </span>
                                        </div>
                                        <p class="text-sm {{ $bodyColor }} leading-relaxed">{{ $p->isi }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center gap-1.5 mt-5">
                        @foreach($pengumumans as $index => $p)
                            <button onclick="pindahSlide({{ $index }})" class="dot-btn w-2 h-2 rounded-full transition-all duration-300 {{ $index == 0 ? 'bg-orange-600 w-5' : 'bg-orange-300 hover:bg-orange-400' }}"></button>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 my-auto">
                        <svg class="w-12 h-12 text-orange-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm font-medium text-orange-400">Tidak ada pengumuman.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Terakhir</h3>
                    <a href="{{ route('siswa.laporan') }}" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">Lihat Semua</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($stats['aktivitas_terakhir'] as $aktivitas)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="w-2 h-2 mt-2 rounded-full 
                                @if($aktivitas['status'] === 'success') bg-green-500
                                @elseif($aktivitas['status'] === 'warning') bg-yellow-500
                                @else bg-red-500 @endif">
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $aktivitas['judul'] }}</p>
                                <p class="text-sm text-gray-500">{{ $aktivitas['deskripsi'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $aktivitas['waktu']->diffForHumans() }}, {{ $aktivitas['waktu']->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Belum ada aktivitas</p>
                        <p class="text-sm text-gray-400 mt-2">Aktivitas akan muncul setelah kamu mengisi presensi, jurnal, atau download surat</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Progres Kelengkapan</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Jurnal Harian</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $stats['progres_jurnal'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $stats['progres_jurnal'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $stats['jurnal_count'] }} / {{ $stats['target_jurnal'] ?? 60 }} Hari Kerja
                    </p>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Laporan PKL</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $stats['progres_laporan'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $stats['progres_laporan'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($stats['laporan_pkl'])
                            Status: {{ ucfirst($stats['laporan_pkl']->status) }}
                        @else
                            Belum upload
                        @endif
                    </p>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Presensi</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $stats['progres_presensi'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $stats['progres_presensi'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Hadir: {{ $stats['total_hadir'] }} | Izin: {{ $stats['total_izin'] }} | Sakit: {{ $stats['total_sakit'] }} | <span class="text-red-500 font-bold">Alpha: {{ $stats['total_alpha'] }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleNotifikasi() {
        const dropdown = document.getElementById('notifikasiDropdown');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notifikasiDropdown');
        const button = event.target.closest('button');
        
        if (dropdown && !dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });

    // ====== SCRIPT SLIDER PENGUMUMAN ======
    let slideSaatIni = 0;
    const totalSlide = {{ $pengumumans->count() }};

    function pindahSlide(index) {
        if (totalSlide === 0) return;
        slideSaatIni = index;
        const slider = document.getElementById('slider-pengumuman');
        slider.style.transform = `translateX(-${slideSaatIni * 100}%)`;
        
        // Animasi Titik Geser
        document.querySelectorAll('.dot-btn').forEach((dot, i) => {
            if (i === slideSaatIni) {
                dot.classList.remove('bg-orange-300');
                dot.classList.add('bg-orange-600', 'w-5');
            } else {
                dot.classList.remove('bg-orange-600', 'w-5');
                dot.classList.add('bg-orange-300');
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