@extends('layouts.admin')

@section('title', 'Data Surat Masuk (Balasan Industri)')

@section('header_breadcrumb', 'Surat Masuk')
@section('header_title', 'SURAT MASUK')

@section('header_actions')
<div class="flex justify-end">
    <button onclick="openModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Surat Masuk
    </button>
</div>
@endsection

@section('content')
<div class="p-0">
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
@endsection

@section('modals')
<!-- Modal Tambah Surat Masuk (Centered & Elevated UI/UX) -->
<div id="modal-tambah" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div id="modalTambahContent" class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100 flex flex-col max-h-[92vh] my-auto">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-white flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-800 tracking-tight">Input Surat Masuk (Balasan)</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Catat respon dan surat resmi dari pihak DU/DI</p>
                </div>
            </div>
            <button type="button" onclick="closeModal()" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition border border-gray-200/60 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            <div class="p-6 overflow-y-auto space-y-4 flex-1 text-sm">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Pengajuan Siswa <span class="text-red-500">*</span></label>
                    <select name="penempatan_magang_id" required class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs font-semibold text-gray-800 transition">
                        <option value="">-- Pilih Siswa & Industri --</option>
                        @foreach($penempatans as $p)
                            <option value="{{ $p->id }}">{{ $p->siswa->nama ?? '-' }} - {{ $p->industri->nama_industri ?? '-' }} ({{ $p->status }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_surat" required placeholder="Contoh: 123/DU-DI/2024" class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pengirim (Dari Industri) <span class="text-red-500">*</span></label>
                        <input type="text" name="pengirim" required placeholder="Nama PT / Instansi" class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Terima <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_terima" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Perihal <span class="text-red-500">*</span></label>
                        <input type="text" name="perihal" required placeholder="Contoh: Penerimaan PKL" class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm transition">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Keputusan Industri <span class="text-red-500">*</span></label>
                    <select name="status_balasan" required class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-250 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs font-bold text-gray-800 transition">
                        <option value="terima">Diterima</option>
                        <option value="tolak">Ditolak</option>
                        <option value="diproses">Dalam Proses</option>
                    </select>
                    <p class="text-[11px] text-gray-500 mt-1">Status pengajuan siswa akan otomatis diperbarui sesuai opsi ini.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload Berkas Scan Surat</label>
                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3.5 py-2 bg-gray-50/50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-xs file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-1">Format: PDF, JPG, PNG (Maks 2MB)</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-white border border-gray-250 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Surat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('modal-tambah');
    const content = document.getElementById('modalTambahContent');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('modal-tambah');
    const content = document.getElementById('modalTambahContent');
    if (!modal || modal.classList.contains('hidden')) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 200);
}

document.getElementById('modal-tambah').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
