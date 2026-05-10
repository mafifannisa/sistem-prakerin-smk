@extends('layouts.admin')

@section('title', 'Data Surat & Verifikasi')

@section('content')
<!-- Top Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Surat & Verifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Verifikasi pengajuan siswa & download surat pengantar</p>
        </div>
        <div class="text-sm text-gray-600">
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="p-8">
    
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-sm font-semibold text-red-700">
                <p class="font-bold mb-1">Terjadi kesalahan pada file Excel:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Industri & Alamat</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Posisi</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Alasan</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuans as $index => $item)
                        @php
                            // Gunakan relasi suratKeluars yang sudah ada di model
                            $suratAda = $item->suratKeluars()
                                            ->where('jenis_surat', 'pengantar')
                                            ->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $pengajuans->firstItem() + $index }}</td>
                            
                            <!-- Siswa -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs uppercase flex-shrink-0">
                                        {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $item->siswa->nama ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Industri & Alamat -->
                            <td class="px-4 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $item->industri->nama_industri ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $item->industri->alamat ?? '-' }}
                                    </p>
                                    @if($item->industri->kelurahan || $item->industri->kecamatan)
                                        <p class="text-xs text-gray-500">
                                            Kel. {{ $item->industri->kelurahan ?? '' }}
                                            Kec. {{ $item->industri->kecamatan ?? '' }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500">
                                        {{ $item->industri->kota ?? '' }}, {{ $item->industri->provinsi ?? '' }}
                                    </p>
                                </div>
                            </td>
                            
                            <!-- Posisi -->
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-800 font-medium">
                                    {{ $item->posisi_magang ?? '-' }}
                                </span>
                            </td>
                            
                            <!-- Alasan -->
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $item->catatan_industri ?? 'Tidak ada catatan' }}">
                                    {{ $item->catatan_industri ?? '-' }}
                                </p>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-4 py-4">
                                @if($item->status == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Menunggu Admin</span>
                                @elseif($item->status == 'verified')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Menunggu Pimpinan</span>
                                @elseif($item->status == 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Selesai / Surat Jadi</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            
                            <!-- Tanggal -->
                            <td class="px-4 py-4 text-sm text-gray-600">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            
                            <!-- Aksi -->
                            <td class="px-4 py-4 text-center">
                                
                                <!-- 1. Jika Pending (Admin Action) -->
                                @if($item->status == 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.data.surat.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition">
                                                ✓ Setujui
                                            </button>
                                        </form>
                                        <!-- Form Tolak dengan Input Alasan -->
                                        <form action="{{ route('admin.data.surat.reject', $item->id) }}" method="POST" class="inline w-full">
                                            @csrf
                                            <textarea name="alasan_penolakan" rows="2" placeholder="Tulis alasan penolakan..." 
                                                    class="w-full text-xs border border-red-200 rounded-lg p-2 mb-2 focus:outline-none focus:ring-1 focus:ring-red-500 resize-none"></textarea>
                                            <button type="submit" class="w-full px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    </div>

                                <!-- 2. Jika Verified (Menunggu Pimpinan) -->
                                @elseif($item->status == 'verified')
                                    <span class="text-xs text-blue-500 font-semibold italic">Menunggu Persetujuan Kepala Sekolah...</span>

                                <!-- 3. Jika Approved (Surat Sudah Jadi) -->
                                @elseif($item->status == 'approved' && $suratAda)
                                    <a href="{{ route('admin.data.surat.download', $suratAda->id) }}" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                @elseif($item->status == 'approved' && !$suratAda)
                                    <span class="text-xs text-gray-400">Surat belum digenerate</span>
                                @else
                                    -
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-semibold">Belum ada data pengajuan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pengajuans->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection