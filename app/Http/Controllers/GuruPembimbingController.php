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
use Illuminate\Http\Request;

class GuruPembimbingController extends Controller
{
    private function getBimbinganPlacements()
    {
        $guru = auth()->user()->guru;
        $jurusanId = $guru ? $guru->jurusan_id : null;

        return PenempatanMagang::with(['siswa.kelas', 'industri'])
            ->where('guru_pembimbing_id', auth()->id())
            ->when($jurusanId, function($q) use ($jurusanId) {
                $q->whereHas('siswa', function($sq) use ($jurusanId) {
                    $sq->where('jurusan_id', $jurusanId);
                });
            })
            ->whereIn('status', ['ongoing', 'completed', 'approved'])
            ->get();
    }

    public function dashboard()
    {
        $placements = $this->getBimbinganPlacements();
        $totalSiswa = $placements->count();
        $bimbingans = $placements;

        $siswaIds = $placements->pluck('siswa_id');

        $unverifiedJournal = JurnalHarian::whereIn('siswa_id', $siswaIds)
            ->where(function($q) {
                $q->whereNull('status')->orWhere('status', 'pending');
            })
            ->count();

        $unverifiedLaporan = LaporanPKL::whereIn('siswa_id', $siswaIds)
            ->where(function($q) {
                $q->whereNull('status')->orWhere('status', 'pending');
            })
            ->count();

        return view('guru_pembimbing.dashboard', compact('totalSiswa', 'unverifiedJournal', 'unverifiedLaporan', 'bimbingans'));
    }

    public function dataSiswa(Request $request)
    {
        $guru = auth()->user()->guru;
        $jurusanId = $guru ? $guru->jurusan_id : null;

        $query = PenempatanMagang::with(['siswa.jurusan', 'siswa.kelas', 'industri'])
            ->where('guru_pembimbing_id', auth()->id())
            ->when($jurusanId, function($q) use ($jurusanId) {
                $q->whereHas('siswa', function($sq) use ($jurusanId) {
                    $sq->where('jurusan_id', $jurusanId);
                });
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%");
            });
        }

        $placements = $query->paginate(10);
        return view('guru_pembimbing.data-siswa', compact('placements'));
    }

    public function rekapAbsen(Request $request)
    {
        $placements = $this->getBimbinganPlacements();
        $siswaIds = $placements->pluck('siswa_id');

        $query = Siswa::with('kelas')->whereIn('id', $siswaIds);

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

        return view('guru_pembimbing.rekap-absen', compact('siswas'));
    }

    public function rekapJurnal(Request $request)
    {
        $placements = $this->getBimbinganPlacements();
        $siswaIds = $placements->pluck('siswa_id');

        $query = JurnalHarian::with(['siswa'])
            ->whereIn('siswa_id', $siswaIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        $jurnals = $query->latest()->paginate(10);
        return view('guru_pembimbing.rekap-jurnal', compact('jurnals'));
    }

    public function verifyJurnal($id, Request $request)
    {
        $placements = $this->getBimbinganPlacements();
        $siswaIds = $placements->pluck('siswa_id');

        $jurnal = JurnalHarian::whereIn('siswa_id', $siswaIds)->findOrFail($id);
        $status = $request->input('status', 'disetujui');
        
        $jurnal->update([
            'status' => $status,
            'catatan_pembimbing' => $request->catatan_pembimbing,
            'disetujui_oleh' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Status jurnal harian berhasil diperbarui.');
    }

    public function rekapLaporan(Request $request)
    {
        $placements = $this->getBimbinganPlacements();
        $siswaIds = $placements->pluck('siswa_id');

        $query = LaporanPKL::with(['siswa'])
            ->whereIn('siswa_id', $siswaIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        $laporans = $query->latest()->paginate(10);
        return view('guru_pembimbing.rekap-laporan', compact('laporans'));
    }

    public function verifyLaporan($id, Request $request)
    {
        $placements = $this->getBimbinganPlacements();
        $siswaIds = $placements->pluck('siswa_id');

        $laporan = LaporanPKL::whereIn('siswa_id', $siswaIds)->findOrFail($id);
        $status = $request->input('status', 'disetujui');
        
        $laporan->update([
            'status' => $status,
            'catatan_pembimbing' => $request->catatan_pembimbing,
            'disetujui_oleh' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Status laporan PKL berhasil diperbarui.');
    }

    public function revisionLaporan($id, Request $request)
    {
        $request->validate([
            'catatan_pembimbing' => 'required|string|max:1000',
        ]);

        $placements = $this->getBimbinganPlacements();
        $siswaIds = $placements->pluck('siswa_id');

        $laporan = LaporanPKL::whereIn('siswa_id', $siswaIds)->findOrFail($id);
        
        $laporan->update([
            'status' => 'perlu_revisi',
            'catatan_pembimbing' => $request->catatan_pembimbing,
            'disetujui_oleh' => auth()->id()
        ]);

        // Buat notifikasi untuk siswa
        \App\Models\Notifikasi::create([
            'siswa_id' => $laporan->siswa_id,
            'judul' => 'Revisi Laporan PKL',
            'pesan' => 'Pembimbing meminta revisi pada laporan PKL Anda: ' . $request->catatan_pembimbing,
            'jenis' => 'warning',
            'tipe' => 'umum',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Permintaan revisi laporan PKL berhasil dikirim.');
    }

    public function laporanMasalah(Request $request)
    {
        $guru = auth()->user()->guru;
        $jurusanId = $guru ? $guru->jurusan_id : null;

        $issues = LaporanMasalahMagang::with(['siswa', 'industri'])
            ->where('pelapor_id', auth()->id())
            ->when($jurusanId, function($q) use ($jurusanId) {
                $q->whereHas('siswa', function($sq) use ($jurusanId) {
                    $sq->where('jurusan_id', $jurusanId);
                });
            })
            ->latest()
            ->paginate(10);

        $bimbingans = $this->getBimbinganPlacements();

        return view('guru_pembimbing.laporan-masalah', compact('issues', 'bimbingans'));
    }

    public function storeMasalah(Request $request)
    {
        $request->validate([
            'penempatan_id' => 'required|exists:penempatan_magangs,id',
            'permasalahan' => 'required|string',
        ]);

        $placements = $this->getBimbinganPlacements();
        $placement = $placements->where('id', $request->penempatan_id)->first();

        if (!$placement) {
            return redirect()->back()->withErrors(['error' => 'Penempatan magang tidak valid atau bukan bimbingan Anda.']);
        }

        LaporanMasalahMagang::create([
            'siswa_id' => $placement->siswa_id,
            'industri_id' => $placement->industri_id,
            'pelapor_id' => auth()->id(),
            'permasalahan' => $request->permasalahan,
            'tanggal_lapor' => now(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Laporan masalah berhasil dikirimkan ke Kepala Jurusan.');
    }
}
