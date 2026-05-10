@extends('layouts.admin')

@section('title', 'Data Surat Masuk (Balasan Industri)')

@section('content')
<!-- Top Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Surat Masuk</h1>
            <p class="text-sm text-gray-500 mt-1">Dokumentasi balasan pengajuan magang dari DU/DI</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Surat Masuk
        </button>
    </div>
</header>

<!-- Main Content -->
<div class="p-8">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nomor Surat</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pengirim</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Perihal & Siswa</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal Terima</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase">File</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratMasuks as $index => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $suratMasuks->firstItem() + $index }}</td>
                            <td class="px-4 py-4 font-medium text-gray-800 text-sm">{{ $item->nomor_surat }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $item->pengirim }}</td>
                            <td class="px-4 py-4 text-sm">
                                <p class="text-gray-800 font-semibold">{{ $item->perihal }}</p>
                                <p class="text-xs text-gray-500">Terkait: {{ $item->penempatanMagang->siswa->nama ?? '-' }} ({{ $item->penempatanMagang->industri->nama_industri ?? '-' }})</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $item->tanggal_terima->format('d M Y') }}</td>
                            <td class="px-4 py-4 text-center">
                                @if($item->file_path)
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-200 text-xs font-semibold rounded-lg hover:bg-blue-100 transition inline-block">
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-semibold">Belum ada data surat masuk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($suratMasuks->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $suratMasuks->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-xl w-full">
            <div class="bg-white px-8 pt-6 pb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Input Surat Masuk (Balasan)</h3>
                    <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form action="{{ route('admin.surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Pengajuan Siswa</label>
                        <select name="penempatan_magang_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">-- Pilih Siswa & Industri --</option>
                            @foreach($penempatans as $p)
                                <option value="{{ $p->id }}">{{ $p->siswa->nama ?? '-' }} - {{ $p->industri->nama_industri ?? '-' }} ({{ $p->status }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Surat</label>
                            <input type="text" name="nomor_surat" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pengirim (Dari Industri)</label>
                            <input type="text" name="pengirim" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Terima</label>
                            <input type="date" name="tanggal_terima" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Perihal</label>
                            <input type="text" name="perihal" required placeholder="Contoh: Penerimaan PKL" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Keputusan Industri</label>
                        <select name="status_balasan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="terima">Diterima</option>
                            <option value="tolak">Ditolak</option>
                            <option value="diproses">Dalam Proses</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Status pengajuan siswa akan otomatis berubah berdasarkan pilihan ini.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Scan Surat (Opsional)</label>
                        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="px-5 py-2 text-gray-600 font-semibold hover:bg-gray-100 rounded-lg transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">Simpan Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
