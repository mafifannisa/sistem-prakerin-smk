@extends('layouts.pimpinan')

@section('title', 'Pusat Laporan')

@section('content')
<!-- Top Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pusat Laporan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola rekapitulasi data program Prakerin</p>
        </div>
        {{-- Filter Tahun & Semester --}}
        <form action="{{ route('pimpinan.laporan') }}" method="GET" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
            <select name="tahun_ajaran" onchange="this.form.submit()" class="bg-transparent text-xs font-bold text-gray-700 outline-none px-2 cursor-pointer">
                @foreach($listTahun as $th)
                    <option value="{{ $th }}" {{ $filterTahun == $th ? 'selected' : '' }}>TA {{ $th }}</option>
                @endforeach
            </select>
            <div class="h-4 w-px bg-gray-300"></div>
            <select name="semester" onchange="this.form.submit()" class="bg-transparent text-xs font-bold text-gray-700 outline-none px-2 cursor-pointer">
                <option value="Ganjil" {{ $filterSemester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                <option value="Genap" {{ $filterSemester == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
            </select>
        </form>
    </div>
</header>

<!-- Main Content -->
<div class="p-8">
    
    <!-- Kartu Aksi Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- 1. Rekap Industri Mitra -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Rekap Industri Mitra</h3>
                    <p class="text-xs text-gray-500">Total: {{ $industris->count() }} Perusahaan</p>
                </div>
            </div>
            <button onclick="toggleModal('modalIndustri')" class="w-full py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-lg hover:bg-indigo-100 transition text-sm">
                Lihat Detail
            </button>
        </div>

        <!-- 2. Rekap Siswa Magang -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Rekap Siswa Magang</h3>
                    <p class="text-xs text-gray-500">Aktif: {{ $siswaMagang->count() }} Siswa</p>
                </div>
            </div>
            <button onclick="toggleModal('modalSiswa')" class="w-full py-2 bg-blue-50 text-blue-700 font-semibold rounded-lg hover:bg-blue-100 transition text-sm">
                Lihat Detail
            </button>
        </div>

        <!-- 3. Rekap Laporan Magang -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Rekap Laporan Magang</h3>
                    <p class="text-xs text-gray-500">{{ $laporanFinal->count() }} Laporan Disetujui</p>
                </div>
            </div>
            <button onclick="toggleModal('modalLaporan')" class="w-full py-2 bg-green-50 text-green-700 font-semibold rounded-lg hover:bg-green-100 transition text-sm">
                Lihat Laporan Final
            </button>
        </div>
    </div>

    <!-- Tabel Log Aktivitas -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Log Aktivitas Terbaru</h3>
            <span class="text-xs text-gray-500">Semester {{ $filterSemester }} TA {{ $filterTahun }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kegiatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logAktivitas as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $log->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($log->siswa->nama, 0, 2) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $log->siswa->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $log->industri->nama_industri ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Pengajuan Magang</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($log->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($log->status == 'approved' || $log->status == 'ongoing') bg-green-100 text-green-700
                                @elseif($log->status == 'rejected') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada aktivitas terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ================= MODALS ================= -->

<!-- Modal 1: Industri Mitra -->
<div id="modalIndustri" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">🏭 Daftar Industri Mitra</h3>
            <button onclick="toggleModal('modalIndustri')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">Nama Perusahaan</th>
                        <th class="px-4 py-3">Alamat</th>
                        <th class="px-4 py-3">Kontak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($industris as $ind)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $ind->nama_industri }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $ind->alamat }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $ind->no_telp ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-4 text-gray-500">Belum ada data industri</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 2: Siswa Magang -->
<div id="modalSiswa" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">👨‍🎓 Daftar Siswa Magang Aktif</h3>
            <button onclick="toggleModal('modalSiswa')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Perusahaan</th>
                        <th class="px-4 py-3">Tanggal Mulai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswaMagang as $siswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $siswa->siswa->nama }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $siswa->industri->nama_industri ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $siswa->tanggal_mulai ? $siswa->tanggal_mulai->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-4 text-gray-500">Belum ada siswa yang aktif magang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 3: Laporan Final -->
<div id="modalLaporan" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">📚 Daftar Laporan Magang Final</h3>
            <button onclick="toggleModal('modalLaporan')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div class="space-y-4">
                @forelse($laporanFinal as $laporan)
                <div class="border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 hover:shadow-md transition">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs">
                            {{ substr($laporan->siswa->nama, 0, 2) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $laporan->siswa->nama }}</h4>
                            <p class="text-xs text-gray-500">{{ $laporan->siswa->jurusan->nama_jurusan ?? '-' }} • {{ $laporan->penempatanMagang->industri->nama_industri ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex-1 px-4 py-2 bg-gray-50 rounded-lg text-sm text-gray-600 truncate w-full md:w-auto">
                        {{ $laporan->judul_laporan }}
                    </div>
                    <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition whitespace-nowrap">
                        📄 Buka PDF
                    </a>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg font-semibold">Belum ada laporan final</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
    }
</script>
@endsection