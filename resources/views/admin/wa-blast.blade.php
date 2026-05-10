@extends('layouts.admin')

@section('title', 'WhatsApp Blast')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">WhatsApp Blast</h1>
        <p class="text-gray-500 mt-2 text-sm">Kirim pesan massal ke siswa secara dinamis dari database.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-7 space-y-6">
            <form action="{{ route('admin.wa-blast.send') }}" method="POST" class="space-y-6" id="formWaBlast">
                @csrf
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-[520px]">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-4 shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Pilih Target Siswa
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 shrink-0">
                        <div class="relative">
                            <select id="filterStatus" onchange="applyFilters()" class="appearance-none w-full px-3 py-2.5 pr-8 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-xs outline-none text-gray-700 font-semibold cursor-pointer">
                                <option value="all">Semua Status</option>
                                <option value="1">Sedang Magang Aktif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <div class="relative">
                            <select id="filterJurusan" onchange="applyFilters()" class="appearance-none w-full px-3 py-2.5 pr-8 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-xs outline-none text-gray-700 font-semibold cursor-pointer">
                                <option value="all">Semua Jurusan</option>
                                @foreach($jurusans as $jur)
                                    <option value="{{ $jur->id }}">{{ $jur->kode_jurusan }} - {{ $jur->nama_jurusan }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <input type="text" id="filterSearch" onkeyup="applyFilters()" placeholder="Cari Nama/WA/Tempat Magang..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-xs outline-none font-semibold">
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-blue-50 px-4 py-3 rounded-lg mb-3 shrink-0 border border-blue-100">
                        <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-blue-700">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllCheckboxes()" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            Pilih Semua yang Ditampilkan
                        </label>
                        <span id="countSelected" class="text-xs font-bold text-blue-800 bg-blue-200 px-2 py-1 rounded-md">0 Terpilih</span>
                    </div>

                    <div class="flex-1 overflow-y-auto pr-2 space-y-2 border border-gray-100 rounded-lg p-2 bg-gray-50/50" id="studentListContainer">
                        @forelse($siswas as $siswa)
                            <label class="student-item flex items-center justify-between p-3 rounded-lg border border-gray-200 bg-white hover:border-blue-300 cursor-pointer transition-all"
                                   data-jurusan="{{ $siswa->jurusan_id }}" 
                                   data-aktif="{{ $siswa->is_magang_aktif }}" 
                                   data-search="{{ strtolower($siswa->nama . ' ' . $siswa->no_wa . ' ' . $siswa->nisn . ' ' . $siswa->nama_industri) }}">
                                
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="targets[]" value="{{ $siswa->no_wa }}" class="target-checkbox w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" onchange="updateCounter()">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $siswa->nama }} <span class="text-xs font-normal text-gray-500">({{ $siswa->jurusan->kode_jurusan ?? '-' }})</span></p>
                                        
                                        @if($siswa->is_magang_aktif == '1' && $siswa->nama_industri)
                                            <p class="text-[10px] text-blue-600 font-semibold mb-0.5">🏢 {{ $siswa->nama_industri }}</p>
                                        @endif

                                        <p class="text-xs font-medium text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                                            {{ $siswa->no_wa }}
                                        </p>
                                    </div>
                                </div>

                                @if($siswa->is_magang_aktif == '1')
                                    <span class="px-2 py-1 text-[10px] font-bold bg-green-100 text-green-700 rounded-md shrink-0">Magang Aktif</span>
                                @endif
                            </label>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">Belum ada data siswa yang memiliki nomor WA.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Tulis Pesan
                        </h3>
                    </div>

                    <div class="mb-4">
                        <textarea name="pesan" id="pesanArea" rows="5" onkeyup="updatePhonePreview()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm outline-none resize-none" placeholder="Halo Siswa SMKN 3 Tuban,&#10;&#10;Tulis pengumuman atau tagihan jurnal Anda di sini..."></textarea>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="button" onclick="submitWaBlast()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                            Kirim WA Blast Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Statistik Pengiriman WA</h3>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded">Real-Time</span>
                </div>
                <div class="flex justify-between items-end mb-2">
                    <span class="text-sm font-semibold text-gray-700">Total Log History:</span>
                    <span class="text-xl font-bold text-gray-900">{{ $statTotal }} Pesan</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 mb-2 flex overflow-hidden">
                    @php $persenSukses = $statTotal > 0 ? ($statTerkirim / $statTotal) * 100 : 0; @endphp
                    <div class="bg-green-500 h-2" style="width: {{ $persenSukses }}%"></div>
                    <div class="bg-red-500 h-2" style="width: {{ 100 - $persenSukses }}%"></div>
                </div>
                <div class="flex justify-between text-xs font-semibold mt-3">
                    <span class="text-gray-500 flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-green-500"></div> Sukses: <span class="text-gray-800">{{ $statTerkirim }}</span></span>
                    <span class="text-gray-500 flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-red-500"></div> Gagal: <span class="text-gray-800">{{ $statGagal }}</span></span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-8 flex justify-center items-center relative overflow-hidden" style="background: radial-gradient(circle at 50% 50%, #f1f5f9 0%, #e2e8f0 100%);">
                <div class="w-[280px] h-[520px] bg-white rounded-[40px] shadow-2xl relative border-[8px] border-gray-800 overflow-hidden flex flex-col">
                    <div class="absolute top-0 inset-x-0 h-6 bg-gray-800 rounded-b-xl w-32 mx-auto z-20"></div>
                    
                    <div class="bg-[#075e54] text-white p-4 pt-8 flex items-center gap-3 z-10 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-500 text-xs">SW</div>
                        <div class="leading-tight">
                            <p class="text-xs font-bold">Siswa SMKN 3 Tuban</p>
                            <p class="text-[10px] text-green-100">online</p>
                        </div>
                    </div>
                    
                    <div class="flex-1 bg-[#efeae2] p-4 flex flex-col overflow-y-auto relative" style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-size: cover;">
                        <div class="bg-white rounded-lg rounded-tl-none p-3 max-w-[85%] shadow-sm text-[11px] text-gray-800 leading-relaxed mt-4 relative">
                            <div id="phoneTextPreview" style="word-wrap: break-word;">
                                <span class='text-gray-400 italic'>Ketik pesan untuk melihat pratinjau...</span>
                            </div>
                            <span class="text-[9px] text-gray-400 float-right mt-1 ml-2">14:20 <svg class="w-3 h-3 inline text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                        </div>
                    </div>
                    
                    <div class="bg-[#f0f0f0] p-2 flex items-center gap-2 z-10">
                        <div class="flex-1 bg-white rounded-full py-2 px-4 flex items-center">
                            <span class="text-gray-400 text-xs">Ketik pesan</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#00897b] flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-8 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
            <div>
                <h3 class="font-bold text-gray-800 text-sm">Status Pengiriman Terakhir</h3>
                <p class="text-xs text-gray-500 mt-0.5">Log pengiriman sistem blast & chatbot</p>
            </div>
            
            <form action="{{ route('admin.wa-blast') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                <input type="text" name="log_search" value="{{ request('log_search') }}" placeholder="Cari Nama/WA/Jurusan..." class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 font-medium">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">Cari Log</button>
                @if(request('log_search'))
                    <a href="{{ route('admin.wa-blast') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-300 transition">Reset</a>
                @endif
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white text-gray-400 text-xs uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Penerima</th>
                        <th class="px-6 py-4">Jurusan</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $log->siswa ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }} flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $log->siswa ? substr($log->siswa->nama, 0, 2) : 'WA' }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $log->siswa->nama ?? 'Siswa Terhapus / Custom' }}</p>
                                    <p class="text-xs font-bold text-blue-600">{{ $log->no_wa_tujuan }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs font-medium">
                            {{ $log->siswa->jurusan->kode_jurusan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs font-medium">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs font-bold uppercase">
                            {{ str_replace('_', ' ', $log->jenis) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($log->status == 'sent')
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Terkirim
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-md">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Gagal
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                            Pencarian log tidak ditemukan atau belum ada riwayat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // FUNGSI FILTER LIST SISWA
    function applyFilters() {
        const valStatus = document.getElementById('filterStatus').value;
        const valJurusan = document.getElementById('filterJurusan').value;
        const valSearch = document.getElementById('filterSearch').value.toLowerCase();
        
        const items = document.querySelectorAll('.student-item');
        
        items.forEach(item => {
            const itemJurusan = item.getAttribute('data-jurusan');
            const itemAktif = item.getAttribute('data-aktif');
            const itemSearch = item.getAttribute('data-search');
            
            let matchStatus = (valStatus === 'all') || (valStatus === itemAktif);
            let matchJurusan = (valJurusan === 'all') || (valJurusan === itemJurusan);
            let matchSearch = itemSearch.includes(valSearch);
            
            if(matchStatus && matchJurusan && matchSearch) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
                item.querySelector('.target-checkbox').checked = false; // Matikan centang jika disembunyikan
            }
        });
        
        document.getElementById('selectAllCheckbox').checked = false;
        updateCounter();
    }

    // FUNGSI PILIH SEMUA (100% AKURAT)
    function toggleAllCheckboxes() {
        const isChecked = document.getElementById('selectAllCheckbox').checked;
        const items = document.querySelectorAll('.student-item');
        
        items.forEach(item => {
            // Hanya centang/uncentang item yang sedang TAMPIL (display tidak none)
            if (item.style.display !== 'none') {
                item.querySelector('.target-checkbox').checked = isChecked;
            }
        });
        
        updateCounter();
    }

    // FUNGSI UPDATE ANGKA TERPILIH
    function updateCounter() {
        const totalChecked = document.querySelectorAll('.target-checkbox:checked').length;
        document.getElementById('countSelected').innerText = totalChecked + ' Terpilih';
    }

    // FUNGSI UPDATE PREVIEW HP
    function updatePhonePreview() {
        const text = document.getElementById('pesanArea').value;
        const preview = document.getElementById('phoneTextPreview');
        
        if(!text.trim()) {
            preview.innerHTML = "<span class='text-gray-400 italic'>Ketik pesan untuk melihat pratinjau...</span>";
            return;
        }

        let htmlText = text.replace(/\n/g, '<br>');
        preview.innerHTML = htmlText;
    }

    // FUNGSI SUBMIT
    function submitWaBlast() {
        const totalChecked = document.querySelectorAll('.target-checkbox:checked').length;
        const pesan = document.getElementById('pesanArea').value.trim();

        if (totalChecked === 0) {
            alert('Peringatan: Anda belum memilih siswa satupun!');
            return;
        }
        
        if (pesan === '') {
            alert('Peringatan: Kolom pesan tidak boleh kosong!');
            return;
        }

        if (confirm(`Anda yakin akan mengirim pesan Blast ini ke ${totalChecked} nomor siswa?`)) {
            document.getElementById('formWaBlast').submit();
        }
    }

    // Inisialisasi awal
    updateCounter();
</script>
@endpush
@endsection