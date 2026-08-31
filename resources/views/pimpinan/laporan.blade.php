@extends('layouts.pimpinan')

@section('title', 'Pusat Laporan')

@section('header_title', 'PUSAT LAPORAN')
@section('header_breadcrumb', 'Laporan')

@section('header_actions')
<form action="{{ route('pimpinan.laporan') }}" method="GET" class="flex items-center gap-2 bg-white/50 p-1.5 rounded-xl border border-gray-250">
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
@endsection

@section('content')
<div class="space-y-6">
    <!-- Kartu Aksi Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- 1. Rekap Industri Mitra -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center border border-orange-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">Rekap Industri Mitra</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Total: {{ $industris->count() }} Perusahaan</p>
                </div>
            </div>
            <button onclick="toggleModal('modalIndustri')" class="w-full py-2 bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold rounded-xl transition text-xs border border-orange-100 shadow-sm">
                Lihat Detail
            </button>
        </div>

        <!-- 2. Rekap Siswa Magang -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center border border-green-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">Rekap Siswa Magang</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Aktif: {{ $siswaMagang->count() }} Siswa</p>
                </div>
            </div>
            <button onclick="toggleModal('modalSiswa')" class="w-full py-2 bg-green-50 hover:bg-green-100 text-green-700 font-bold rounded-xl transition text-xs border border-green-100 shadow-sm">
                Lihat Detail
            </button>
        </div>

        <!-- 3. Rekap Laporan Magang -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">Rekap Laporan Magang</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $laporanFinal->count() }} Laporan Disetujui</p>
                </div>
            </div>
            <button onclick="toggleModal('modalLaporan')" class="w-full py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-xl transition text-xs border border-amber-100 shadow-sm">
                Lihat Laporan Final
            </button>
        </div>
    </div>

    <!-- Tabel Log Aktivitas -->
    <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-white/50 bg-white/30 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Log Aktivitas Terbaru</h3>
            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-xl">Semester {{ $filterSemester }} TA {{ $filterTahun }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/30 border-b border-gray-150">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Perusahaan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kegiatan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logAktivitas as $log)
                    <tr class="hover:bg-orange-50/20 transition">
                        <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $log->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-400 rounded-full flex items-center justify-center text-white font-extrabold text-[10px] uppercase shadow-sm">
                                    {{ substr($log->siswa->nama, 0, 2) }}
                                </div>
                                <span class="text-sm font-bold text-gray-800">{{ $log->siswa->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600 font-semibold">{{ $log->industri->nama_industri ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs text-gray-600 font-medium">Pengajuan Magang</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-md border 
                                @if($log->status == 'pending') bg-amber-50 text-amber-700 border-amber-100
                                @elseif($log->status == 'approved' || $log->status == 'ongoing') bg-green-50 text-green-700 border-green-100
                                @elseif($log->status == 'rejected') bg-red-50 text-red-750 border-red-100
                                @else bg-gray-50 text-gray-600 border-gray-100 @endif">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada aktivitas terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- ================= MODALS ================= -->

<!-- Modal 1: Industri Mitra -->
<div id="modalIndustri" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 overflow-y-auto">
    <div id="modalIndustriContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-150 flex flex-col max-h-[92vh] my-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-150 bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-800 tracking-tight">🏭 Daftar Industri Mitra</h3>
            <button onclick="toggleModal('modalIndustri')" class="text-gray-400 hover:text-gray-650 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition">✕</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Nama Perusahaan</th>
                        <th class="px-4 py-3">Alamat</th>
                        <th class="px-4 py-3">Kontak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($industris as $ind)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $ind->nama_industri }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600 font-medium">{{ $ind->alamat }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600 font-semibold">{{ $ind->no_telp ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-8 text-gray-400 font-medium">Belum ada data industri</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 2: Siswa Magang -->
<div id="modalSiswa" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 overflow-y-auto">
    <div id="modalSiswaContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-150 flex flex-col max-h-[92vh] my-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-150 bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-800 tracking-tight">👨‍🎓 Daftar Siswa Magang Aktif</h3>
            <button onclick="toggleModal('modalSiswa')" class="text-gray-400 hover:text-gray-650 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition">✕</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Perusahaan</th>
                        <th class="px-4 py-3">Tanggal Mulai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($siswaMagang as $siswa)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $siswa->siswa->nama }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600 font-semibold">{{ $siswa->industri->nama_industri ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600 font-semibold">{{ $siswa->tanggal_mulai ? $siswa->tanggal_mulai->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-8 text-gray-400 font-medium">Belum ada siswa yang aktif magang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 3: Laporan Final -->
<div id="modalLaporan" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 overflow-y-auto">
    <div id="modalLaporanContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-150 flex flex-col max-h-[92vh] my-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-150 bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-800 tracking-tight">📚 Daftar Laporan Magang Final</h3>
            <button onclick="toggleModal('modalLaporan')" class="text-gray-400 hover:text-gray-650 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition">✕</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div class="space-y-4">
                @forelse($laporanFinal as $laporan)
                <div class="border border-gray-150 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 hover:shadow-md transition bg-white/50 backdrop-blur-md">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-400 rounded-full flex items-center justify-center text-white font-extrabold text-xs uppercase shadow-sm">
                            {{ substr($laporan->siswa->nama, 0, 2) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $laporan->siswa->nama }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5">{{ $laporan->siswa->jurusan->nama_jurusan ?? '-' }} • {{ $laporan->penempatanMagang->industri->nama_industri ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex-1 px-4 py-2 bg-gray-50 rounded-xl text-xs font-semibold text-gray-600 truncate w-full md:w-auto">
                        {{ $laporan->judul_laporan }}
                    </div>
                    <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                        📄 Buka PDF
                    </a>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 font-medium">
                    <p>Belum ada laporan final</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        if (!modal) return;
        
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                if (content) {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        } else {
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 200);
        }
    }

    ['modalIndustri', 'modalSiswa', 'modalLaporan'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) toggleModal(id);
            });
        }
    });
</script>
@endsection