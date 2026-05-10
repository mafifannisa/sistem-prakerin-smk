@extends('layouts.pimpinan')

@section('title', 'Dashboard Pimpinan')

@section('content')
<header class="bg-white border-b border-gray-200 px-6 py-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Pimpinan</h1>
            <p class="text-sm text-gray-500 font-medium">
                Monitor Prakerin: <span class="text-blue-600 font-bold">Semester {{ $filterSemester }} TA {{ $filterTahun }}</span>
            </p>
        </div>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
            <form action="{{ route('pimpinan.dashboard') }}" method="GET" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
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

            <div class="flex items-center gap-4 border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6">
                <div class="relative">
                    <button onclick="toggleNotifikasi()" class="relative text-gray-500 hover:text-blue-600 transition p-2 bg-gray-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notifBadge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">0</span>
                    </button>

                    <div id="notifikasiDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                            <h4 class="font-bold text-gray-800 text-sm">Notifikasi</h4>
                            <button onclick="markAllAsRead()" class="text-[10px] font-bold text-blue-600 hover:underline uppercase">Baca Semua</button>
                        </div>
                        <div id="notifList" class="max-h-96 overflow-y-auto"></div>
                    </div>
                </div>

                <div class="text-right shrink-0 hidden md:block">
                    <p class="text-sm font-bold text-gray-800 leading-none">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d M') }}</p>
                    <p class="text-[11px] font-medium text-gray-500 mt-1 uppercase tracking-wider">{{ \Carbon\Carbon::now()->year }}</p>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Total Siswa Terdaftar</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_siswa'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Diterima Magang</p>
                <div class="flex items-center gap-2 mt-1">
                    <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $stats['siswa_diterima'] }}</h3>
                    <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-1 rounded-md border border-green-200">{{ $stats['persentase_terpenuhi'] }}% Terpenuhi</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Surat Menunggu</p>
                <h3 class="text-3xl font-black {{ $stats['surat_pending'] > 0 ? 'text-red-600' : 'text-gray-900' }} leading-none">{{ $stats['surat_pending'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold text-xl shrink-0">!</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Total Industri</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_industri'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center font-bold text-xl shrink-0">🏢</div>
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
                    <div class="p-3 bg-green-50 border border-green-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-green-700">Selesai Magang</span>
                        <span class="font-black text-green-800">{{ $stats['status_magang']['selesai'] }}</span>
                    </div>
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-red-700">Belum Ada Lokasi</span>
                        <span class="font-black text-red-800">{{ $stats['status_magang']['belum'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Sebaran per Jurusan (Semester ini)</h3>
            <div class="w-full h-64">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Periode {{ $filterSemester }} {{ $filterTahun }}</h3>
            <a href="{{ route('pimpinan.approval.surat') }}" class="text-blue-600 hover:text-white hover:bg-blue-600 px-4 py-1.5 rounded-lg text-sm font-bold border border-blue-200 transition">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Industri</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($aktivitas as $item)
                        <tr class="hover:bg-blue-50/50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center text-blue-700 font-extrabold text-xs uppercase shadow-sm shrink-0">
                                        {{ substr($item->siswa->nama ?? 'S', 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $item->siswa->nama ?? 'Siswa' }}</p>
                                        <p class="text-[11px] text-gray-500 font-medium">NISN: {{ $item->siswa->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200">
                                    {{ $item->siswa->jurusan->kode_jurusan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 font-semibold truncate max-w-[150px]">
                                {{ $item->industri->nama_industri ?? '-' }}
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
                                    $class = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-3 py-1.5 {{ $class }} text-[10px] font-bold uppercase tracking-wider rounded-md border">
                                    {{ $item->status == 'pending' ? 'Menunggu' : $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'pending')
                                    <button onclick="openModal({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}', '{{ $item->industri->nama_industri ?? '-' }}', '{{ $item->created_at->format('d M Y') }}')" 
                                            class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                        Review
                                    </button>
                                @else
                                    <button onclick="alert('Pengajuan sudah diproses.')" 
                                            class="px-4 py-1.5 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed border border-gray-200">
                                        Selesai
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">Tidak ada aktivitas pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="reviewModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Review Pengajuan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="p-6"></div>
        <div class="p-4 border-t border-gray-100 text-center">
            <button onclick="closeModal()" class="px-8 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Tutup</button>
        </div>
    </div>
</div>

<script>
// --- LOGIKA NOTIFIKASI ---
let readNotificationIds = JSON.parse(sessionStorage.getItem('readNotifIds') || '[]');

function toggleNotifikasi() {
    const dropdown = document.getElementById('notifikasiDropdown');
    dropdown.classList.toggle('hidden');
    if (!dropdown.classList.contains('hidden')) { loadNotifikasi(); }
}

function loadNotifikasi() {
    fetch('/pimpinan/notifikasi')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('notifList');
            const badge = document.getElementById('notifBadge');
            const unread = data.notifications.filter(n => !readNotificationIds.includes(n.id));
            
            badge.textContent = unread.length > 9 ? '9+' : unread.length;
            badge.classList.toggle('hidden', unread.length === 0);
            
            list.innerHTML = data.notifications.length ? data.notifications.map(n => `
                <div class="p-4 border-b border-gray-100 ${readNotificationIds.includes(n.id) ? 'bg-white' : 'bg-blue-50'} cursor-pointer hover:bg-gray-50 transition" onclick="markRead(${n.id})">
                    <p class="font-bold text-sm text-gray-800">${n.judul}</p>
                    <p class="text-xs text-gray-500 mt-1">${n.pesan}</p>
                </div>
            `).join('') : '<div class="p-8 text-center text-sm text-gray-400 font-medium">Tidak ada notifikasi</div>';
        });
}

function markRead(id) {
    if(!readNotificationIds.includes(id)) { 
        readNotificationIds.push(id); 
        sessionStorage.setItem('readNotifIds', JSON.stringify(readNotificationIds)); 
    }
    loadNotifikasi();
}

function markAllAsRead() {
    fetch('/pimpinan/notifikasi').then(r => r.json()).then(d => { 
        d.notifications.forEach(n => { 
            if(!readNotificationIds.includes(n.id)) readNotificationIds.push(n.id); 
        }); 
        sessionStorage.setItem('readNotifIds', JSON.stringify(readNotificationIds)); 
        loadNotifikasi(); 
    });
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notifikasiDropdown');
    const button = event.target.closest('button[onclick="toggleNotifikasi()"]');
    if (dropdown && !dropdown.contains(event.target) && !button) {
        dropdown.classList.add('hidden');
    }
});

// --- LOGIKA CHARTS ---
// --- LOGIKA CHARTS ---
Chart.defaults.font.family = "'Inter', sans-serif";
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
                {{ $stats['status_magang']['selesai'] }} // DATA BARU
            ],
            // Warna: Biru, Kuning, Merah, Hijau (#10b981)
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
jurusanGradient.addColorStop(0, '#3b82f6');
jurusanGradient.addColorStop(1, '#6366f1');

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
            barThickness: 30 
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false, 
        plugins: { 
            legend: { display: false },
            tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 8, displayColors: false }
        }, 
        scales: { 
            y: { beginAtZero: true, grid: { color: '#f8fafc', drawBorder: false }, ticks: { padding: 10, font: { size: 11 }, stepSize: 1 } }, 
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
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Nama Siswa</p>
                    <p class="text-sm font-bold text-gray-800">${nama}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Jurusan</p>
                    <p class="text-sm font-bold text-gray-800">${jurusan}</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Industri Tujuan</p>
                <p class="text-sm font-bold text-gray-800">${industri}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Tanggal Pengajuan</p>
                <p class="text-sm font-bold text-gray-800">${tgl}</p>
            </div>
        </div>`;
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeModal() { 
    document.getElementById('reviewModal').classList.add('hidden'); 
}
</script>
@endsection