@extends('layouts.admin')

@section('title', 'Laporan & Cetak')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan & Cetak Rekap</h1>
        <p class="text-sm text-gray-500 mt-1">Unduh rekapitulasi data siswa magang dan nilai berdasarkan industri atau jurusan.</p>
    </div>
</header>

<div class="p-6 md:p-8 space-y-10">
    {{-- Form Cetak --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Per Industri -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Per Industri</h3>
            </div>
            <form action="{{ route('admin.laporan.export') }}" method="GET" target="_blank" class="space-y-5">
                <input type="hidden" name="jenis" value="industri">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Industri</label>
                    <select name="industri_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all">Semua Industri</option>
                        @foreach($industris as $ind)
                            <option value="{{ $ind->id }}">{{ $ind->nama_industri }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-sm transition-all">
                    Cetak Laporan PDF
                </button>
            </form>
        </div>

        <!-- Per Jurusan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Per Jurusan</h3>
            </div>
            <form action="{{ route('admin.laporan.export') }}" method="GET" target="_blank" class="space-y-5">
                <input type="hidden" name="jenis" value="jurusan">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Jurusan</label>
                    <select name="jurusan_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="all">Semua Jurusan</option>
                        @foreach($jurusans as $jur)
                            <option value="{{ $jur->id }}">{{ $jur->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-sm transition-all">
                    Cetak Laporan PDF
                </button>
            </form>
        </div>
    </div>

    {{-- Rekap per Industri --}}
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
            Rekap Data per Industri
        </h2>

        {{-- Search --}}
        <div class="mb-6">
            <div class="relative max-w-md">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchIndustry" placeholder="Cari nama industri..." 
                       class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>
        </div>

        <div id="industryList" class="space-y-5">
            @forelse($rekapIndustri as $item)
                <div class="industry-item bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-name="{{ strtolower($item['industri']->nama_industri) }}">
                    <button onclick="toggleAccordion(this)" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50/80 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-lg font-bold text-gray-800">{{ $item['industri']->nama_industri }}</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">{{ $item['total_siswa'] }} Siswa</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300 accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-content px-6 pb-6" style="display: none;">
                        @if($item['siswa']->isEmpty())
                            <p class="text-center text-gray-400 py-8">Belum ada siswa magang di industri ini.</p>
                        @else
                            <div class="overflow-x-auto mt-4 rounded-xl border border-gray-100">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                                            <th class="p-4 font-semibold">Nama Siswa</th>
                                            <th class="p-4 font-semibold">Jurusan</th>
                                            <th class="p-4 font-semibold text-center">Kehadiran (H/I/S/A)</th>
                                            <th class="p-4 font-semibold text-center">Jurnal (Setuju/Pending/Revisi)</th>
                                            <th class="p-4 font-semibold text-center">Laporan PKL</th>
                                            <th class="p-4 font-semibold text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach($item['siswa'] as $s)
                                            <tr class="hover:bg-gray-50/50 transition">
                                                <td class="p-4 font-medium text-gray-800">{{ $s->nama }}</td>
                                                <td class="p-4 text-sm text-gray-600">{{ $s->jurusan->nama_jurusan ?? '-' }}</td>
                                                <td class="p-4 text-center">
                                                    <div class="flex items-center justify-center gap-2 text-sm">
                                                        <span class="text-green-700 font-semibold">{{ $s->hadir }}</span>
                                                        <span class="text-gray-400">/</span>
                                                        <span class="text-yellow-700">{{ $s->izin }}</span>
                                                        <span class="text-gray-400">/</span>
                                                        <span class="text-blue-700">{{ $s->sakit }}</span>
                                                        <span class="text-gray-400">/</span>
                                                        <span class="text-red-600 font-semibold">{{ $s->alpha }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-4 text-center">
    <div class="flex items-center justify-center gap-2 text-sm">
        <span class="text-green-600 font-medium">{{ $s->jurnal_disetujui }}</span>
        <span class="text-gray-400">/</span>
        <span class="text-yellow-600 font-medium">{{ $s->jurnal_pending }}</span>
        <span class="text-gray-400">/</span>
        <span class="text-red-500 font-medium">{{ $s->jurnal_revisi }}</span>
    </div>
</td>
                                                <td class="p-4 text-center">
                                                    @if($s->laporan)
                                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                            @if($s->laporan->status == 'disetujui') bg-green-100 text-green-700
                                                            @elseif($s->laporan->status == 'pending') bg-yellow-100 text-yellow-700
                                                            @elseif($s->laporan->status == 'perlu_revisi') bg-red-100 text-red-700
                                                            @endif">
                                                            {{ ucfirst($s->laporan->status == 'perlu_revisi' ? 'Revisi' : $s->laporan->status) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 text-sm">Belum Upload</span>
                                                    @endif
                                                </td>
                                                <td class="p-4 text-center">
    <div class="flex flex-col gap-2 items-stretch">
        <a href="{{ route('admin.verifikasi.jurnal', ['siswa_id' => $s->id]) }}" 
           class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-100 hover:border-blue-300 transition">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Jurnal
        </a>
        <a href="{{ route('admin.verifikasi.laporan-pkl', ['siswa_id' => $s->id]) }}" 
           class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-purple-50 border border-purple-200 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-100 hover:border-purple-300 transition">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Laporan
        </a>
    </div>
</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-gray-500">Belum ada data siswa magang yang aktif.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Script Accordion & Search --}}
<script>
    // Accordion
    function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.accordion-icon');
        
        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.style.maxHeight = '0';
            content.style.opacity = '0';
            icon.style.transform = 'rotate(0deg)';
            setTimeout(() => {
                content.style.display = 'none';
            }, 300);
        }
    }

    document.querySelectorAll('.accordion-content').forEach(el => {
        el.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
        el.style.overflow = 'hidden';
        el.style.maxHeight = '0';
        el.style.opacity = '0';
    });

    // Search Filter
    const searchInput = document.getElementById('searchIndustry');
    const items = document.querySelectorAll('.industry-item');

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase().trim();
        
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(keyword)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

<style>
    .accordion-icon {
        transition: transform 0.3s ease;
    }
</style>
@endsection