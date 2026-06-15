@extends('layouts.kepala_jurusan')

@section('title', 'Ujian Magang & Catatan Kelulusan')
@section('header_breadcrumb', 'Ujian Magang')
@section('header_title', 'UJIAN LAPORAN AKHIR')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('kepala_jurusan.ujian-magang') }}" method="GET" class="flex items-center gap-3">
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
            <a href="{{ route('kepala_jurusan.ujian-magang') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Placements Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Mitra Industri</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Guru Penguji</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Nilai Penguji</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Catatan Penguji</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($placements as $index => $placement)
                        @php
                            $nilai = $placement->nilai;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $placements->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $placement->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $placement->siswa->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">
                                {{ $placement->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($placement->guruPenguji)
                                    <span class="font-semibold text-gray-800">{{ $placement->guruPenguji->nama_lengkap }}</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-100 rounded-lg text-xs font-semibold">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($nilai && $nilai->nilai_penguji !== null)
                                    <span class="text-emerald-700 font-black text-base">{{ round($nilai->nilai_penguji, 1) }}</span>
                                @else
                                    <span class="text-yellow-600 font-semibold text-xs">Belum Diinput</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $nilai->catatan_penguji ?? '' }}">
                                {{ $nilai->catatan_penguji ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openAssignPengujiModal({{ $placement->id }}, '{{ addslashes($placement->siswa->nama) }}', '{{ $placement->guru_penguji_id }}')" class="px-3.5 py-2 bg-white hover:bg-gray-50 text-emerald-700 border border-emerald-600 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        Tugaskan Penguji
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data penempatan aktif.</p>
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

@section('modals')
<!-- Modal Tugaskan Guru Penguji -->
<div id="assignPengujiModal" class="hidden fixed inset-0 w-screen h-screen bg-black/60 z-[9999] flex items-center justify-center backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 font-display">Tugaskan Guru Penguji</h3>
            <button onclick="closeAssignPengujiModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="assignPengujiForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Siswa</label>
                <p id="assignSiswaName" class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"></p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Guru Penguji *</label>
                <select name="guru_penguji_id" id="modalGuruPengujiId" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Pilih Guru Penguji --</option>
                    @foreach($gurusPenguji as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
                <button type="button" onclick="closeAssignPengujiModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold rounded-xl shadow-md transition">
                    Tugaskan Penguji
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignPengujiModal = document.getElementById('assignPengujiModal');
    if (assignPengujiModal) {
        document.body.appendChild(assignPengujiModal);
    }
});

function openAssignPengujiModal(id, siswaNama, currentPengujiId) {
    document.getElementById('assignSiswaName').textContent = siswaNama;
    document.getElementById('modalGuruPengujiId').value = currentPengujiId || '';
    document.getElementById('assignPengujiForm').action = "{{ route('kepala_jurusan.ujian-magang.assign-penguji', ':id') }}".replace(':id', id);
    document.getElementById('assignPengujiModal').classList.remove('hidden');
}

function closeAssignPengujiModal() {
    document.getElementById('assignPengujiModal').classList.add('hidden');
}

// Close modals when clicking background
document.getElementById('assignPengujiModal').addEventListener('click', function(e) {
    if (e.target === this) closeAssignPengujiModal();
});
</script>
</script>
@endsection
