@extends('layouts.admin')

@section('title', 'Tracking Surat')

@section('header_breadcrumb', 'Cetak Surat')
@section('header_title', 'TRACKING SURAT')

@section('content')
<div class="p-0">
    
    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="relative flex-1">
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama siswa atau nomor surat..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="suratTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nomor Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal Kirim</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($surats as $index => $surat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surats->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $surat->nomor_surat }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs uppercase">
                                        {{ substr($surat->penempatanMagang->siswa->nama ?? 'S', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $surat->penempatanMagang->siswa->nama ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $surat->penempatanMagang->industri->nama_industri ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">
                                {{ str_replace('_', ' ', $surat->jenis_surat) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($surat->status == 'approved' || $surat->status == 'sent')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        {{ $surat->status == 'sent' ? 'Terkirim' : 'Disetujui' }}
                                    </span>
                                @elseif($surat->status == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">{{ ucfirst($surat->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $surat->tanggal_kirim ? \Carbon\Carbon::parse($surat->tanggal_kirim)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" 
                                   class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold">Belum ada riwayat surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($surats->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $surats->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function searchTable() {
    let input = document.getElementById('searchInput');
    let filter = input.value.toUpperCase();
    let table = document.getElementById('suratTable');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let tdSiswa = tr[i].getElementsByTagName('td')[2];
        let tdNomor = tr[i].getElementsByTagName('td')[1];
        if (tdSiswa || tdNomor) {
            let txtSiswa = tdSiswa ? (tdSiswa.textContent || tdSiswa.innerText) : "";
            let txtNomor = tdNomor ? (tdNomor.textContent || tdNomor.innerText) : "";
            
            if (txtSiswa.toUpperCase().indexOf(filter) > -1 || txtNomor.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>
@endsection