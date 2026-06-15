@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('header_breadcrumb', 'Data Siswa')
@section('header_title', 'DATA SISWA')

@section('content')
<div class="p-0">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-green-800 font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-red-800 font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <form action="{{ route('admin.data-siswa') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau NISN..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <!-- Custom Dropdown Jurusan -->
                <div class="relative inline-block text-left" id="filterJurusanContainer">
                    <input type="hidden" name="jurusan_id" id="filterJurusanInput" value="{{ request('jurusan_id') }}">
                    <button type="button" id="filterJurusanBtn" onclick="toggleFilterJurusan()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition inline-flex items-center justify-between gap-2 min-w-[200px]">
                        <span id="filterJurusanLabel">
                            @if(request('jurusan_id') && $jurusans->firstWhere('id', request('jurusan_id')))
                                {{ $jurusans->firstWhere('id', request('jurusan_id'))->nama_jurusan }}
                            @else
                                Semua Jurusan
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="filterJurusanChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div id="filterJurusanMenu" class="hidden absolute left-0 mt-1.5 w-72 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-150 py-1.5 z-[99] max-h-60 overflow-y-auto">
                        <button type="button" onclick="selectFilterJurusan('', 'Semua Jurusan')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            Semua Jurusan
                        </button>
                        @foreach($jurusans as $jurusan)
                            <button type="button" onclick="selectFilterJurusan('{{ $jurusan->id }}', '{{ $jurusan->nama_jurusan }}')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                {{ $jurusan->nama_jurusan }}
                            </button>
                        @endforeach
                    </div>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.data-siswa') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.data-siswa.template') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2 border border-gray-300">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Template
                </a>
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Import Excel
                </button>

                <button onclick="openModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Siswa
                </button>
            </div>
            <div id="importModal" class="fixed inset-0 z-[100] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-xl font-bold text-gray-800">Import Data Siswa</h3>
                        <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <form action="{{ route('admin.data-siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel (.xlsx / .csv)</label>
                            <input type="file" name="file_excel" accept=".xlsx, .csv" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                            <p class="text-[10px] text-gray-500 mt-2 italic">Format Kolom: Nama, NISN, Jurusan (Kode), Kelas, Email, No WA, Tempat Lahir, Tgl Lahir (YYYY-MM-DD), Nama Wali, No WA Wali, Alamat.</p>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl font-medium">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 shadow-sm">Mulai Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[5%]">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[20%]">Nama / NISN</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[12%]">TTL</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[12%]">Jurusan / Kelas</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[18%]">Kontak</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[15%]">Wali Murid</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase w-[13%]">Alamat</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase w-[10%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswas as $index => $siswa)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $siswas->firstItem() + $index }}</td>
                            
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs uppercase flex-shrink-0">
                                        {{ substr($siswa->nama, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $siswa->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ $siswa->nisn }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="flex flex-col">
                                    <span>{{ $siswa->tempat_lahir ?? '-' }}</span>
                                    <span class="text-xs text-gray-400">{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-' }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $siswa->jurusan->kode_jurusan ?? '-' }}</span>
                                    <span class="text-xs text-gray-400">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="flex flex-col">
                                    <span>{{ $siswa->email }}</span>
                                    <span class="text-xs text-gray-400">{{ $siswa->no_wa }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $siswa->nama_wali ?? '-' }}</span>
                                    <span class="text-xs text-gray-400">{{ $siswa->no_wa_wali ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $siswa->alamat ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editSiswa(
                                        {{ $siswa->id }}, 
                                        '{{ $siswa->nama }}', 
                                        '{{ $siswa->nisn }}', 
                                        {{ $siswa->jurusan_id }}, 
                                        {{ $siswa->kelas_id ?? 'null' }}, 
                                        '{{ $siswa->email }}', 
                                        '{{ $siswa->no_wa }}', 
                                        '{{ $siswa->tempat_lahir }}', 
                                        '{{ $siswa->tanggal_lahir }}',
                                        '{{ $siswa->nama_wali }}',
                                        '{{ $siswa->no_wa_wali }}',
                                        '{{ $siswa->alamat }}'
                                    )" 
                                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteSiswa({{ $siswa->id }}, '{{ $siswa->nama }}')" 
                                            class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data siswa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($siswas->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>
</div>

<div id="siswaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Siswa</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="siswaForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="siswaId" name="_method" value="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Data Diri</h4>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" id="nama" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NISN *</label>
                        <input type="text" name="nisn" id="nisn" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="text" name="password" id="password" placeholder="Bebas (Default: NISN)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <p class="text-xs text-orange-500 mt-1">*Kosongkan jika tidak ingin mengubah (saat Edit)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Sekolah & Kontak</h4>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jurusan *</label>
                        <select name="jurusan_id" id="jurusan_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas *</label>
                        <select name="kelas_id" id="kelas_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp *</label>
                        <input type="text" name="no_wa" id="no_wa" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Wali & Alamat</h4>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Wali</label>
                        <input type="text" name="nama_wali" id="nama_wali" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp Wali</label>
                        <input type="text" name="no_wa_wali" id="no_wa_wali" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap *</label>
                        <textarea name="alamat" id="alamat" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Siswa';
    document.getElementById('siswaForm').reset();
    document.getElementById('password').value = ''; // Kosongkan password
    document.getElementById('siswaId').value = 'POST';
    document.getElementById('siswaForm').action = "{{ route('admin.data-siswa') }}";
    document.getElementById('siswaModal').classList.remove('hidden');
}

// Parameter diupdate sesuai data baru
function editSiswa(id, nama, nisn, jurusanId, kelasId, email, noWa, tempatLahir, tglLahir, namaWali, noWaWali, alamat) {
    document.getElementById('modalTitle').textContent = 'Edit Siswa';
    
    document.getElementById('nama').value = nama;
    document.getElementById('nisn').value = nisn;
    document.getElementById('jurusan_id').value = jurusanId;
    document.getElementById('kelas_id').value = kelasId;
    document.getElementById('email').value = email;
    document.getElementById('no_wa').value = noWa;
    
    // Pastikan password selalu kosong saat modal edit dibuka
    document.getElementById('password').value = ''; 
    
    // Isi data baru
    document.getElementById('tempat_lahir').value = tempatLahir;
    document.getElementById('tanggal_lahir').value = tglLahir;
    document.getElementById('nama_wali').value = namaWali;
    document.getElementById('no_wa_wali').value = noWaWali;
    document.getElementById('alamat').value = alamat;
    
    document.getElementById('siswaId').value = 'PUT';
    document.getElementById('siswaForm').action = "{{ route('admin.data-siswa.update', ':id') }}".replace(':id', id);
    
    document.getElementById('siswaModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('siswaModal').classList.add('hidden');
}

function deleteSiswa(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus siswa "${nama}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.data-siswa.delete', ':id') }}".replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('siswaModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function toggleFilterJurusan() {
    const menu = document.getElementById('filterJurusanMenu');
    const chevron = document.getElementById('filterJurusanChevron');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        chevron.classList.add('rotate-180');
    } else {
        menu.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
}

function selectFilterJurusan(id, name) {
    document.getElementById('filterJurusanInput').value = id;
    document.getElementById('filterJurusanLabel').textContent = name;
    document.getElementById('filterJurusanMenu').classList.add('hidden');
    document.getElementById('filterJurusanChevron').classList.remove('rotate-180');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const container = document.getElementById('filterJurusanContainer');
    const menu = document.getElementById('filterJurusanMenu');
    const chevron = document.getElementById('filterJurusanChevron');
    if (container && !container.contains(event.target)) {
        menu.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
});
</script>
@endsection