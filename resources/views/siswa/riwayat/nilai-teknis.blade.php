@extends('layouts.siswa')

@section('title', 'Riwayat Input Nilai Teknis')

@section('header_breadcrumb', 'Riwayat / Nilai Teknis')
@section('header_title', 'RIWAYAT INPUT NILAI TEKNIS')

@section('content')
<div class="p-0">
    <!-- LOCK MESSAGE: Jika Belum Boleh Lapor -->
    @if(!isset($bolehAbsen) || !$bolehAbsen)
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Riwayat Nilai Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses riwayat nilai.' }}</p>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                        📍 Buka Cek Status Magang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Grid Layout -->
        <div class="space-y-6">
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 flex items-center gap-2">
                    <span>✅</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if($nilai)
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Rata-rata Nilai -->
                    <div class="bg-white/50 backdrop-blur-xl rounded-2xl border border-white/35 p-6 flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-tr from-amber-500 to-yellow-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-yellow-500/20">
                            📊
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Rata-Rata Nilai Teknis</p>
                            <h3 class="text-2xl font-black text-gray-800 mt-0.5">
                                {{ number_format(($nilai->nilai_1 + $nilai->nilai_2 + $nilai->nilai_3) / 3, 2) }}
                            </h3>
                        </div>
                    </div>

                    <!-- Predikat -->
                    <div class="bg-white/50 backdrop-blur-xl rounded-2xl border border-white/35 p-6 flex items-center gap-4">
                        @php
                            $avg = ($nilai->nilai_1 + $nilai->nilai_2 + $nilai->nilai_3) / 3;
                            $predikat = 'E';
                            $badgeColor = 'from-red-500 to-orange-500 shadow-red-500/20';
                            if ($avg >= 86) { $predikat = 'A'; $badgeColor = 'from-emerald-500 to-teal-500 shadow-emerald-500/20'; }
                            elseif ($avg >= 70) { $predikat = 'B'; $badgeColor = 'from-blue-500 to-indigo-500 shadow-blue-500/20'; }
                            elseif ($avg >= 56) { $predikat = 'C'; $badgeColor = 'from-yellow-500 to-amber-500 shadow-yellow-500/20'; }
                        @endphp
                        <div class="w-14 h-14 bg-gradient-to-tr {{ $badgeColor }} rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-lg">
                            {{ $predikat }}
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Predikat Performa</p>
                            <h3 class="text-lg font-black text-gray-800 mt-0.5">
                                @if($predikat === 'A') Sangat Baik
                                @elseif($predikat === 'B') Baik
                                @elseif($predikat === 'C') Cukup
                                @else Kurang @endif
                            </h3>
                        </div>
                    </div>

                    <!-- Tanggal Input -->
                    <div class="bg-white/50 backdrop-blur-xl rounded-2xl border border-white/35 p-6 flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-tr from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/20">
                            📅
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Input</p>
                            <h3 class="text-sm font-bold text-gray-800 mt-1">
                                {{ \Carbon\Carbon::parse($nilai->tanggal_input)->format('d M Y, H:i') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Detail Table & Photo Preview -->
                <div class="bg-white/50 backdrop-blur-xl rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.03),0_5px_15px_rgba(0,0,0,0.01)] border border-white/35 overflow-hidden">
                    <div class="p-6 border-b border-gray-100/50 flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 bg-amber-500/10 border border-amber-500/20 rounded-lg text-amber-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Detail Kegiatan & Nilai Teknis</h2>
                                <p class="text-xs text-gray-500 mt-0.5 font-medium">Informasi rincian kompetensi teknis yang telah Anda inputkan</p>
                            </div>
                        </div>
                        <a href="{{ route('siswa.laporan.nilai') }}" 
                           class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-md shadow-amber-500/10 hover:scale-[1.02] transition flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Edit Nilai Teknis
                        </a>
                    </div>

                    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Side: Table of Kegiatan -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm bg-white/40">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            <th class="p-4 w-12 text-center">No</th>
                                            <th class="p-4">Nama Kegiatan/Materi Kompetensi</th>
                                            <th class="p-4 w-28 text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-sm font-semibold text-gray-700">
                                        <tr class="hover:bg-gray-50/30 transition">
                                            <td class="p-4 text-center text-gray-400">1</td>
                                            <td class="p-4 text-gray-800 font-bold">{{ $nilai->kegiatan_1 }}</td>
                                            <td class="p-4 text-center text-amber-600 font-black text-base">{{ number_format($nilai->nilai_1, 2) }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/30 transition">
                                            <td class="p-4 text-center text-gray-400">2</td>
                                            <td class="p-4 text-gray-800 font-bold">{{ $nilai->kegiatan_2 }}</td>
                                            <td class="p-4 text-center text-amber-600 font-black text-base">{{ number_format($nilai->nilai_2, 2) }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/30 transition">
                                            <td class="p-4 text-center text-gray-400">3</td>
                                            <td class="p-4 text-gray-800 font-bold">{{ $nilai->kegiatan_3 }}</td>
                                            <td class="p-4 text-center text-amber-600 font-black text-base">{{ number_format($nilai->nilai_3, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if($nilai->catatan_penguji)
                                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
                                    <h4 class="text-xs font-black text-blue-800 uppercase tracking-wider mb-1">💬 Catatan Penguji</h4>
                                    <p class="text-xs text-blue-700 leading-relaxed font-semibold">{{ $nilai->catatan_penguji }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Right Side: Photo Proof Upload -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-wider">🖼️ Bukti Lembar Penilaian</h4>
                            @if($nilai->foto_nilai)
                                <div class="group relative rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50 cursor-pointer" onclick="openPhotoModal('{{ Storage::url($nilai->foto_nilai) }}')">
                                    <img src="{{ Storage::url($nilai->foto_nilai) }}" alt="Lembar Penilaian" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center text-white text-xs font-bold gap-1.5">
                                        <span>🔍</span> Lihat Ukuran Penuh
                                    </div>
                                </div>
                            @else
                                <div class="h-48 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center text-gray-400 bg-white/40">
                                    <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs font-bold">Belum mengupload foto bukti</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16 border-2 border-dashed border-gray-200 rounded-3xl bg-white/50">
                    <div class="w-20 h-20 mx-auto mb-4 bg-amber-50 rounded-full flex items-center justify-center text-3xl">
                        📊
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Belum Ada Riwayat Input Nilai</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto font-semibold">Anda belum menginputkan rincian kegiatan kompetensi teknis beserta nilai magang Anda.</p>
                    <a href="{{ route('siswa.laporan.nilai') }}" 
                       class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-md shadow-amber-500/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Input Nilai Teknis Sekarang
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Modal Preview Photo -->
<div id="photoModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm" onclick="closePhotoModal()">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] p-4 shadow-2xl border border-gray-100 flex flex-col transform transition-all scale-95 opacity-0 duration-300 relative" id="photoModalContent" onclick="event.stopPropagation()">
        <button type="button" onclick="closePhotoModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition bg-gray-100 hover:bg-gray-200 p-2 rounded-full z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="flex-1 w-full bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 flex items-center justify-center p-2 min-h-0">
            <img id="previewImg" src="" class="max-w-full max-h-[75vh] object-contain rounded-xl">
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoModal = document.getElementById('photoModal');
        if (photoModal) {
            document.body.appendChild(photoModal);
        }
    });

    function openPhotoModal(url) {
        const modal = document.getElementById('photoModal');
        const modalContent = document.getElementById('photoModalContent');
        const img = document.getElementById('previewImg');
        
        img.src = url;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        const modalContent = document.getElementById('photoModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
