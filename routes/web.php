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

Route::get('/sertifikat/view/{id}', [SuratController::class, 'publicDownloadSertifikat'])->name('sertifikat.public-download');

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

    // DATA SURAT & CETAK SURAT
    Route::get('/cetak-surat', [DashboardController::class, 'viewDataSurat'])->name('admin.data.surat');
    Route::post('/data-surat/{id}/approve', [DashboardController::class, 'adminApprove'])->name('admin.data.surat.approve');
    Route::post('/data-surat/{id}/reject', [DashboardController::class, 'adminReject'])->name('admin.data.surat.reject');
    Route::get('/data-surat/download/{surat_id}', [DashboardController::class, 'downloadSuratAdmin'])->name('admin.data.surat.download');

    // ROUTES UNTUK CETAK SURAT DARI TEMPLATE
    Route::get('/cetak-surat/pengantar', [DashboardController::class, 'cetakPengantarForm'])->name('admin.cetak.pengantar');
    Route::post('/cetak-surat/pengantar/pdf', [DashboardController::class, 'generatePengantarPDF'])->name('admin.cetak.pengantar.pdf');

    Route::get('/cetak-surat/tugas', [DashboardController::class, 'cetakTugasForm'])->name('admin.cetak.tugas');
    Route::post('/cetak-surat/tugas/pdf', [DashboardController::class, 'generateTugasPDF'])->name('admin.cetak.tugas.pdf');

    Route::get('/cetak-surat/dispensasi', [DashboardController::class, 'cetakDispensasiForm'])->name('admin.cetak.dispensasi');
    Route::post('/cetak-surat/dispensasi/pdf', [DashboardController::class, 'generateDispensasiPDF'])->name('admin.cetak.dispensasi.pdf');

    Route::get('/cetak-surat/sppd', [DashboardController::class, 'cetakSppdForm'])->name('admin.cetak.sppd');
    Route::post('/cetak-surat/sppd/pdf', [DashboardController::class, 'generateSppdPDF'])->name('admin.cetak.sppd.pdf');
    
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
    Route::post('/generate-sertifikat/border', [DashboardController::class, 'uploadBorderTemplate'])->name('admin.generate-sertifikat.upload-border');
    Route::delete('/generate-sertifikat/border/{id}', [DashboardController::class, 'deleteBorderTemplate'])->name('admin.generate-sertifikat.delete-border');
    
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



    // DATA GURU
    Route::get('/data-guru', [DashboardController::class, 'dataGuru'])->name('admin.data-guru');
    Route::post('/data-guru', [DashboardController::class, 'storeGuru'])->name('admin.data-guru.store');
    Route::put('/data-guru/{id}', [DashboardController::class, 'updateGuru'])->name('admin.data-guru.update');
    Route::delete('/data-guru/{id}', [DashboardController::class, 'deleteGuru'])->name('admin.data-guru.delete');

    // DATA MAGANG & LAPORAN MASALAH
    Route::get('/data-magang-all', [DashboardController::class, 'adminDataMagang'])->name('admin.data-magang-all');
    Route::get('/laporan-masalah-all', [DashboardController::class, 'adminLaporanMasalah'])->name('admin.laporan-masalah-all');
    
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
    
    // Riwayat
    Route::get('/riwayat/absensi', [LaporanController::class, 'riwayatAbsensi'])->name('siswa.riwayat.absensi');
    Route::get('/riwayat/jurnal', [LaporanController::class, 'riwayatJurnal'])->name('siswa.riwayat.jurnal');
    Route::get('/riwayat/laporan-pkl', [LaporanController::class, 'riwayatLaporan'])->name('siswa.riwayat.laporan');
    Route::get('/riwayat/nilai-teknis', [LaporanController::class, 'riwayatNilai'])->name('siswa.riwayat.nilai');
    
    // Laporan PKL
    Route::get('/laporan/laporan-pkl', [LaporanController::class, 'laporanPKL'])->name('siswa.laporan.pkl');
    Route::post('/laporan/laporan-pkl', [LaporanController::class, 'storeLaporanPKL']);

    // Input Nilai
    Route::get('/laporan/nilai', [LaporanController::class, 'nilai'])->name('siswa.laporan.nilai');
    Route::post('/laporan/nilai', [LaporanController::class, 'storeNilai'])->name('siswa.laporan.nilai.store');

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
    Route::post('/download-sertifikat/email', [SuratController::class, 'kirimEmailSertifikat'])->name('siswa.kirim-email-sertifikat');

    // Bantuan
    Route::get('/bantuan', [SuratController::class, 'bantuan'])->name('siswa.bantuan');

    // Detail Industri
    Route::get('/detail-industri/{id}', [DashboardController::class, 'detailIndustriSiswa'])->name('siswa.industri.detail');

    Route::post('/update-profil', [DashboardController::class, 'updateNoWa'])->name('siswa.update.profil');

});

// ==================== GURU PEMBIMBING ROUTES ====================
Route::middleware(['role:guru_pembimbing'])->prefix('guru-pembimbing')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\GuruPembimbingController::class, 'dashboard'])->name('guru_pembimbing.dashboard');
    Route::get('/data-siswa', [\App\Http\Controllers\GuruPembimbingController::class, 'dataSiswa'])->name('guru_pembimbing.data-siswa');
    Route::get('/rekap-absen', [\App\Http\Controllers\GuruPembimbingController::class, 'rekapAbsen'])->name('guru_pembimbing.rekap-absen');
    Route::get('/rekap-koreksi', [\App\Http\Controllers\GuruPembimbingController::class, 'rekapKoreksi'])->name('guru_pembimbing.rekap-koreksi');
    Route::post('/rekap-koreksi/{id}/verify', [\App\Http\Controllers\GuruPembimbingController::class, 'verifyKoreksi'])->name('guru_pembimbing.rekap-koreksi.verify');
    Route::get('/rekap-jurnal', [\App\Http\Controllers\GuruPembimbingController::class, 'rekapJurnal'])->name('guru_pembimbing.rekap-jurnal');
    Route::post('/rekap-jurnal/{id}/verify', [\App\Http\Controllers\GuruPembimbingController::class, 'verifyJurnal'])->name('guru_pembimbing.rekap-jurnal.verify');
    Route::get('/rekap-laporan', [\App\Http\Controllers\GuruPembimbingController::class, 'rekapLaporan'])->name('guru_pembimbing.rekap-laporan');
    Route::post('/rekap-laporan/{id}/verify', [\App\Http\Controllers\GuruPembimbingController::class, 'verifyLaporan'])->name('guru_pembimbing.rekap-laporan.verify');
    Route::post('/rekap-laporan/{id}/revision', [\App\Http\Controllers\GuruPembimbingController::class, 'revisionLaporan'])->name('guru_pembimbing.rekap-laporan.revision');
    Route::get('/laporan-masalah', [\App\Http\Controllers\GuruPembimbingController::class, 'laporanMasalah'])->name('guru_pembimbing.laporan-masalah');
    Route::post('/laporan-masalah', [\App\Http\Controllers\GuruPembimbingController::class, 'storeMasalah'])->name('guru_pembimbing.laporan-masalah.store');
});

// ==================== KEPALA JURUSAN ROUTES ====================
Route::middleware(['role:kepala_jurusan'])->prefix('kepala-jurusan')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\KepalaJurusanController::class, 'dashboard'])->name('kepala_jurusan.dashboard');
    Route::get('/data-siswa', [\App\Http\Controllers\KepalaJurusanController::class, 'dataSiswa'])->name('kepala_jurusan.data-siswa');
    Route::get('/data-magang', [\App\Http\Controllers\KepalaJurusanController::class, 'dataMagang'])->name('kepala_jurusan.data-magang');
    Route::post('/data-magang', [\App\Http\Controllers\KepalaJurusanController::class, 'storeMagang'])->name('kepala_jurusan.data-magang.store');
    Route::post('/data-magang/{id}/update', [\App\Http\Controllers\KepalaJurusanController::class, 'updateMagang'])->name('kepala_jurusan.data-magang.update');
    Route::delete('/data-magang/{id}', [\App\Http\Controllers\KepalaJurusanController::class, 'destroyMagang'])->name('kepala_jurusan.data-magang.destroy');
    Route::get('/rekap-absen', [\App\Http\Controllers\KepalaJurusanController::class, 'rekapAbsen'])->name('kepala_jurusan.rekap-absen');
    Route::get('/rekap-jurnal', [\App\Http\Controllers\KepalaJurusanController::class, 'rekapJurnal'])->name('kepala_jurusan.rekap-jurnal');
    Route::delete('/rekap-jurnal/{id}', [\App\Http\Controllers\KepalaJurusanController::class, 'destroyJurnal'])->name('kepala_jurusan.rekap-jurnal.destroy');
    Route::get('/rekap-laporan', [\App\Http\Controllers\KepalaJurusanController::class, 'rekapLaporan'])->name('kepala_jurusan.rekap-laporan');
    Route::get('/import-nilai', [\App\Http\Controllers\KepalaJurusanController::class, 'importNilai'])->name('kepala_jurusan.import-nilai');
    Route::post('/import-nilai/{placement_id}', [\App\Http\Controllers\KepalaJurusanController::class, 'storeNilai'])->name('kepala_jurusan.import-nilai.store');
    Route::get('/laporan-masalah', [\App\Http\Controllers\KepalaJurusanController::class, 'laporanMasalah'])->name('kepala_jurusan.laporan-masalah');
    Route::post('/laporan-masalah/{id}/resolve', [\App\Http\Controllers\KepalaJurusanController::class, 'resolveMasalah'])->name('kepala_jurusan.laporan-masalah.resolve');
    Route::get('/ujian-magang', [\App\Http\Controllers\KepalaJurusanController::class, 'ujianMagang'])->name('kepala_jurusan.ujian-magang');
    Route::post('/ujian-magang/{placement_id}', [\App\Http\Controllers\KepalaJurusanController::class, 'storeUjian'])->name('kepala_jurusan.ujian-magang.store');
    Route::post('/ujian-magang/{placement_id}/assign-penguji', [\App\Http\Controllers\KepalaJurusanController::class, 'assignPenguji'])->name('kepala_jurusan.ujian-magang.assign-penguji');

    // Periode Magang
    Route::post('/periode-magang', [\App\Http\Controllers\KepalaJurusanController::class, 'storePeriode'])->name('kepala_jurusan.periode-magang.store');
    Route::post('/periode-magang/{id}/update', [\App\Http\Controllers\KepalaJurusanController::class, 'updatePeriode'])->name('kepala_jurusan.periode-magang.update');
    Route::delete('/periode-magang/{id}', [\App\Http\Controllers\KepalaJurusanController::class, 'destroyPeriode'])->name('kepala_jurusan.periode-magang.destroy');
});

// ==================== GURU PENGUJI ROUTES ====================
Route::middleware(['role:guru_penguji'])->prefix('guru-penguji')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\GuruPengujiController::class, 'dashboard'])->name('guru_penguji.dashboard');
    Route::get('/ujian-magang', [\App\Http\Controllers\GuruPengujiController::class, 'ujianMagang'])->name('guru_penguji.ujian-magang');
    Route::post('/ujian-magang/{placement_id}', [\App\Http\Controllers\GuruPengujiController::class, 'storeUjian'])->name('guru_penguji.ujian-magang.store');
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