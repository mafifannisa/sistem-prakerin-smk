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

<!-- Modal Tambah/Edit Jurusan -->
<div id="jurusanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Jurusan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="jurusanForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="jurusanId" name="_method" value="POST">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Jurusan *</label>
                    <input type="text" name="kode_jurusan" id="kode_jurusan" required 
                           placeholder="Contoh: RPL, TKJ, TPM"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Jurusan *</label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" required 
                           placeholder="Contoh: Rekayasa Perangkat Lunak"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kepala Jurusan</label>
                    <input type="text" name="kepala_jurusan" id="kepala_jurusan" 
                           placeholder="Nama kepala jurusan"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" 
                              placeholder="Deskripsi jurusan..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Aktif</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Centang jika jurusan aktif digunakan</p>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Jurusan';
    document.getElementById('jurusanForm').reset();
    document.getElementById('jurusanId').value = 'POST';
    document.getElementById('jurusanForm').action = "{{ route('admin.data-jurusan') }}";
    document.getElementById('jurusanModal').classList.remove('hidden');
}

function editJurusan(id, kode, nama, kepalaJurusan, deskripsi, isActive) {
    document.getElementById('modalTitle').textContent = 'Edit Jurusan';
    
    document.getElementById('kode_jurusan').value = kode;
    document.getElementById('nama_jurusan').value = nama;
    document.getElementById('kepala_jurusan').value = kepalaJurusan || '';
    document.getElementById('deskripsi').value = deskripsi || '';
    document.getElementById('is_active').checked = isActive == 1;
    
    document.getElementById('jurusanId').value = 'PUT';
    document.getElementById('jurusanForm').action = "{{ route('admin.data-jurusan.update', ':id') }}".replace(':id', id);
    
    document.getElementById('jurusanModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('jurusanModal').classList.add('hidden');
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