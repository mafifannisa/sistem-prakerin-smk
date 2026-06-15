@extends('layouts.pimpinan')

@section('title', 'Statistik & Laporan')

@section('header_title', 'STATISTIK PRAKERIN')
@section('header_breadcrumb', 'Statistik')

@section('header_actions')
<form action="{{ route('pimpinan.statistik') }}" method="GET" class="flex items-center gap-2 bg-white/50 p-1.5 rounded-xl border border-gray-250">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Siswa Magang Aktif</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalSiswa }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center shrink-0 border border-orange-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Mitra Industri</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalIndustri }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0 border border-amber-100 text-xl font-bold">🏢</div>
        </div>

        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Laporan Selesai</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalLaporan }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0 border border-green-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 rounded-2xl shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 truncate">Rata-rata Hadir</p>
                <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $kehadiranRate }}<span class="text-xl">%</span></h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0 border border-purple-100 font-bold text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 sm:p-8 rounded-3xl shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Distribusi Status Siswa</h3>
        <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
            <div class="relative w-48 h-48 shrink-0">
                <canvas id="statusChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-3xl font-black text-gray-800 leading-none">
                        {{ $statusData['pending'] + $statusData['verified'] + $statusData['approved'] + $statusData['ongoing'] + $statusData['completed'] + $statusData['rejected'] }}
                    </span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total</span>
                </div>
            </div>

            <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 gap-4 w-full">
                <div class="p-4 border border-amber-100 bg-amber-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-sm">
                    <p class="text-[9px] font-bold text-amber-605 uppercase mb-1">Menunggu</p>
                    <p class="text-2xl font-black text-amber-700">{{ $statusData['pending'] }}</p>
                </div>
                <div class="p-4 border border-orange-100 bg-orange-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-sm">
                    <p class="text-[9px] font-bold text-orange-605 uppercase mb-1">Verifikasi</p>
                    <p class="text-2xl font-black text-orange-700">{{ $statusData['verified'] }}</p>
                </div>
                <div class="p-4 border border-purple-100 bg-purple-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-sm">
                    <p class="text-[9px] font-bold text-purple-605 uppercase mb-1">Setuju</p>
                    <p class="text-2xl font-black text-purple-700">{{ $statusData['approved'] }}</p>
                </div>
                <div class="p-4 border border-blue-100 bg-blue-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-sm">
                    <p class="text-[9px] font-bold text-blue-605 uppercase mb-1">Aktif</p>
                    <p class="text-2xl font-black text-blue-700">{{ $statusData['ongoing'] }}</p>
                </div>
                <div class="p-4 border border-red-100 bg-red-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-sm">
                    <p class="text-[9px] font-bold text-red-605 uppercase mb-1">Tolak</p>
                    <p class="text-2xl font-black text-red-700">{{ $statusData['rejected'] }}</p>
                </div>
                <div class="p-4 border border-green-100 bg-green-50/50 rounded-2xl flex flex-col justify-center transition hover:shadow-sm">
                    <p class="text-[9px] font-bold text-green-605 uppercase mb-1">Selesai</p>
                    <p class="text-2xl font-black text-green-700">{{ $statusData['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white/65 backdrop-blur-md border border-white/50 p-6 sm:p-8 rounded-3xl shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Tren Pengajuan Magang (Bulan)</h3>
            <div class="w-full h-72">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 sm:p-8 rounded-3xl shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Top 5 Mitra Industri</h3>
            <div class="space-y-4">
                @forelse($industriData as $namaIndustri => $count)
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-400 rounded-full flex items-center justify-center font-bold text-xs uppercase text-white shadow-sm shrink-0">
                                {{ substr($namaIndustri, 0, 2) }}
                            </div>
                            <p class="font-bold text-gray-800 text-xs truncate max-w-[120px]">{{ $namaIndustri }}</p>
                        </div>
                        <span class="px-2.5 py-1 bg-orange-600 text-white text-[10px] font-bold rounded-lg">{{ $count }} Siswa</span>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 font-bold italic border-2 border-dashed border-gray-200 rounded-2xl">Belum ada data industri</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 sm:p-8 rounded-3xl shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Distribusi per Jurusan</h3>
            </div>
            <div class="w-full h-64">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>

        <div class="bg-white/65 backdrop-blur-md border border-white/50 p-6 sm:p-8 rounded-3xl shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Rekapitulasi Kinerja Siswa</h3>
            
            <div class="mb-6">
                <div class="flex justify-between text-xs mb-2">
                    <span class="font-bold text-gray-550 uppercase tracking-wider text-[9px]">Tingkat Kehadiran Keseluruhan</span>
                    <span class="font-black text-orange-600">{{ $kehadiranRate }}%</span>
                </div>
                <div class="w-full bg-gray-150 rounded-full h-2.5 mb-3">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-400 h-2.5 rounded-full" style="width: {{ $kehadiranRate }}%"></div>
                </div>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-green-50/50 p-2 rounded-xl border border-green-100"><p class="text-[9px] font-bold text-green-600 uppercase">Hadir</p><p class="font-black text-gray-800 text-sm mt-0.5">{{ $hadir }}</p></div>
                    <div class="bg-yellow-50/50 p-2 rounded-xl border border-yellow-100"><p class="text-[9px] font-bold text-yellow-600 uppercase">Izin</p><p class="font-black text-gray-800 text-sm mt-0.5">{{ $izin }}</p></div>
                    <div class="bg-orange-50/50 p-2 rounded-xl border border-orange-100"><p class="text-[9px] font-bold text-orange-600 uppercase">Sakit</p><p class="font-black text-gray-800 text-sm mt-0.5">{{ $sakit }}</p></div>
                    <div class="bg-red-50/50 p-2 rounded-xl border border-red-100"><p class="text-[9px] font-bold text-red-650 uppercase">Alpha</p><p class="font-black text-gray-800 text-sm mt-0.5">{{ $alpha }}</p></div>
                </div>
            </div>

            <hr class="border-white/50 my-6">

            <div>
                <div class="flex justify-between text-xs mb-2">
                    <span class="font-bold text-gray-550 uppercase tracking-wider text-[9px]">Penyelesaian Jurnal Harian</span>
                    <span class="font-black text-purple-600">{{ $totalJurnal }} Total</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-purple-600 uppercase">Disetujui</span>
                        <span class="text-xl font-black text-gray-800 leading-none">{{ $jurnalDisetujui }}</span>
                    </div>
                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-200 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">Pending</span>
                        <span class="text-xl font-black text-gray-800 leading-none">{{ $jurnalPending }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
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
    monthlyGradient.addColorStop(0, 'rgba(249, 115, 22, 0.3)');
    monthlyGradient.addColorStop(1, 'rgba(249, 115, 22, 0.01)');

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Pengajuan Magang',
                data: monthlyValues,
                borderColor: '#f97316',
                backgroundColor: monthlyGradient,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#f97316',
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
                y: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 0.4)', drawBorder: false }, ticks: { padding: 10, font: { size: 10 }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { padding: 10, font: { weight: '700', size: 10 } } }
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
    jurusanGradient.addColorStop(0, '#f97316');
    jurusanGradient.addColorStop(1, '#f59e0b');

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
                y: { beginAtZero: true, grid: { color: 'rgba(241, 245, 249, 0.4)', drawBorder: false }, ticks: { padding: 10, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { padding: 10, font: { weight: '700', size: 10 } } }
            }
        }
    });
</script>
@endsection