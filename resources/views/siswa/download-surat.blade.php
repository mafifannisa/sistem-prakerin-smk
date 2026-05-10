@extends('layouts.siswa')

@section('title', 'Download Surat')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Unduh Dokumen Administrasi</h1>
            <p class="text-sm text-gray-500 mt-1">Pastikan data profil Anda sudah lengkap sebelum mengunduh dokumen</p>
        </div>
        <div class="text-sm text-gray-600">{{ tanggal_indonesia() }}</div>
    </div>
</header>

<div class="p-8">
    <!-- Info Box -->
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-orange-800 mb-2">Informasi Penting</h3>
                <p class="text-sm text-orange-700">
                    Dokumen yang telah diunduh harus dicetak menggunakan kertas <strong>F4/Legal 80gr</strong> dan ditandatangani oleh pihak terkait sesuai instruksi di tiap surat.
                </p>
            </div>
        </div>
    </div>

    <!-- Daftar Surat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Dokumen</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Format</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($surats as $surat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        @if($surat['jenis'] === 'pengantar')
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        @elseif($surat['jenis'] === 'izin_ortu')
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $surat['nama'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $surat['deskripsi'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $surat['format'] }} ({{ $surat['ukuran'] }})
                            </td>
                            <td class="px-6 py-4">
                                @if($surat['status'] === 'tersedia')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        ● Tersedia
                                    </span>
                                @elseif($surat['status'] === 'belum_rilis')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        ! Perlu diproses
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                        ● Belum Rilis
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($surat['status'] === 'tersedia')
                                    @if($surat['jenis'] === 'pengantar')
                                        <a href="{{ route('siswa.download.pengantar') }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    @elseif($surat['jenis'] === 'izin_ortu')
                                        <a href="{{ route('siswa.download.izin') }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    @else
                                        <a href="{{ route('siswa.download.template') }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    @endif
                                @else
                                    <button disabled 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-300 text-gray-500 text-sm font-semibold rounded-lg cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Locked
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Dokumen Lainnya -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Dokumen Lainnya</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Buku Panduan Prakerin</h3>
                        <p class="text-sm text-gray-500">Panduan lengkap tata tertib</p>
                    </div>
                </div>
                <a href="{{ route('siswa.download.panduan') }}" class="w-10 h-10 bg-orange-100 hover:bg-orange-200 rounded-lg flex items-center justify-center transition">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection