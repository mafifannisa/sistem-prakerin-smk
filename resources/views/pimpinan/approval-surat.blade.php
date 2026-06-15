@extends('layouts.pimpinan')

@section('title', 'Approval Surat')

@section('header_title', 'PERSETUJUAN SURAT')
@section('header_breadcrumb', 'Approval')

@section('header_actions')
<form action="{{ route('pimpinan.approval.surat') }}" method="GET" class="flex items-center gap-2 bg-white/50 p-1.5 rounded-xl border border-gray-250">
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
    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-green-50/50 backdrop-blur-md border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold text-green-700">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50/50 backdrop-blur-md border border-red-200 rounded-2xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div onclick="showSection('pending')" class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl p-6 shadow-sm cursor-pointer hover:shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Menunggu Persetujuan</p>
                    <p class="text-3xl font-black text-amber-600 leading-none">{{ $stats['total_pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div onclick="showSection('approved')" class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl p-6 shadow-sm cursor-pointer hover:shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Disetujui</p>
                    <p class="text-3xl font-black text-green-600 leading-none">{{ $stats['total_approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100 shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div onclick="showSection('rejected')" class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl p-6 shadow-sm cursor-pointer hover:shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Ditolak</p>
                    <p class="text-3xl font-black text-red-650 leading-none">{{ $stats['total_rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center border border-red-100 shrink-0">
                    <svg class="w-6 h-6 text-red-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION PENDING --}}
    <div id="section-pending" class="section-content">
        <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-white/50 bg-white/30">
                <h2 class="text-lg font-bold text-gray-800">📋 Pengajuan Menunggu Persetujuan</h2>
                <p class="text-xs text-gray-500 font-medium">Semester {{ $filterSemester }} TA {{ $filterTahun }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/30 border-b border-gray-150">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Industri Tujuan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengajuans as $pengajuan)
                            <tr class="hover:bg-orange-50/20 transition">
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $pengajuan->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-orange-500 to-amber-400 rounded-full flex items-center justify-center text-white font-extrabold text-xs uppercase shadow-sm shrink-0">
                                            {{ substr($pengajuan->siswa->nama ?? 'S', 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-800 text-sm truncate">{{ $pengajuan->siswa->nama ?? 'Siswa' }}</p>
                                            <p class="text-[10px] text-gray-450 font-medium">NISN: {{ $pengajuan->siswa->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 font-semibold truncate max-w-[150px]">{{ $pengajuan->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-bold truncate max-w-[150px]">{{ $pengajuan->industri->nama_industri ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $pengajuan->posisi_magang ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openModal({{ $pengajuan->id }}, '{{ addslashes($pengajuan->siswa->nama) }}', '{{ addslashes($pengajuan->siswa->jurusan->nama_jurusan ?? '-') }}', '{{ addslashes($pengajuan->industri->nama_industri ?? '-') }}', '{{ addslashes($pengajuan->posisi_magang ?? '-') }}', '{{ $pengajuan->created_at->format('d M Y') }}')" 
                                            class="px-4 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200">
                                        Preview & Approve
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">Tidak ada pengajuan yang menunggu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pengajuans->hasPages())
                <div class="p-6 border-t border-white/50">
                    {{ $pengajuans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION APPROVED --}}
    <div id="section-approved" class="section-content hidden">
        <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-white/50 bg-white/30 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">✅ History Pengajuan Disetujui</h2>
                    <p class="text-xs text-gray-500 font-medium">Semester {{ $filterSemester }} TA {{ $filterTahun }}</p>
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-xl">Total: {{ $stats['total_approved'] }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/30 border-b border-gray-150">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Industri Tujuan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($approved as $item)
                            <tr class="hover:bg-orange-50/20 transition">
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-400 rounded-full flex items-center justify-center text-white font-extrabold text-xs uppercase shadow-sm shrink-0">
                                            {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-800 text-sm truncate">{{ $item->siswa->nama ?? 'Siswa' }}</p>
                                            <p class="text-[10px] text-gray-450 font-medium">NISN: {{ $item->siswa->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 font-semibold truncate max-w-[150px]">{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-bold truncate max-w-[150px]">{{ $item->industri->nama_industri ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $item->posisi_magang ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-50 text-green-700 text-[10px] font-bold uppercase rounded-md border border-green-100">Disetujui</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada pengajuan yang disetujui.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SECTION REJECTED --}}
    <div id="section-rejected" class="section-content hidden">
        <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-white/50 bg-white/30 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">❌ History Pengajuan Ditolak</h2>
                    <p class="text-xs text-gray-500 font-medium">Semester {{ $filterSemester }} TA {{ $filterTahun }}</p>
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-xl">Total: {{ $stats['total_rejected'] }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/30 border-b border-gray-150">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Industri Tujuan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rejected as $item)
                            <tr class="hover:bg-orange-50/20 transition">
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-red-500 to-rose-400 rounded-full flex items-center justify-center text-white font-extrabold text-xs uppercase shadow-sm shrink-0">
                                            {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-800 text-sm truncate">{{ $item->siswa->nama ?? 'Siswa' }}</p>
                                            <p class="text-[10px] text-gray-450 font-medium">NISN: {{ $item->siswa->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 font-semibold truncate max-w-[150px]">{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-bold truncate max-w-[150px]">{{ $item->industri->nama_industri ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $item->posisi_magang ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-red-50 text-red-700 text-[10px] font-bold uppercase rounded-md border border-red-100">Ditolak</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada pengajuan yang ditolak.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Preview --}}
<div id="previewModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto transform scale-95 transition-all duration-200 border border-white" id="modalContainer">
        <div class="flex items-center justify-between p-6 border-b border-gray-150 sticky top-0 bg-white/95 backdrop-blur-md z-10">
            <h3 class="text-lg font-bold text-gray-850">Pratinjau Pengajuan Surat</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-650 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-8">
            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 mb-6 bg-gray-50/50">
                <div class="bg-white p-8 shadow-[0_10px_30px_rgba(0,0,0,0.02)] rounded-xl border border-gray-100">
                    <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
                        <h2 class="text-2xl font-bold uppercase tracking-wider text-gray-900">SMK NEGERI 3 TUBAN</h2>
                        <p class="text-xs text-gray-550 mt-1">Jl. Doktor Wahidin Sudiro Husodo No. 123, Tuban, Jawa Timur</p>
                        <p class="text-xs text-gray-555">Telp: (0356) 123456 | Email: info@smkn3tuban.sch.id</p>
                    </div>
                    <div class="mb-6 space-y-4 text-sm text-gray-850 leading-relaxed">
                        <p>Nomor: <strong>421/SMK.3-TUBAN/XXXX/<span id="bulanSurat"></span>/<span id="tahunSurat"></span></strong></p>
                        <p>Hal: <strong>Permohonan Praktik Kerja Industri (Prakerin)</strong></p>
                        <div class="pt-2">
                            <p>Yth. Pimpinan <span class="font-bold" id="namaIndustri"></span></p>
                            <p>Di Tempat</p>
                        </div>
                        <p class="pt-2">Dengan hormat,</p>
                        <p>Melalui surat ini kami mengajukan permohonan agar siswa kami atas nama:</p>
                        <div class="ml-8 bg-gray-50 p-4 rounded-xl space-y-2 border border-gray-150">
                            <p><strong>Nama:</strong> <span id="namaSiswa"></span></p>
                            <p><strong>NISN:</strong> <span id="nisnSiswa"></span></p>
                            <p><strong>Jurusan:</strong> <span id="jurusanSiswa"></span></p>
                            <p><strong>Posisi:</strong> <span id="posisiMagang"></span></p>
                        </div>
                        <p>dapat melaksanakan Praktik Kerja Industri di perusahaan Bapak/Ibu pada periode <span id="periodeMagang"></span>.</p>
                        <p>Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
                    </div>
                    <div class="flex justify-end mt-8">
                        <div class="text-center">
                            <p class="text-xs text-gray-550">Tuban, <span id="tanggalSurat"></span></p>
                            <p class="text-sm font-bold text-gray-800 mt-1 mb-4">Kepala SMK Negeri 3 Tuban</p>
                            <div class="h-24 mb-2 flex items-center justify-center">
                                <img src="{{ asset('images/signature.png') }}" alt="Tanda Tangan" class="h-full object-contain" onerror="this.style.display='none'">
                            </div>
                            <p class="font-bold text-gray-800 text-sm">Drs. HERU SUSANTO, M.Pd</p>
                            <p class="text-[10px] text-gray-500 font-semibold tracking-wider uppercase">NIP. 19680101 199003 1 001</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-xs bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div><p class="text-gray-400 font-bold uppercase tracking-wider">Nama Siswa</p><p class="font-bold text-gray-800 text-sm mt-0.5" id="infoNamaSiswa"></p></div>
                <div><p class="text-gray-400 font-bold uppercase tracking-wider">Tujuan Industri</p><p class="font-bold text-gray-800 text-sm mt-0.5" id="infoIndustri"></p></div>
                <div><p class="text-gray-400 font-bold uppercase tracking-wider">Tanggal Pengajuan</p><p class="font-bold text-gray-800 text-sm mt-0.5" id="infoTanggal"></p></div>
                <div><p class="text-gray-400 font-bold uppercase tracking-wider">Periode Magang</p><p class="font-bold text-gray-800 text-sm mt-0.5" id="infoPeriode"></p></div>
            </div>
        </div>
        <div class="p-6 border-t border-gray-150 sticky bottom-0 bg-white/95 backdrop-blur-md z-10">
            <div id="rejectForm" class="hidden mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alasan Penolakan *</label>
                <textarea id="alasanPenolakan" rows="3" placeholder="Jelaskan alasan mengapa pengajuan ini ditolak..." class="w-full px-4 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 bg-white/50 backdrop-blur-md"></textarea>
                <div class="flex items-center justify-end gap-3 mt-3">
                    <button onclick="cancelReject()" class="px-4 py-2 text-xs font-bold text-gray-650 hover:bg-gray-100 rounded-xl transition">Batal</button>
                    <button onclick="confirmReject()" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm transition">❌ Konfirmasi Penolakan</button>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3" id="mainButtons">
                <button onclick="closeModal()" class="px-6 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold rounded-xl transition">Batal</button>
                <button onclick="showRejectForm()" class="px-6 py-2 bg-red-50 text-red-750 border border-red-200 hover:bg-red-100 text-xs font-bold rounded-xl transition">❌ Tolak</button>
                <button onclick="approveAction(currentPengajuanId)" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition">✅ Setujui</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPengajuanId = null;

function showSection(section) {
    document.querySelectorAll('.section-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('section-' + section).classList.remove('hidden');
}

function openModal(id, nama, jurusan, industri, posisi, tanggal) {
    currentPengajuanId = id;
    document.getElementById('namaSiswa').textContent = nama;
    document.getElementById('infoNamaSiswa').textContent = nama;
    document.getElementById('jurusanSiswa').textContent = jurusan;
    document.getElementById('infoIndustri').textContent = industri;
    document.getElementById('namaIndustri').textContent = industri;
    document.getElementById('posisiMagang').textContent = posisi;
    document.getElementById('infoTanggal').textContent = tanggal;
    document.getElementById('infoPeriode').textContent = '-';
    document.getElementById('periodeMagang').textContent = '-';
    const now = new Date();
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    document.getElementById('tanggalSurat').textContent = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    document.getElementById('bulanSurat').textContent = roman[now.getMonth()];
    document.getElementById('tahunSurat').textContent = now.getFullYear();
    
    const modal = document.getElementById('previewModal');
    const container = document.getElementById('modalContainer');
    modal.classList.remove('hidden');
    setTimeout(() => {
        container.classList.remove('scale-95');
        container.classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('previewModal');
    const container = document.getElementById('modalContainer');
    container.classList.remove('scale-100');
    container.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        currentPengajuanId = null;
        document.getElementById('rejectForm').classList.add('hidden');
        document.getElementById('mainButtons').classList.remove('hidden');
        document.getElementById('alasanPenolakan').value = '';
    }, 200);
}

function showRejectForm() {
    document.getElementById('rejectForm').classList.remove('hidden');
    document.getElementById('mainButtons').classList.add('hidden');
}

function cancelReject() {
    document.getElementById('rejectForm').classList.add('hidden');
    document.getElementById('mainButtons').classList.remove('hidden');
}

function confirmReject() {
    const alasan = document.getElementById('alasanPenolakan').value.trim();
    if (!alasan) { alert('Mohon isi alasan penolakan!'); return; }
    if (currentPengajuanId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/pimpinan/approval-surat/' + currentPengajuanId + '/reject';
        form.innerHTML = '@csrf <input type="hidden" name="alasan_penolakan" value="' + alasan + '">';
        document.body.appendChild(form);
        form.submit();
    }
}

function approveAction(id) {
    if (!id) { alert('ID pengajuan tidak ditemukan!'); return; }
    if (confirm('Setujui pengajuan ini? Surat akan dibuat otomatis.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/pimpinan/approval-surat/' + id + '/approve';
        form.innerHTML = '@csrf <input type="hidden" name="_method" value="POST">';
        document.body.appendChild(form);
        form.submit();
        closeModal();
    }
}

document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection