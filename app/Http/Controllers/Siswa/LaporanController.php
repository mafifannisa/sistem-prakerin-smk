<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JurnalHarian;
use App\Models\LaporanPKL;
use App\Models\PenempatanMagang;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\Pengumuman;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // ==================== HALAMAN UTAMA LAPORAN ====================
    public function index()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        $stats = [
            'total_hadir' => Absensi::where('siswa_id', $siswaId)->where('status', 'hadir')->count(),
            'total_izin' => Absensi::where('siswa_id', $siswaId)->where('status', 'izin')->count(),
            'total_sakit' => Absensi::where('siswa_id', $siswaId)->where('status', 'sakit')->count(),
            'total_alpha' => Absensi::where('siswa_id', $siswaId)->where('status', 'alpha')->count(),
            'jurnal_total' => JurnalHarian::where('siswa_id', $siswaId)->count(),
            'jurnal_pending' => JurnalHarian::where('siswa_id', $siswaId)->where('status', 'pending')->count(),
            'laporan_pkl' => LaporanPKL::where('siswa_id', $siswaId)->latest()->first(),
        ];
        
        return view('siswa.laporan.index', compact('stats', 'penempatan'));
    }

    // ==================== ABSENSI ====================
    public function absensi()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        // CEK APAKAH SUDAH DIAPPROVE UNTUK MAGANG
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang. Silakan ajukan tempat magang terlebih dahulu.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval. Absensi akan terbuka setelah disetujui.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak. Silakan hubungi TU untuk informasi lebih lanjut.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            // Tidak ada absensi yang ditampilkan jika belum boleh absen
            return view('siswa.laporan.absensi', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        // Get semua absensi yang sudah diisi
        $absensis = Absensi::where('siswa_id', $siswaId)
                        ->orderBy('tanggal', 'desc')
                        ->paginate(15);
        
        // Cek apakah sudah absen hari ini
        $sudahAbsenHariIni = Absensi::where('siswa_id', $siswaId)
                                    ->whereDate('tanggal', now())
                                    ->exists();
        
        // AUTO-ALPHA: Cek hari yang terlewat
        $this->checkAndCreateAutoAlpha($siswaId, $penempatan);
        
        // Refresh absensis setelah auto-alpha
        $absensis = Absensi::where('siswa_id', $siswaId)
                        ->orderBy('tanggal', 'desc')
                        ->paginate(15);
        
        return view('siswa.laporan.absensi', compact('absensis', 'penempatan', 'sudahAbsenHariIni', 'bolehAbsen', 'pesanLock'));
    }

    // autoalpha
    private function checkAndCreateAutoAlpha($siswaId, $penempatan)
    {
        if (!$penempatan) return;
        
        // Dapatkan tanggal mulai magang
        $tanggalMulai = $penempatan->tanggal_mulai;
        if (!$tanggalMulai) return;
        
        // Dapatkan semua tanggal yang sudah diisi absensi
        $absensiDates = Absensi::where('siswa_id', $siswaId)
                            ->pluck('tanggal')
                            ->map(function($date) {
                                return $date->format('Y-m-d');
                            })
                            ->toArray();
        
        // Loop dari tanggal mulai sampai kemarin
        $currentDate = clone $tanggalMulai;
        $yesterday = now()->subDay()->format('Y-m-d');
        
        while ($currentDate->format('Y-m-d') <= $yesterday) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Skip weekend (Sabtu & Minggu) - opsional
            if ($currentDate->dayOfWeek == 0 || $currentDate->dayOfWeek == 6) {
                $currentDate->addDay();
                continue;
            }
            
            // Jika tanggal ini belum ada absensi, buat auto-alpha
            if (!in_array($dateStr, $absensiDates)) {
                $existingAlpha = Absensi::where('siswa_id', $siswaId)
                                    ->whereDate('tanggal', $dateStr)
                                    ->where('status', 'alpha')
                                    ->first();
                
                if (!$existingAlpha) {
                    Absensi::create([
                        'siswa_id' => $siswaId,
                        'penempatan_magang_id' => $penempatan->id,
                        'tanggal' => $dateStr,
                        'status' => 'alpha',
                        'keterangan' => 'Tidak mengisi absensi (Auto Alpha)',
                    ]);
                    
                    // Buat notifikasi
                    Notifikasi::create([
                        'siswa_id' => $siswaId,
                        'judul' => 'Absensi Terlewat - Auto Alpha',
                        'pesan' => 'Anda tidak mengisi absensi tanggal ' . $dateStr . '. Status tercatat Alpha.',
                        'jenis' => 'error',
                        'tipe' => 'umum',
                        'is_read' => false,
                    ]);
                }
            }
            
            $currentDate->addDay();
        }
    }

    public function storeAbsensi(Request $request)
    {
        $siswaId = session('siswa_id');
        $penempatan = \App\Models\PenempatanMagang::where('siswa_id', $siswaId)
            ->whereIn('status', ['ongoing', 'approved', 'completed'])
            ->first();

        if (!$penempatan) {
            return redirect()->back()->with('error', 'Anda belum memiliki penempatan magang aktif.');
        }

        $validated = $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk' => 'nullable',   // Izinkan ambil dari form
            'jam_pulang' => 'nullable',  // Izinkan ambil dari form
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|max:2048'
        ]);

        $tanggalHariIni = now()->toDateString();

        $absensiHariIni = \App\Models\Absensi::where('siswa_id', $siswaId)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        // Update Jam Pulang
        if ($absensiHariIni) {
            if ($absensiHariIni->status == 'hadir' && $request->status == 'hadir') {
                if (!$absensiHariIni->jam_pulang) {
                    // Cek apakah form jam_pulang diisi. Jika kosong, pakai jam sekarang otomatis
                    $jamPulang = $request->jam_pulang ? $request->jam_pulang . ':00' : now()->format('H:i:s');
                    
                    $absensiHariIni->update([
                        'jam_pulang' => $jamPulang, 
                    ]);
                    return redirect()->back()->with('success', 'Jam Pulang berhasil dicatat!');
                }
                return redirect()->back()->with('error', 'Anda sudah absen masuk & pulang hari ini.');
            }
            return redirect()->back()->with('error', 'Anda sudah absen hari ini.');
        }

        // Simpan Absen Masuk
        $validated['siswa_id'] = $siswaId;
        $validated['penempatan_magang_id'] = $penempatan->id;
        $validated['tanggal'] = $tanggalHariIni;
        
        if ($request->status == 'hadir') {
            // Cek apakah form jam_masuk diisi. Jika kosong, pakai jam sekarang otomatis
            $validated['jam_masuk'] = $request->jam_masuk ? $request->jam_masuk . ':00' : now()->format('H:i:s');
            
            // Jika siswa sekalian mengisi jam pulang di waktu yang sama
            if ($request->jam_pulang) {
                $validated['jam_pulang'] = $request->jam_pulang . ':00';
            }
        }

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('absensi', 'public');
        }

        \App\Models\Absensi::create($validated);

        return redirect()->back()->with('success', 'Presensi berhasil disimpan!');
    }

    // ==================== JURNAL HARIAN ====================
    public function jurnal()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        // ⬇️ CEK APAKAH SUDAH DIAPPROVE ⬇️
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            return view('siswa.laporan.jurnal', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        $jurnals = JurnalHarian::where('siswa_id', $siswaId)
                            ->orderBy('tanggal', 'desc')
                            ->paginate(15);
        
        // Cek apakah sudah isi jurnal hari ini
        $sudahIsiJurnalHariIni = JurnalHarian::where('siswa_id', $siswaId)
                                            ->whereDate('tanggal', now())
                                            ->exists();
        
        return view('siswa.laporan.jurnal', compact('jurnals', 'penempatan', 'sudahIsiJurnalHariIni', 'bolehAbsen', 'pesanLock'));
    }

    public function storeJurnal(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'minggu_ke' => 'required|integer|min:1|max:24',
            'kegiatan' => 'required|string|min:15|max:1000',
            'durasi_jam' => 'required|integer|min:1|max:12',
            'bukti_foto' => 'required|image|max:2048', // WAJIB foto wajah!
        ]);

        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();

        // Cek apakah sudah isi jurnal di tanggal yang sama
        $existing = JurnalHarian::where('siswa_id', $siswaId)
                               ->whereDate('tanggal', $request->tanggal)
                               ->first();
        
        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi jurnal untuk tanggal ini!');
        }

        // Upload foto bukti (WAJIB)
        $fotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $fotoPath = $request->file('bukti_foto')->store('jurnal/' . date('Y-m'), 'public');
        }

        JurnalHarian::create([
            'siswa_id' => $siswaId,
            'penempatan_magang_id' => $penempatan->id ?? null,
            'tanggal' => $request->tanggal,
            'minggu_ke' => $request->minggu_ke,
            'kegiatan' => $request->kegiatan,
            'durasi_jam' => $request->durasi_jam,
            'bukti_foto' => $fotoPath,
            'status' => 'disetujui',
        ]);

        // Buat notifikasi
        Notifikasi::create([
            'siswa_id' => $siswaId,
            'judul' => 'Jurnal Harian Berhasil Dikirim',
            'pesan' => 'Jurnal hari ' . $request->tanggal . ' berhasil disimpan.',
            'jenis' => 'success',
            'tipe' => 'umum',
            'is_read' => false,
        ]);

        return back()->with('success', 'Jurnal harian berhasil ditambahkan!');
    }

    // ==================== LAPORAN PKL ====================
    public function laporanPKL()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        // ⬇️ CEK APAKAH SUDAH DIAPPROVE ⬇️
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            return view('siswa.laporan.laporan-pkl', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        $laporans = LaporanPKL::where('siswa_id', $siswaId)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        return view('siswa.laporan.laporan-pkl', compact('laporans', 'penempatan', 'bolehAbsen', 'pesanLock'));
    }

    public function storeLaporanPKL(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'abstrak' => 'nullable|string|max:1000',
            'file_path' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();

        // Cek apakah sudah upload laporan dan statusnya bukan perlu_revisi
        $existing = LaporanPKL::where('siswa_id', $siswaId)->latest()->first();
        
        if ($existing && in_array($existing->status, ['pending', 'disetujui'])) {
            return back()->with('error', 'Anda sudah mengupload laporan PKL dan sedang diproses atau disetujui!');
        }

        // Upload file PDF
        $filePath = $request->file('file_path')->store('laporan-pkl/' . date('Y-m'), 'public');

        LaporanPKL::create([
            'siswa_id' => $siswaId,
            'penempatan_magang_id' => $penempatan->id ?? null,
            'judul_laporan' => $request->judul_laporan,
            'abstrak' => $request->abstrak,
            'file_path' => $filePath,
            'jenis' => 'submit',
            'tanggal_submit' => now(),
            'status' => 'pending',
        ]);

        // Buat notifikasi
        Notifikasi::create([
            'siswa_id' => $siswaId,
            'judul' => 'Laporan PKL Berhasil Diupload',
            'pesan' => 'Laporan PKL Anda berhasil diupload. Tunggu verifikasi dari pembimbing.',
            'jenis' => 'info',
            'tipe' => 'umum',
            'is_read' => false,
        ]);

        return back()->with('success', 'Laporan PKL berhasil diupload!');
    }

    // ==================== INPUT NILAI TEKNIS ====================
    public function nilai()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        $nilai = null;
        if ($penempatan) {
            $nilai = Nilai::where('penempatan_magang_id', $penempatan->id)->first();
        }
        
        return view('siswa.laporan.nilai', compact('nilai', 'penempatan', 'bolehAbsen', 'pesanLock'));
    }

    public function storeNilai(Request $request)
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        if (!$penempatan) {
            return back()->with('error', 'Anda belum memiliki penempatan magang aktif.');
        }

        $existingNilai = Nilai::where('penempatan_magang_id', $penempatan->id)->first();

        $rules = [
            'kegiatan_1' => 'required|string|max:255',
            'nilai_1' => 'required|numeric|min:0|max:100',
            'kegiatan_2' => 'required|string|max:255',
            'nilai_2' => 'required|numeric|min:0|max:100',
            'kegiatan_3' => 'required|string|max:255',
            'nilai_3' => 'required|numeric|min:0|max:100',
        ];

        if ($existingNilai && $existingNilai->foto_nilai) {
            $rules['foto_nilai'] = 'nullable|image|max:2048';
        } else {
            $rules['foto_nilai'] = 'required|image|max:2048';
        }

        $request->validate($rules);

        // Upload foto
        $fotoPath = $existingNilai ? $existingNilai->foto_nilai : null;
        if ($request->hasFile('foto_nilai')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto_nilai')->store('nilai-teknis', 'public');
        }

        $keg1 = $request->kegiatan_1;
        $val1 = floatval($request->nilai_1);
        $keg2 = $request->kegiatan_2;
        $val2 = floatval($request->nilai_2);
        $keg3 = $request->kegiatan_3;
        $val3 = floatval($request->nilai_3);

        $avgTeknis = ($val1 + $val2 + $val3) / 3;

        // If non-technical grades are already filled, compute overall average
        if ($existingNilai && $existingNilai->nilai_sikap !== null && $existingNilai->nilai_keterampilan !== null && $existingNilai->nilai_pengetahuan !== null) {
            $akhir = ($val1 + $val2 + $val3 + floatval($existingNilai->nilai_sikap) + floatval($existingNilai->nilai_keterampilan) + floatval($existingNilai->nilai_pengetahuan)) / 6;
        } else {
            $akhir = $avgTeknis;
        }

        // Calculate predikat
        $predikat = 'E';
        if ($akhir >= 86) $predikat = 'A';
        elseif ($akhir >= 70) $predikat = 'B';
        elseif ($akhir >= 56) $predikat = 'C';
        elseif ($akhir >= 40) $predikat = 'D';

        Nilai::updateOrCreate(
            ['penempatan_magang_id' => $penempatan->id],
            [
                'kegiatan_1' => $keg1,
                'nilai_1' => $val1,
                'kegiatan_2' => $keg2,
                'nilai_2' => $val2,
                'kegiatan_3' => $keg3,
                'nilai_3' => $val3,
                'foto_nilai' => $fotoPath,
                'nilai_akhir' => $akhir,
                'predikat' => $predikat,
                'tanggal_input' => now(),
            ]
        );

        // Buat notifikasi
        Notifikasi::create([
            'siswa_id' => $siswaId,
            'judul' => 'Nilai Teknis Berhasil Diinput',
            'pesan' => 'Nilai teknis magang Anda berhasil disimpan.',
            'jenis' => 'success',
            'tipe' => 'umum',
            'is_read' => false,
        ]);

        return redirect()->route('siswa.riwayat.nilai')->with('success', 'Nilai teknis berhasil disimpan!');
    }

    // ==================== HELPER FUNCTIONS ====================
    private function hitungKehadiran($siswaId)
    {
        $totalAbsensi = Absensi::where('siswa_id', $siswaId)->count();
        if ($totalAbsensi == 0) return 0;
        $totalHadir = Absensi::where('siswa_id', $siswaId)->where('status', 'hadir')->count();
        return round(($totalHadir / $totalAbsensi) * 100);
    }

    private function hitungProgresJurnal($siswaId)
    {
        $targetJurnal = 60;
        $totalJurnal = JurnalHarian::where('siswa_id', $siswaId)->where('status', 'disetujui')->count();
        if ($totalJurnal == 0) return 0;
        return min(100, round(($totalJurnal / $targetJurnal) * 100));
    }

    private function hitungProgresLaporan($siswaId)
    {
        $laporan = LaporanPKL::where('siswa_id', $siswaId)->latest()->first();
        if (!$laporan) return 0;
        if ($laporan->status === 'disetujui') return 100;
        if ($laporan->status === 'perlu_revisi') return 50;
        if ($laporan->status === 'pending') return 40;
        if ($laporan->jenis === 'submit') return 50;
        if ($laporan->jenis === 'draft') return 20;
        return 0;
    }

    // ==================== CEK STATUS MAGANG (Existing) ====================
    public function cekStatus()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::with(['jurusan', 'penempatanMagangs.industri'])->find($siswaId);
        
        if (!$siswa) {
            return redirect('/login')->with('error', 'Data siswa tidak ditemukan');
        }
        
        $penempatan = $siswa->penempatanMagangs->first();
        $timeline = $this->generateTimeline($penempatan);
        $progress = $this->calculateProgress($timeline);
        $industris = Industri::where('is_active', true)->orderBy('nama_industri')->get();
        
        return view('siswa.cek-status', compact('siswa', 'penempatan', 'timeline', 'progress', 'industris'));
    }

    public function submitPilihan(Request $request)
    {
        $request->validate([
            'industri_id' => 'required|exists:industris,id',
            'posisi_magang' => 'required|string|max:255',
            'alasan' => 'nullable|string|max:500',
        ]);
        
        $siswaId = session('siswa_id');
        $siswa = Siswa::find($siswaId);
        
        // Cek apakah sudah punya penempatan
        $existing = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki penempatan magang.');
        }
        
        // Buat penempatan baru
        $penempatan = PenempatanMagang::create([
            'siswa_id' => $siswaId,
            'industri_id' => $request->industri_id,
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'tanggal_mulai' => now()->addDays(30),
            'tanggal_selesai' => now()->addDays(90),
            'status' => 'pending',
            'posisi_magang' => $request->posisi_magang,
            'catatan_industri' => $request->alasan,
        ]);
        
        // ✅ AUTO CREATE SURAT PENGANTAR
        \App\Models\SuratKeluar::create([
            'siswa_id' => $siswaId,
            'penempatan_magang_id' => $penempatan->id,
            'jenis_surat' => 'pengantar',
            'nomor_surat' => '421/SMK.3-TUBAN/' . rand(1000,9999) . '/' . date('m') . '/' . date('Y'),
            'status' => 'pending',
            'tanggal_kirim' => now(),
        ]);
        
        // Buat notifikasi
        \App\Models\Notifikasi::create([
            'siswa_id' => $siswaId,
            'judul' => 'Pengajuan Magang Dikirim',
            'pesan' => 'Pengajuan tempat magang Anda telah dikirim. Tunggu verifikasi dari TU.',
            'jenis' => 'info',
            'tipe' => 'umum',
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Pengajuan tempat magang berhasil dikirim!');
    }

    private function generateTimeline($penempatan)
    {
        $timeline = [
            ['step' => 1, 'title' => 'Pengajuan', 'desc' => 'Siswa mengajukan tempat magang', 'status' => 'pending', 'date' => null],
            ['step' => 2, 'title' => 'Verifikasi TU', 'desc' => 'Tata Usaha memverifikasi data', 'status' => 'pending', 'date' => null],
            ['step' => 3, 'title' => 'Approval Pimpinan', 'desc' => 'Kepala sekolah menyetujui', 'status' => 'pending', 'date' => null],
            ['step' => 4, 'title' => 'Penempatan', 'desc' => 'Siswa ditempatkan di industri', 'status' => 'pending', 'date' => null],
            ['step' => 5, 'title' => 'Selesai', 'desc' => 'Program magang selesai', 'status' => 'pending', 'date' => null],
        ];
        
        if ($penempatan) {
            $timeline[0]['status'] = 'completed';
            $timeline[0]['date'] = $penempatan->created_at;
            
            if ($penempatan->status === 'pending') {
                $timeline[1]['status'] = 'current';
            } elseif ($penempatan->status === 'approved' || $penempatan->status === 'verified') {
                $timeline[1]['status'] = 'completed';
                $timeline[2]['status'] = 'current';
                $timeline[2]['date'] = $penempatan->tanggal_approval;
            } elseif ($penempatan->status === 'ongoing') {
                $timeline[1]['status'] = 'completed';
                $timeline[2]['status'] = 'completed';
                $timeline[3]['status'] = 'current';
                $timeline[3]['date'] = $penempatan->tanggal_mulai;
            } elseif ($penempatan->status === 'completed') {
                $timeline[1]['status'] = 'completed';
                $timeline[2]['status'] = 'completed';
                $timeline[3]['status'] = 'completed';
                $timeline[4]['status'] = 'completed';
                $timeline[4]['date'] = $penempatan->tanggal_selesai;
            }
        }
        
        return $timeline;
    }

    private function calculateProgress($timeline)
    {
        $completed = 0;
        $total = count($timeline);
        foreach ($timeline as $item) {
            if ($item['status'] === 'completed') $completed++;
            elseif ($item['status'] === 'current') $completed += 0.5;
        }
        return ($completed / $total) * 100;
    }

    // ==================== RIWAYAT ABSENSI & JURNAL ====================
    public function riwayatAbsensi()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            return view('siswa.riwayat.absensi', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        $absensis = Absensi::where('siswa_id', $siswaId)
                        ->orderBy('tanggal', 'desc')
                        ->paginate(15);
                        
        return view('siswa.riwayat.absensi', compact('absensis', 'penempatan', 'bolehAbsen', 'pesanLock'));
    }

    public function riwayatJurnal()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            return view('siswa.riwayat.jurnal', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        $jurnals = JurnalHarian::where('siswa_id', $siswaId)
                            ->orderBy('tanggal', 'desc')
                            ->paginate(15);
                            
        return view('siswa.riwayat.jurnal', compact('jurnals', 'penempatan', 'bolehAbsen', 'pesanLock'));
    }

    public function riwayatLaporan()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)->first();
        
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            return view('siswa.riwayat.laporan-pkl', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        $laporans = LaporanPKL::where('siswa_id', $siswaId)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
                            
        return view('siswa.riwayat.laporan-pkl', compact('laporans', 'penempatan', 'bolehAbsen', 'pesanLock'));
    }

    public function riwayatNilai()
    {
        $siswaId = session('siswa_id');
        $penempatan = PenempatanMagang::with('nilai')->where('siswa_id', $siswaId)->first();
        
        $bolehAbsen = false;
        $pesanLock = '';
        
        if (!$penempatan) {
            $bolehAbsen = false;
            $pesanLock = 'Anda belum memiliki penempatan magang.';
        } elseif ($penempatan->status === 'pending') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda masih menunggu approval.';
        } elseif ($penempatan->status === 'rejected') {
            $bolehAbsen = false;
            $pesanLock = 'Pengajuan magang Anda ditolak.';
        } elseif (in_array($penempatan->status, ['approved', 'ongoing', 'completed'])) {
            $bolehAbsen = true;
        }
        
        if (!$bolehAbsen) {
            return view('siswa.riwayat.nilai-teknis', compact('penempatan', 'bolehAbsen', 'pesanLock'));
        }
        
        $nilai = $penempatan->nilai;
        
        return view('siswa.riwayat.nilai-teknis', compact('nilai', 'penempatan', 'bolehAbsen', 'pesanLock'));
    }
}