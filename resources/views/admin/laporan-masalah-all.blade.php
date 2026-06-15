@extends('layouts.admin')

@section('title', 'Laporan Masalah Magang')

@section('header_breadcrumb', 'Laporan Masalah')
@section('header_title', 'LAPORAN MASALAH MAGANG')

@section('content')
<div class="p-0">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <form action="{{ route('admin.laporan-masalah-all') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <!-- Custom Dropdown Status -->
                <div class="relative inline-block text-left" id="filterStatusContainer">
                    <input type="hidden" name="status" id="filterStatusInput" value="{{ request('status', 'all') }}">
                    <button type="button" id="filterStatusBtn" onclick="toggleFilterStatus()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition inline-flex items-center justify-between gap-2 min-w-[150px]">
                        <span id="filterStatusLabel">
                            @if(request('status') == 'pending')
                                Pending
                            @elseif(request('status') == 'ditinjau')
                                Ditinjau
                            @elseif(request('status') == 'selesai')
                                Selesai
                            @else
                                Semua Status
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="filterStatusChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div id="filterStatusMenu" class="hidden absolute left-0 mt-1.5 w-48 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-150 py-1.5 z-[99]">
                        <button type="button" onclick="selectFilterStatus('all', 'Semua Status')" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            Semua Status
                        </button>
                        <button type="button" onclick="selectFilterStatus('pending', 'Pending')" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            Pending
                        </button>
                        <button type="button" onclick="selectFilterStatus('ditinjau', 'Ditinjau')" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Ditinjau
                        </button>
                        <button type="button" onclick="selectFilterStatus('selesai', 'Selesai')" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Selesai
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.laporan-masalah-all') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pelapor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Siswa / Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Industri / Mitra</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Permasalahan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporans as $index => $laporan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $laporans->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 text-sm">{{ $laporan->pelapor->nama_lengkap ?? 'Siswa' }}</p>
                                <p class="text-xs text-gray-500">Role: {{ ucwords(str_replace('_', ' ', $laporan->pelapor->role ?? 'Siswa')) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800 text-sm">{{ $laporan->siswa->nama }}</p>
                                <p class="text-xs text-gray-500">NISN: {{ $laporan->siswa->nisn }}</p>
                                <p class="text-xs text-blue-600 font-semibold">{{ $laporan->siswa->jurusan->nama_jurusan ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $laporan->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-sm whitespace-normal">
                                <p class="font-medium">{{ $laporan->permasalahan }}</p>
                                @if($laporan->solusi)
                                    <p class="text-xs text-green-700 bg-green-50 p-2 rounded-lg border border-green-150 mt-1">
                                        <strong>Solusi Kajur:</strong> {{ $laporan->solusi }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $laporan->tanggal_lapor ? \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($laporan->status == 'selesai') bg-green-100 text-green-700
                                    @elseif($laporan->status == 'ditinjau') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ strtoupper($laporan->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada laporan masalah</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($laporans->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function toggleFilterStatus() {
    const menu = document.getElementById('filterStatusMenu');
    const chevron = document.getElementById('filterStatusChevron');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        chevron.classList.add('rotate-180');
    } else {
        menu.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
}

function selectFilterStatus(val, name) {
    document.getElementById('filterStatusInput').value = val;
    document.getElementById('filterStatusLabel').textContent = name;
    document.getElementById('filterStatusMenu').classList.add('hidden');
    document.getElementById('filterStatusChevron').classList.remove('rotate-180');
}

document.addEventListener('click', function(event) {
    const container = document.getElementById('filterStatusContainer');
    const menu = document.getElementById('filterStatusMenu');
    const chevron = document.getElementById('filterStatusChevron');
    if (container && !container.contains(event.target)) {
        menu.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
});
</script>
@endsection
