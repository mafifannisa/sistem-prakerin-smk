@extends('layouts.admin')

@section('title', 'Verifikasi Laporan PKL')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Verifikasi Laporan PKL</h1>
        <p class="text-sm text-gray-500 mt-1">Periksa, beri catatan, dan setujui laporan PKL siswa. Riwayat tetap tercatat.</p>
    </div>
</header>

<div class="p-8">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Info siswa yang difilter --}}
    @if(isset($filteredSiswa))
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="font-semibold text-blue-800">{{ $filteredSiswa->nama }}</span>
                <span class="text-blue-600 text-sm">({{ $filteredSiswa->jurusan->kode_jurusan ?? '' }})</span>
            </div>
            <a href="{{ route('admin.verifikasi.laporan-pkl') }}" class="text-sm font-medium text-blue-600 hover:underline">← Lihat Semua Siswa</a>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        {{-- Filter & Search --}}
        <form method="GET" class="flex flex-col md:flex-row gap-4 mb-6 items-end">
            @if(request('siswa_id'))
                <input type="hidden" name="siswa_id" value="{{ request('siswa_id') }}">
            @endif

            <div class="flex items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Status</label>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" 
                                class="appearance-none w-44 pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="perlu_revisi" {{ request('status') == 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('admin.verifikasi.laporan-pkl', request('siswa_id') ? ['siswa_id' => request('siswa_id')] : []) }}" 
                   class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-sm font-medium rounded-xl transition-colors">
                    Reset
                </a>
            </div>

            @if(!request('siswa_id'))
            <div class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                       class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    Cari
                </button>
            </div>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-semibold">Siswa & Industri</th>
                        <th class="p-4 font-semibold">Judul Laporan</th>
                        <th class="p-4 font-semibold text-center">Abstrak</th>
                        <th class="p-4 font-semibold text-center">File</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporans as $l)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <p class="font-bold text-gray-800 text-sm">{{ $l->siswa->nama ?? 'Nama Tidak Ditemukan' }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ $l->penempatanMagang->industri->nama_industri ?? '-' }}</p>
                        </td>
                        <td class="p-4 text-sm text-gray-700 max-w-xs">
                            <p class="line-clamp-2">{{ $l->judul_laporan }}</p>
                        </td>
                        <td class="p-4 text-sm text-gray-600 text-center">
                            @if($l->abstrak)
                                <p class="line-clamp-2 text-xs">{{ $l->abstrak }}</p>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($l->file_path)
                                <a href="{{ Storage::url($l->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Lihat PDF
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($l->status === 'disetujui') bg-green-100 text-green-700
                                @elseif($l->status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                @if($l->status === 'disetujui') Disetujui
                                @elseif($l->status === 'pending') Pending
                                @else Perlu Revisi
                                @endif
                            </span>
                            @if($l->catatan_pembimbing)
                                <p class="text-xs text-gray-500 mt-1 italic">"{{ $l->catatan_pembimbing }}"</p>
                            @endif
                        </td>
                        <td class="p-4 align-top text-center">
                            @if($l->status === 'pending')
                            <form action="{{ route('admin.verifikasi.laporan-pkl.update', $l->id) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                <textarea name="catatan_pembimbing" rows="2" placeholder="Tulis catatan (opsional)..." 
                                          class="w-full text-xs p-2 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" name="status" value="disetujui" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 rounded-lg transition shadow-sm">
                                        ✅ Setujui
                                    </button>
                                    <button type="submit" name="status" value="perlu_revisi" class="flex-1 bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 rounded-lg transition shadow-sm">
                                        ❌ Revisi
                                    </button>
                                </div>
                            </form>
                            @else
                                <span class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg bg-gray-100 text-gray-400 border border-gray-200">
                                    Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-gray-500 font-medium">Tidak ada laporan PKL yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $laporans->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection