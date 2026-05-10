<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\PenempatanMagang;
use App\Models\SuratKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    // Halaman Download Surat
    public function index()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::with(['jurusan', 'penempatanMagangs.industri'])->find($siswaId);
        
        // ⬇️ FIX: Akses dari $siswa, bukan $penempatanMagangs
        $penempatan = $siswa->penempatanMagangs->first();
        
        // Daftar surat yang tersedia
        $surats = [
            [
                'id' => 1,
                'nama' => 'Surat Pengantar Prakerin',
                'jenis' => 'pengantar',
                'status' => $penempatan && in_array($penempatan->status, ['approved', 'ongoing', 'completed']) ? 'tersedia' : 'belum_rilis',
                'format' => 'PDF',
                'ukuran' => '245 KB',
                'deskripsi' => 'Surat pengantar resmi dari sekolah untuk tempat magang',
            ],
            [
                'id' => 2,
                'nama' => 'Surat Izin Orang Tua',
                'jenis' => 'izin_ortu',
                'status' => 'tersedia',
                'format' => 'PDF',
                'ukuran' => '180 KB',
                'deskripsi' => 'Surat izin dari orang tua/wali',
            ],
            [
                'id' => 3,
                'nama' => 'Template Laporan Akhir Magang',
                'jenis' => 'template_laporan',
                'status' => 'tersedia',
                'format' => 'DOCX',
                'ukuran' => '1.2 MB',
                'deskripsi' => 'Template laporan akhir magang untuk diisi',
            ],
        ];
        
        return view('siswa.download-surat', compact('siswa', 'penempatan', 'surats'));
    }

    // Generate Surat Pengantar PDF
    public function generateSuratPengantar()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::with(['jurusan', 'penempatanMagangs.industri'])->find($siswaId);
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan!');
        }
        
        $penempatan = $siswa->penempatanMagangs->first();
        
        if (!$penempatan || !in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            return redirect()->back()->with('error', 'Surat pengantar belum tersedia. Pengajuan magang Anda belum disetujui!');
        }
        
        // Data untuk surat
        $data = [
            'siswa' => $siswa,
            'penempatan' => $penempatan,
            'nomor_surat' => $this->generateNomorSurat(),
            'tanggal_surat' => now(),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('pdf.surat-pengantar', $data);
        $pdf->setPaper('A4', 'portrait');
        
        // Save ke storage (opsional, untuk tracking)
        $filename = 'surat_pengantar_' . $siswa->nisn . '_' . date('YmdHis') . '.pdf';
        $pdf->save(storage_path('app/public/surat/' . $filename));
        
        // Log surat keluar
        SuratKeluar::create([
            'siswa_id' => $siswaId,
            'penempatan_magang_id' => $penempatan->id,
            'jenis_surat' => 'pengantar',
            'nomor_surat' => $data['nomor_surat'],
            'file_path' => 'surat/' . $filename,
            'status' => 'sent',
            'tanggal_kirim' => now(),
            'created_by' => 1, 
            'catatan' => null,
            'template_surat_id' => null,
        ]);
        
        // Download
        return $pdf->download('Surat_Pengantar_Prakerin_' . $siswa->nisn . '.pdf');
    }

    // Generate Surat Izin Orang Tua PDF
    // 1. Untuk Surat Izin Orang Tua (Generate PDF)
public function generateSuratIzinOrtu()
{
    $siswaId = session('siswa_id');
    $siswa = \App\Models\Siswa::with('jurusan')->find($siswaId);
    $penempatan = \App\Models\PenempatanMagang::with('industri')->where('siswa_id', $siswaId)->first();

    $pdf = Pdf::loadView('pdf.surat-izin-ortu', compact('siswa', 'penempatan'));
    return $pdf->download('Surat_Izin_Orang_Tua_'.$siswa->nisn.'.pdf');
}

// 2. Untuk Template Laporan & Buku Panduan (Download File Word)
public function downloadTemplateLaporan()
{
    $filePath = public_path('assets/dokumen/Template_Laporan_Akhir.docx');
    if (file_exists($filePath)) {
        return response()->download($filePath);
    }
    return redirect()->back()->with('error', 'File template belum tersedia.');
}

// Tambahkan fungsi baru untuk Buku Panduan
public function downloadBukuPanduan()
{
    $filePath = public_path('assets/dokumen/Buku_Panduan_Prakerin.docx');
    if (file_exists($filePath)) {
        return response()->download($filePath);
    }
    return redirect()->back()->with('error', 'File buku panduan belum tersedia.');
}

    // Helper: Generate Nomor Surat
    private function generateNomorSurat()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $random = rand(1000, 9999);
        
        return '421/SMK.3-TUBAN/' . $random . '/' . $bulan . '/' . $tahun;
    }

    // Halaman Download Sertifikat
    public function downloadSertifikat()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::with(['jurusan', 'penempatanMagangs.industri'])->find($siswaId);
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan!');
        }
        
        $penempatan = $siswa->penempatanMagangs->first();
        
        // Cek apakah sudah selesai magang dan laporan disetujui
        $bolehDownload = false;
        if ($penempatan && $penempatan->status === 'completed') {
            $laporan = LaporanPKL::where('siswa_id', $siswaId)->where('status', 'disetujui')->first();
            if ($laporan) {
                $bolehDownload = true;
            }
        }
        
        // Data nilai (dari database atau dummy untuk sementara)
        $nilai = [
            'kedisiplinan' => 90,
            'kerja_sama' => 85,
            'inisiatif' => 88,
            'keahlian_kompetensi' => 92,
            'rata_rata' => 88.75,
            'predikat' => 'Sangat Baik',
        ];
        
        return view('siswa.download-sertifikat', compact('siswa', 'penempatan', 'nilai', 'bolehDownload'));
    }

    // Generate Sertifikat PDF
    public function generateSertifikatPDF()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::with(['jurusan', 'penempatanMagangs.industri'])->find($siswaId);
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan!');
        }
        
        $penempatan = $siswa->penempatanMagangs->first();
        
        if (!$penempatan || $penempatan->status !== 'completed') {
            return redirect()->back()->with('error', 'Sertifikat belum tersedia!');
        }
        
        $nilai = [
            'kedisiplinan' => 90,
            'kerja_sama' => 85,
            'inisiatif' => 88,
            'keahlian_kompetensi' => 92,
            'rata_rata' => 88.75,
            'predikat' => 'Sangat Baik',
        ];
        
        $data = [
            'siswa' => $siswa,
            'penempatan' => $penempatan,
            'nilai' => $nilai,
            'nomor_sertifikat' => 'SKL/' . $siswa->nisn . '/' . date('Y'),
            'tanggal_terbit' => now(),
        ];
        
        $pdf = Pdf::loadView('pdf.sertifikat', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Sertifikat_Prakerin_' . $siswa->nisn . '.pdf');
    }

    public function downloadSurat($id)
    {
        $siswaId = session('siswa_id');
        
        $surat = \App\Models\SuratKeluar::whereHas('penempatanMagang', function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            })
            ->where('id', $id)
            ->where('status', 'approved')
            ->first();
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan!');
        }
        
        $filePath = storage_path('app/public/' . $surat->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File surat tidak ditemukan!');
        }
        
        return response()->download($filePath);
    }

    // Halaman Bantuan & FAQ
    public function bantuan()
    {
        $faqs = [
            [
                'id' => 1,
                'pertanyaan' => 'Bagaimana cara mengajukan tempat magang baru?',
                'jawaban' => 'Untuk mengajukan tempat magang, buka menu "Cek Status Magang", pilih industri yang tersedia, isi form pengajuan, lalu klik "Ajukan Pengajuan". Tunggu approval dari TU dan Pimpinan.',
            ],
            [
                'id' => 2,
                'pertanyaan' => 'Berapa lama proses validasi berkas?',
                'jawaban' => 'Proses validasi berkas biasanya memakan waktu 3-5 hari kerja. Anda akan mendapat notifikasi jika berkas sudah disetujui atau perlu revisi.',
            ],
            [
                'id' => 3,
                'pertanyaan' => 'Apakah saya bisa pindah tempat magang?',
                'jawaban' => 'Pindah tempat magang hanya dapat dilakukan sebelum masa magang dimulai dan harus ada persetujuan dari sekolah serta industri yang dituju.',
            ],
            [
                'id' => 4,
                'pertanyaan' => 'Cara mengunduh sertifikat Prakerin?',
                'jawaban' => 'Sertifikat dapat diunduh setelah menyelesaikan magang dan laporan PKL disetujui. Buka menu "Download Sertifikat", lalu klik tombol "Download PDF".',
            ],
            [
                'id' => 5,
                'pertanyaan' => 'Bagaimana cara mengisi jurnal harian?',
                'jawaban' => 'Buka menu "Laporan" > "Jurnal Harian", isi deskripsi kegiatan (minimal 50 karakter), upload foto wajah, pilih durasi kerja, lalu kirim.',
            ],
            [
                'id' => 6,
                'pertanyaan' => 'Apa yang harus dilakukan jika lupa password?',
                'jawaban' => 'Hubungi Admin Sekolah melalui menu "Bantuan" atau klik "Lupa Password" di halaman login untuk reset password.',
            ],
        ];
        
        return view('siswa.bantuan', compact('faqs'));
    }
}