@extends('layouts.guru_pembimbing')

@section('title', 'Siswa Bimbingan')
@section('header_breadcrumb', 'Siswa Bimbingan')
@section('header_title', 'SISWA BIMBINGAN AKTIF')

@section('content')
<div class="space-y-6">
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Siswa Bimbingan</h2>
            <p class="text-xs text-gray-500 mt-1">Daftar siswa magang yang berada di bawah bimbingan Anda beserta kontak wali murid.</p>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Kelas / Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Perusahaan Mitra</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Kontak Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama & Kontak Wali</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Alamat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($placements as $index => $placement)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $placements->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $placement->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $placement->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-700">{{ $placement->siswa->kelas->nama_kelas ?? '-' }}</div>
                                <div class="text-xs text-emerald-600 font-semibold">{{ $placement->siswa->jurusan->nama_jurusan ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $placement->industri->nama_industri }}
                                <div class="text-xs font-normal text-gray-500">Posisi: {{ $placement->posisi_magang }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $placement->siswa->email }}</div>
                                <div class="text-xs text-gray-400">WA: {{ $placement->siswa->no_wa }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-700">{{ $placement->siswa->nama_wali ?? '-' }}</div>
                                <div class="text-xs text-gray-400">WA: {{ $placement->siswa->no_wa_wali ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $placement->siswa->alamat }}">
                                {{ $placement->siswa->alamat ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Anda belum ditugaskan untuk membimbing siswa magang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($placements->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $placements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
