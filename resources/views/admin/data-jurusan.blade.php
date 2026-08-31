@extends('layouts.admin')

@section('title', 'Data Jurusan')

@section('header_breadcrumb', 'Data Jurusan')
@section('header_title', 'DATA JURUSAN')

@section('content')
<div class="p-0">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-semibold">Validasi Gagal!</h3>
                    <ul class="text-red-700 text-sm mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <!-- Search -->
            <form action="{{ route('admin.data-jurusan') }}" method="GET" class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama jurusan atau kode..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Cari
                </button>
                <a href="{{ route('admin.data-jurusan') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
            
            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="openModal()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Jurusan
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kepala Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jurusans as $index => $jurusan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $jurusans->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg">
                                    {{ $jurusan->kode_jurusan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 text-sm">{{ $jurusan->nama_jurusan }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $jurusan->kepala_jurusan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $jurusan->deskripsi ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($jurusan->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editJurusan({{ $jurusan->id }}, '{{ $jurusan->kode_jurusan }}', '{{ $jurusan->nama_jurusan }}', '{{ $jurusan->kepala_jurusan }}', '{{ $jurusan->deskripsi }}', {{ $jurusan->is_active }})" 
                                            class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteJurusan({{ $jurusan->id }}, '{{ $jurusan->nama_jurusan }}')" 
                                            class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-lg font-semibold text-gray-600">Belum ada data jurusan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($jurusans->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $jurusans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah/Edit Jurusan (Centered & Elevated UI/UX) -->
<div id="jurusanModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div id="jurusanModalContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100 flex flex-col max-h-[92vh] my-auto">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 id="modalTitle" class="text-lg font-black text-gray-800 tracking-tight">Tambah Jurusan Baru</h3>
                        <span id="modalModeBadge" class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-blue-100 text-blue-700 tracking-wider">Baru</span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Kelola kompetensi keahlian dan kepemimpinan jurusan</p>
                </div>
            </div>
            <button type="button" onclick="closeModal()" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition border border-gray-200/60 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="jurusanForm" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            <input type="hidden" id="jurusanId" name="_method" value="POST">
            
            <div class="p-6 overflow-y-auto space-y-4 flex-1 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kode Jurusan <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_jurusan" id="kode_jurusan" required 
                               placeholder="Contoh: RPL, TKJ, TPM"
                               class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-bold uppercase transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kepala Jurusan</label>
                        <input type="text" name="kepala_jurusan" id="kepala_jurusan" 
                               placeholder="Nama kepala jurusan"
                               class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Jurusan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" required 
                           placeholder="Contoh: Rekayasa Perangkat Lunak"
                           class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm font-semibold transition">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" 
                              placeholder="Deskripsi singkat jurusan..."
                              class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition"></textarea>
                </div>
                
                <div class="p-3 bg-gray-50/80 rounded-xl border border-gray-150 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-800 block">Status Aktif Jurusan</span>
                        <p class="text-[11px] text-gray-500">Jurusan aktif dapat dipilih pada form siswa & guru</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-250 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal()" 
                        class="px-5 py-2.5 bg-white border border-gray-250 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data Jurusan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('jurusanModal');
    const content = document.getElementById('jurusanModalContent');
    
    document.getElementById('modalTitle').textContent = 'Tambah Jurusan Baru';
    const badge = document.getElementById('modalModeBadge');
    if (badge) {
        badge.textContent = 'Baru';
        badge.className = 'px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-blue-100 text-blue-700 tracking-wider';
    }
    
    document.getElementById('jurusanForm').reset();
    document.getElementById('is_active').checked = true;
    document.getElementById('jurusanId').value = 'POST';
    document.getElementById('jurusanForm').action = "{{ route('admin.data-jurusan') }}";
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function editJurusan(id, kode, nama, kepalaJurusan, deskripsi, isActive) {
    const modal = document.getElementById('jurusanModal');
    const content = document.getElementById('jurusanModalContent');
    
    document.getElementById('modalTitle').textContent = 'Edit Data Jurusan';
    const badge = document.getElementById('modalModeBadge');
    if (badge) {
        badge.textContent = 'Mode Edit';
        badge.className = 'px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-amber-100 text-amber-700 tracking-wider';
    }
    
    document.getElementById('kode_jurusan').value = kode;
    document.getElementById('nama_jurusan').value = nama;
    document.getElementById('kepala_jurusan').value = kepalaJurusan || '';
    document.getElementById('deskripsi').value = deskripsi || '';
    document.getElementById('is_active').checked = isActive == 1;
    
    document.getElementById('jurusanId').value = 'PUT';
    document.getElementById('jurusanForm').action = "{{ route('admin.data-jurusan.update', ':id') }}".replace(':id', id);
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('jurusanModal');
    const content = document.getElementById('jurusanModalContent');
    if (!modal || modal.classList.contains('hidden')) return;
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 200);
}

function deleteJurusan(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus jurusan "${nama}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.data-jurusan.delete', ':id') }}".replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('jurusanModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection