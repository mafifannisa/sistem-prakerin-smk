@extends('layouts.guru_pembimbing')

@section('title', 'Rekap Absensi Siswa')
@section('header_breadcrumb', 'Rekap Absen')
@section('header_title', 'ABSENSI SISWA BIMBINGAN')

@section('content')
<div class="space-y-6">
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('guru_pembimbing.rekap-absen') }}" method="GET" class="flex items-center gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama siswa..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 outline-none">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition">
                Filter
            </button>
            <a href="{{ route('guru_pembimbing.rekap-absen') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-green-600 uppercase">Hadir</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-blue-600 uppercase">Sakit</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-yellow-600 uppercase">Izin</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-red-600 uppercase">Alpha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($siswas as $index => $siswa)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $siswas->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">
                                {{ $siswa->kelas->nama_kelas ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center font-extrabold text-green-600 text-base">
                                {{ $siswa->hadir }}
                            </td>
                            <td class="px-6 py-4 text-center font-extrabold text-blue-600 text-base">
                                {{ $siswa->sakit }}
                            </td>
                            <td class="px-6 py-4 text-center font-extrabold text-yellow-600 text-base">
                                {{ $siswa->izin }}
                            </td>
                            <td class="px-6 py-4 text-center font-extrabold text-red-600 text-base">
                                {{ $siswa->alpha }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada rekap data absensi siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($siswas->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
