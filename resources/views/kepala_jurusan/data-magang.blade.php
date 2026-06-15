@extends('layouts.kepala_jurusan')

@section('title', 'Data Magang Jurusan')
@section('header_breadcrumb', 'Data Magang')
@section('header_title', 'PENEMPATAN MAGANG')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex flex-col gap-1">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-bold">{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/40 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Manajemen Penempatan</h2>
            <p class="text-xs text-gray-500 mt-1">Daftarkan dan tempatkan siswa pada mitra industri yang bekerjasama.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openPeriodeModal()" class="px-5 py-2.5 bg-white hover:bg-gray-50 text-emerald-700 border border-emerald-600 hover:border-emerald-700 font-bold text-sm rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Periode Magang
            </button>
            <button onclick="openCreatePlacementModal()" class="px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-600 hover:from-yellow-600 hover:to-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Penempatan Baru
            </button>
        </div>
    </div>

    <!-- Placements Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Industri / Mitra</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Guru Pembimbing</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Posisi / Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($placements as $index => $placement)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">{{ $placements->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $placement->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $placement->siswa->nisn }} | {{ $placement->siswa->kelas->nama_kelas ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $placement->industri->nama_industri }}</div>
                                <div class="text-xs text-gray-400">{{ $placement->industri->bidang_usaha ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">
                                {{ $placement->guruPembimbing->nama_lengkap ?? 'Belum Ditugaskan' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="font-semibold text-emerald-700">{{ $placement->posisi_magang }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ $placement->tanggal_mulai ? $placement->tanggal_mulai->format('d M Y') : '-' }} s/d {{ $placement->tanggal_selesai ? $placement->tanggal_selesai->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($placement->status == 'approved') bg-green-100 text-green-700
                                    @elseif($placement->status == 'ongoing') bg-blue-100 text-blue-700
                                    @elseif($placement->status == 'completed') bg-teal-100 text-teal-700
                                    @elseif($placement->status == 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($placement->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" 
                                            onclick='openEditPlacementModal(@json($placement))'
                                            class="px-3 py-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-lg transition border border-yellow-150">
                                        Edit
                                    </button>
                                    <form action="{{ route('kepala_jurusan.data-magang.destroy', $placement->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus penempatan magang ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition border border-red-150">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data penempatan magang siswa.</p>
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
<!-- Modal Form Penempatan -->
<div id="placementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 id="placementModalTitle" class="text-xl font-bold text-gray-800">Buat Penempatan Magang Siswa</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="placementForm" action="{{ route('kepala_jurusan.data-magang.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Siswa (Belum Magang) *</label>
                    <select name="siswa_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih Siswa...</option>
                        @foreach($siswaBelumPlacement as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->nama }} (NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Industri / Perusahaan Mitra *</label>
                    <select name="industri_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih Mitra...</option>
                        @foreach($industris as $ind)
                            @php
                                $sisa = $ind->sisa_kapasitas;
                                $isFull = $sisa <= 0;
                            @endphp
                            <option value="{{ $ind->id }}" data-full="{{ $isFull ? 'true' : 'false' }}" {{ $isFull ? 'disabled' : '' }}>
                                {{ $ind->nama_industri }} (Sisa: {{ $sisa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Guru Pembimbing Lapangan *</label>
                    <select name="guru_pembimbing_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih Guru...</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Periode Magang *</label>
                    <select name="periode_magang_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih Periode...</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} (TA: {{ $p->tahun_ajaran }} | {{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi Pekerjaan Magang (Opsional)</label>
                    <input type="text" name="posisi_magang" placeholder="Contoh: Web Developer / Network Administrator" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" id="btnSubmitPlacement" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-emerald-600 hover:from-yellow-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    Simpan Penempatan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kelola Periode Magang -->
<div id="periodeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Kelola Periode Magang</h3>
            <button onclick="closePeriodeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Form Tambah / Edit Periode -->
            <form id="periodeForm" action="{{ route('kepala_jurusan.periode-magang.store') }}" method="POST" class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="periodeMethod" value="POST">
                <h4 id="periodeFormTitle" class="text-sm font-bold text-gray-700">Tambah Periode Magang Baru</h4>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Periode *</label>
                        <input type="text" name="nama" id="p_nama" required placeholder="Contoh: Tahap 1 / Ganjil" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun Ajaran *</label>
                        <input type="text" name="tahun_ajaran" id="p_tahun_ajaran" required placeholder="2025/2026" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" id="p_tanggal_mulai" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" id="p_tanggal_selesai" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" id="btnCancelEditPeriode" onclick="resetPeriodeForm()" class="hidden px-4 py-1.5 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitPeriode" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        Simpan Periode
                    </button>
                </div>
            </form>

            <!-- Tabel Daftar Periode -->
            <div class="border border-gray-100 rounded-2xl overflow-hidden bg-white">
                <div class="max-h-60 overflow-y-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100">
                                <th class="px-4 py-3">Nama Periode</th>
                                <th class="px-4 py-3">Tahun Ajaran</th>
                                <th class="px-4 py-3">Tanggal Mulai</th>
                                <th class="px-4 py-3">Tanggal Selesai</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($periodes as $p)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->nama }}</td>
                                    <td class="px-4 py-3 text-gray-600 font-medium">{{ $p->tahun_ajaran }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $p->tanggal_mulai ? $p->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $p->tanggal_selesai ? $p->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" 
                                                    onclick='editPeriode(@json($p))'
                                                    class="px-2 py-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-xs font-semibold rounded transition border border-yellow-150">
                                                Edit
                                            </button>
                                            <form action="{{ route('kepala_jurusan.periode-magang.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded transition border border-red-150">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400 text-xs">Belum ada data periode magang. Silakan tambahkan di atas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('placementModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('placementModal').classList.add('hidden');
}

function openCreatePlacementModal() {
    document.getElementById('placementModalTitle').innerText = 'Buat Penempatan Magang Siswa';
    
    const form = document.getElementById('placementForm');
    form.reset();
    form.action = "{{ route('kepala_jurusan.data-magang.store') }}";
    
    // Enable siswa select for new creation
    const siswaSelect = document.querySelector('select[name="siswa_id"]');
    siswaSelect.disabled = false;
    
    // Reset industri select options disabled states based on data-full
    const industriSelect = document.querySelector('select[name="industri_id"]');
    for (let i = 0; i < industriSelect.options.length; i++) {
        const opt = industriSelect.options[i];
        opt.disabled = opt.getAttribute('data-full') === 'true';
    }
    
    document.getElementById('btnSubmitPlacement').innerText = 'Simpan Penempatan';
    openModal();
}

function openEditPlacementModal(placement) {
    document.getElementById('placementModalTitle').innerText = 'Edit Penempatan Magang Siswa';
    
    const form = document.getElementById('placementForm');
    form.reset();
    form.action = "{{ url('kepala-jurusan/data-magang') }}/" + placement.id + "/update";
    
    const siswaSelect = document.querySelector('select[name="siswa_id"]');
    
    // Check if current student option exists in the list
    let optionExists = false;
    for (let i = 0; i < siswaSelect.options.length; i++) {
        if (siswaSelect.options[i].value == placement.siswa_id) {
            optionExists = true;
            break;
        }
    }
    
    // If not exists (because the list shows only unassigned students), dynamically append it
    if (!optionExists && placement.siswa) {
        const opt = document.createElement('option');
        opt.value = placement.siswa_id;
        opt.text = `${placement.siswa.nama} (NISN: ${placement.siswa.nisn} | Kelas: ${placement.siswa.kelas ? placement.siswa.kelas.nama_kelas : '-'})`;
        siswaSelect.add(opt);
    }
    
    siswaSelect.value = placement.siswa_id;
    // Don't allow changing the student during edit to maintain integrity (or keep it editable, let's keep it editable but preset)
    
    // Enable current industry even if full, disable others if full
    const industriSelect = document.querySelector('select[name="industri_id"]');
    for (let i = 0; i < industriSelect.options.length; i++) {
        const opt = industriSelect.options[i];
        if (opt.value == placement.industri_id) {
            opt.disabled = false;
        } else {
            opt.disabled = opt.getAttribute('data-full') === 'true';
        }
    }
    
    industriSelect.value = placement.industri_id;
    document.querySelector('select[name="guru_pembimbing_id"]').value = placement.guru_pembimbing_id;
    document.querySelector('select[name="periode_magang_id"]').value = placement.periode_magang_id;
    document.querySelector('input[name="posisi_magang"]').value = placement.posisi_magang || '';
    
    document.getElementById('btnSubmitPlacement').innerText = 'Perbarui Penempatan';
    openModal();
}

function openPeriodeModal() {
    document.getElementById('periodeModal').classList.remove('hidden');
}

function closePeriodeModal() {
    document.getElementById('periodeModal').classList.add('hidden');
    resetPeriodeForm();
}

function editPeriode(periode) {
    document.getElementById('periodeFormTitle').innerText = 'Edit Periode Magang';
    
    // Set form fields
    document.getElementById('p_nama').value = periode.nama;
    document.getElementById('p_tahun_ajaran').value = periode.tahun_ajaran;
    
    // Format dates to YYYY-MM-DD
    const dateMulai = periode.tanggal_mulai.split('T')[0];
    const dateSelesai = periode.tanggal_selesai.split('T')[0];
    document.getElementById('p_tanggal_mulai').value = dateMulai;
    document.getElementById('p_tanggal_selesai').value = dateSelesai;
    
    // Update Form Action
    const form = document.getElementById('periodeForm');
    form.action = "{{ url('kepala-jurusan/periode-magang') }}/" + periode.id + "/update";
    
    document.getElementById('btnSubmitPeriode').innerText = 'Perbarui Periode';
    document.getElementById('btnCancelEditPeriode').classList.remove('hidden');
}

function resetPeriodeForm() {
    document.getElementById('periodeFormTitle').innerText = 'Tambah Periode Magang Baru';
    document.getElementById('periodeForm').reset();
    document.getElementById('periodeForm').action = "{{ route('kepala_jurusan.periode-magang.store') }}";
    
    document.getElementById('btnSubmitPeriode').innerText = 'Simpan Periode';
    document.getElementById('btnCancelEditPeriode').classList.add('hidden');
}

document.getElementById('placementModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('periodeModal').addEventListener('click', function(e) {
    if (e.target === this) closePeriodeModal();
});
</script>
@endsection
