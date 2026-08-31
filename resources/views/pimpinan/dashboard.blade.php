@extends('layouts.pimpinan')

@section('title', 'Dashboard Pimpinan')

@section('header_title', 'DASHBOARD MONITORING')
@section('header_breadcrumb', 'Overview')

@section('header_actions')
<form action="{{ route('pimpinan.dashboard') }}" method="GET" class="flex items-center gap-2 bg-white/50 p-1.5 rounded-xl border border-gray-250">
    <select name="tahun_ajaran" onchange="this.form.submit()" class="bg-transparent text-xs font-bold text-gray-700 outline-none px-2 cursor-pointer">
        @foreach($listTahun as $th)
            <option value="{{ $th }}" {{ $filterTahun == $th ? 'selected' : '' }}>TA {{ $th }}</option>
        @endforeach
    </select>
    <div class="h-4 w-px bg-gray-300"></div>
    <select name="semester" onchange="this.form.submit()" class="bg-transparent text-xs font-bold text-gray-700 outline-none px-2 cursor-pointer">
        <option value="Ganjil" {{ $filterSemester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
        <option value="Genap" {{ $filterSemester == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
    </select>
</form>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -right-2 -top-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
        <div class="relative z-10 space-y-2">
            <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                Periode Aktif
            </span>
            <h2 class="text-2xl md:text-3xl font-black">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h2>
            <p class="text-sm text-white/90 max-w-xl font-medium">
                Berikut adalah rangkuman data Prakerin siswa SMK Negeri 3 Tuban untuk <span class="font-bold">Semester {{ $filterSemester }} Tahun Ajaran {{ $filterTahun }}</span>.
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Total Siswa Terdaftar</p>
                <h3 class="text-3xl font-black text-gray-800 leading-none">{{ $stats['total_siswa'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center shrink-0 border border-orange-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Diterima Magang</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <h3 class="text-3xl font-black text-gray-800 leading-none">{{ $stats['siswa_diterima'] }}</h3>
                    <span class="text-[9px] font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded-md border border-green-150">{{ $stats['persentase_terpenuhi'] }}%</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 border border-green-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Surat Menunggu</p>
                <h3 class="text-3xl font-black {{ $stats['surat_pending'] > 0 ? 'text-red-650' : 'text-gray-850' }} leading-none">{{ $stats['surat_pending'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold text-xl shrink-0 border border-red-100">!</div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Total Industri Mitra</p>
                <h3 class="text-3xl font-black text-gray-800 leading-none">{{ $stats['total_industri'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0 border border-amber-100">🏢</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Status Magang Siswa ({{ $filterSemester }})</h3>
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <div class="relative w-44 h-44 shrink-0 mx-auto sm:mx-0">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-gray-800">{{ $stats['siswa_diterima'] + $stats['status_magang']['belum'] }}</span>
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Target</span>
                    </div>
                </div>
                <div class="flex-1 space-y-3 w-full">
                    <div class="p-3 bg-blue-50/50 border border-blue-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-blue-700">Aktif Magang</span>
                        <span class="font-black text-blue-800">{{ $stats['status_magang']['aktif'] }}</span>
                    </div>
                    <div class="p-3 bg-yellow-50/50 border border-yellow-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-yellow-700">Proses Penempatan</span>
                        <span class="font-black text-yellow-800">{{ $stats['status_magang']['proses'] }}</span>
                    </div>
                    <div class="p-3 bg-green-50/50 border border-green-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-green-700">Selesai Magang</span>
                        <span class="font-black text-green-800">{{ $stats['status_magang']['selesai'] }}</span>
                    </div>
                    <div class="p-3 bg-red-50/50 border border-red-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-red-700">Belum Penempatan</span>
                        <span class="font-black text-red-800">{{ $stats['status_magang']['belum'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Sebaran Siswa per Jurusan</h3>
            <div class="w-full h-64">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-white/50 flex items-center justify-between bg-white/30">
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Pengajuan Terbaru</h3>
            <a href="{{ route('pimpinan.approval.surat') }}" class="text-orange-600 hover:text-white hover:bg-orange-600 px-4 py-1.5 rounded-xl text-xs font-bold border border-orange-200 transition duration-200">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/30 border-b border-gray-150">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Industri</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($aktivitas as $item)
                        <tr class="hover:bg-orange-50/20 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-400 rounded-full flex items-center justify-center text-white font-extrabold text-xs uppercase shadow-sm shrink-0">
                                        {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate">{{ $item->siswa->nama ?? 'Siswa' }}</p>
                                        <p class="text-[10px] text-gray-450 font-medium">NISN: {{ $item->siswa->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200">
                                    {{ $item->siswa->jurusan->kode_jurusan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-semibold truncate max-w-[150px]">
                                {{ $item->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-50 text-yellow-750 border-yellow-200',
                                        'verified' => 'bg-blue-50 text-blue-750 border-blue-200',
                                        'approved' => 'bg-green-50 text-green-750 border-green-200',
                                        'ongoing' => 'bg-green-50 text-green-750 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-750 border-red-200'
                                    ];
                                    $class = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-3 py-1.5 {{ $class }} text-[9px] font-bold uppercase tracking-wider rounded-md border">
                                    {{ $item->status == 'pending' ? 'Menunggu' : $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'pending')
                                    <button onclick="openModal({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}', '{{ $item->industri->nama_industri ?? '-' }}', '{{ $item->created_at->format('d M Y') }}')" 
                                            class="px-4 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200">
                                        Review
                                    </button>
                                @else
                                    <span class="text-xs font-semibold text-gray-400">Telah Ditinjau</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">Tidak ada aktivitas pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modals')
<div id="reviewModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-150 flex flex-col my-auto" id="modalContainer">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-800 tracking-tight">Review Pengajuan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-650 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="p-6"></div>
        <div class="p-4 border-t border-gray-100 text-center bg-gray-50/50">
            <button onclick="closeModal()" class="px-8 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl text-xs transition duration-200">Tutup</button>
        </div>
    </div>
</div>

<script>
// --- LOGIKA CHARTS ---
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#94a3b8';

// 1. Grafik Donut (Status Magang)
new Chart(document.getElementById('statusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Aktif Magang', 'Proses Penempatan', 'Belum Ada Lokasi', 'Selesai Magang'],
        datasets: [{
            data: [
                {{ $stats['status_magang']['aktif'] }}, 
                {{ $stats['status_magang']['proses'] }}, 
                {{ $stats['status_magang']['belum'] }},
                {{ $stats['status_magang']['selesai'] }}
            ],
            backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981'], 
            borderWidth: 0, 
            hoverOffset: 4, 
            spacing: 5,     
            borderRadius: 8 
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false, 
        cutout: '75%', 
        plugins: { 
            legend: { display: false }
        } 
    }
});

// 2. Grafik Batang (Sebaran Jurusan)
const jLabels = @json($sebaranJurusan->pluck('kode_jurusan'));
const jValues = @json($sebaranJurusan->pluck('siswas_count'));

const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
const jurusanGradient = jurusanCtx.createLinearGradient(0, 0, 0, 300);
jurusanGradient.addColorStop(0, '#f97316');
jurusanGradient.addColorStop(1, '#f59e0b');

new Chart(jurusanCtx, {
    type: 'bar',
    data: {
        labels: jLabels,
        datasets: [{ 
            label: 'Jumlah Siswa',
            data: jValues, 
            backgroundColor: jurusanGradient, 
            borderRadius: 6, 
            borderSkipped: false,
            barThickness: 24
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false, 
        plugins: { 
            legend: { display: false },
            tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8, displayColors: false }
        }, 
        scales: { 
            y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { padding: 10, font: { size: 11 }, stepSize: 1 } }, 
            x: { grid: { display: false }, ticks: { padding: 10, font: { weight: '700', size: 11 } } } 
        } 
    }
});

// --- FUNGSI MODAL ---
function openModal(id, nama, jurusan, industri, tgl) {
    document.getElementById('modalContent').innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</p>
                    <p class="text-sm font-bold text-gray-800">${nama}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jurusan</p>
                    <p class="text-sm font-bold text-gray-800">${jurusan}</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Industri Tujuan</p>
                <p class="text-sm font-bold text-gray-800">${industri}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                <p class="text-sm font-bold text-gray-800">${tgl}</p>
            </div>
        </div>`;
    
    const modal = document.getElementById('reviewModal');
    const container = document.getElementById('modalContainer');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() { 
    const modal = document.getElementById('reviewModal');
    const container = document.getElementById('modalContainer');
    if (!modal || modal.classList.contains('hidden')) return;
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden'); 
        document.body.classList.remove('overflow-hidden');
    }, 200);
}

document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection