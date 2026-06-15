@extends('layouts.admin')

@section('title', 'Verifikasi Jurnal Harian')

@section('header_breadcrumb', 'Verifikasi')
@section('header_title', 'VERIFIKASI JURNAL HARIAN')

@section('content')
<div class="p-0">
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
            <a href="{{ route('admin.verifikasi.jurnal') }}" class="text-sm font-medium text-blue-600 hover:underline">← Lihat Semua Siswa</a>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        {{-- Filter & Search --}}
        <form method="GET" class="flex flex-col md:flex-row gap-4 mb-6 items-end">
            {{-- Simpan siswa_id jika ada --}}
            @if(request('siswa_id'))
                <input type="hidden" name="siswa_id" value="{{ request('siswa_id') }}">
            @endif

            {{-- Filter Status + Reset --}}
            <div class="flex items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Status</label>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" 
                                class="appearance-none w-44 pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Direvisi</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('admin.verifikasi.jurnal', request('siswa_id') ? ['siswa_id' => request('siswa_id')] : []) }}" 
                   class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-sm font-medium rounded-xl transition-colors">
                    Reset
                </a>
            </div>

            {{-- Pencarian (hanya jika tidak filter spesifik siswa) --}}
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
                        <th class="p-4 font-semibold">Tanggal & Waktu</th>
                        <th class="p-4 font-semibold w-1/3">Deskripsi Kegiatan</th>
                        <th class="p-4 font-semibold text-center">Foto Bukti</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jurnals as $j)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <p class="font-bold text-gray-800 text-sm">{{ $j->siswa->nama ?? 'Nama Tidak Ditemukan' }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ $j->penempatanMagang->industri->nama_industri ?? '-' }}</p>
                        </td>
                        <td class="p-4">
                            <p class="font-semibold text-sm text-gray-800">{{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">Minggu ke-{{ $j->minggu_ke }} • {{ $j->durasi_jam }} Jam Kerja</p>
                        </td>
                        <td class="p-4 text-sm text-gray-700">
                            <p class="line-clamp-3">{{ $j->kegiatan }}</p>
                        </td>
                        <td class="p-4 text-center">
                            @if($j->bukti_foto)
                                <a href="{{ asset('storage/' . $j->bukti_foto) }}" target="_blank" class="inline-block">
                                    <img src="{{ asset('storage/' . $j->bukti_foto) }}" alt="Foto" class="w-16 h-16 object-cover rounded-lg border border-gray-200 mx-auto hover:scale-110 transition shadow-sm">
                                </a>
                            @else
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">Tanpa Foto</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($j->status === 'disetujui') bg-green-100 text-green-700
                                @elseif($j->status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                @if($j->status === 'disetujui') Disetujui
                                @elseif($j->status === 'pending') Pending
                                @else Perlu Revisi
                                @endif
                            </span>
                            @if($j->catatan_pembimbing)
                                <p class="text-xs text-gray-500 mt-1 italic">"{{ $j->catatan_pembimbing }}"</p>
                            @endif
                        </td>
                        <td class="p-4 align-top text-center">
                            @if($j->status === 'pending')
                            <form action="{{ route('admin.verifikasi.jurnal.update', $j->id) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                <textarea name="catatan_pembimbing" rows="2" placeholder="Tulis catatan (opsional)..." 
                                          class="w-full text-xs p-2 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" name="status" value="disetujui" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 rounded-lg transition shadow-sm">
                                        Setujui
                                    </button>
                                    <button type="submit" name="status" value="ditolak" class="flex-1 bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 rounded-lg transition shadow-sm">
                                        Revisi
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
                                <p class="text-gray-500 font-medium">Tidak ada jurnal yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $jurnals->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection