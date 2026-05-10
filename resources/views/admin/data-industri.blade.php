@extends('layouts.admin')

@section('title', 'Data Industri')

@section('content')
<!-- Top Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Industri</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data industri mitra prakerin SMK Negeri 3 Tuban</p>
        </div>
        <div class="text-sm text-gray-600">
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="p-8">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Validation -->
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
            <!-- Search & Filter -->
            <form action="{{ route('admin.data-industri') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama industri..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <input type="text" name="kota" value="{{ request('kota') }}" 
                       placeholder="Filter kota..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.data-industri') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Reset
                </a>
            </form>
            
            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="openModal()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Industri
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">NIB</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Industri</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Alamat</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kontak & HR</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kapasitas</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($industris as $index => $industri)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $industris->firstItem() + $index }}</td>
                            
                            <!-- ✅ TAMPILKAN NIB DI KOLOM KODE -->
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @if($industri->nib)
                                    <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded" title="{{ $industri->nib }}">
                                        {{ \Str::limit($industri->nib, 15) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Kolom Nama Industri -->
                            <td class="px-4 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $industri->nama_industri }}</p>
                                    @if($industri->kategori)
                                        <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ $industri->kategori }}</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600 max-w-xs">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $industri->alamat }}</span>
                                    @if($industri->kelurahan)
                                        <span class="text-xs text-gray-500">Kel. {{ $industri->kelurahan }}</span>
                                    @endif
                                    @if($industri->kecamatan)
                                        <span class="text-xs text-gray-500">Kec. {{ $industri->kecamatan }}</span>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ $industri->kota }}, {{ $industri->provinsi }} {{ $industri->kode_pos }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium">{{ $industri->no_telp }}</span>
                                    @if($industri->email)
                                        <span class="text-xs text-gray-400">{{ $industri->email }}</span>
                                    @endif
                                    @if($industri->nama_hr)
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <span class="text-xs font-semibold text-gray-700">HR: {{ $industri->nama_hr }}</span>
                                            @if($industri->no_wa_hr)
                                                <span class="text-xs text-gray-500 block">{{ $industri->no_wa_hr }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-center">
                                @if($industri->kapasitas_magang)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold">{{ $industri->kapasitas_magang }} Siswa</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- ✅ PERBAIKAN: Parameter dikirim sesuai urutan database -->
                                    <button onclick="editIndustri(
                                        {{ $industri->id }}, 
                                        '{{ $industri->nib ?? '' }}', 
                                        '{{ $industri->nama_industri }}', 
                                        '{{ $industri->alamat }}', 
                                        '{{ $industri->kelurahan }}',
                                        '{{ $industri->kecamatan }}',
                                        '{{ $industri->kota }}', 
                                        '{{ $industri->provinsi }}',
                                        '{{ $industri->kode_pos }}',
                                        '{{ $industri->no_telp }}', 
                                        '{{ $industri->email }}', 
                                        '{{ $industri->website }}', 
                                        '{{ $industri->nama_hr }}',
                                        '{{ $industri->no_wa_hr }}',
                                        '{{ $industri->kategori }}',
                                        {{ $industri->kapasitas_magang }}
                                    )" 
                                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteIndustri({{ $industri->id }}, '{{ $industri->nama_industri }}')" 
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
                                <p class="text-lg font-semibold text-gray-600">Belum ada data industri</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($industris->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $industris->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah/Edit Industri -->
<div id="industriModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-6xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Industri</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="industriForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="industriId" name="_method" value="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom 1: Informasi Dasar -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Informasi Dasar</h4>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIB (Nomor Induk Berusaha) *</label>
                        <input type="text" name="nib" id="nib" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Industri *</label>
                        <input type="text" name="nama_industri" id="nama_industri" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                        <input type="text" name="kategori" id="kategori" placeholder="Contoh: Manufaktur, Jasa, dll"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kapasitas Magang</label>
                        <input type="number" name="kapasitas_magang" id="kapasitas_magang" placeholder="Jumlah siswa"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Kolom 2: Alamat Lengkap -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Alamat Lengkap</h4>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Jalan *</label>
                        <textarea name="alamat" id="alamat" rows="2" required 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelurahan</label>
                            <input type="text" name="kelurahan" id="kelurahan" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatan" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kota/Kabupaten *</label>
                        <input type="text" name="kota" id="kota" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Provinsi *</label>
                        <input type="text" name="provinsi" id="provinsi" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Kolom 3: Kontak & HR -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Kontak & HR</h4>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon Industri *</label>
                        <input type="text" name="no_telp" id="no_telp" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Website</label>
                        <input type="text" name="website" id="website" placeholder="https://www.sukamakmur.co.id"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div class="pt-4 border-t">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Kontak HRD/Personalia</p>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama HR</label>
                                <input type="text" name="nama_hr" id="nama_hr" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">No. WA HR</label>
                                <input type="text" name="no_wa_hr" id="no_wa_hr" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t">
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
    document.getElementById('modalTitle').textContent = 'Tambah Industri';
    document.getElementById('industriForm').reset();
    document.getElementById('industriId').value = 'POST';
    document.getElementById('industriForm').action = "{{ route('admin.data-industri') }}";
    document.getElementById('industriModal').classList.remove('hidden');
}

// ✅ PERBAIKAN: Parameter sesuai urutan database (id, nib, nama, alamat, ...)
function editIndustri(id, nib, nama, alamat, kelurahan, kecamatan, kota, provinsi, kodePos, noTelp, email, website, namaHr, noWaHr, kategori, kapasitasMagang) {
    console.log('Edit Industri:', {
        id, nib, nama, alamat, kelurahan, kecamatan, kota, provinsi, 
        kodePos, noTelp, email, website, namaHr, noWaHr, kategori, kapasitasMagang
    });
    
    document.getElementById('modalTitle').textContent = 'Edit Industri';
    
    // Isi semua field
    document.getElementById('nib').value = nib || '';
    document.getElementById('nama_industri').value = nama;
    document.getElementById('alamat').value = alamat;
    document.getElementById('kelurahan').value = kelurahan || '';
    document.getElementById('kecamatan').value = kecamatan || '';
    document.getElementById('kota').value = kota;
    document.getElementById('provinsi').value = provinsi;
    document.getElementById('kode_pos').value = kodePos || '';
    document.getElementById('no_telp').value = noTelp;
    document.getElementById('email').value = email || '';
    document.getElementById('website').value = website || '';
    document.getElementById('nama_hr').value = namaHr || '';
    document.getElementById('no_wa_hr').value = noWaHr || '';
    document.getElementById('kategori').value = kategori || '';
    document.getElementById('kapasitas_magang').value = kapasitasMagang || '';
    
    // Set method dan action
    const form = document.getElementById('industriForm');
    const hiddenMethod = document.getElementById('industriId');
    
    hiddenMethod.value = 'PUT';
    
    const actionUrl = "{{ route('admin.data-industri.update', ':id') }}".replace(':id', id);
    form.action = actionUrl;
    
    console.log('Form Action:', form.action);
    console.log('Form Method:', hiddenMethod.value);
    
    document.getElementById('industriModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('industriModal').classList.add('hidden');
}

function deleteIndustri(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus industri "${nama}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.data-industri.delete', ':id') }}".replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('industriModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Debug form submit
document.getElementById('industriForm').addEventListener('submit', function(e) {
    console.log('Form submitting...');
    console.log('Action:', this.action);
    console.log('Method:', document.getElementById('industriId').value);
    
    const formData = new FormData(this);
    for (let [key, value] of formData.entries()) {
        console.log(key, ':', value);
    }
});
</script>
@endsection