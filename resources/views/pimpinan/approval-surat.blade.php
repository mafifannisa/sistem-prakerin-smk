@extends('layouts.pimpinan')

@section('title', 'Approval Surat')

@section('content')
{{-- Header --}}
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Approval Surat</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola persetujuan pengajuan magang dan surat siswa</p>
        </div>
        {{-- Filter Tahun & Semester --}}
        <form action="{{ route('pimpinan.approval.surat') }}" method="GET" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
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

<div class="p-8">
    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div onclick="showSection('pending')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Menunggu Persetujuan</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['total_pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div onclick="showSection('approved')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div onclick="showSection('rejected')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['total_rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION PENDING --}}
    <div id="section-pending" class="section-content">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">📋 Daftar Pengajuan Menunggu Persetujuan</h2>
                <p class="text-sm text-gray-500 mt-1">Semester {{ $filterSemester }} TA {{ $filterTahun }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Industri Tujuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Posisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengajuans as $pengajuan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pengajuan->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs uppercase">
                                            {{ substr($pengajuan->siswa->nama ?? 'S', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $pengajuan->siswa->nama ?? 'Siswa' }}</p>
                                            <p class="text-xs text-gray-500">{{ $pengajuan->siswa->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pengajuan->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-semibold">{{ $pengajuan->industri->nama_industri ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pengajuan->posisi_magang ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <button onclick="openModal({{ $pengajuan->id }}, '{{ addslashes($pengajuan->siswa->nama) }}', '{{ addslashes($pengajuan->siswa->jurusan->nama_jurusan ?? '-') }}', '{{ addslashes($pengajuan->industri->nama_industri ?? '-') }}', '{{ addslashes($pengajuan->posisi_magang ?? '-') }}', '{{ $pengajuan->created_at->format('d M Y') }}')" 
                                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition">
                                        👁️ Preview & Approve
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada pengajuan yang menunggu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pengajuans->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $pengajuans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION APPROVED --}}
    <div id="section-approved" class="section-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">✅ History Pengajuan Disetujui</h2>
                    <p class="text-sm text-gray-500 mt-1">Semester {{ $filterSemester }} TA {{ $filterTahun }}</p>
                </div>
                <span class="text-sm text-gray-500">Total: {{ $stats['total_approved'] }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Industri Tujuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Posisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($approved as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-xs uppercase">
                                            {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->siswa->nama ?? 'Siswa' }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->siswa->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-semibold">{{ $item->industri->nama_industri ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->posisi_magang ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">✅ Disetujui</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada pengajuan yang disetujui.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SECTION REJECTED --}}
    <div id="section-rejected" class="section-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">❌ History Pengajuan Ditolak</h2>
                    <p class="text-sm text-gray-500 mt-1">Semester {{ $filterSemester }} TA {{ $filterTahun }}</p>
                </div>
                <span class="text-sm text-gray-500">Total: {{ $stats['total_rejected'] }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Industri Tujuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Posisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rejected as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold text-xs uppercase">
                                            {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->siswa->nama ?? 'Siswa' }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->siswa->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-semibold">{{ $item->industri->nama_industri ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->posisi_magang ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">❌ Ditolak</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada pengajuan yang ditolak.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Preview --}}
<div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-800">Pratinjau Pengajuan Surat</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-8">
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 mb-6 bg-gray-50">
                <div class="bg-white p-8 shadow-sm">
                    <div class="text-center border-b-2 border-black pb-4 mb-6">
                        <h2 class="text-2xl font-bold uppercase">SMK NEGERI 3 TUBAN</h2>
                        <p class="text-sm">Jl. Doktor Wahidin Sudiro Husodo No. 123, Tuban, Jawa Timur</p>
                        <p class="text-sm">Telp: (0356) 123456 | Email: info@smkn3tuban.sch.id</p>
                    </div>
                    <div class="mb-6">
                        <p class="mb-2">Nomor: <strong>421/SMK.3-TUBAN/XXXX/<span id="bulanSurat"></span>/<span id="tahunSurat"></span></strong></p>
                        <p class="mb-4">Hal: <strong>Permohonan Praktik Kerja Industri (Prakerin)</strong></p>
                        <p class="mb-4">Yth. Pimpinan <span id="namaIndustri"></span></p>
                        <p class="mb-6">Di Tempat</p>
                        <p class="mb-4">Dengan hormat,</p>
                        <p class="mb-4">Melalui surat ini kami mengajukan permohonan agar siswa kami atas nama:</p>
                        <div class="ml-8 mb-4">
                            <p><strong>Nama:</strong> <span id="namaSiswa"></span></p>
                            <p><strong>NISN:</strong> <span id="nisnSiswa"></span></p>
                            <p><strong>Jurusan:</strong> <span id="jurusanSiswa"></span></p>
                            <p><strong>Posisi:</strong> <span id="posisiMagang"></span></p>
                        </div>
                        <p class="mb-4">dapat melaksanakan Praktik Kerja Industri di perusahaan Bapak/Ibu pada periode <span id="periodeMagang"></span>.</p>
                        <p class="mb-6">Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
                    </div>
                    <div class="flex justify-end mt-8">
                        <div class="text-center">
                            <p>Tuban, <span id="tanggalSurat"></span></p>
                            <p class="mb-4">Kepala SMK Negeri 3 Tuban</p>
                            <div class="h-24 mb-2">
                                <img src="{{ asset('images/signature.png') }}" alt="Tanda Tangan" class="h-full mx-auto" onerror="this.style.display='none'">
                            </div>
                            <p class="font-bold">Drs. HERU SUSANTO, M.Pd</p>
                            <p class="text-sm">NIP. 19680101 199003 1 001</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-500">Nama Siswa</p><p class="font-semibold" id="infoNamaSiswa"></p></div>
                <div><p class="text-gray-500">Tujuan Industri</p><p class="font-semibold" id="infoIndustri"></p></div>
                <div><p class="text-gray-500">Tanggal Pengajuan</p><p class="font-semibold" id="infoTanggal"></p></div>
                <div><p class="text-gray-500">Periode Magang</p><p class="font-semibold" id="infoPeriode"></p></div>
            </div>
        </div>
        <div class="p-6 border-t border-gray-200 sticky bottom-0 bg-white">
            <div id="rejectForm" class="hidden mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan *</label>
                <textarea id="alasanPenolakan" rows="3" placeholder="Jelaskan alasan mengapa pengajuan ini ditolak..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                <div class="flex items-center justify-end gap-3 mt-3">
                    <button onclick="cancelReject()" class="px-4 py-2 text-gray-600 font-semibold hover:bg-gray-100 rounded-lg transition">Batal</button>
                    <button onclick="confirmReject()" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition">❌ Konfirmasi Penolakan</button>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3" id="mainButtons">
                <button onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button onclick="showRejectForm()" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition">❌ Tolak Pengajuan</button>
                <button onclick="approveAction(currentPengajuanId)" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition">✅ Setujui</button>
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
    document.getElementById('previewModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('previewModal').classList.add('hidden');
    currentPengajuanId = null;
    document.getElementById('rejectForm').classList.add('hidden');
    document.getElementById('mainButtons').classList.remove('hidden');
    document.getElementById('alasanPenolakan').value = '';
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