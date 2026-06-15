<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\PenempatanMagang;
use App\Models\Absensi;
use App\Models\Notifikasi;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // 1. UPDATE STATUS MAGANG
    PenempatanMagang::where('status', 'approved')
        ->whereNotNull('tanggal_mulai')
        ->whereDate('tanggal_mulai', '<=', now())
        ->update(['status' => 'ongoing']);
        
    PenempatanMagang::where('status', 'ongoing')
        ->whereNotNull('tanggal_selesai')
        ->whereDate('tanggal_selesai', '<=', now())
        ->update(['status' => 'completed']);

    // 2. AUTO-ALPHA (Cek Hari Ini di jam 23:55)
    $today = now();
    if ($today->isWeekday()) { // Hanya berlaku Senin - Jumat
        $dateStr = $today->format('Y-m-d');
        
        $penempatans = PenempatanMagang::where('status', 'ongoing')->get();
        
        foreach ($penempatans as $p) {
            $sudahAbsen = Absensi::where('siswa_id', $p->siswa_id)
                ->whereDate('tanggal', $dateStr)
                ->exists();
                
            if (!$sudahAbsen) {
                Absensi::create([
                    'siswa_id' => $p->siswa_id,
                    'penempatan_magang_id' => $p->id,
                    'tanggal' => $dateStr,
                    'status' => 'alpha',
                    'keterangan' => 'Tidak mengisi absensi (Auto Alpha)',
                ]);
                
                Notifikasi::create([
                    'siswa_id' => $p->siswa_id,
                    'judul' => 'Absensi Terlewat - Auto Alpha',
                    'pesan' => 'Anda tidak mengisi absensi tanggal ' . $dateStr . '. Status tercatat Alpha.',
                    'jenis' => 'error',
                    'tipe' => 'umum',
                    'is_read' => false,
                ]);
            }
        }
    }
})->dailyAt('23:55')->name('prakerin:daily-tasks');
