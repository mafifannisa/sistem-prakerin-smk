@extends('layouts.kepala_jurusan')

@section('title', 'Laporan Masalah Magang')
@section('header_breadcrumb', 'Masalah Magang')
@section('header_title', 'LAPORAN MASALAH MAGANG')

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
            <h2 class="text-lg font-bold text-gray-800">Daftar Masalah Prakerin</h2>
            <p class="text-xs text-gray-500 mt-1">Pantau dan berikan solusi atas kendala/masalah yang dialami siswa atau dilaporkan guru pembimbing.</p>
        </div>
        
        <form action="{{ route('kepala_jurusan.laporan-masalah') }}" method="GET" class="flex items-center gap-3">
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 outline-none text-sm font-semibold bg-white">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="ditinjau" {{ request('status') == 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </form>
    </div>

    <!-- Problems Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Mitra Perusahaan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Pelapor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Permasalahan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Solusi / Penanganan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($laporans as $index => $issue)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $laporans->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $issue->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $issue->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">
                                {{ $issue->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">
                                {{ $issue->pelapor->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $issue->permasalahan }}">
                                {{ $issue->permasalahan }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $issue->solusi }}">
                                {{ $issue->solusi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                    @if($issue->status == 'selesai') bg-green-150 text-green-700 border border-green-200
                                    @elseif($issue->status == 'ditinjau') bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-red-50 text-red-700 border border-red-200 @endif">
                                    {{ strtoupper($issue->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openResolveModal({{ $issue->id }}, '{{ addslashes($issue->siswa->nama) }}', '{{ addslashes($issue->permasalahan) }}', '{{ addslashes($issue->solusi ?? '') }}', '{{ $issue->status }}', '{{ addslashes($issue->catatan_kajur ?? '') }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition border border-emerald-700">
                                    Tindak Lanjut
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada laporan masalah magang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($laporans->hasPages())
            <div class="p-6 border-t border-gray-100/60 bg-white/50">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tindak Lanjut Masalah -->
<div id="resolveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 font-display">Tindak Lanjut & Resolusi Masalah</h3>
            <button onclick="closeResolveModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="resolveForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Siswa</label>
                <p id="modalSiswaName" class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi Masalah</label>
                <p id="modalProblemDesc" class="text-sm text-gray-700 bg-red-50/50 px-3 py-2 rounded-lg border border-red-100/60 leading-relaxed"></p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Solusi / Penanganan *</label>
                <textarea name="solusi" id="modalSolusi" rows="3" required placeholder="Jelaskan tindakan yang diambil untuk menyelesaikan masalah ini..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Kajur (Opsional)</label>
                <textarea name="catatan_kajur" id="modalCatatanKajur" rows="2" placeholder="Catatan internal tambahan..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status Penanganan *</label>
                <select name="status" id="modalStatus" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                    <option value="ditinjau">Ditinjau (Sedang Diproses)</option>
                    <option value="selesai">Selesai (Masalah Teratasi)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
                <button type="button" onclick="closeResolveModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-600 hover:from-yellow-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openResolveModal(id, siswaNama, masalah, solusi, status, catatan) {
    document.getElementById('modalSiswaName').textContent = siswaNama;
    document.getElementById('modalProblemDesc').textContent = masalah;
    document.getElementById('modalSolusi').value = solusi;
    document.getElementById('modalCatatanKajur').value = catatan;
    document.getElementById('modalStatus').value = status === 'pending' ? 'ditinjau' : status;
    document.getElementById('resolveForm').action = "{{ route('kepala_jurusan.laporan-masalah.resolve', ':id') }}".replace(':id', id);
    document.getElementById('resolveModal').classList.remove('hidden');
}

function closeResolveModal() {
    document.getElementById('resolveModal').classList.add('hidden');
}

document.getElementById('resolveModal').addEventListener('click', function(e) {
    if (e.target === this) closeResolveModal();
});
</script>
@endsection
