@extends('layouts.admin')

@section('title', 'Kontrol Waktu Magang')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kontrol Waktu Magang</h1>
            <p class="text-sm text-gray-500 mt-1">Atur jadwal mulai dan selesai magang untuk siswa yang telah disetujui.</p>
        </div>
    </div>
</header>

<div class="p-8">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 font-semibold">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Industri</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Magang</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penempatans as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800 text-sm">{{ $p->siswa->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $p->siswa->jurusan->kode_jurusan ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-700 text-sm">{{ $p->industri->nama_industri ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $p->posisi_magang ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($p->status == 'approved')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Siap Mulai</span>
                            @elseif($p->status == 'ongoing')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Sedang Berlangsung</span>
                            @elseif($p->status == 'completed')
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-green-600 font-semibold text-xs">Mulai: {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y, H:i') : 'Belum diatur' }}</span>
                                <span class="text-red-500 font-semibold text-xs">Akhir: {{ $p->tanggal_selesai ? \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y, H:i') : 'Belum diatur' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="openKontrolWaktu({{ $p->id }}, '{{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('Y-m-d\TH:i') : '' }}', '{{ $p->tanggal_selesai ? \Carbon\Carbon::parse($p->tanggal_selesai)->format('Y-m-d\TH:i') : '' }}')" 
                                    class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-4 py-2 rounded-lg text-xs font-bold transition">
                                Atur Waktu
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">Belum ada siswa yang disetujui (Approved) untuk magang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">{{ $penempatans->links() }}</div>
    </div>
</div>

<div id="modalKontrolWaktu" class="hidden fixed inset-0 z-[100] bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Atur Waktu Magang</h3>
            <button onclick="closeModalKontrol()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="p-6 space-y-8">
            <form id="formStart" method="POST" class="space-y-3">
                @csrf
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Langkah 1: Mulai Magang (Set Ongoing)</label>
                <div class="flex gap-2">
                    <input type="datetime-local" name="tanggal_mulai" id="inputMulai" required class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition">MULAI</button>
                </div>
            </form>

            <form id="formEnd" method="POST" class="space-y-3">
                @csrf
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Langkah 2: Akhiri Magang (Set Selesai)</label>
                <div class="flex gap-2">
                    <input type="datetime-local" name="tanggal_selesai" id="inputSelesai" required class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-green-500">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition">AKHIRI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openKontrolWaktu(id, mulai, selesai) {
    const modal = document.getElementById('modalKontrolWaktu');
    
    // Set Action Route secara dinamis
    document.getElementById('formStart').action = `/admin/kontrol-magang/${id}/start`;
    document.getElementById('formEnd').action = `/admin/kontrol-magang/${id}/end`;
    
    // Isi value jika sudah pernah di-set
    document.getElementById('inputMulai').value = mulai;
    document.getElementById('inputSelesai').value = selesai;
    
    // Tampilkan Modal
    modal.classList.remove('hidden');
}

function closeModalKontrol() {
    document.getElementById('modalKontrolWaktu').classList.add('hidden');
}
</script>
@endsection