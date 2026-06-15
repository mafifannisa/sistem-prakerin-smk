@extends('layouts.admin')

@section('title', 'Data Guru')

@section('header_breadcrumb', 'Data Guru')
@section('header_title', 'DATA GURU')

@section('content')
<div class="p-0">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-green-800 font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-red-800 font-bold">Terjadi Kesalahan Validasi:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <form action="{{ route('admin.data-guru') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau NIP..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.data-guru') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
            
            <button onclick="openModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Guru
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama / NIP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Username / Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No. Telp</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan / Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jabatan / Role</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gurus as $index => $guru)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $gurus->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 text-sm">{{ $guru->nama }}</p>
                                <p class="text-xs text-gray-500">NIP: {{ $guru->nip ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">{{ $guru->user->username ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $guru->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $guru->no_telp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <p>{{ $guru->jurusan->nama_jurusan ?? 'Semua Jurusan / Umum' }}</p>
                                @if($guru->kelas)
                                    <p class="text-xs text-gray-400 mt-1 font-semibold">Kelas: {{ $guru->kelas->nama_kelas }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($guru->jabatan == 'kepala_jurusan') bg-purple-100 text-purple-700
                                    @elseif($guru->jabatan == 'guru_pembimbing') bg-blue-100 text-blue-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ ucwords(str_replace('_', ' ', $guru->jabatan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editGuru(
                                        {{ $guru->id }}, 
                                        '{{ $guru->nama }}', 
                                        '{{ $guru->nip }}', 
                                        '{{ $guru->user->username ?? '' }}', 
                                        '{{ $guru->user->email ?? '' }}', 
                                        '{{ $guru->no_telp }}', 
                                        '{{ $guru->jurusan_id }}', 
                                        '{{ $guru->kelas_id }}', 
                                        '{{ $guru->jabatan }}'
                                    )" 
                                    class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteGuru({{ $guru->id }}, '{{ $guru->nama }}')" 
                                            class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold text-gray-600">Belum ada data guru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($gurus->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Form Guru -->
<div id="guruModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Guru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="guruForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="guruMethod" name="_method" value="POST">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIP (Optional)</label>
                    <input type="text" name="nip" id="nip" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telp / WA</label>
                    <input type="text" name="no_telp" id="no_telp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username *</label>
                    <input type="text" name="username" id="username" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <p id="passwordHelp" class="text-xs text-orange-500 mt-1">*Kosongkan jika tidak ingin mengubah (saat Edit)</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan / Role *</label>
                    <select name="jabatan" id="jabatan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="guru_pembimbing">Guru Pembimbing</option>
                        <option value="kepala_jurusan">Kepala Jurusan</option>
                        <option value="guru_penguji">Guru Penguji</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua / Tidak Ada</option>
                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas Bimbingan (Optional)</label>
                    <select name="kelas_id" id="kelas_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Tidak Ada</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-6 border-t mt-4">
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
    document.getElementById('modalTitle').textContent = 'Tambah Guru';
    document.getElementById('guruForm').reset();
    document.getElementById('password').required = true;
    document.getElementById('passwordHelp').classList.add('hidden');
    document.getElementById('guruMethod').value = 'POST';
    document.getElementById('guruForm').action = "{{ route('admin.data-guru.store') }}";
    document.getElementById('guruModal').classList.remove('hidden');
}

function editGuru(id, nama, nip, username, email, noTelp, jurusanId, kelasId, jabatan) {
    document.getElementById('modalTitle').textContent = 'Edit Guru';
    
    document.getElementById('nama').value = nama;
    document.getElementById('nip').value = nip;
    document.getElementById('username').value = username;
    document.getElementById('email').value = email;
    document.getElementById('no_telp').value = noTelp;
    document.getElementById('jurusan_id').value = jurusanId || '';
    document.getElementById('kelas_id').value = kelasId || '';
    document.getElementById('jabatan').value = jabatan;
    
    document.getElementById('password').required = false;
    document.getElementById('passwordHelp').classList.remove('hidden');
    
    document.getElementById('guruMethod').value = 'PUT';
    document.getElementById('guruForm').action = "{{ route('admin.data-guru.update', ':id') }}".replace(':id', id);
    
    document.getElementById('guruModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('guruModal').classList.add('hidden');
}

function deleteGuru(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus data guru "${nama}" beserta akun loginnya?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.data-guru.delete', ':id') }}".replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('guruModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
