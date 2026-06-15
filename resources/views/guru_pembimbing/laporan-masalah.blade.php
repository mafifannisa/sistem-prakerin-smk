@extends('layouts.guru_pembimbing')

@section('title', 'Laporkan Masalah Magang')
@section('header_breadcrumb', 'Laporan Masalah')
@section('header_title', 'LAPORKAN PERMASALAHAN')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Laporan Kendala Magang</h2>
            <p class="text-xs text-gray-500 mt-1">Gunakan formulir ini untuk melaporkan masalah atau kendala yang dialami siswa bimbingan Anda di tempat magang.</p>
        </div>
        <button onclick="openModal()" class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-650 hover:from-red-600 hover:to-red-700 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Laporkan Masalah Baru
        </button>
    </div>

    <!-- Issues Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / Perusahaan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Masalah / Kendala</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tanggal Dilaporkan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($issues as $index => $issue)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $issues->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $issue->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $issue->industri->nama_industri ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-md whitespace-normal">
                                <div class="font-semibold text-red-700">{{ $issue->permasalahan }}</div>
                                @if($issue->solusi)
                                    <div class="mt-2 text-xs text-emerald-800 bg-emerald-50 border border-emerald-150 p-2 rounded-lg">
                                        <strong>Solusi Kajur:</strong> {{ $issue->solusi }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $issue->tanggal_lapor ? \Carbon\Carbon::parse($issue->tanggal_lapor)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($issue->status == 'selesai') bg-green-100 text-green-700
                                    @elseif($issue->status == 'ditinjau') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ strtoupper($issue->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada laporan masalah yang Anda kirimkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($issues->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

@section('modals')
<!-- Modal Form Laporan Masalah -->
<div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 font-display">Laporkan Kendala Siswa</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('guru_pembimbing.laporan-masalah.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Siswa Bermasalah *</label>
                <select name="penempatan_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Pilih Siswa...</option>
                    @foreach($bimbingans as $bim)
                        <option value="{{ $bim->id }}">{{ $bim->siswa->nama }} ({{ $bim->industri->nama_industri ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Permasalahan / Kendala *</label>
                <textarea name="permasalahan" required rows="5" placeholder="Tuliskan secara detail permasalahan yang dihadapi siswa, misalnya: tidak disiplin, dipindahkan sepihak oleh industri, sakit, dsb." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-650 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-xl shadow-md transition">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('reportModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

document.getElementById('reportModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
