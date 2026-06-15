@extends('layouts.admin')

@section('title', 'Data Magang Semua')

@section('header_breadcrumb', 'Data Magang')
@section('header_title', 'SEMUA DATA MAGANG')

@section('content')
<div class="p-0">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <form action="{{ route('admin.data-magang-all') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama siswa..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.data-magang-all') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Industri Mitra</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Guru Pembimbing</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Periode Magang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($placements as $index => $placement)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $placements->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 text-sm">{{ $placement->siswa->nama }}</p>
                                <p class="text-xs text-gray-500">NISN: {{ $placement->siswa->nisn }} | Kelas: {{ $placement->siswa->kelas->nama_kelas ?? '-' }}</p>
                                <p class="text-xs text-blue-600 font-semibold">{{ $placement->siswa->jurusan->nama_jurusan ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800 text-sm">{{ $placement->industri->nama_industri }}</p>
                                <p class="text-xs text-gray-500">Posisi: {{ $placement->posisi_magang ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $placement->guruPembimbing->nama_lengkap ?? 'Belum Ditentukan' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex flex-col">
                                    <span>{{ $placement->tanggal_mulai ? \Carbon\Carbon::parse($placement->tanggal_mulai)->format('d M Y') : '-' }} s/d</span>
                                    <span>{{ $placement->tanggal_selesai ? \Carbon\Carbon::parse($placement->tanggal_selesai)->format('d M Y') : '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($placement->status == 'approved') bg-green-100 text-green-700
                                    @elseif($placement->status == 'ongoing') bg-blue-100 text-blue-700
                                    @elseif($placement->status == 'completed') bg-teal-100 text-teal-700
                                    @elseif($placement->status == 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($placement->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data penempatan magang</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($placements->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $placements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
