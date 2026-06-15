@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('header_breadcrumb', 'DASHBOARD')
@section('header_title', 'DASHBOARD UTAMA')

@section('content')
<div class="p-0">
    <!-- Monitor info banner -->
    <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-4 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white text-sm shrink-0">
                📊
            </div>
            <div>
                <p class="text-xs font-black text-blue-800 uppercase tracking-wider">Mode Pemantauan Aktif</p>
                <p class="text-[11px] text-blue-600 font-semibold mt-0.5">Menampilkan status Prakerin semester {{ $filterSemester }} Tahun Ajaran {{ $filterTahun }}</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="shrink-0 self-start md:self-auto">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 bg-white/95 p-1 rounded-xl border border-blue-200/50 shadow-[0_2px_8px_rgba(59,130,246,0.04)] relative" id="filterForm">
                <input type="hidden" name="tahun_ajaran" id="inputTahun" value="{{ $filterTahun }}">
                <input type="hidden" name="semester" id="inputSemester" value="{{ $filterSemester }}">

                <!-- Dropdown Tahun Ajaran -->
                <div class="relative inline-block text-left" id="dropdownTahunContainer">
                    <button type="button" onclick="toggleCustomDropdown('dropdownTahun')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-gray-700 hover:bg-blue-50/50 transition duration-200">
                        <span>TA {{ $filterTahun }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" id="arrowTahun" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <!-- Custom Menu List -->
                    <div id="dropdownTahun" class="hidden absolute left-0 mt-2.5 w-44 bg-white/95 backdrop-blur-md rounded-xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden z-50 py-1 transition-all duration-200">
                        @foreach($listTahun as $th)
                            <button type="button" onclick="selectDropdownOption('tahun_ajaran', '{{ $th }}')" class="w-full text-left px-4 py-2 text-[11px] font-bold text-gray-650 hover:bg-blue-50 hover:text-blue-600 transition flex items-center justify-between">
                                <span>TA {{ $th }}</span>
                                @if($filterTahun == $th)
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="h-4 w-px bg-gray-200"></div>

                <!-- Dropdown Semester -->
                <div class="relative inline-block text-left" id="dropdownSemesterContainer">
                    <button type="button" onclick="toggleCustomDropdown('dropdownSemester')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-gray-700 hover:bg-blue-50/50 transition duration-200">
                        <span>{{ $filterSemester }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" id="arrowSemester" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <!-- Custom Menu List -->
                    <div id="dropdownSemester" class="hidden absolute right-0 mt-2.5 w-40 bg-white/95 backdrop-blur-md rounded-xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden z-50 py-1 transition-all duration-200">
                        <button type="button" onclick="selectDropdownOption('semester', 'Ganjil')" class="w-full text-left px-4 py-2 text-[11px] font-bold text-gray-650 hover:bg-blue-50 hover:text-blue-600 transition flex items-center justify-between">
                            <span>Ganjil</span>
                            @if($filterSemester == 'Ganjil')
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                        <button type="button" onclick="selectDropdownOption('semester', 'Genap')" class="w-full text-left px-4 py-2 text-[11px] font-bold text-gray-650 hover:bg-blue-50 hover:text-blue-600 transition flex items-center justify-between">
                            <span>Genap</span>
                            @if($filterSemester == 'Genap')
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/65 backdrop-blur-md p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] border border-white/50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Siswa Terdaftar</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_siswa'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/20 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] border border-white/50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div>
                <p class="text-gray-500 text-sm mb-1">Diterima Magang</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['siswa_diterima'] }}</h3>
                <span class="text-[9px] font-extrabold text-green-600 bg-green-50 px-2 py-0.5 rounded-full mt-2 inline-block border border-green-100">{{ $stats['persentase_terpenuhi'] }}% Terpenuhi</span>
            </div>
            <div class="w-12 h-12 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] border border-white/50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div>
                <p class="text-gray-500 text-sm mb-1">Surat Menunggu</p>
                <h3 class="text-2xl font-bold {{ $stats['surat_pending'] > 0 ? 'text-red-500' : 'text-gray-800' }}">{{ $stats['surat_pending'] }}</h3>
                @if($stats['surat_pending'] > 0)
                    <span class="text-[9px] font-extrabold text-red-500 bg-red-50 px-2 py-0.5 rounded-full mt-2 inline-block border border-red-100">Perlu Verifikasi</span>
                @else
                    <span class="text-[9px] font-extrabold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full mt-2 inline-block border border-gray-100">Bersih</span>
                @endif
            </div>
            <div class="w-12 h-12 bg-red-500/10 border border-red-500/20 text-red-650 rounded-xl flex items-center justify-center font-bold">!</div>
        </div>

        <div class="bg-white/65 backdrop-blur-md p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] border border-white/50 flex items-center justify-between hover:shadow-md transition duration-300">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Industri</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_industri'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-500/10 border border-yellow-500/20 text-yellow-600 rounded-xl flex items-center justify-center font-bold">🏢</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white/65 backdrop-blur-md p-6 rounded-2xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
            <h3 class="text-base font-black text-gray-800 uppercase tracking-wider mb-6">Status Magang Siswa ({{ $filterSemester }})</h3>
            <div class="flex flex-col xl:flex-row items-center gap-8">
                <div class="relative w-44 h-44 shrink-0">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-gray-800 leading-none">{{ $stats['siswa_diterima'] + $stats['status_magang']['belum'] }}</span>
                        <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mt-1">Target</span>
                    </div>
                </div>
                <div class="flex-1 space-y-3 w-full">
                    <div class="p-3 bg-blue-500/5 border border-blue-500/10 rounded-xl flex justify-between items-center hover:scale-[1.02] transition">
                        <span class="text-xs font-bold text-blue-700">Aktif Magang</span>
                        <span class="font-black text-blue-800">{{ $stats['status_magang']['aktif'] }}</span>
                    </div>
                    <div class="p-3 bg-yellow-500/5 border border-yellow-500/10 rounded-xl flex justify-between items-center hover:scale-[1.02] transition">
                        <span class="text-xs font-bold text-yellow-700">Proses Plotting</span>
                        <span class="font-black text-yellow-800">{{ $stats['status_magang']['proses'] }}</span>
                    </div>
                    <div class="p-3 bg-red-500/5 border border-red-100/10 rounded-xl flex justify-between items-center hover:scale-[1.02] transition">
                        <span class="text-xs font-bold text-red-700">Belum Ada Lokasi</span>
                        <span class="font-black text-red-800">{{ $stats['status_magang']['belum'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md p-6 rounded-2xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
            <h3 class="text-base font-black text-gray-800 uppercase tracking-wider mb-6">Sebaran per Jurusan (Semester ini)</h3>
            <div class="h-64">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru Table -->
    <div class="bg-white/65 backdrop-blur-md rounded-2xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.015)] overflow-hidden mt-8">
        <div class="p-5 sm:p-6 border-b border-gray-150/50 flex items-center justify-between bg-white/40">
            <h3 class="text-base font-black text-gray-800 uppercase tracking-wider">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.data.surat') }}" class="text-blue-600 hover:text-white hover:bg-blue-600 px-4 py-1.5 rounded-xl text-xs font-black border border-blue-200 transition duration-200">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/30 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Industri</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($aktivitasTerbaru as $aktivitas)
                        <tr class="hover:bg-blue-50/30 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-extrabold text-[11px] uppercase shadow-sm shrink-0">
                                        {{ substr($aktivitas->siswa->nama ?? 'S', 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate">{{ $aktivitas->siswa->nama ?? 'Siswa Terhapus' }}</p>
                                        <p class="text-[10px] text-gray-400 font-semibold">NISN: {{ $aktivitas->siswa->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-extrabold text-gray-700 bg-white/80 px-2.5 py-1 rounded-lg border border-gray-200">
                                    {{ $aktivitas->siswa->jurusan->kode_jurusan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-700 font-semibold truncate max-w-[200px]">
                                {{ $aktivitas->industri->nama_industri ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-50 text-yellow-750 border-yellow-200/50',
                                        'verified' => 'bg-blue-50 text-blue-750 border-blue-200/50',
                                        'approved' => 'bg-green-50 text-green-750 border-green-200/50',
                                        'ongoing' => 'bg-green-50 text-green-750 border-green-200/50',
                                        'rejected' => 'bg-red-50 text-red-755 border-red-200/50'
                                    ];
                                    $class = $statusClasses[$aktivitas->status] ?? 'bg-gray-50 text-gray-655 border-gray-200/50';
                                @endphp
                                <span class="px-3 py-1.5 {{ $class }} text-[9px] font-black uppercase tracking-wider rounded-lg border">
                                    {{ $aktivitas->status == 'pending' ? 'Menunggu' : $aktivitas->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown({{ $aktivitas->id }})" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                                    </button>
                                    
                                    <div id="dropdown-{{ $aktivitas->id }}" class="hidden absolute right-0 mt-1 w-40 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-150 z-50 overflow-hidden">
                                        <div class="py-1">
                                            <a href="{{ route('admin.data.surat') }}" class="block px-4 py-2.5 text-xs text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 transition">
                                                Lihat Detail
                                            </a>
                                            
                                            <div class="h-px bg-gray-100 my-1"></div>
                                            <button onclick="hapusPengajuan({{ $aktivitas->id }})" class="block w-full text-left px-4 py-2.5 text-xs text-red-600 font-extrabold hover:bg-red-50 transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-xs font-semibold text-gray-500 bg-white/30">Belum ada aktivitas pengajuan magang terbaru.</td>
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

Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
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

function toggleCustomDropdown(id) {
    const target = document.getElementById(id);
    const arrow = id === 'dropdownTahun' ? document.getElementById('arrowTahun') : document.getElementById('arrowSemester');
    
    // Close other dropdown
    const otherId = id === 'dropdownTahun' ? 'dropdownSemester' : 'dropdownTahun';
    const otherTarget = document.getElementById(otherId);
    const otherArrow = id === 'dropdownTahun' ? document.getElementById('arrowSemester') : document.getElementById('arrowTahun');
    if (otherTarget && !otherTarget.classList.contains('hidden')) {
        otherTarget.classList.add('hidden');
        if (otherArrow) otherArrow.classList.remove('rotate-180');
    }

    if (target) {
        const isHidden = target.classList.contains('hidden');
        if (isHidden) {
            target.classList.remove('hidden');
            if (arrow) arrow.classList.add('rotate-180');
        } else {
            target.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180');
        }
    }
}

function selectDropdownOption(name, value) {
    if (name === 'tahun_ajaran') {
        document.getElementById('inputTahun').value = value;
    } else if (name === 'semester') {
        document.getElementById('inputSemester').value = value;
    }
    document.getElementById('filterForm').submit();
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative.inline-block')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }

    // Click outside for custom filter dropdowns
    const dropdownTahun = document.getElementById('dropdownTahun');
    const containerTahun = document.getElementById('dropdownTahunContainer');
    const arrowTahun = document.getElementById('arrowTahun');
    if (dropdownTahun && containerTahun && !containerTahun.contains(event.target)) {
        dropdownTahun.classList.add('hidden');
        if (arrowTahun) arrowTahun.classList.remove('rotate-180');
    }

    const dropdownSemester = document.getElementById('dropdownSemester');
    const containerSemester = document.getElementById('dropdownSemesterContainer');
    const arrowSemester = document.getElementById('arrowSemester');
    if (dropdownSemester && containerSemester && !containerSemester.contains(event.target)) {
        dropdownSemester.classList.add('hidden');
        if (arrowSemester) arrowSemester.classList.remove('rotate-180');
    }
});

function hapusPengajuan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')) {
        alert('Fitur hapus akan segera ditambahkan. ID: ' + id);
    }
}
</script>
@endsection