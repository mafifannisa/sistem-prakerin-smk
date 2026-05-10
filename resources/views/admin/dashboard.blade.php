@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
<header class="bg-white border-b border-gray-200 px-6 py-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Utama</h1>
            <p class="text-sm text-gray-500 font-medium">
                Monitor Prakerin: <span class="text-blue-600 font-bold">Semester {{ $filterSemester }} TA {{ $filterTahun }}</span>
            </p>
        </div>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 lg:gap-6">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200" id="filterForm">
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

            <div class="flex items-center gap-4 w-full md:w-auto justify-between border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6">
                <button onclick="toggleNotifikasi()" class="relative text-gray-500 hover:text-blue-600 transition p-2 bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($stats['surat_pending'] > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $stats['surat_pending'] }}</span>
                    @endif
                </button>

                <div class="text-right shrink-0">
                    <p class="text-sm font-bold text-gray-800 leading-none">{{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('l, d M') }}</p>
                    <p class="text-[11px] font-medium text-gray-500 mt-1 uppercase tracking-wider">{{ \Carbon\Carbon::now()->year }}</p>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Total Siswa Terdaftar</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $stats['total_siswa'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Diterima Magang</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $stats['siswa_diterima'] }}</h3>
                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full mt-2 inline-block">{{ $stats['persentase_terpenuhi'] }}% Terpenuhi</span>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Surat Menunggu</p>
                <h3 class="text-3xl font-black {{ $stats['surat_pending'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $stats['surat_pending'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold">!</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Total Industri</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $stats['total_industri'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center font-bold">🏢</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Status Magang Siswa ({{ $filterSemester }})</h3>
            <div class="flex flex-col xl:flex-row items-center gap-8">
                <div class="relative w-44 h-44">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-gray-800">{{ $stats['siswa_diterima'] + $stats['status_magang']['belum'] }}</span>
                        <span class="text-[9px] font-bold text-gray-400 uppercase">Target</span>
                    </div>
                </div>
                <div class="flex-1 space-y-3 w-full">
                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-blue-700">Aktif Magang</span>
                        <span class="font-black text-blue-800">{{ $stats['status_magang']['aktif'] }}</span>
                    </div>
                    <div class="p-3 bg-yellow-50 border border-yellow-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-yellow-700">Proses Plotting</span>
                        <span class="font-black text-yellow-800">{{ $stats['status_magang']['proses'] }}</span>
                    </div>
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-red-700">Belum Ada Lokasi</span>
                        <span class="font-black text-red-800">{{ $stats['status_magang']['belum'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Sebaran per Jurusan (Semester ini)</h3>
            <div class="h-64">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.data.surat') }}" class="text-blue-600 hover:text-white hover:bg-blue-600 px-4 py-1.5 rounded-lg text-sm font-bold border border-blue-200 transition">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Industri</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($aktivitasTerbaru as $aktivitas)
                        <tr class="hover:bg-blue-50/50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center text-blue-700 font-extrabold text-xs uppercase shadow-sm shrink-0">
                                        {{ substr($aktivitas->siswa->nama ?? 'S', 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $aktivitas->siswa->nama ?? 'Siswa Terhapus' }}</p>
                                        <p class="text-[11px] text-gray-500 font-medium">NISN: {{ $aktivitas->siswa->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200">
                                    {{ $aktivitas->siswa->jurusan->kode_jurusan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 font-semibold truncate max-w-[150px]">
                                {{ $aktivitas->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'verified' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'approved' => 'bg-green-50 text-green-700 border-green-200',
                                        'ongoing' => 'bg-green-50 text-green-700 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200'
                                    ];
                                    $class = $statusClasses[$aktivitas->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-3 py-1.5 {{ $class }} text-[10px] font-bold uppercase tracking-wider rounded-md border">
                                    {{ $aktivitas->status == 'pending' ? 'Menunggu' : $aktivitas->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown({{ $aktivitas->id }})" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                                    </button>
                                    
                                    <div id="dropdown-{{ $aktivitas->id }}" class="hidden absolute right-0 mt-1 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                        <div class="py-1">
                                            <a href="{{ route('admin.data.surat') }}" class="block px-4 py-2.5 text-sm text-gray-700 font-medium hover:bg-blue-50 hover:text-blue-600 transition">
                                                Lihat Detail
                                            </a>
                                            
                                            <div class="h-px bg-gray-100 my-1"></div>
                                            <button onclick="hapusPengajuan({{ $aktivitas->id }})" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 font-bold hover:bg-red-50 transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleNotifikasi() {
    const dropdown = document.getElementById('notifikasiDropdown');
    if (dropdown) dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notifikasiDropdown');
    const bell = event.target.closest('button[onclick="toggleNotifikasi()"]');
    if (dropdown && !dropdown.contains(event.target) && !bell) {
        dropdown.classList.add('hidden');
    }
});

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#94a3b8';

const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Aktif Magang', 'Proses Penempatan', 'Belum Ada Lokasi'],
        datasets: [{
            data: [
                {{ $stats['status_magang']['aktif'] }},
                {{ $stats['status_magang']['proses'] }},
                {{ $stats['status_magang']['belum'] }}
            ],
            backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'],
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
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 12,
                cornerRadius: 8,
                displayColors: false,
                bodyFont: { font: { weight: 'bold' } }
            }
        }
    }
});

const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
const jurusanLabels = @json($sebaranJurusan->pluck('kode_jurusan'));
const jurusanValues = @json($sebaranJurusan->pluck('total'));

const jurusanGradient = jurusanCtx.createLinearGradient(0, 0, 0, 300);
jurusanGradient.addColorStop(0, '#3b82f6');
jurusanGradient.addColorStop(1, '#6366f1');

new Chart(jurusanCtx, {
    type: 'bar',
    data: {
        labels: jurusanLabels,
        datasets: [{
            label: 'Jumlah Siswa',
            data: jurusanValues,
            backgroundColor: jurusanGradient,
            borderRadius: 6,
            borderSkipped: false,
            barThickness: 30
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 12,
                cornerRadius: 8,
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f8fafc', drawBorder: false },
                ticks: { padding: 10, font: { size: 11 }, stepSize: 1 }
            },
            x: {
                grid: { display: false },
                ticks: { padding: 10, font: { weight: '700', size: 11 }, maxRotation: 0 }
            }
        }
    }
});

function toggleDropdown(id) {
    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
        if (dropdown.id !== 'dropdown-' + id) dropdown.classList.add('hidden');
    });
    document.getElementById('dropdown-' + id).classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative.inline-block')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

function hapusPengajuan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')) {
        alert('Fitur hapus akan segera ditambahkan. ID: ' + id);
    }
}
</script>
@endsection