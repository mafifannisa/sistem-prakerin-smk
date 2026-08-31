@extends('layouts.guru_pembimbing')

@section('title', 'Verifikasi Koreksi Presensi')
@section('header_breadcrumb', 'Koreksi Presensi')
@section('header_title', 'VERIFIKASI KOREKSI PRESENSI DARURAT')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <form action="{{ route('guru_pembimbing.rekap-koreksi') }}" method="GET" class="flex items-center gap-3 flex-wrap">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu Verifikasi (Pending)</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>
            
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition text-sm">
                Filter
            </button>
            <a href="{{ route('guru_pembimbing.rekap-koreksi') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition text-sm">
                Reset
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa / Industri</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tanggal & Jam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Jenis & Alasan Kendala</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Bukti Lampiran</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($koreksis as $index => $koreksi)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $koreksis->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $koreksi->siswa?->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $koreksi->siswa?->kelas?->nama_kelas }} • NISN: {{ $koreksi->siswa?->nisn }}</div>
                                <div class="text-xs font-medium text-emerald-700 mt-1">🏢 {{ $koreksi->penempatanMagang?->industri?->nama_industri }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">📅 {{ $koreksi->tanggal->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">⏰ Pukul: {{ substr($koreksi->jam_diajukan, 0, 5) }} WIB</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-purple-100 text-purple-700 capitalize">
                                    {{ str_replace('_', ' & ', $koreksi->jenis_koreksi) }}
                                </span>
                                <p class="text-xs text-gray-600 mt-1 whitespace-normal leading-relaxed">{{ $koreksi->alasan }}</p>
                                @if($koreksi->catatan_pembimbing)
                                    <div class="text-xs text-amber-700 bg-amber-50 p-1.5 rounded mt-1">
                                        <strong>Catatan Anda:</strong> {{ $koreksi->catatan_pembimbing }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($koreksi->bukti_lampiran)
                                    <a href="{{ asset('storage/' . $koreksi->bukti_lampiran) }}" target="_blank" 
                                       class="px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold inline-flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada lampiran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($koreksi->status === 'disetujui')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">✅ Disetujui</span>
                                @elseif($koreksi->status === 'ditolak')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">❌ Ditolak</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">⏳ Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($koreksi->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Form Approve -->
                                        <form action="{{ route('guru_pembimbing.rekap-koreksi.verify', $koreksi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI presensi darurat siswa ini?');">
                                            @csrf
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Form Reject -->
                                        <form action="{{ route('guru_pembimbing.rekap-koreksi.verify', $koreksi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK tiket koreksi ini?');">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Selesai diverifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-base font-semibold text-gray-600">Belum ada pengajuan koreksi presensi darurat dari siswa bimbingan Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($koreksis->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $koreksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
