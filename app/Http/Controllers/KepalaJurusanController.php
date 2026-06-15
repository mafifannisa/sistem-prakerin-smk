<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\PenempatanMagang;
use App\Models\Absensi;
use App\Models\JurnalHarian;
use App\Models\LaporanPKL;
use App\Models\LaporanMasalahMagang;
use App\Models\Nilai;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KepalaJurusanController extends Controller
{
    private function getJurusanId()
    {
        $guru = auth()->user()->guru;
        return $guru ? $guru->jurusan_id : null;
    }

    public function dashboard()
    {
        $jurusanId = $this->getJurusanId();
        if (!$jurusanId) {
            return redirect()->route('login')->withErrors(['identity' => 'Anda tidak memiliki profil guru yang valid.']);
        }

        $totalSiswa = Siswa::where('jurusan_id', $jurusanId)->count();
        $totalMagang = PenempatanMagang::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->count();
        $totalMasalah = LaporanMasalahMagang::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->where('status', '!=', 'selesai')->count();
        
        $aktivitasTerbaru = PenempatanMagang::with(['siswa', 'industri'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('kepala_jurusan.dashboard', compact('totalSiswa', 'totalMagang', 'totalMasalah', 'aktivitasTerbaru'));
    }

    public function dataSiswa(Request $request)
    {
        $jurusanId = $this->getJurusanId();
        $query = Siswa::with(['kelas', 'jurusan'])->where('jurusan_id', $jurusanId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%");
            });
        }

        $siswas = $query->paginate(10);
        return view('kepala_jurusan.data-siswa', compact('siswas'));
    }

    public function dataMagang(Request $request)
    {
        $jurusanId = $this->getJurusanId();
        
        $placements = PenempatanMagang::with(['siswa.kelas', 'industri', 'guruPembimbing'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->latest()->paginate(10);

        // Siswa yang belum magang (tidak punya penempatan atau ditolak)
        $siswaBelumPlacement = Siswa::with('kelas')->where('jurusan_id', $jurusanId)
            ->whereDoesntHave('penempatanMagangs', function($q) {
                $q->where('status', '!=', 'rejected');
            })->get();

        // Periode magang
        $periodes = \App\Models\PeriodeMagang::latest()->get();
        
        // Determine active/latest year and semester to compute current remaining slots
        $latestPeriode = $periodes->first();
        if ($latestPeriode) {
            $currentSemester = (date('n', strtotime($latestPeriode->tanggal_mulai)) >= 7) ? 'Ganjil' : 'Genap';
            $currentTahunAjar = $latestPeriode->tahun_ajaran;
        } else {
            $now = now();
            $month = $now->month;
            $year = $now->year;
            if ($month >= 7 && $month <= 12) {
                $currentSemester = 'Ganjil';
                $currentTahunAjar = $year . '/' . ($year + 1);
            } else {
                $currentSemester = 'Genap';
                $currentTahunAjar = ($year - 1) . '/' . $year;
            }
        }

        $industris = Industri::where('is_active', true)->get()->map(function($ind) use ($currentTahunAjar, $currentSemester) {
            $used = PenempatanMagang::where('industri_id', $ind->id)
                ->where('tahun_ajaran', $currentTahunAjar)
                ->where('semester', $currentSemester)
                ->where('status', '!=', 'rejected')
                ->count();
            $ind->sisa_kapasitas = max(0, $ind->kapasitas_magang - $used);
            $ind->used_count = $used;
            return $ind;
        });
        
        // Guru Pembimbing (user accounts with role guru_pembimbing) belonging to this department
        $gurus = User::where('role', 'guru_pembimbing')
            ->whereHas('guru', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->get();

        return view('kepala_jurusan.data-magang', compact('placements', 'siswaBelumPlacement', 'industris', 'gurus', 'periodes'));
    }

    public function storeMagang(Request $request)
    {
        $jurusanId = $this->getJurusanId();

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_pembimbing_id' => 'required|exists:users,id',
            'periode_magang_id' => 'required|exists:periode_magangs,id',
            'posisi_magang' => 'nullable|string|max:255',
        ]);

        // Validate that student exists and belongs to Kajur's department
        $siswaExists = Siswa::where('id', $request->siswa_id)
            ->where('jurusan_id', $jurusanId)
            ->exists();
            
        // Validate that teacher exists and belongs to Kajur's department
        $guruExists = User::where('id', $request->guru_pembimbing_id)
            ->where('role', 'guru_pembimbing')
            ->whereHas('guru', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->exists();

        if (!$siswaExists || !$guruExists) {
            return redirect()->back()->withErrors(['error' => 'Siswa atau Guru Pembimbing tidak valid untuk jurusan Anda.']);
        }

        $periode = \App\Models\PeriodeMagang::findOrFail($request->periode_magang_id);
        $semester = (date('n', strtotime($periode->tanggal_mulai)) >= 7) ? 'Ganjil' : 'Genap';
        $tahunAjar = $periode->tahun_ajaran;

        // Hitung kuota yang sudah terisi di industri ini untuk tahun ajaran & semester bersangkutan
        $industri = Industri::findOrFail($request->industri_id);
        $terisi = PenempatanMagang::where('industri_id', $industri->id)
            ->where('tahun_ajaran', $tahunAjar)
            ->where('semester', $semester)
            ->where('status', '!=', 'rejected')
            ->count();

        if ($terisi >= $industri->kapasitas_magang) {
            return redirect()->back()->withErrors(['error' => 'Kapasitas magang pada industri ' . $industri->nama_industri . ' sudah penuh untuk periode ini.']);
        }

        $startDate = \Carbon\Carbon::parse($periode->tanggal_mulai)->startOfDay();
        $status = now()->startOfDay()->gte($startDate) ? 'ongoing' : 'approved';

        PenempatanMagang::create([
            'siswa_id' => $request->siswa_id,
            'industri_id' => $request->industri_id,
            'guru_pembimbing_id' => $request->guru_pembimbing_id,
            'periode_magang_id' => $periode->id,
            'posisi_magang' => $request->posisi_magang ?? '-',
            'tanggal_mulai' => $periode->tanggal_mulai,
            'tanggal_selesai' => $periode->tanggal_selesai,
            'tahun_ajaran' => $periode->tahun_ajaran,
            'semester' => (date('n', strtotime($periode->tanggal_mulai)) >= 7) ? 'Ganjil' : 'Genap',
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Penempatan magang berhasil dibuat.');
    }

    public function updateMagang(Request $request, $id)
    {
        $jurusanId = $this->getJurusanId();

        $placement = PenempatanMagang::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->findOrFail($id);

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_pembimbing_id' => 'required|exists:users,id',
            'periode_magang_id' => 'required|exists:periode_magangs,id',
            'posisi_magang' => 'nullable|string|max:255',
        ]);

        // Validate that student exists and belongs to Kajur's department
        $siswaExists = Siswa::where('id', $request->siswa_id)
            ->where('jurusan_id', $jurusanId)
            ->exists();
            
        // Validate that teacher exists and belongs to Kajur's department
        $guruExists = User::where('id', $request->guru_pembimbing_id)
            ->where('role', 'guru_pembimbing')
            ->whereHas('guru', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->exists();

        if (!$siswaExists || !$guruExists) {
            return redirect()->back()->withErrors(['error' => 'Siswa atau Guru Pembimbing tidak valid untuk jurusan Anda.']);
        }

        $periode = \App\Models\PeriodeMagang::findOrFail($request->periode_magang_id);
        $semester = (date('n', strtotime($periode->tanggal_mulai)) >= 7) ? 'Ganjil' : 'Genap';
        $tahunAjar = $periode->tahun_ajaran;

        // Hitung kuota yang sudah terisi di industri ini untuk tahun ajaran & semester bersangkutan (kecuali penempatan ini)
        if ($placement->industri_id != $request->industri_id || $placement->tahun_ajaran != $tahunAjar || $placement->semester != $semester) {
            $industri = Industri::findOrFail($request->industri_id);
            $terisi = PenempatanMagang::where('industri_id', $industri->id)
                ->where('tahun_ajaran', $tahunAjar)
                ->where('semester', $semester)
                ->where('status', '!=', 'rejected')
                ->where('id', '!=', $placement->id)
                ->count();

            if ($terisi >= $industri->kapasitas_magang) {
                return redirect()->back()->withErrors(['error' => 'Kapasitas magang pada industri ' . $industri->nama_industri . ' sudah penuh untuk periode ini.']);
            }
        }

        $startDate = \Carbon\Carbon::parse($periode->tanggal_mulai)->startOfDay();
        $status = now()->startOfDay()->gte($startDate) ? 'ongoing' : 'approved';

        $placement->update([
            'siswa_id' => $request->siswa_id,
            'industri_id' => $request->industri_id,
            'guru_pembimbing_id' => $request->guru_pembimbing_id,
            'periode_magang_id' => $periode->id,
            'posisi_magang' => $request->posisi_magang ?? '-',
            'tanggal_mulai' => $periode->tanggal_mulai,
            'tanggal_selesai' => $periode->tanggal_selesai,
            'tahun_ajaran' => $periode->tahun_ajaran,
            'semester' => (date('n', strtotime($periode->tanggal_mulai)) >= 7) ? 'Ganjil' : 'Genap',
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Penempatan magang berhasil diperbarui.');
    }

    public function storePeriode(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        \App\Models\PeriodeMagang::create([
            'nama' => $request->nama,
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Periode magang berhasil ditambahkan.');
    }

    public function updatePeriode(Request $request, $id)
    {
        $periode = \App\Models\PeriodeMagang::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $periode->update([
            'nama' => $request->nama,
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        // Sync penempatan_magangs under this period
        $computedSemester = (date('n', strtotime($periode->tanggal_mulai)) >= 7) ? 'Ganjil' : 'Genap';
        PenempatanMagang::where('periode_magang_id', $periode->id)->update([
            'tahun_ajaran' => $periode->tahun_ajaran,
            'semester' => $computedSemester,
            'tanggal_mulai' => $periode->tanggal_mulai,
            'tanggal_selesai' => $periode->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Periode magang berhasil diperbarui.');
    }

    public function destroyPeriode($id)
    {
        $periode = \App\Models\PeriodeMagang::findOrFail($id);
        $periode->delete();

        return redirect()->back()->with('success', 'Periode magang berhasil dihapus.');
    }

    public function destroyMagang($id)
    {
        $jurusanId = $this->getJurusanId();
        $placement = PenempatanMagang::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->findOrFail($id);

        $placement->delete();
        return redirect()->back()->with('success', 'Penempatan magang berhasil dihapus.');
    }

    public function rekapAbsen(Request $request)
    {
        $jurusanId = $this->getJurusanId();
        
        $query = Siswa::with('kelas')->where('jurusan_id', $jurusanId)
            ->whereHas('penempatanMagangs', function($q) {
                $q->whereIn('status', ['ongoing', 'completed']);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%");
        }

        $siswas = $query->paginate(10);

        foreach ($siswas as $siswa) {
            $siswa->hadir = Absensi::where('siswa_id', $siswa->id)->where('status', 'hadir')->count();
            $siswa->sakit = Absensi::where('siswa_id', $siswa->id)->where('status', 'sakit')->count();
            $siswa->izin = Absensi::where('siswa_id', $siswa->id)->where('status', 'izin')->count();
            $siswa->alpha = Absensi::where('siswa_id', $siswa->id)->where('status', 'alpha')->count();
        }

        return view('kepala_jurusan.rekap-absen', compact('siswas'));
    }

    public function rekapJurnal(Request $request)
    {
        $jurusanId = $this->getJurusanId();
        
        $query = JurnalHarian::with(['siswa', 'disetujuiOleh'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        $jurnals = $query->latest()->paginate(10);
        return view('kepala_jurusan.rekap-jurnal', compact('jurnals'));
    }

    public function destroyJurnal($id)
    {
        $jurusanId = $this->getJurusanId();
        $jurnal = JurnalHarian::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->findOrFail($id);

        if ($jurnal->bukti_foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($jurnal->bukti_foto)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($jurnal->bukti_foto);
        }

        $jurnal->delete();

        return redirect()->back()->with('success', 'Jurnal harian siswa berhasil dihapus.');
    }

    public function rekapLaporan(Request $request)
    {
        $jurusanId = $this->getJurusanId();
        
        $query = LaporanPKL::with(['siswa', 'disetujuiOleh'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        $laporans = $query->latest()->paginate(10);
        return view('kepala_jurusan.rekap-laporan', compact('laporans'));
    }

    public function importNilai(Request $request)
    {
        $jurusanId = $this->getJurusanId();

        $query = PenempatanMagang::with(['siswa', 'industri', 'nilai'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->whereIn('status', ['ongoing', 'completed']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        $placements = $query->paginate(10);
        return view('kepala_jurusan.import-nilai', compact('placements'));
    }

    public function storeNilai(Request $request, $placement_id)
    {
        $jurusanId = $this->getJurusanId();
        $placementExists = PenempatanMagang::where('id', $placement_id)
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->exists();
        if (!$placementExists) {
            abort(403, 'Penempatan magang tidak valid untuk jurusan Anda.');
        }

        $request->validate([
            'nilai_sikap' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan' => 'required|numeric|min:0|max:100',
            'nilai_pengetahuan' => 'required|numeric|min:0|max:100',
        ]);

        $nilai = Nilai::where('penempatan_magang_id', $placement_id)->first();
        
        $sikap = floatval($request->nilai_sikap);
        $keterampilan = floatval($request->nilai_keterampilan);
        $pengetahuan = floatval($request->nilai_pengetahuan);

        if ($nilai && $nilai->nilai_1 !== null) {
            $n1 = floatval($nilai->nilai_1);
            $n2 = floatval($nilai->nilai_2);
            $n3 = floatval($nilai->nilai_3);
            $akhir = ($sikap + $keterampilan + $pengetahuan + $n1 + $n2 + $n3) / 6;
        } else {
            $akhir = ($sikap + $keterampilan + $pengetahuan) / 3;
        }

        $predikat = 'E';
        if ($akhir >= 86) $predikat = 'A';
        elseif ($akhir >= 70) $predikat = 'B';
        elseif ($akhir >= 56) $predikat = 'C';
        elseif ($akhir >= 40) $predikat = 'D';

        Nilai::updateOrCreate(
            ['penempatan_magang_id' => $placement_id],
            [
                'nilai_sikap' => $sikap,
                'nilai_keterampilan' => $keterampilan,
                'nilai_pengetahuan' => $pengetahuan,
                'nilai_akhir' => $akhir,
                'predikat' => $predikat,
                'tanggal_input' => now(),
                'input_by' => auth()->id(),
            ]
        );

        return redirect()->back()->with('success', 'Nilai magang berhasil disimpan.');
    }

    public function laporanMasalah(Request $request)
    {
        $jurusanId = $this->getJurusanId();

        $query = LaporanMasalahMagang::with(['siswa', 'industri', 'pelapor'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            });

        if ($request->filled('status') && $request->status != 'all') {
            $query->status($request->status);
        }

        $laporans = $query->latest()->paginate(10);
        return view('kepala_jurusan.laporan-masalah', compact('laporans'));
    }

    public function resolveMasalah(Request $request, $id)
    {
        $jurusanId = $this->getJurusanId();

        $request->validate([
            'solusi' => 'required|string',
            'status' => 'required|in:ditinjau,selesai',
            'catatan_kajur' => 'nullable|string',
        ]);

        $laporan = LaporanMasalahMagang::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->findOrFail($id);
        $laporan->update([
            'solusi' => $request->solusi,
            'status' => $request->status,
            'catatan_kajur' => $request->catatan_kajur,
            'ditinjau_oleh' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Laporan masalah berhasil diperbarui dengan solusi/feedback.');
    }

    public function ujianMagang(Request $request)
    {
        $jurusanId = $this->getJurusanId();

        $query = PenempatanMagang::with(['siswa', 'industri', 'nilai', 'guruPenguji'])
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->whereIn('status', ['ongoing', 'completed']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        $placements = $query->paginate(10);

        // Fetch all teachers who have 'guru_penguji' role
        $gurusPenguji = \App\Models\User::where('role', 'guru_penguji')->get();

        return view('kepala_jurusan.ujian-magang', compact('placements', 'gurusPenguji'));
    }

    public function storeUjian(Request $request, $placement_id)
    {
        $jurusanId = $this->getJurusanId();
        $placementExists = PenempatanMagang::where('id', $placement_id)
            ->whereHas('siswa', function($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            })->exists();
        if (!$placementExists) {
            abort(403, 'Penempatan magang tidak valid untuk jurusan Anda.');
        }

        $request->validate([
            'catatan_penguji' => 'required|string',
        ]);

        $nilai = Nilai::where('penempatan_magang_id', $placement_id)->first();
        if (!$nilai) {
            return redirect()->back()->with('error', 'Nilai kompetensi magang harus diisi terlebih dahulu sebelum menjadwalkan/menyelesaikan ujian.');
        }

        $nilai->update([
            'catatan_penguji' => $request->catatan_penguji,
        ]);

        return redirect()->back()->with('success', 'Catatan ujian magang berhasil diperbarui.');
    }

    public function assignPenguji(Request $request, $placement_id)
    {
        $jurusanId = $this->getJurusanId();
        $placement = PenempatanMagang::whereHas('siswa', function($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->findOrFail($placement_id);

        $request->validate([
            'guru_penguji_id' => 'required|exists:users,id',
        ]);

        // Validate that examiner has 'guru_penguji' role
        $pengujiExists = \App\Models\User::where('id', $request->guru_penguji_id)
            ->where('role', 'guru_penguji')
            ->exists();

        if (!$pengujiExists) {
            return redirect()->back()->withErrors(['error' => 'Guru Penguji tidak valid.']);
        }

        $placement->update([
            'guru_penguji_id' => $request->guru_penguji_id,
        ]);

        return redirect()->back()->with('success', 'Guru penguji berhasil ditugaskan untuk siswa ini.');
    }
}
