<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\PenempatanMagang;
use App\Models\SuratKeluar;
use App\Models\Absensi;
use App\Models\JurnalHarian;
use App\Models\LaporanPKL;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Pengumuman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;


class DashboardController extends Controller
{
    // ==================== ADMIN DASHBOARD ====================
    public function adminDashboard(Request $request)
    {
        \App\Models\PenempatanMagang::where('status', 'approved')
            ->whereNotNull('tanggal_mulai')
            ->where('tanggal_mulai', '<=', now())
            ->update(['status' => 'ongoing']);
            
        \App\Models\PenempatanMagang::where('status', 'ongoing')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', now())
            ->update(['status' => 'completed']);

        // 1. LOGIKA PERSISTENCE (Agar filter tidak hilang saat pindah halaman)
        $now = now();
        $month = $now->month;
        $year = $now->year;

        // Tentukan default awal berdasarkan kalender
        if ($month >= 7 && $month <= 12) {
            $defaultSemester = 'Ganjil';
            $defaultTahun = $year . '/' . ($year + 1);
        } else {
            $defaultSemester = 'Genap';
            $defaultTahun = ($year - 1) . '/' . $year;
        }

        // Cek apakah ada request baru, jika tidak ambil dari session, jika tidak ada ambil default
        if ($request->has('tahun_ajaran')) {
            session(['filter_tahun' => $request->tahun_ajaran]);
        }
        if ($request->has('semester')) {
            session(['filter_semester' => $request->semester]);
        }

        $filterTahun = session('filter_tahun', $defaultTahun);
        $filterSemester = session('filter_semester', $defaultSemester);

        // 2. QUERY UTAMA
        $baseQuery = \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)
                                                 ->where('semester', $filterSemester);

        // Statistik
        $totalSiswa = \App\Models\Siswa::count();
        $totalIndustri = \App\Models\Industri::count();
        
        // Surat Pending & Siswa Diterima (Global & Filtered)
        $suratPending = \App\Models\PenempatanMagang::where('status', 'pending')->count();
        $siswaDiterima = (clone $baseQuery)->whereIn('status', ['approved', 'ongoing', 'completed'])->count();
        
        $statusMagang = [
            'aktif' => (clone $baseQuery)->whereIn('status', ['ongoing', 'completed'])->count(),
            'proses' => (clone $baseQuery)->whereIn('status', ['pending', 'verified', 'approved'])->count(),
            'belum' => \App\Models\Siswa::whereDoesntHave('penempatanMagangs', function($q) use ($filterTahun, $filterSemester) {
                    $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester)
                      ->where('status', '!=', 'rejected'); // Siswa yang ditolak dianggap 'Belum Ada Lokasi'
                })->count(),
        ];
        
        // FIX: Sebaran Jurusan (Hanya hitung yang TIDAK ditolak)
        $sebaranJurusan = \App\Models\Siswa::selectRaw('jurusans.kode_jurusan, jurusans.nama_jurusan, COUNT(DISTINCT siswas.id) as total')
            ->leftJoin('jurusans', 'siswas.jurusan_id', '=', 'jurusans.id')
            ->join('penempatan_magangs', 'siswas.id', '=', 'penempatan_magangs.siswa_id')
            ->where('penempatan_magangs.tahun_ajaran', $filterTahun)
            ->where('penempatan_magangs.semester', $filterSemester)
            ->where('penempatan_magangs.status', '!=', 'rejected') // <--- DATA DITOLAK AKAN HILANG DARI GRAFIK
            ->groupBy('jurusans.id', 'jurusans.kode_jurusan', 'jurusans.nama_jurusan')
            ->orderBy('total', 'desc')
            ->get();
        
        // Ambil daftar Tahun Ajaran secara dinamis dari DB
        $listTahun = \App\Models\PenempatanMagang::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();
        if (!in_array($defaultTahun, $listTahun)) { $listTahun[] = $defaultTahun; }
        rsort($listTahun);

        // Aktivitas Terbaru (Global - Agar admin tahu pergerakan terakhir meskipun beda semester)
        $queryAktivitas = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri'])
            ->where('tahun_ajaran', $filterTahun) // Mengikuti filter tahun di atas
            ->where('semester', $filterSemester)   // Mengikuti filter semester di atas
            ->latest();

        // Tetap pertahankan fitur pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->search;
            $queryAktivitas->where(function($q) use ($search) {
                $q->whereHas('siswa', function($qs) use ($search) { 
                    $qs->where('nama', 'like', "%$search%"); 
                });
            });
        }

        $aktivitasTerbaru = $queryAktivitas->limit(10)->get();

        $stats = [
            'total_siswa' => $totalSiswa,
            'total_industri' => $totalIndustri,
            'surat_pending' => $suratPending,
            'siswa_diterima' => $siswaDiterima,
            'persentase_terpenuhi' => $totalSiswa > 0 ? round(($siswaDiterima / $totalSiswa) * 100, 2) : 0,
            'status_magang' => $statusMagang,
        ];
        
        return view('admin.dashboard', compact('stats', 'sebaranJurusan', 'aktivitasTerbaru', 'filterTahun', 'filterSemester', 'listTahun'));
    }

    // ==================== PENGUMUMAN ====================
    public function pengumumanView()
    {
        $pengumumans = \App\Models\Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman', compact('pengumumans'));
    }

    public function storePengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            // Sesuaikan dengan ENUM di database
            'prioritas' => 'required|in:rendah,sedang,tinggi', 
            // Wajib ada tanggal sesuai database
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        \App\Models\Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'prioritas' => $request->prioritas,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => 1,
            // Sesuaikan dengan nama kolom di databasemu
            'dibuat_oleh' => auth()->id() ?? 1, 
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil disiarkan ke seluruh siswa!');
    }

    public function deletePengumuman($id)
    {
        \App\Models\Pengumuman::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pengumuman berhasil ditarik/dihapus!');
    }

    // ==================== DATA SISWA ====================
    public function dataSiswa(Request $request)
    {
        $query = \App\Models\Siswa::with('jurusan');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $query->where('jurusan_id', $request->jurusan_id);
        }
        
        $siswas = $query->latest()->paginate(10);
        $jurusans = \App\Models\Jurusan::all();
        
        return view('admin.data-siswa', compact('siswas', 'jurusans'));
    }

    public function storeSiswa(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswas,nisn',
            'jurusan_id' => 'required|exists:jurusans,id',
            'kelas' => 'required|string|max:50',
            'email' => 'required|email|unique:siswas,email',
            'no_wa' => 'required|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nama_wali' => 'nullable|string|max:255',
            'no_wa_wali' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:4',
        ]);
        
        if (empty($validated['tanggal_lahir'])) $validated['tanggal_lahir'] = null;
        if (empty($validated['tempat_lahir'])) $validated['tempat_lahir'] = null;

        $passwordInput = $request->filled('password') ? $request->password : $validated['nisn'];
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($passwordInput);
        
        \App\Models\Siswa::create($validated);
        
        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswas,nisn,' . $id,
            'jurusan_id' => 'required|exists:jurusans,id',
            'kelas' => 'required|string|max:50',
            'email' => 'required|email|unique:siswas,email,' . $id,
            'no_wa' => 'required|string',
            'password' => 'nullable|string|min:4',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nama_wali' => 'nullable|string|max:255',
            'no_wa_wali' => 'nullable|string|max:20',
            'alamat' => 'required|string',
        ]);
        
        if (empty($validated['tanggal_lahir'])) $validated['tanggal_lahir'] = null;
        if (empty($validated['tempat_lahir'])) $validated['tempat_lahir'] = null;
        
        if ($request->filled('password')) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        } else {
            unset($validated['password']);
        }
        
        $siswa->update($validated);
        
        return redirect()->back()->with('success', 'Data siswa berhasil diupdate!');
    }

    public function deleteSiswa($id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        $siswa->delete();
        
        return redirect()->back()->with('success', 'Data siswa berhasil dihapus!');
    }

    public function importSiswa(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file_excel');

            // 2. PINDAHKAN FILE FISIK KE FOLDER PUBLIC (Bebas dari campur tangan Windows)
            // Sistem akan otomatis membuat folder 'temp' di dalam folder 'public' jika belum ada
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('temp'), $nama_file);
            
            $path_lengkap = public_path('temp/' . $nama_file);

            // 3. Baca dari path lengkap yang sudah aman tersebut
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\SiswaImport, $path_lengkap);

            // 4. Hapus manual file tersebut agar project tidak penuh
            if (file_exists($path_lengkap)) {
                unlink($path_lengkap);
            }

            // 5. Tampilkan laporan detail
            $stats = session('import_stats');
            $sukses = $stats['success'] ?? 0;
            $skip = $stats['skipped'] ?? 0;

            return redirect()->back()->with('success', "Import Selesai! Berhasil masuk: $sukses data. Dilewati: $skip data (karena NISN/Email sudah ada).");

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return redirect()->back()->with('error', 'Format isi excel ada yang salah atau berantakan.');
        } catch (\Exception $e) {
            // Jika masih error, path lengkapnya juga harus dihapus
            if (isset($path_lengkap) && file_exists($path_lengkap)) {
                unlink($path_lengkap);
            }
            return redirect()->back()->with('error', 'Kesalahan Sistem: ' . $e->getMessage());
        }
    }

    public function downloadTemplateSiswa()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'A1' => 'nisn',
            'B1' => 'nama',
            'C1' => 'jurusan',
            'D1' => 'kelas',
            'E1' => 'email',
            'F1' => 'no_wa',
            'G1' => 'tempat_lahir',
            'H1' => 'tanggal_lahir',
            'I1' => 'nama_wali',
            'J1' => 'no_wa_wali',
            'K1' => 'alamat',
        ];

        foreach ($headers as $cell => $label) {
            $sheet->setCellValue($cell, $label);
        }
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        // Format kolom A sebagai teks (NISN)
        $sheet->getStyle('A')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // Contoh data
        $sheet->setCellValueExplicit('A2', '0051234567', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B2', 'Nama Siswa');
        $sheet->setCellValue('C2', 'RPL');
        $sheet->setCellValue('D2', 'XII RPL 1');
        $sheet->setCellValue('E2', 'siswa@email.com');
        $sheet->setCellValue('F2', '08123456789');
        $sheet->setCellValue('G2', 'Tuban');
        $sheet->setCellValue('H2', '2007-01-01');
        $sheet->setCellValue('I2', 'Nama Wali');
        $sheet->setCellValue('J2', '08123456780');
        $sheet->setCellValue('K2', 'Jl. Contoh No. 1');

        // Auto width
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Template_Import_Siswa.xlsx';
        $tempPath = storage_path('app/temp/');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $writer->save($tempPath . $filename);

        return response()->download($tempPath . $filename)->deleteFileAfterSend();
    }
    
    // ==================== DATA INDUSTRI ====================
    public function dataIndustri(Request $request)
    {
        $query = \App\Models\Industri::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_industri', 'like', "%{$search}%")
                  ->orWhere('nib', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('kota') && $request->kota != '') {
            $query->where('kota', 'like', "%{$request->kota}%");
        }
        
        $industris = $query->latest()->paginate(10);
        
        return view('admin.data-industri', compact('industris'));
    }

    public function storeIndustri(Request $request)
    {
        $validated = $request->validate([
            'nib' => 'required|string|unique:industris,nib',
            'nama_industri' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'required|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'nama_hr' => 'nullable|string|max:255',
            'no_wa_hr' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'kapasitas_magang' => 'nullable|integer',
        ]);
        
        \App\Models\Industri::create($validated);
        
        return redirect()->back()->with('success', 'Data industri berhasil ditambahkan!');
    }

    public function updateIndustri(Request $request, $id)
    {
        $industri = \App\Models\Industri::findOrFail($id);
        
        $validated = $request->validate([
            'nib' => 'required|string|unique:industris,nib,' . $id,
            'nama_industri' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'required|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'nama_hr' => 'nullable|string|max:255',
            'no_wa_hr' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'kapasitas_magang' => 'nullable|integer',
        ]);
        
        $industri->update($validated);
        
        return redirect()->back()->with('success', 'Data industri berhasil diupdate!');
    }

    public function deleteIndustri($id)
    {
        $industri = \App\Models\Industri::findOrFail($id);
        $industri->delete();
        
        return redirect()->back()->with('success', 'Data industri berhasil dihapus!');
    }

    // ==================== DATA JURUSAN ====================
    public function dataJurusan(Request $request)
    {
        $query = \App\Models\Jurusan::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_jurusan', 'like', "%{$search}%")
                  ->orWhere('kode_jurusan', 'like', "%{$search}%");
            });
        }
        
        $jurusans = $query->latest()->paginate(10);
        
        return view('admin.data-jurusan', compact('jurusans'));
    }

    public function storeJurusan(Request $request)
    {
        $validated = $request->validate([
            'kode_jurusan' => 'required|string|unique:jurusans,kode_jurusan',
            'nama_jurusan' => 'required|string|max:255',
            'kepala_jurusan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        \App\Models\Jurusan::create($validated);
        
        return redirect()->back()->with('success', 'Data jurusan berhasil ditambahkan!');
    }

    public function updateJurusan(Request $request, $id)
    {
        $jurusan = \App\Models\Jurusan::findOrFail($id);
        
        $validated = $request->validate([
            'kode_jurusan' => 'required|string|unique:jurusans,kode_jurusan,' . $id,
            'nama_jurusan' => 'required|string|max:255',
            'kepala_jurusan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $jurusan->update($validated);
        
        return redirect()->back()->with('success', 'Data jurusan berhasil diupdate!');
    }

    public function deleteJurusan($id)
    {
        $jurusan = \App\Models\Jurusan::findOrFail($id);
        $jurusan->delete();
        
        return redirect()->back()->with('success', 'Data jurusan berhasil dihapus!');
    }

    // ==================== DATA SURAT ====================
    public function viewDataSurat()
    {
        $pengajuans = \App\Models\PenempatanMagang::with([
            'siswa', 
            'siswa.jurusan',
            'industri',
            'suratKeluar' 
        ])
        ->latest()
        ->paginate(10);

        return view('admin.data-surat', compact('pengajuans'));
    }

    public function downloadSuratAdmin($id)
    {
        $surat = \App\Models\SuratKeluar::find($id);

        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan!');
        }

        $filePath = storage_path('app/public/' . $surat->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()->with('error', 'File surat tidak ditemukan.');
    }

    // ==================== SURAT MASUK ====================
    public function viewSuratMasuk(Request $request)
    {
        $query = \App\Models\SuratMasuk::with(['penempatanMagang.siswa', 'penempatanMagang.industri', 'createdBy']);
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
        }
        
        $suratMasuks = $query->latest()->paginate(10);
        $penempatans = \App\Models\PenempatanMagang::with(['siswa', 'industri'])->whereIn('status', ['pending', 'verified', 'approved', 'rejected'])->get();
        
        return view('admin.surat-masuk', compact('suratMasuks', 'penempatans'));
    }

    public function storeSuratMasuk(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string',
            'pengirim' => 'required|string',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'penempatan_magang_id' => 'required|exists:penempatan_magangs,id',
            'status_balasan' => 'required|in:terima,tolak,diproses'
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat_masuk', 'public');
        }

        $suratMasuk = \App\Models\SuratMasuk::create([
            'nomor_surat' => $validated['nomor_surat'],
            'pengirim' => $validated['pengirim'],
            'tanggal_terima' => $validated['tanggal_terima'],
            'perihal' => $validated['perihal'],
            'file_path' => $filePath,
            'status' => 'selesai',
            'penempatan_magang_id' => $validated['penempatan_magang_id'],
            'created_by' => auth()->id()
        ]);

        $penempatan = \App\Models\PenempatanMagang::find($validated['penempatan_magang_id']);
        if ($validated['status_balasan'] == 'terima') {
            $penempatan->update(['status' => 'approved']);
            \App\Models\Notifikasi::create([
                'siswa_id' => $penempatan->siswa_id,
                'judul' => 'Pengajuan Diterima Industri',
                'pesan' => "Selamat, pengajuan magang Anda diterima oleh {$penempatan->industri->nama_industri}.",
                'jenis' => 'success',
                'tipe' => 'app',
                'is_read' => false,
            ]);
        } elseif ($validated['status_balasan'] == 'tolak') {
            $penempatan->update([
                'status' => 'rejected',
                'alasan_penolakan' => 'Ditolak oleh Industri: ' . $validated['perihal']
            ]);
            \App\Models\Notifikasi::create([
                'siswa_id' => $penempatan->siswa_id,
                'judul' => 'Pengajuan Ditolak Industri',
                'pesan' => "Mohon maaf, pengajuan Anda ditolak oleh {$penempatan->industri->nama_industri}.",
                'jenis' => 'error',
                'tipe' => 'app',
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Surat Masuk berhasil dicatat.');
    }

    // ==================== NILAI & SERTIFIKAT ====================
    public function viewImportNilai()
    {
        return view('admin.import-nilai');
    }

    public function viewGenerateSertifikat()
    {
        $pengajuans = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai', 'sertifikat'])
            ->whereIn('status', ['approved', 'completed'])
            ->whereHas('nilai')
            ->latest()
            ->paginate(15);
            
        return view('admin.generate-sertifikat', compact('pengajuans'));
    }

    public function importNilai(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\NilaiImport, $request->file('file_excel'));
            return redirect()->back()->with('success', 'Data Nilai berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import nilai: ' . $e->getMessage());
        }
    }

    public function generateSertifikat($id)
    {
        $penempatan = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai'])->findOrFail($id);
        
        if (!$penempatan->nilai) {
            return redirect()->back()->with('error', 'Siswa belum memiliki nilai, tidak dapat mencetak sertifikat.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat-magang', compact('penempatan'));
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'Sertifikat_' . str_replace(' ', '_', $penempatan->siswa->nama) . '_' . date('YmdHis') . '.pdf';
        
        if (!file_exists(storage_path('app/public/sertifikat'))) {
            mkdir(storage_path('app/public/sertifikat'), 0777, true);
        }
        
        $pdf->save(storage_path('app/public/sertifikat/' . $filename));
        
        \App\Models\Sertifikat::updateOrCreate(
            ['penempatan_magang_id' => $penempatan->id],
            [
                'nilai_id' => $penempatan->nilai->id,
                'nomor_sertifikat' => 'SERT/' . date('Y') . '/' . str_pad($penempatan->id, 4, '0', STR_PAD_LEFT),
                'file_path' => 'sertifikat/' . $filename,
                'tanggal_terbit' => now(),
                'status' => 'issued',
                'generated_by' => auth()->id()
            ]
        );
        
        return $pdf->download($filename);
    }

    // ==================== WA BLAST & LOGS ====================
    public function viewWaBlast(Request $request)
    {
        // 1. Ambil data jurusan untuk Filter
        $jurusans = \App\Models\Jurusan::where('is_active', 1)->get();

        // 2. Ambil data siswa + relasi industri
        $siswas = \App\Models\Siswa::with(['jurusan', 'penempatanMagangs.industri'])
                    ->whereNotNull('no_wa')
                    ->where('no_wa', '!=', '')
                    ->orderBy('nama', 'asc')
                    ->get();
        
        // 3. Set penanda magang aktif & nama industri
        $siswas->each(function($siswa) {
            $magangAktif = $siswa->penempatanMagangs->where('status', 'ongoing')->first();
            $siswa->is_magang_aktif = $magangAktif ? '1' : '0';
            $siswa->nama_industri = $magangAktif ? ($magangAktif->industri->nama_industri ?? '') : '';
        });

        // 4. Logika Pencarian Log Pengiriman
        $queryLogs = \App\Models\LogWa::with('siswa.jurusan')->latest();
        
        if ($request->has('log_search') && $request->log_search != '') {
            $search = $request->log_search;
            $queryLogs->where(function($q) use ($search) {
                $q->where('no_wa_tujuan', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($qSiswa) use ($search) {
                      $qSiswa->where('nama', 'like', "%{$search}%")
                             ->orWhereHas('jurusan', function($qJur) use ($search) {
                                 $qJur->where('kode_jurusan', 'like', "%{$search}%")
                                      ->orWhere('nama_jurusan', 'like', "%{$search}%");
                             });
                  });
            });
        }
        
        // Pagination Log (10 per halaman)
        $logs = $queryLogs->paginate(10); 
        
        // 5. Statistik Asli dari Database
        $statTerkirim = \App\Models\LogWa::where('status', 'sent')->count();
        $statGagal = \App\Models\LogWa::where('status', 'failed')->count();
        $statTotal = $statTerkirim + $statGagal;
        
        return view('admin.wa-blast', compact('siswas', 'jurusans', 'logs', 'statTerkirim', 'statGagal', 'statTotal'));
    }

    public function sendWaBlast(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string',
            'targets' => 'required|array', // Sekarang menerima array dari checkbox
            'targets.*' => 'string'
        ]);

        $pesan = $request->pesan;
        $nomorTujuan = $request->targets; // Berisi kumpulan nomor WA

        $token = env('FONNTE_TOKEN'); 
        if (empty($nomorTujuan) || !$token) {
            return redirect()->back()->with('error', 'Token Fonnte belum diatur atau tidak ada nomor tujuan yang dipilih.');
        }

        try {
            // Hit API Fonnte sekaligus (Multi-target dipisah koma)
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => implode(',', $nomorTujuan),
                'message' => $pesan,
                'delay' => '2-5'
            ]);

            $result = $response->json();
            $isSuccess = isset($result['status']) && $result['status'] == true;

            // Catat log ke tabel log_was
            foreach ($nomorTujuan as $no) {
                $siswa = \App\Models\Siswa::where('no_wa', $no)->first();
                \App\Models\LogWa::create([
                    'siswa_id' => $siswa ? $siswa->id : null,
                    'no_wa_tujuan' => $no,
                    'pesan' => $pesan,
                    'jenis' => 'blast',
                    'status' => $isSuccess ? 'sent' : 'failed',
                    'created_by' => auth()->id()
                ]);
            }

            if($isSuccess) {
                return redirect()->back()->with('success', count($nomorTujuan) . ' Pesan Blast berhasil masuk antrean server Fonnte.');
            } else {
                return redirect()->back()->with('error', 'Gagal API Fonnte: ' . ($result['reason'] ?? 'Unknown Error'));
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ==================== LAPORAN & CETAK ====================
    public function laporanCetak()
    {
        $industris = \App\Models\Industri::all();
        $jurusans = \App\Models\Jurusan::all();

        $siswaAktif = \App\Models\Siswa::whereHas('penempatanMagangs', function ($q) {
            $q->whereIn('status', ['approved', 'ongoing', 'completed']);
        })
        ->with(['jurusan', 'penempatanMagangs' => function ($q) {
            $q->whereIn('status', ['approved', 'ongoing', 'completed'])->with('industri');
        }])
        ->get();

        foreach ($siswaAktif as $s) {
            $s->hadir = \App\Models\Absensi::where('siswa_id', $s->id)->where('status', 'hadir')->count();
            $s->izin = \App\Models\Absensi::where('siswa_id', $s->id)->where('status', 'izin')->count();
            $s->sakit = \App\Models\Absensi::where('siswa_id', $s->id)->where('status', 'sakit')->count();
            $s->alpha = \App\Models\Absensi::where('siswa_id', $s->id)->where('status', 'alpha')->count();

            $s->jurnal_disetujui = \App\Models\JurnalHarian::where('siswa_id', $s->id)->where('status', 'disetujui')->count();
            $s->jurnal_pending = \App\Models\JurnalHarian::where('siswa_id', $s->id)->where('status', 'pending')->count();
            $s->jurnal_revisi = \App\Models\JurnalHarian::where('siswa_id', $s->id)->where('status', 'ditolak')->count();

            $laporanDisetujui = \App\Models\LaporanPKL::where('siswa_id', $s->id)
                    ->where('status', 'disetujui')
                    ->latest()
                    ->first();
            $s->laporan = $laporanDisetujui 
                    ?? \App\Models\LaporanPKL::where('siswa_id', $s->id)->latest()->first();
        }

        $rekapJurusan = $jurusans->map(function ($jurusan) use ($siswaAktif) {
            $siswa = $siswaAktif->where('jurusan_id', $jurusan->id)->values();
            return [
                'jurusan' => $jurusan,
                'siswa' => $siswa,
                'total_siswa' => $siswa->count(),
            ];
        })->filter(function ($item) {
            return $item['total_siswa'] > 0;
        });

        $rekapIndustri = $industris->map(function ($industri) use ($siswaAktif) {
            $siswa = $siswaAktif->filter(function ($s) use ($industri) {
                $penempatan = $s->penempatanMagangs->first();
                return $penempatan && $penempatan->industri_id == $industri->id;
            })->values();
            return [
                'industri' => $industri,
                'siswa' => $siswa,
                'total_siswa' => $siswa->count(),
            ];
        })->filter(function ($item) {
            return $item['total_siswa'] > 0;
        });

        return view('admin.laporan-cetak', compact('industris', 'jurusans', 'rekapJurusan', 'rekapIndustri'));
    }

    public function adminApprove($id)
    {
        $penempatan = \App\Models\PenempatanMagang::findOrFail($id);
        
        $penempatan->update(['status' => 'verified']);
        
        \App\Models\Notifikasi::create([
            'siswa_id' => $penempatan->siswa_id,
            'judul' => 'Pengajuan Diverifikasi Admin',
            'pesan' => 'Pengajuan Anda telah diverifikasi dan menunggu persetujuan Kepala Sekolah.',
            'jenis' => 'info',
            'tipe' => 'app',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi & diteruskan ke Pimpinan.');
    }

    public function startMagang(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai' => 'required'
        ]);

        $penempatan = \App\Models\PenempatanMagang::findOrFail($id);
        $tglMulai = \Carbon\Carbon::parse($request->tanggal_mulai);

        // Jika waktu yang diset sudah lewat/sekarang, langsung mulai
        if ($tglMulai->isPast()) {
            $penempatan->update([
                'status' => 'ongoing',
                'tanggal_mulai' => $tglMulai
            ]);
            $pesan = 'Waktu telah tiba, Magang telah DIMULAI secara resmi.';
        } else {
            // Jika waktu masih di masa depan, hanya simpan tanggalnya saja
            $penempatan->update([
                'tanggal_mulai' => $tglMulai
            ]);
            $pesan = 'Jadwal mulai berhasil diatur. Sistem akan otomatis memulai saat waktunya tiba.';
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function endMagang(Request $request, $id)
    {
        $request->validate([
            'tanggal_selesai' => 'required'
        ]);

        $penempatan = \App\Models\PenempatanMagang::findOrFail($id);
        $tglSelesai = \Carbon\Carbon::parse($request->tanggal_selesai);

        // Jika waktu yang diset sudah lewat/sekarang, langsung selesaikan
        if ($tglSelesai->isPast()) {
            $penempatan->update([
                'status' => 'completed',
                'tanggal_selesai' => $tglSelesai
            ]);
            $pesan = 'Waktu telah tiba, Magang telah otomatis DIAKHIRI/SELESAI.';
        } else {
            // FIX: Paksa kembalikan status ke 'ongoing' karena waktunya masih di masa depan
            $penempatan->update([
                'status' => 'ongoing',
                'tanggal_selesai' => $tglSelesai
            ]);
            $pesan = 'Jadwal selesai berhasil diatur. Sistem akan otomatis mengakhiri saat waktunya tiba.';
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function adminReject(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'nullable|string'
        ]);

        $penempatan = \App\Models\PenempatanMagang::findOrFail($id);
        $penempatan->update([
            'status' => 'rejected',
            'alasan_penolakan' => $validated['alasan_penolakan'] ?? 'Tidak ada alasan diberikan.',
        ]);
        
        \App\Models\Notifikasi::create([
            'siswa_id' => $penempatan->siswa_id,
            'judul' => 'Pengajuan Ditolak Admin',
            'pesan' => 'Pengajuan Anda ditolak. Alasan: ' . ($validated['alasan_penolakan'] ?? 'Lihat detail di sistem.'),
            'jenis' => 'error',
            'tipe' => 'app',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }

    // ==================== KONTROL MAGANG ====================
    public function kontrolMagangView()
    {
        // --- SENSOR OTOMATIS: Berjalan setiap kali halaman direfresh ---
        \App\Models\PenempatanMagang::where('status', 'approved')
            ->whereNotNull('tanggal_mulai')
            ->where('tanggal_mulai', '<=', now())
            ->update(['status' => 'ongoing']);
            
        \App\Models\PenempatanMagang::where('status', 'ongoing')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', now())
            ->update(['status' => 'completed']);
        // ---------------------------------------------------------------

        // Hanya tampilkan siswa yang sudah disetujui (approved), sedang magang (ongoing), atau selesai (completed)
        $penempatans = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri'])
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->paginate(15);
            
        return view('admin.kontrol-magang', compact('penempatans'));
    }

    // ==================== PIMPINAN DASHBOARD ====================
    public function pimpinanDashboard(Request $request)
    {
        // 1. SENSOR OTOMATIS: Update Status Waktu Magang
        \App\Models\PenempatanMagang::where('status', 'approved')
            ->whereNotNull('tanggal_mulai')
            ->where('tanggal_mulai', '<=', now())
            ->update(['status' => 'ongoing']);
            
        \App\Models\PenempatanMagang::where('status', 'ongoing')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', now())
            ->update(['status' => 'completed']);

        // 2. LOGIKA PERSISTENCE FILTER (Sama seperti Admin)
        $now = now();
        $month = $now->month;
        $year = $now->year;

        if ($month >= 7 && $month <= 12) {
            $defaultSemester = 'Ganjil';
            $defaultTahun = $year . '/' . ($year + 1);
        } else {
            $defaultSemester = 'Genap';
            $defaultTahun = ($year - 1) . '/' . $year;
        }

        if ($request->has('tahun_ajaran')) { session(['filter_tahun' => $request->tahun_ajaran]); }
        if ($request->has('semester')) { session(['filter_semester' => $request->semester]); }

        $filterTahun = session('filter_tahun', $defaultTahun);
        $filterSemester = session('filter_semester', $defaultSemester);

        $listTahun = \App\Models\PenempatanMagang::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();
        if (!in_array($defaultTahun, $listTahun)) $listTahun[] = $defaultTahun;
        sort($listTahun);

        // 3. HITUNG STATISTIK FILTERED (Disamakan dengan Admin)
        $totalSiswa = \App\Models\Siswa::count();
        $stats = [
            'total_siswa' => $totalSiswa,
            'siswa_diterima' => \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)
                ->where('semester', $filterSemester)
                ->whereIn('status', ['approved', 'ongoing', 'completed'])
                ->count(),
            'surat_pending' => \App\Models\PenempatanMagang::where('status', 'pending')->count(),
            'total_industri' => \App\Models\Industri::where('is_active', 1)->count(),
            'status_magang' => [
                'aktif' => \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester)->where('status', 'ongoing')->count(),
                'proses' => \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester)->whereIn('status', ['pending', 'verified', 'approved'])->count(),
                'selesai' => \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester)->where('status', 'completed')->count(),
                'belum' => $totalSiswa - \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester)->whereIn('status', ['approved', 'ongoing', 'completed'])->count()
            ]
        ];
        $stats['persentase_terpenuhi'] = $stats['total_siswa'] > 0 ? round(($stats['siswa_diterima'] / $stats['total_siswa']) * 100) : 0;

        // 4. Sebaran Jurusan
        $sebaranJurusan = \App\Models\Jurusan::withCount(['siswas' => function($q) use ($filterTahun, $filterSemester) {
            $q->whereHas('penempatanMagangs', function($p) use ($filterTahun, $filterSemester) {
                $p->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester)->whereIn('status', ['approved', 'ongoing', 'completed']);
            });
        }])->get();

        // 5. Aktivitas Terbaru (Untuk tabel)
        $aktivitas = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri'])
            ->where('tahun_ajaran', $filterTahun)
            ->where('semester', $filterSemester)
            ->latest()
            ->limit(5)
            ->get();

        return view('pimpinan.dashboard', compact('stats', 'filterTahun', 'filterSemester', 'listTahun', 'sebaranJurusan', 'aktivitas'));
    }

    // ==================== NOTIFIKASI ====================
    public function getNotifikasi()
    {
        $approvalPending = \App\Models\PenempatanMagang::where('status', 'verified')->count();
        
        $notifications = [];
        
        if ($approvalPending > 0) {
            $notifications[] = [
                'id' => 1,
                'judul' => 'Ada ' . $approvalPending . ' Pengajuan Menunggu Approval',
                'pesan' => 'Pengajuan magang sudah diverifikasi TU dan menunggu persetujuan Anda.',
                'created_at' => now()->subMinutes(5),
                'is_read' => false,
            ];
        }
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => count($notifications)
        ]);
    }

    public function markNotifikasiAsRead($id)
    {
        return response()->json(['success' => true]);
    }

    public function markAllNotifikasiAsRead()
    {
        return response()->json(['success' => true]);
    }

    // ==================== APPROVAL SURAT (PIMPINAN) ====================
    public function approvalSurat(Request $request)
    {
        // 1. Logika Semester & Tahun (Pakai session biar sinkron dengan dashboard)
        $now = now();
        $month = $now->month;
        $year = $now->year;

        if ($month >= 7 && $month <= 12) {
            $defaultSemester = 'Ganjil';
            $defaultTahun = $year . '/' . ($year + 1);
        } else {
            $defaultSemester = 'Genap';
            $defaultTahun = ($year - 1) . '/' . $year;
        }

        // Jika halaman ini dikunjungi dengan parameter, update session
        if ($request->has('tahun_ajaran')) {
            session(['filter_tahun' => $request->tahun_ajaran]);
        }
        if ($request->has('semester')) {
            session(['filter_semester' => $request->semester]);
        }

        $filterTahun = session('filter_tahun', $defaultTahun);
        $filterSemester = session('filter_semester', $defaultSemester);

        $listTahun = \App\Models\PenempatanMagang::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();
        if (!in_array($defaultTahun, $listTahun)) $listTahun[] = $defaultTahun;
        rsort($listTahun);

        // 2. QUERY BASE DENGAN FILTER
        $baseQuery = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri'])
                        ->where('tahun_ajaran', $filterTahun)
                        ->where('semester', $filterSemester);

        // Menunggu Persetujuan (status verified)
        $pengajuans = (clone $baseQuery)->where('status', 'verified')
                        ->latest()
                        ->paginate(10);

        // Disetujui (limit 10)
        $approved = (clone $baseQuery)->where('status', 'approved')
                        ->latest()
                        ->limit(10)
                        ->get();

        // Ditolak (limit 10)
        $rejected = (clone $baseQuery)->where('status', 'rejected')
                        ->latest()
                        ->limit(10)
                        ->get();

        // 3. STATISTIK BERDASARKAN FILTER
        $stats = [
            'total_pending' => (clone $baseQuery)->where('status', 'verified')->count(),
            'total_approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'total_rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        return view('pimpinan.approval-surat', compact(
            'pengajuans', 'approved', 'rejected', 'stats',
            'filterTahun', 'filterSemester', 'listTahun'
        ));
    }
    
    public function approveSurat($id)
    {
        $penempatan = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri'])->find($id);
        
        if (!$penempatan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        try {
            $penempatan->update(['status' => 'approved']);
            
            $nomorSurat = '421/SMK.3-TUBAN/' . rand(1000,9999) . '/' . date('m') . '/' . date('Y');
            $data = [
                'penempatan' => $penempatan,
                'nomor_surat' => $nomorSurat,
                'tanggal_surat' => now(),
            ];
            
            $pdf = Pdf::loadView('pdf.surat-pengantar', $data);
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'Surat_Pengantar_' . str_replace(' ', '_', $penempatan->siswa->nama) . '_' . date('YmdHis') . '.pdf';
            
            if (!file_exists(storage_path('app/public/surat'))) {
                mkdir(storage_path('app/public/surat'), 0777, true);
            }
            
            $pdf->save(storage_path('app/public/surat/' . $filename));
            
            \App\Models\SuratKeluar::create([
                'penempatan_magang_id' => $penempatan->id,
                'jenis_surat' => 'pengantar',
                'nomor_surat' => $nomorSurat,
                'status' => 'approved',
                'file_path' => 'surat/' . $filename,
                'tanggal_kirim' => now(),
                'created_by' => auth()->id(),
                'catatan' => null,
                'template_surat_id' => null,
            ]);
            
            $namaIndustri = $penempatan->industri->nama_industri ?? 'industri';
            \App\Models\Notifikasi::create([
                'siswa_id' => $penempatan->siswa_id,
                'judul' => 'Pengajuan Magang Disetujui',
                'pesan' => "Pengajuan magang Anda di {$namaIndustri} telah disetujui oleh Kepala Sekolah. Silakan download Surat Pengantar Anda di Dashboard.",
                'jenis' => 'success',
                'tipe' => 'umum',
                'is_read' => false,
            ]);
            
            return redirect()->back()->with('success', 'Pengajuan berhasil disetujui dan Surat Pengantar telah dibuat.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function rejectSurat(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'nullable|string'
        ]);

        $penempatan = \App\Models\PenempatanMagang::findOrFail($id);
        $penempatan->update([
            'status' => 'rejected',
            'alasan_penolakan' => $validated['alasan_penolakan'] ?? 'Tidak ada alasan diberikan.',
        ]);
        
        \App\Models\Notifikasi::create([
            'siswa_id' => $penempatan->siswa_id,
            'judul' => 'Pengajuan Ditolak Pimpinan',
            'pesan' => 'Pengajuan Anda ditolak oleh Kepala Sekolah. Alasan: ' . ($validated['alasan_penolakan'] ?? 'Lihat detail di sistem.'),
            'jenis' => 'error',
            'tipe' => 'app',
            'is_read' => false,
        ]);
        
        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }

    // ==================== STATISTIK ====================
    public function statistik(Request $request)
    {
        // 1. Logika Semester & Tahun (Pakai session biar sinkron dengan dashboard)
        $now = now();
        $month = $now->month;
        $year = $now->year;

        if ($month >= 7 && $month <= 12) {
            $defaultSemester = 'Ganjil';
            $defaultTahun = $year . '/' . ($year + 1);
        } else {
            $defaultSemester = 'Genap';
            $defaultTahun = ($year - 1) . '/' . $year;
        }

        if ($request->has('tahun_ajaran')) {
            session(['filter_tahun' => $request->tahun_ajaran]);
        }
        if ($request->has('semester')) {
            session(['filter_semester' => $request->semester]);
        }

        $filterTahun = session('filter_tahun', $defaultTahun);
        $filterSemester = session('filter_semester', $defaultSemester);

        $listTahun = \App\Models\PenempatanMagang::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();
        if (!in_array($defaultTahun, $listTahun)) $listTahun[] = $defaultTahun;
        rsort($listTahun);

        // 2. QUERY BASE DENGAN FILTER TAHUN AJARAN & SEMESTER
        $baseQuery = \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)
                                                ->where('semester', $filterSemester);

        // Total Siswa Magang (semua yang tidak rejected pada periode ini)
        $totalSiswa = (clone $baseQuery)->where('status', '!=', 'rejected')->count();
        
        // Total Industri (semua industri yang dipakai pada periode ini)
        $totalIndustri = \App\Models\Industri::whereHas('penempatanMagangs', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        // Total Laporan PKL (pada periode ini)
        $totalLaporan = \App\Models\LaporanPKL::whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();

        // Distribusi Status Siswa
        $statusData = (clone $baseQuery)->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $statusData = array_merge([
            'pending' => 0, 'verified' => 0, 'approved' => 0, 'ongoing' => 0, 'completed' => 0, 'rejected' => 0,
        ], $statusData);

        // Siswa per Jurusan
        $jurusanData = (clone $baseQuery)->selectRaw('jurusans.kode_jurusan, count(penempatan_magangs.id) as count')
            ->join('siswas', 'penempatan_magangs.siswa_id', '=', 'siswas.id')
            ->leftJoin('jurusans', 'siswas.jurusan_id', '=', 'jurusans.id')
            ->groupBy('jurusans.kode_jurusan')
            ->pluck('count', 'kode_jurusan')
            ->toArray();

        // Top 5 Industri
        $industriData = (clone $baseQuery)->selectRaw('industris.nama_industri, count(*) as count')
            ->leftJoin('industris', 'penempatan_magangs.industri_id', '=', 'industris.id')
            ->groupBy('industris.nama_industri')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('count', 'nama_industri')
            ->toArray();

        // Kehadiran
        $totalAbsensi = \App\Models\Absensi::whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $hadir = \App\Models\Absensi::where('status', 'hadir')->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $izin = \App\Models\Absensi::where('status', 'izin')->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $sakit = \App\Models\Absensi::where('status', 'sakit')->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $alpha = \App\Models\Absensi::where('status', 'alpha')->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $kehadiranRate = $totalAbsensi > 0 ? round(($hadir / $totalAbsensi) * 100, 1) : 0;

        // Jurnal
        $totalJurnal = \App\Models\JurnalHarian::whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $jurnalDisetujui = \App\Models\JurnalHarian::where('status', 'disetujui')->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();
        
        $jurnalPending = \App\Models\JurnalHarian::where('status', 'pending')->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->count();

        // Monthly Trend (6 bulan terakhir dalam periode)
        $monthlyData = (clone $baseQuery)->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyLabels = [];
        $monthlyValues = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = $months[$i - 1];
            $monthlyValues[] = $monthlyData[$i] ?? 0;
        }

        return view('pimpinan.statistik', compact(
            'totalSiswa', 'totalIndustri', 'totalLaporan', 'statusData', 'jurusanData', 'industriData',
            'kehadiranRate', 'hadir', 'izin', 'sakit', 'alpha', 'totalAbsensi', 'totalJurnal',
            'jurnalDisetujui', 'jurnalPending', 'monthlyLabels', 'monthlyValues',
            'filterTahun', 'filterSemester', 'listTahun'
        ));
    }

    // ==================== GENERATE PDF ====================
    public function generateSuratPDF($id)
    {
        $penempatan = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri'])->find($id);
        
        if (!$penempatan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        
        $data = [
            'penempatan' => $penempatan,
            'nomor_surat' => '421/SMK.3-TUBAN/' . rand(1000,9999) . '/' . date('m') . '/' . date('Y'),
            'tanggal_surat' => now(),
        ];
        
        $pdf = Pdf::loadView('pdf.surat-pengantar', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Surat_Pengantar_' . str_replace(' ', '_', $penempatan->siswa->nama) . '_' . date('YmdHis') . '.pdf';
        
        if (!file_exists(storage_path('app/public/surat'))) {
            mkdir(storage_path('app/public/surat'), 0777, true);
        }
        
        $pdf->save(storage_path('app/public/surat/' . $filename));
        
        \App\Models\SuratKeluar::updateOrCreate(
            ['penempatan_magang_id' => $penempatan->id, 'jenis_surat' => 'pengantar'],
            [
                'nomor_surat' => $data['nomor_surat'],
                'status' => 'approved',
                'file_path' => 'surat/' . $filename,
                'tanggal_kirim' => now(),
                'created_by' => auth()->id(),
                'catatan' => null,
                'template_surat_id' => null,
            ]
        );
        
        $namaIndustri = $penempatan->industri->nama_industri ?? 'industri';
        \App\Models\Notifikasi::create([
            'siswa_id' => $penempatan->siswa_id,
            'judul' => 'Pengajuan Magang Disetujui',
            'pesan' => "Pengajuan magang Anda di {$namaIndustri} telah disetujui. Surat pengantar sudah tersedia untuk diunduh.",
            'jenis' => 'success',
            'tipe' => 'app',
            'is_read' => false,
        ]);
        
        return $pdf->download('Surat_Pengantar_' . $penempatan->siswa->nisn . '.pdf');
    }

    // ==================== LAPORAN PIMPINAN ====================
    public function laporan(Request $request)
    {
        // 1. Logika Semester & Tahun (Pakai session biar sinkron)
        $now = now();
        $month = $now->month;
        $year = $now->year;

        if ($month >= 7 && $month <= 12) {
            $defaultSemester = 'Ganjil';
            $defaultTahun = $year . '/' . ($year + 1);
        } else {
            $defaultSemester = 'Genap';
            $defaultTahun = ($year - 1) . '/' . $year;
        }

        if ($request->has('tahun_ajaran')) {
            session(['filter_tahun' => $request->tahun_ajaran]);
        }
        if ($request->has('semester')) {
            session(['filter_semester' => $request->semester]);
        }

        $filterTahun = session('filter_tahun', $defaultTahun);
        $filterSemester = session('filter_semester', $defaultSemester);

        $listTahun = \App\Models\PenempatanMagang::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();
        if (!in_array($defaultTahun, $listTahun)) $listTahun[] = $defaultTahun;
        rsort($listTahun);

        // 2. QUERY BASE DENGAN FILTER
        $baseQuery = \App\Models\PenempatanMagang::where('tahun_ajaran', $filterTahun)
                                                ->where('semester', $filterSemester);

        // Industri (yang dipakai di periode ini)
        $industris = \App\Models\Industri::whereHas('penempatanMagangs', function($q) use ($filterTahun, $filterSemester) {
            $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
        })->latest()->get();

        // Siswa Magang Aktif (ongoing)
        $siswaMagang = (clone $baseQuery)->with(['siswa', 'industri'])
            ->where('status', 'ongoing')
            ->latest()
            ->get();

        // Laporan Final (disetujui)
        $laporanFinal = \App\Models\LaporanPKL::with(['siswa', 'penempatanMagang.industri'])
            ->where('status', 'disetujui')
            ->whereHas('penempatanMagang', function($q) use ($filterTahun, $filterSemester) {
                $q->where('tahun_ajaran', $filterTahun)->where('semester', $filterSemester);
            })
            ->latest()
            ->get();

        // Log Aktivitas
        $logAktivitas = (clone $baseQuery)->with(['siswa', 'industri'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pimpinan.laporan', compact(
            'industris', 'siswaMagang', 'laporanFinal', 'logAktivitas',
            'filterTahun', 'filterSemester', 'listTahun'
        ));
    }

    // ==================== SISWA DASHBOARD ====================
    public function siswaDashboard()
    {
        // --- SENSOR OTOMATIS: Update Status Waktu Magang ---
        \App\Models\PenempatanMagang::where('status', 'approved')
            ->whereNotNull('tanggal_mulai')
            ->where('tanggal_mulai', '<=', now())
            ->update(['status' => 'ongoing']);
            
        \App\Models\PenempatanMagang::where('status', 'ongoing')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', now())
            ->update(['status' => 'completed']);
        // ----------------------------------------------------
    
        $siswaId = session('siswa_id');
        $siswa = Siswa::with(['jurusan', 'penempatanMagangs.industri'])->find($siswaId);
        
        if (!$siswa) {
            return redirect('/login')->with('error', 'Data siswa tidak ditemukan');
        }
        
        $penempatan = $siswa->penempatanMagangs->first();

        $pengumumans = \App\Models\Pengumuman::where('is_active', 1)->latest()->take(5)->get();
        
        $sisa_hari = 0;
        if ($penempatan && in_array($penempatan->status, ['approved', 'ongoing'])) {
            // Ambil dari database, bukan di-hardcode 4 bulan
            if ($penempatan->tanggal_selesai) {
                $tanggal_selesai = \Carbon\Carbon::parse($penempatan->tanggal_selesai);
                if (now()->lessThan($tanggal_selesai)) {
                    $sisa_hari = intval(now()->diffInDays($tanggal_selesai));
                }
            }
        }

        // Hitung target jurnal dinamis (Hanya menghitung hari kerja: Senin - Jumat)
        $target_jurnal = 60; 
        if ($penempatan && $penempatan->tanggal_mulai && $penempatan->tanggal_selesai) {
            $mulai = \Carbon\Carbon::parse($penempatan->tanggal_mulai);
            $selesai = \Carbon\Carbon::parse($penempatan->tanggal_selesai);
            if ($selesai->greaterThan($mulai)) {
                $target_jurnal = max(1, $mulai->diffInWeekdays($selesai));
            }
        }

        $stats = [
            'status_magang' => $penempatan ? $penempatan->status : 'belum_ada',
            'sisa_hari' => $sisa_hari,
            'kehadiran' => $this->hitungKehadiran($siswaId),
            'total_hadir' => Absensi::where('siswa_id', $siswaId)->where('status', 'hadir')->count(),
            'total_izin' => Absensi::where('siswa_id', $siswaId)->where('status', 'izin')->count(),
            'total_sakit' => Absensi::where('siswa_id', $siswaId)->where('status', 'sakit')->count(),
            'total_alpha' => Absensi::where('siswa_id', $siswaId)->where('status', 'alpha')->count(),
            'jurnal_total' => JurnalHarian::where('siswa_id', $siswaId)->count(),
            'jurnal_pending' => JurnalHarian::where('siswa_id', $siswaId)->where('status', 'pending')->count(),
            'laporan_pkl' => LaporanPKL::where('siswa_id', $siswaId)->latest()->first(),
            'notifikasi_unread' => Notifikasi::where('siswa_id', $siswaId)->where('is_read', false)->count(),
            'notifikasis' => Notifikasi::where('siswa_id', $siswaId)->latest()->limit(5)->get(),
            'pengumumans' => Pengumuman::where('is_active', 1)->latest()->limit(3)->get(),
            'target_jurnal' => $target_jurnal,
            'progres_jurnal' => $this->hitungProgresJurnal($siswaId, $target_jurnal),
            'progres_laporan' => $this->hitungProgresLaporan($siswaId),
            'progres_presensi' => $this->hitungKehadiran($siswaId),
            'jurnal_count' => JurnalHarian::where('siswa_id', $siswaId)->count(),
            'aktivitas_terakhir' => $this->getAktivitasTerakhir($siswaId),
        ];
        
        return view('siswa.dashboard', compact('siswa', 'penempatan', 'stats', 'pengumumans'));
    }

    public function updateNoWa(Request $request)
    {
        $request->validate([
            'no_wa' => 'required|string|max:20',
        ]);

        $siswaId = session('siswa_id');
        $siswa = \App\Models\Siswa::find($siswaId);

        if ($siswa) {
            $siswa->update(['no_wa' => $request->no_wa]);
            return redirect()->back()->with('success', 'Nomor WhatsApp berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
    }

    // ==================== HELPER FUNCTIONS ====================
    private function hitungKehadiran($siswaId)
    {
        $totalAbsensi = Absensi::where('siswa_id', $siswaId)->count();
        if ($totalAbsensi == 0) return 0;
        $totalHadir = Absensi::where('siswa_id', $siswaId)->where('status', 'hadir')->count();
        return round(($totalHadir / $totalAbsensi) * 100);
    }

    private function getAktivitasTerakhir($siswaId)
    {
        $aktivitas = [];
        
        $jurnals = \App\Models\JurnalHarian::where('siswa_id', $siswaId)->orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($jurnals as $jurnal) {
            $aktivitas[] = [
                'jenis' => 'jurnal',
                'judul' => 'Jurnal Hari ' . \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y'),
                'deskripsi' => $jurnal->status === 'disetujui' ? 'Disetujui' : ($jurnal->status === 'pending' ? 'Menunggu Approval' : 'Perlu Revisi'),
                'waktu' => $jurnal->created_at,
                'status' => $jurnal->status === 'disetujui' ? 'success' : ($jurnal->status === 'pending' ? 'warning' : 'error'),
            ];
        }
        
        $absensis = \App\Models\Absensi::where('siswa_id', $siswaId)->orderBy('tanggal', 'desc')->limit(2)->get();
        foreach ($absensis as $absensi) {
            $aktivitas[] = [
                'jenis' => 'absensi',
                'judul' => 'Presensi ' . ucfirst($absensi->status),
                'deskripsi' => $absensi->jam_masuk ? 'Jam: ' . \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') . ' WIB' : '',
                'waktu' => $absensi->created_at,
                'status' => $absensi->status === 'hadir' ? 'success' : 'warning',
            ];
        }
        
        $surats = \App\Models\SuratKeluar::whereHas('penempatanMagang', function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            })->orderBy('created_at', 'desc')->limit(2)->get();
        foreach ($surats as $surat) {
            $aktivitas[] = [
                'jenis' => 'surat',
                'judul' => 'Surat ' . ucfirst($surat->jenis_surat),
                'deskripsi' => 'Status: ' . ucfirst($surat->status),
                'waktu' => $surat->created_at,
                'status' => $surat->status === 'sent' ? 'success' : 'warning',
            ];
        }
        
        usort($aktivitas, function($a, $b) { return $b['waktu']->timestamp - $a['waktu']->timestamp; });
        return array_slice($aktivitas, 0, 5);
    }

    private function hitungProgresJurnal($siswaId, $targetJurnal = 60)
    {
        $totalJurnal = \App\Models\JurnalHarian::where('siswa_id', $siswaId)->where('status', 'disetujui')->count();
        if ($targetJurnal == 0 || $totalJurnal == 0) return 0;
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

    // ==================== ADMIN: VERIFIKASI PENGAJUAN ====================
    public function approveMagang($id)
    {
        $penempatan = PenempatanMagang::find($id);
        
        if ($penempatan) {
            $penempatan->update(['status' => 'verified']);
            
            Notifikasi::create([
                'siswa_id' => $penempatan->siswa_id,
                'judul' => 'Pengajuan Diverifikasi Admin',
                'pesan' => 'Pengajuan magang Anda telah diverifikasi oleh Admin dan sedang menunggu persetujuan Kepala Sekolah.',
                'jenis' => 'info',
                'tipe' => 'app',
                'is_read' => false,
            ]);
            
            return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi dan dikirim ke Pimpinan!');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan!');
    }

    public function rejectMagang($id)
    {
        $penempatan = PenempatanMagang::find($id);
        if ($penempatan) {
            $penempatan->update(['status' => 'rejected']);
            
            Notifikasi::create([
                'siswa_id' => $penempatan->siswa_id,
                'judul' => 'Pengajuan Magang Ditolak',
                'pesan' => 'Pengajuan magang Anda di ' . ($penempatan->industri->nama_industri ?? 'industri') . ' ditolak. Silakan hubungi TU untuk informasi lebih lanjut.',
                'jenis' => 'error',
                'tipe' => 'app',
                'is_read' => false,
            ]);
            
            return redirect()->back()->with('success', 'Pengajuan ditolak!');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan!');
    }

    // ==================== SISWA: CEK STATUS & PENGAJUAN ====================
    public function cekStatusSiswa()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::with('jurusan')->find($siswaId);
        
        if (!$siswa) {
            return redirect('/login')->with('error', 'Data siswa tidak ditemukan');
        }
        
        $penempatan = PenempatanMagang::with(['industri', 'siswa.jurusan'])
            ->where('siswa_id', $siswaId)
            ->latest()
            ->first();
        
        // Logika Kalender agar pengecekan kuota akurat
        $now = now();
        $month = $now->month;
        $year = $now->year;
        if ($month >= 7 && $month <= 12) {
            $semester = 'Ganjil';
            $tahunAjar = $year . '/' . ($year + 1);
        } else {
            $semester = 'Genap';
            $tahunAjar = ($year - 1) . '/' . $year;
        }

        // Ambil industri dan hitung sisa kuotanya untuk ditampilkan di Dropdown
        $industris = Industri::where('is_active', 1)
            ->orderBy('nama_industri')
            ->get()
            ->map(function($ind) use ($tahunAjar, $semester) {
                $terisi = PenempatanMagang::where('industri_id', $ind->id)
                    ->where('tahun_ajaran', $tahunAjar)
                    ->where('semester', $semester)
                    ->where('status', '!=', 'rejected')
                    ->count();
                
                $ind->sisa_kuota = max(0, $ind->kapasitas_magang - $terisi);
                return $ind;
            });
        
        return view('siswa.cek-status', compact('siswa', 'penempatan', 'industris'));
    }

    public function submitPengajuanMitra(Request $request)
    {
        $validated = $request->validate([
            'industri_id' => 'required|exists:industris,id',
            'posisi_magang' => 'required|string|max:255',
            'alasan' => 'nullable|string',
        ]);
        
        $existing = PenempatanMagang::where('siswa_id', session('siswa_id'))
            ->whereIn('status', ['pending', 'verified', 'approved', 'ongoing'])
            ->first();
            
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan yang masih aktif.');
        }

        $industri = Industri::findOrFail($validated['industri_id']);

        $now = now();
        $month = $now->month;
        $year = $now->year;
        if ($month >= 7 && $month <= 12) {
            $semester = 'Ganjil';
            $tahunAjar = $year . '/' . ($year + 1);
        } else {
            $semester = 'Genap';
            $tahunAjar = ($year - 1) . '/' . $year;
        }

        // ================= KUNCI GANDA: BACKEND VALIDATION =================
        // Hitung yang sudah mendaftar di kalender ini (kecuali yang ditolak)
        $terisi = PenempatanMagang::where('industri_id', $industri->id)
            ->where('tahun_ajaran', $tahunAjar)
            ->where('semester', $semester)
            ->where('status', '!=', 'rejected')
            ->count();

        // Jika terisi >= kapasitas, tendang keluar dan beri peringatan error!
        if ($terisi >= $industri->kapasitas_magang) {
            return redirect()->back()->with('error', 'GAGAL: Kuota magang di ' . $industri->nama_industri . ' sudah penuh. Silakan pilih mitra lain.');
        }
        // ===================================================================
        
        PenempatanMagang::create([
            'siswa_id' => session('siswa_id'),
            'industri_id' => $industri->id,
            'posisi_magang' => $validated['posisi_magang'],
            'catatan_industri' => $validated['alasan'], 
            'status' => 'pending',
            'tahun_ajaran' => $tahunAjar,
            'semester' => $semester,
        ]);
        
        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi TU.');
    }

    public function submitPengajuanMandiri(Request $request)
    {
        $validated = $request->validate([
            'nama_industri' => 'required|string|max:255',
            'nib' => 'nullable|string|max:50',
            'alamat' => 'required|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'required|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'nama_hr' => 'nullable|string|max:255',
            'no_wa_hr' => 'nullable|string',
            'posisi_magang' => 'required|string|max:255',
            'alasan' => 'nullable|string',
        ]);
        
        $existing = PenempatanMagang::where('siswa_id', session('siswa_id'))
            ->whereIn('status', ['pending', 'verified', 'approved', 'ongoing'])
            ->first();
            
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan yang masih aktif.');
        }
        
        $industri = Industri::create([
            'nama_industri' => $validated['nama_industri'],
            'nib' => $validated['nib'],
            'alamat' => $validated['alamat'],
            'kelurahan' => $validated['kelurahan'],
            'kecamatan' => $validated['kecamatan'],
            'kota' => $validated['kota'],
            'provinsi' => $validated['provinsi'],
            'kode_pos' => $validated['kode_pos'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'kategori' => $validated['kategori'],
            'nama_hr' => $validated['nama_hr'],
            'no_wa_hr' => $validated['no_wa_hr'],
            'is_active' => 1, 
        ]);

        $now = now();
        $month = $now->month;
        $year = $now->year;
        if ($month >= 7 && $month <= 12) {
            $semester = 'Ganjil';
            $tahunAjar = $year . '/' . ($year + 1);
        } else {
            $semester = 'Genap';
            $tahunAjar = ($year - 1) . '/' . $year;
        }
        
        PenempatanMagang::create([
            'siswa_id' => session('siswa_id'),
            'industri_id' => $industri->id,
            'posisi_magang' => $validated['posisi_magang'],
            'catatan_industri' => $validated['alasan'],
            'status' => 'pending',
            'tahun_ajaran' => $tahunAjar,
            'semester' => $semester,
        ]);
        
        return redirect()->back()->with('success', 'Pengajuan perusahaan mandiri berhasil dikirim! Menunggu verifikasi TU.');
    }

    public function ajukanUlang()
    {
        $siswaId = session('siswa_id');
        
        $penempatan = PenempatanMagang::where('siswa_id', $siswaId)
            ->where('status', 'rejected')
            ->latest()
            ->first();
        
        if ($penempatan) {
            $penempatan->delete();
            return redirect()->back()->with('success', 'Data pengajuan lama dihapus. Silakan ajukan kembali.');
        }
        
        return redirect()->back()->with('error', 'Tidak ditemukan data pengajuan yang ditolak.');
    }

    // ==================== DETAIL INDUSTRI SISWA ====================
    public function detailIndustriSiswa($id)
    {
        $industri = Industri::findOrFail($id);
        
        $now = now();
        $month = $now->month;
        $year = $now->year;
        if ($month >= 7 && $month <= 12) { $sem = 'Ganjil'; $ta = $year . '/' . ($year + 1); }
        else { $sem = 'Genap'; $ta = ($year - 1) . '/' . $year; }

        $terisi = \App\Models\PenempatanMagang::where('industri_id', $id)
            ->where('tahun_ajaran', $ta)
            ->where('semester', $sem)
            ->where('status', '!=', 'rejected')
            ->count();

        $sisaKuota = max(0, $industri->kapasitas_magang - $terisi);

        // TAMBAHAN BARU: Cek pengajuan siswa saat ini
        $siswaId = session('siswa_id');
        $penempatanAktif = \App\Models\PenempatanMagang::where('siswa_id', $siswaId)
            ->whereIn('status', ['pending', 'verified', 'approved', 'ongoing'])
            ->first();

        // Jangan lupa tambahkan $penempatanAktif di compact
        return view('siswa.industri-detail', compact('industri', 'sisaKuota', 'terisi', 'penempatanAktif'));
    }

    // ==================== VERIFIKASI JURNAL (ADMIN) ====================
    public function listJurnalSiswa(Request $request)
    {
        $query = \App\Models\JurnalHarian::with(['siswa', 'penempatanMagang.industri'])->latest();

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search') && !$request->filled('siswa_id')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $jurnals = $query->paginate(20)->withQueryString();

        $filteredSiswa = null;
        if ($request->filled('siswa_id')) {
            $filteredSiswa = \App\Models\Siswa::find($request->siswa_id);
        }

        return view('admin.verifikasi.jurnal', compact('jurnals', 'filteredSiswa'));
    }

    public function verifikasiJurnal(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_pembimbing' => 'nullable|string'
        ]);

        $jurnal = \App\Models\JurnalHarian::findOrFail($id);
        $jurnal->update([
            'status' => $request->status,
            'catatan_pembimbing' => $request->catatan_pembimbing,
            'disetujui_oleh' => auth()->id()
        ]);

        \App\Models\Notifikasi::create([
            'siswa_id' => $jurnal->siswa_id,
            'judul' => $request->status == 'disetujui' ? 'Jurnal Disetujui' : 'Jurnal Perlu Revisi',
            'pesan' => "Jurnal tanggal {$jurnal->tanggal->format('d/m/Y')} telah diperiksa. Catatan: " . ($request->catatan_pembimbing ?? '-'),
            'jenis' => $request->status == 'disetujui' ? 'info' : 'error',
            'tipe' => 'umum',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Status jurnal berhasil diperbarui.');
    }

    // ==================== VERIFIKASI LAPORAN PKL (ADMIN) ====================
    public function listLaporanPKLSiswa(Request $request)
    {
        $query = \App\Models\LaporanPKL::with(['siswa', 'penempatanMagang.industri'])->latest();

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search') && !$request->filled('siswa_id')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $laporans = $query->paginate(15)->withQueryString();

        $filteredSiswa = null;
        if ($request->filled('siswa_id')) {
            $filteredSiswa = \App\Models\Siswa::find($request->siswa_id);
        }

        return view('admin.verifikasi.laporan-pkl', compact('laporans', 'filteredSiswa'));
    }

    public function verifikasiLaporanPKL(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,perlu_revisi',
            'catatan_pembimbing' => 'nullable|string'
        ]);

        $laporan = \App\Models\LaporanPKL::findOrFail($id);
        $laporan->update([
            'status' => $request->status,
            'catatan_pembimbing' => $request->catatan_pembimbing
        ]);

        return redirect()->back()->with('success', 'Status laporan PKL berhasil diperbarui.');
    }

    // ==================== IMPORT NILAI & SERTIFIKAT BATCH ====================
    public function downloadTemplateExcel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A1' => 'nisn', 'B1' => 'nilai_sikap', 'C1' => 'nilai_keterampilan',
            'D1' => 'nilai_pengetahuan', 'E1' => 'catatan',
        ];

        foreach ($headers as $cell => $label) {
            $sheet->setCellValue($cell, $label);
        }
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        $sheet->setCellValueExplicit('A2', '0051234567', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B2', 85);
        $sheet->setCellValue('C2', 80);
        $sheet->setCellValue('D2', 90);
        $sheet->setCellValue('E2', 'Baik');

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_import_nilai.xlsx';
        $tempPath = storage_path('app/temp/');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $writer->save($tempPath . $filename);

        return response()->download($tempPath . $filename)->deleteFileAfterSend();
    }

    public function checkNisn(Request $request)
    {
        $nisnList = $request->input('nisn', []);
        if (empty($nisnList) || !is_array($nisnList)) return response()->json([]);

        $siswas = \App\Models\Siswa::whereIn('nisn', $nisnList)->get(['nisn', 'nama']);
        $registeredNisn = $siswas->pluck('nama', 'nisn')->toArray();

        $result = [];
        foreach ($nisnList as $nisn) {
            $result[$nisn] = [
                'registered' => isset($registeredNisn[$nisn]),
                'nama' => $registeredNisn[$nisn] ?? null,
            ];
        }
        return response()->json($result);
    }

    public function generateSertifikatBatch()
    {
        $pengajuans = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai'])
            ->whereIn('status', ['approved', 'completed'])
            ->whereHas('nilai')
            ->get();

        if ($pengajuans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data siswa yang siap dicetak sertifikatnya.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat-batch', compact('pengajuans'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Batch_Sertifikat_Magang_' . date('YmdHis') . '.pdf');
    }

    public function kirimSertifikat($id)
    {
        $penempatan = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai'])->findOrFail($id);
        if (!$penempatan->nilai) {
            return redirect()->back()->with('error', 'Siswa belum memiliki nilai.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat-magang', compact('penempatan'));
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'Sertifikat_' . str_replace(' ', '_', $penempatan->siswa->nama) . '_' . date('YmdHis') . '.pdf';
        if (!file_exists(storage_path('app/public/sertifikat'))) {
            mkdir(storage_path('app/public/sertifikat'), 0777, true);
        }
        $pdf->save(storage_path('app/public/sertifikat/' . $filename));
        
        \App\Models\Sertifikat::updateOrCreate(
            ['penempatan_magang_id' => $penempatan->id],
            [
                'nilai_id' => $penempatan->nilai->id,
                'nomor_sertifikat' => 'SERT/' . date('Y') . '/' . str_pad($penempatan->id, 4, '0', STR_PAD_LEFT),
                'file_path' => 'sertifikat/' . $filename,
                'tanggal_terbit' => now(),
                'status' => 'issued',
                'generated_by' => auth()->id()
            ]
        );

        \App\Models\Notifikasi::create([
            'siswa_id' => $penempatan->siswa_id,
            'judul' => 'Sertifikat Lulus PKL!',
            'pesan' => "Selamat! Sertifikat kompetensi magang Anda telah diterbitkan. Silakan unduh di menu Download Sertifikat.",
            'jenis' => 'success',
            'tipe' => 'umum',
            'is_read' => false,
        ]);
        
        return redirect()->back()->with('success', 'Sertifikat berhasil dibuat dan dikirimkan ke Dashboard Siswa!');
    }

    public function downloadBatchZip(Request $request)
    {
        $ids = explode(',', $request->input('selected_ids'));
        if (empty($ids) || $ids[0] == '') {
            return redirect()->back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        $pengajuans = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai'])
            ->whereIn('id', $ids)
            ->get();

        if ($pengajuans->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'Batch_Sertifikat_' . date('YmdHis') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($pengajuans as $penempatan) {
                if (!$penempatan->nilai) continue;
                $pdf = Pdf::loadView('pdf.sertifikat-magang', compact('penempatan'));
                $pdf->setPaper('A4', 'landscape');
                $pdfContent = $pdf->output();
                $pdfName = 'Sertifikat_' . str_replace(' ', '_', $penempatan->siswa->nama) . '.pdf';
                $zip->addFromString($pdfName, $pdfContent);
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // ==================== WA BLAST & EXPORT ====================
    public function exportLaporan(Request $request)
    {
        $jenis = $request->input('jenis');
        $query = \App\Models\PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai'])
                    ->whereIn('status', ['approved', 'ongoing', 'completed']);

        if ($jenis == 'industri') {
            $industriId = $request->input('industri_id');
            if ($industriId != 'all') {
                $query->where('industri_id', $industriId);
                $namaIndustri = \App\Models\Industri::find($industriId)->nama_industri ?? 'Unknown';
                $judul = 'Laporan Rekapitulasi - ' . $namaIndustri;
            } else {
                $judul = 'Laporan Rekapitulasi - Semua Industri';
            }
        } else {
            $jurusanId = $request->input('jurusan_id');
            if ($jurusanId != 'all') {
                $query->whereHas('siswa', function($q) use ($jurusanId) {
                    $q->where('jurusan_id', $jurusanId);
                });
                $namaJurusan = \App\Models\Jurusan::find($jurusanId)->nama_jurusan ?? 'Unknown';
                $judul = 'Laporan Rekapitulasi - Jurusan ' . $namaJurusan;
            } else {
                $judul = 'Laporan Rekapitulasi - Semua Jurusan';
            }
        }

        $penempatans = $query->latest()->get();

        foreach ($penempatans as $p) {
            $siswaId = $p->siswa_id;
            $totalAbsen = \App\Models\Absensi::where('siswa_id', $siswaId)->count();
            $hadir = \App\Models\Absensi::where('siswa_id', $siswaId)->where('status', 'hadir')->count();
            $p->nilai_absen = $totalAbsen > 0 ? round(($hadir / $totalAbsen) * 100) : 0;

            $jurnalDisetujui = \App\Models\JurnalHarian::where('siswa_id', $siswaId)->where('status', 'disetujui')->count();
            $p->nilai_jurnal = min(100, round(($jurnalDisetujui / 60) * 100));

            $laporan = \App\Models\LaporanPKL::where('siswa_id', $siswaId)->where('status', 'disetujui')->first();
            $p->nilai_laporan_pkl = $laporan ? 100 : 0;
            $p->nilai_perusahaan = $p->nilai->nilai_akhir ?? 0;
            $p->skor_akhir = round(($p->nilai_absen + $p->nilai_jurnal + $p->nilai_laporan_pkl + $p->nilai_perusahaan) / 4);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.rekap-laporan', compact('penempatans', 'judul'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Rekap_Magang_' . date('Ymd_His') . '.pdf');
    }
}