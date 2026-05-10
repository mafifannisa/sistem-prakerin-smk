<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Siswa\LaporanController;
use App\Http\Controllers\Siswa\SuratController;

// Guest routes (belum login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout (semua bisa akses)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/contact-admin', function () {
    return view('auth.contact-admin');
})->name('contact.admin');

// ==================== ADMIN ROUTES ====================
Route::middleware(['role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::get('/approval-surat', [DashboardController::class, 'approvalSurat'])->name('admin.approval.surat');

    // DATA SISWA ROUTES
    Route::get('/data-siswa', [DashboardController::class, 'dataSiswa'])->name('admin.data-siswa');
    Route::post('/data-siswa', [DashboardController::class, 'storeSiswa'])->name('admin.data-siswa.store');
    Route::put('/data-siswa/{id}', [DashboardController::class, 'updateSiswa'])->name('admin.data-siswa.update');
    Route::delete('/data-siswa/{id}', [DashboardController::class, 'deleteSiswa'])->name('admin.data-siswa.delete');
    // Route::post('/admin/data-siswa/import', [DashboardController::class, 'importSiswa'])->name('admin.data-siswa.import');
    // Import Data Siswa
    Route::post('/data-siswa/import', [DashboardController::class, 'importSiswa'])->name('admin.data-siswa.import');
    
    // Download Template Excel Siswa (Tambahkan ini)
    Route::get('/data-siswa/template', [DashboardController::class, 'downloadTemplateSiswa'])->name('admin.data-siswa.template');

    // DATA INDUSTRI ROUTES
    Route::get('/data-industri', [DashboardController::class, 'dataIndustri'])->name('admin.data-industri');
    Route::post('/data-industri', [DashboardController::class, 'storeIndustri'])->name('admin.data-industri.store');
    Route::put('/admin/data-industri/{id}', [DashboardController::class, 'updateIndustri'])->name('admin.data-industri.update');
    Route::delete('/data-industri/{id}', [DashboardController::class, 'deleteIndustri'])->name('admin.data-industri.delete');

    // DATA JURUSAN 
    Route::get('/data-jurusan', [DashboardController::class, 'dataJurusan'])->name('admin.data-jurusan');
    Route::post('/data-jurusan', [DashboardController::class, 'storeJurusan'])->name('admin.data-jurusan.store');
    Route::put('/data-jurusan/{id}', [DashboardController::class, 'updateJurusan'])->name('admin.data-jurusan.update');
    Route::delete('/data-jurusan/{id}', [DashboardController::class, 'deleteJurusan'])->name('admin.data-jurusan.delete');

    //VERIFIKASI PENGAJUAN
    Route::get('/verifikasi-pengajuan', [DashboardController::class, 'adminVerifikasi'])->name('admin.verifikasi');
    Route::post('/verifikasi-pengajuan/{id}/approve', [DashboardController::class, 'adminApprove'])->name('admin.verifikasi.approve');
    Route::post('/verifikasi-pengajuan/{id}/reject', [DashboardController::class, 'adminReject'])->name('admin.verifikasi.reject');

    // DATA SURAT (Pengganti Generate Surat & Tracking Surat)
    Route::get('/data-surat', [DashboardController::class, 'viewDataSurat'])->name('admin.data.surat');
    Route::post('/data-surat/{id}/approve', [DashboardController::class, 'adminApprove'])->name('admin.data.surat.approve');
    Route::post('/data-surat/{id}/reject', [DashboardController::class, 'adminReject'])->name('admin.data.surat.reject');
    Route::get('/data-surat/download/{surat_id}', [DashboardController::class, 'downloadSuratAdmin'])->name('admin.data.surat.download');
    
    // SURAT MASUK (Balasan Industri)
    Route::get('/surat-masuk', [DashboardController::class, 'viewSuratMasuk'])->name('admin.surat-masuk');
    Route::post('/surat-masuk', [DashboardController::class, 'storeSuratMasuk'])->name('admin.surat-masuk.store');

    // IMPORT NILAI
    Route::get('/import-nilai', [DashboardController::class, 'viewImportNilai'])->name('admin.import-nilai.view');
    Route::post('/import-nilai', [DashboardController::class, 'importNilai'])->name('admin.import-nilai');
    Route::get('/import-nilai/template', [DashboardController::class, 'downloadTemplateExcel'])->name('admin.import-nilai.template');
    Route::post('/import-nilai/check-nisn', [DashboardController::class, 'checkNisn'])->name('admin.import-nilai.check-nisn');

    // GENERATE SERTIFIKAT
    Route::get('/generate-sertifikat', [DashboardController::class, 'viewGenerateSertifikat'])->name('admin.generate-sertifikat.view');
    Route::get('/generate-sertifikat/{id}', [DashboardController::class, 'generateSertifikat'])->name('admin.generate-sertifikat');
    Route::get('/generate-sertifikat/batch', [\App\Http\Controllers\Auth\DashboardController::class, 'generateSertifikatBatch'])->name('admin.generate-sertifikat.batch');
    Route::post('/generate-sertifikat/kirim/{id}', [\App\Http\Controllers\Auth\DashboardController::class, 'kirimSertifikat'])->name('admin.generate-sertifikat.kirim');
    Route::post('/generate-sertifikat/batch-zip', [\App\Http\Controllers\Auth\DashboardController::class, 'downloadBatchZip'])->name('admin.generate-sertifikat.batch-zip');
    
    // WHATSAPP BLAST
    Route::get('/wa-blast', [DashboardController::class, 'viewWaBlast'])->name('admin.wa-blast');
    Route::post('/wa-blast', [DashboardController::class, 'sendWaBlast'])->name('admin.wa-blast.send');
    
    // LAPORAN & CETAK
    Route::get('/laporan-cetak', [DashboardController::class, 'laporanCetak'])->name('admin.laporan-cetak');
    Route::get('/laporan/export', [DashboardController::class, 'exportLaporan'])->name('admin.laporan.export');

    // Verifikasi Jurnal
    Route::get('/verifikasi/jurnal', [DashboardController::class, 'listJurnalSiswa'])->name('admin.verifikasi.jurnal');
    Route::post('/verifikasi/jurnal/{id}', [DashboardController::class, 'verifikasiJurnal'])->name('admin.verifikasi.jurnal.update');

    // Verifikasi Laporan PKL
    Route::get('/verifikasi/laporan-pkl', [DashboardController::class, 'listLaporanPKLSiswa'])->name('admin.verifikasi.laporan-pkl');
    Route::post('/verifikasi/laporan-pkl/{id}', [DashboardController::class, 'verifikasiLaporanPKL'])->name('admin.verifikasi.laporan-pkl.update');

    // PENGUMUMAN
    Route::get('/pengumuman', [DashboardController::class, 'pengumumanView'])->name('admin.pengumuman');
    Route::post('/pengumuman', [DashboardController::class, 'storePengumuman'])->name('admin.pengumuman.store');
    Route::delete('/pengumuman/{id}', [DashboardController::class, 'deletePengumuman'])->name('admin.pengumuman.delete');

    // HALAMAN KONTROL DURASI MAGANG
    Route::get('/kontrol-magang', [DashboardController::class, 'kontrolMagangView'])->name('admin.kontrol-magang');
    Route::post('/kontrol-magang/{id}/start', [DashboardController::class, 'startMagang'])->name('admin.verifikasi.start');
    Route::post('/kontrol-magang/{id}/end', [DashboardController::class, 'endMagang'])->name('admin.verifikasi.end');
    
});

// ==================== WEBHOOK WHATSAPP ====================
Route::post('/api/wa-webhook', [\App\Http\Controllers\Api\WhatsappWebhookController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ==================== PIMPINAN ROUTES ====================
// Pimpinan routes
Route::middleware(['role:pimpinan'])->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pimpinanDashboard'])->name('pimpinan.dashboard');

    // Notifikasi Routes
    Route::get('/notifikasi', [DashboardController::class, 'getNotifikasi'])->name('pimpinan.notifikasi');
    Route::post('/notifikasi/{id}/read', [DashboardController::class, 'markNotifikasiAsRead'])->name('pimpinan.notifikasi.read');
    Route::post('/notifikasi/read-all', [DashboardController::class, 'markAllNotifikasiAsRead'])->name('pimpinan.notifikasi.read-all');
    
    // APPROVAL SURAT (dari penempatan_magangs)
    Route::get('/approval-surat', [DashboardController::class, 'approvalSurat'])->name('pimpinan.approval.surat');
    Route::post('/approval-surat/{id}/approve', [DashboardController::class, 'approveSurat'])->name('pimpinan.approval.surat.approve');
    Route::post('/approval-surat/{id}/reject', [DashboardController::class, 'rejectSurat'])->name('pimpinan.approval.surat.reject');

    // STATISTIK
    Route::get('/statistik', [DashboardController::class, 'statistik'])->name('pimpinan.statistik');

    // TAMBAHKAN ROUTE LAPORAN INI
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('pimpinan.laporan');

    // Template surat
    Route::get('/approval-surat/{id}/download-pdf', [DashboardController::class, 'generateSuratPDF'])
        ->name('pimpinan.approval.surat.pdf');
});

// ==================== SISWA ROUTES ====================
Route::middleware(['role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'siswaDashboard'])->name('siswa.dashboard');
    
    // Cek Status Magang
    Route::get('/cek-status', [LaporanController::class, 'cekStatus'])->name('siswa.cek-status');
    Route::post('/cek-status/submit', [LaporanController::class, 'submitPilihan'])->name('siswa.cek-status.submit');

    Route::get('/cek-status', [DashboardController::class, 'cekStatusSiswa'])->name('siswa.cek-status');
    Route::post('/cek-status/ajukan-mitra', [DashboardController::class, 'submitPengajuanMitra'])->name('siswa.ajukan-mitra');
    Route::post('/cek-status/ajukan-mandiri', [DashboardController::class, 'submitPengajuanMandiri'])->name('siswa.ajukan-mandiri');

    // ajukan ulang
    Route::post('/siswa/cek-status/ajukan-ulang', [DashboardController::class, 'ajukanUlang'])->name('siswa.ajukan-ulang');
    
    // Laporan (Absensi, Jurnal, PKL)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('siswa.laporan');
    
    // Absensi
    Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('siswa.laporan.absensi');
    Route::post('/laporan/absensi', [LaporanController::class, 'storeAbsensi']);
    
    // Jurnal Harian
    Route::get('/laporan/jurnal', [LaporanController::class, 'jurnal'])->name('siswa.laporan.jurnal');
    Route::post('/laporan/jurnal', [LaporanController::class, 'storeJurnal']);
    
    // Laporan PKL
    Route::get('/laporan/laporan-pkl', [LaporanController::class, 'laporanPKL'])->name('siswa.laporan.pkl');
    Route::post('/laporan/laporan-pkl', [LaporanController::class, 'storeLaporanPKL']);

    // Download Surat
    Route::get('/download-surat', [SuratController::class, 'index'])->name('siswa.download.surat');
    Route::get('/download-surat/pengantar', [SuratController::class, 'generateSuratPengantar'])->name('siswa.download.pengantar');
    Route::get('/download-surat/izin-ortu', [SuratController::class, 'generateSuratIzinOrtu'])->name('siswa.download.izin');
    Route::get('/download-surat/template-laporan', [SuratController::class, 'downloadTemplateLaporan'])->name('siswa.download.template');
    Route::get('/download-surat/buku-panduan', [SuratController::class, 'downloadBukuPanduan'])->name('siswa.download.panduan');
    
    // UBAH NAME INI (tambahkan .file di akhir agar unik)
    Route::get('/download-surat/{id}', [SuratController::class, 'downloadSurat'])->name('siswa.download.surat.file');

    // Download Sertifikat 
    Route::get('/download-sertifikat', [SuratController::class, 'downloadSertifikat'])->name('siswa.download.sertifikat');
    Route::get('/download-sertifikat/pdf', [SuratController::class, 'generateSertifikatPDF'])->name('siswa.download.sertifikat.pdf');

    // Bantuan
    Route::get('/bantuan', [SuratController::class, 'bantuan'])->name('siswa.bantuan');

    // Detail Industri
    Route::get('/detail-industri/{id}', [DashboardController::class, 'detailIndustriSiswa'])->name('siswa.industri.detail');

    Route::post('/update-profil', [DashboardController::class, 'updateNoWa'])->name('siswa.update.profil');

});

// ==================== HOME REDIRECT ====================
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->role . '.dashboard');
    }
    if (session()->has('siswa_id')) {
        return redirect()->route('siswa.dashboard');
    }
    return redirect()->route('login');
});