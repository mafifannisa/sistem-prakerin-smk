@extends('layouts.pimpinan')

@section('title', 'Statistik & Laporan')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-800">Statistik & Laporan Magang</h1>
            <p class="text-sm text-gray-500 font-medium">
                Analisis Data Prakerin: <span class="text-blue-600 font-bold">Semester {{ $filterSemester }} TA {{ $filterTahun }}</span>
            </p>
        </div>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
            <form action="{{ route('pimpinan.statistik') }}" method="GET" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
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
            
            <div class="text-right border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6">
                <p class="text-sm font-bold text-gray-800 leading-none">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d M') }}</p>
                <p class="text-[11px] font-medium text-gray-500 mt-1 uppercase tracking-wider">{{ \Carbon\Carbon::now()->year }}</p>
            </div>
        </div>
    </div>
</header>

<div class="p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Siswa Magang Aktif</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalSiswa }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Mitra Industri</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalIndustri }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center shrink-0 font-bold text-xl">🏢</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Laporan Selesai</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalLaporan }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1 truncate">Rata-rata Hadir</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $kehadiranRate }}<span class="text-xl">%</span></h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0 font-bold text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-8">Distribusi Status Siswa</h3>
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="relative w-56 h-56 shrink-0">
                <canvas id="statusChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-black text-gray-800">
                        {{ $statusData['pending'] + $statusData['verified'] + $statusData['approved'] + $statusData['ongoing'] + $statusData['completed'] + $statusData['rejected'] }}
                    </span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total</span>
                </div>
            </div>

            <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 gap-5 w-full">
                <div class="p-5 border border-yellow-100 bg-yellow-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-md">
                    <p class="text-[10px] font-bold text-yellow-600 uppercase mb-1">Menunggu</p>
                    <p class="text-3xl font-black text-yellow-700">{{ $statusData['pending'] }}</p>
                </div>
                <div class="p-5 border border-orange-100 bg-orange-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-md">
                    <p class="text-[10px] font-bold text-orange-600 uppercase mb-1">Verifikasi</p>
                    <p class="text-3xl font-black text-orange-700">{{ $statusData['verified'] }}</p>
                </div>
                <div class="p-5 border border-purple-100 bg-purple-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-md">
                    <p class="text-[10px] font-bold text-purple-600 uppercase mb-1">Setuju</p>
                    <p class="text-3xl font-black text-purple-700">{{ $statusData['approved'] }}</p>
                </div>
                <div class="p-5 border border-blue-100 bg-blue-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-md">
                    <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">Aktif</p>
                    <p class="text-3xl font-black text-blue-700">{{ $statusData['ongoing'] }}</p>
                </div>
                <div class="p-5 border border-red-100 bg-red-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-md">
                    <p class="text-[10px] font-bold text-red-600 uppercase mb-1">Tolak</p>
                    <p class="text-3xl font-black text-red-700">{{ $statusData['rejected'] }}</p>
                </div>
                <div class="p-5 border border-green-100 bg-green-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-md">
                    <p class="text-[10px] font-bold text-green-600 uppercase mb-1">Selesai</p>
                    <p class="text-3xl font-black text-green-700">{{ $statusData['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Tren Pengajuan Magang (Bulan)</h3>
            <div class="w-full h-72">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Top 5 Mitra Industri</h3>
            <div class="space-y-4">
                @forelse($industriData as $namaIndustri => $count)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($namaIndustri, 0, 2) }}
                            </div>
                            <p class="font-bold text-gray-800 text-sm truncate max-w-[120px]">{{ $namaIndustri }}</p>
                        </div>
                        <span class="px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-lg">{{ $count }} Siswa</span>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 font-bold italic border-2 border-dashed border-gray-200 rounded-2xl">Belum ada data industri</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Distribusi per Jurusan</h3>
            </div>
            <div class="w-full h-64">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Rekapitulasi Kinerja Siswa</h3>
            
            <div class="mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-gray-600 uppercase tracking-widest text-[10px]">Tingkat Kehadiran Keseluruhan</span>
                    <span class="font-black text-blue-600">{{ $kehadiranRate }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 mb-3">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $kehadiranRate }}%"></div>
                </div>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-green-50 p-2 rounded-xl border border-green-100"><p class="text-[9px] font-bold text-green-600 uppercase">Hadir</p><p class="font-black text-gray-800">{{ $hadir }}</p></div>
                    <div class="bg-yellow-50 p-2 rounded-xl border border-yellow-100"><p class="text-[9px] font-bold text-yellow-600 uppercase">Izin</p><p class="font-black text-gray-800">{{ $izin }}</p></div>
                    <div class="bg-orange-50 p-2 rounded-xl border border-orange-100"><p class="text-[9px] font-bold text-orange-600 uppercase">Sakit</p><p class="font-black text-gray-800">{{ $sakit }}</p></div>
                    <div class="bg-red-50 p-2 rounded-xl border border-red-100"><p class="text-[9px] font-bold text-red-600 uppercase">Alpha</p><p class="font-black text-gray-800">{{ $alpha }}</p></div>
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-gray-600 uppercase tracking-widest text-[10px]">Penyelesaian Jurnal Harian</span>
                    <span class="font-black text-purple-600">{{ $totalJurnal }} Total</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div class="bg-purple-50 p-4 rounded-2xl border border-purple-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-purple-600 uppercase">Disetujui</span>
                        <span class="text-xl font-black text-gray-800">{{ $jurnalDisetujui }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Pending</span>
                        <span class="text-xl font-black text-gray-800">{{ $jurnalPending }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // ---------------------------------------------------------
    // 1. DONUT CHART (6 STATUS)
    // ---------------------------------------------------------
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Verifikasi', 'Setuju', 'Aktif', 'Tolak', 'Selesai'],
            datasets: [{
                data: [
                    {{ $statusData['pending'] }}, 
                    {{ $statusData['verified'] }}, 
                    {{ $statusData['approved'] }}, 
                    {{ $statusData['ongoing'] }}, 
                    {{ $statusData['rejected'] }}, 
                    {{ $statusData['completed'] }}
                ],
                backgroundColor: [
                    '#f59e0b', // Kuning (Menunggu)
                    '#f97316', // Orange (Verifikasi)
                    '#a855f7', // Ungu (Setuju)
                    '#3b82f6', // Biru (Aktif)
                    '#ef4444', // Merah (Tolak)
                    '#10b981'  // Hijau (Selesai)
                ],
                borderWidth: 0,
                hoverOffset: 6,
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
                    bodyFont: { weight: 'bold' }
                }
            }
        }
    });

    // ---------------------------------------------------------
    // 2. LINE CHART (TREN BULANAN)
    // ---------------------------------------------------------
    const monthlyLabels = @json($monthlyLabels);
    const monthlyValues = @json($monthlyValues);
    
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyGradient = monthlyCtx.createLinearGradient(0, 0, 0, 300);
    monthlyGradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    monthlyGradient.addColorStop(1, 'rgba(59, 130, 246, 0.01)');

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Pengajuan Magang',
                data: monthlyValues,
                borderColor: '#3b82f6',
                backgroundColor: monthlyGradient,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: { label: (ctx) => ` ${ctx.raw} Pengajuan` }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { padding: 10, font: { size: 11 }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { padding: 10, font: { weight: '700', size: 11 } } }
            }
        }
    });

    // ---------------------------------------------------------
    // 3. BAR CHART (SEBARAN JURUSAN)
    // ---------------------------------------------------------
    const jurusanLabels = @json(array_keys($jurusanData));
    const jurusanValues = @json(array_values($jurusanData));

    const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
    const jurusanGradient = jurusanCtx.createLinearGradient(0, 0, 0, 300);
    jurusanGradient.addColorStop(0, '#3b82f6');
    jurusanGradient.addColorStop(1, '#6366f1');

    new Chart(jurusanCtx, {
        type: 'bar',
        data: {
            labels: jurusanLabels,
            datasets: [{
                label: 'Siswa',
                data: jurusanValues,
                backgroundColor: jurusanGradient,
                borderRadius: 6,
                barThickness: 25
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
                y: { beginAtZero: true, grid: { color: '#f8fafc', drawBorder: false }, ticks: { padding: 10, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { padding: 10, font: { weight: '700', size: 11 } } }
            }
        }
    });
</script>
@endsection