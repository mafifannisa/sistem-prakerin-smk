@extends('layouts.admin')

@section('title', 'Kelola Pengumuman')

@section('header_breadcrumb', 'Pengumuman')
@section('header_title', 'PENGUMUMAN SISTEM')

@section('content')
<div class="p-0">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 font-semibold">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Buat Pengumuman Baru</h3>
                <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Judul Pengumuman *</label>
                        <input type="text" name="judul" required placeholder="Contoh: Libur Idul Fitri" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Prioritas *</label>
                            <select name="prioritas" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="rendah">Rendah (Info Biasa)</option>
                                <option value="sedang">Sedang (Peringatan)</option>
                                <option value="tinggi">Tinggi (Darurat)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Mulai *</label>
                            <input type="date" name="tanggal_mulai" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tgl Selesai (Opsional)</label>
                            <input type="date" name="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Isi Pengumuman *</label>
                        <textarea name="isi" required rows="4" placeholder="Tulis pesan lengkap di sini..." class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition">Siarkan Pengumuman</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pengumuman</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengumumans as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full 
                                        {{ $p->tipe == 'danger' ? 'bg-red-500' : ($p->tipe == 'warning' ? 'bg-orange-500' : ($p->tipe == 'success' ? 'bg-green-500' : 'bg-blue-500')) }}">
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $p->judul }}</p>
                                        <p class="text-xs text-gray-500 truncate max-w-xs">{{ $p->isi }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $p->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.pengumuman.delete', $p->id) }}" method="POST" onsubmit="return confirm('Tarik/Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold bg-red-50 px-3 py-1 rounded-lg">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada pengumuman yang disiarkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $pengumumans->links() }}</div>
        </div>
    </div>
</div>
@endsection