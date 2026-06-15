<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PenempatanMagang;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;

class GuruPengujiController extends Controller
{
    public function dashboard()
    {
        $totalSiswaUjian = PenempatanMagang::where('guru_penguji_id', auth()->id())
            ->whereIn('status', ['ongoing', 'completed'])->count();
        $totalDinilai = Nilai::whereHas('penempatanMagang', function($q) {
            $q->where('guru_penguji_id', auth()->id());
        })->whereNotNull('catatan_penguji')->count();
        
        $aktivitasTerbaru = PenempatanMagang::with(['siswa', 'industri', 'nilai'])
            ->where('guru_penguji_id', auth()->id())
            ->whereIn('status', ['ongoing', 'completed'])
            ->latest()
            ->limit(5)
            ->get();

        return view('guru_penguji.dashboard', compact('totalSiswaUjian', 'totalDinilai', 'aktivitasTerbaru'));
    }

    public function ujianMagang(Request $request)
    {
        $query = PenempatanMagang::with(['siswa.jurusan', 'industri', 'nilai', 'laporanPkls'])
            ->where('guru_penguji_id', auth()->id())
            ->whereIn('status', ['ongoing', 'completed']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%");
            });
        }

        $placements = $query->paginate(10);
        return view('guru_penguji.ujian-magang', compact('placements'));
    }

    public function storeUjian(Request $request, $placement_id)
    {
        // Secure placement by assigned examiner
        $placement = PenempatanMagang::where('guru_penguji_id', auth()->id())
            ->whereIn('status', ['ongoing', 'completed'])
            ->findOrFail($placement_id);

        $request->validate([
            'catatan_penguji' => 'required|string',
            'nilai_penguji' => 'required|numeric|min:0|max:100',
        ]);

        $nilai = Nilai::where('penempatan_magang_id', $placement_id)->first();
        if (!$nilai) {
            // Jika nilai kompetensi belum diisi Kajur, Penguji masih bisa membuat record nilai kosong dengan catatan penguji
            $nilai = Nilai::create([
                'penempatan_magang_id' => $placement_id,
                'nilai_sikap' => 0,
                'nilai_keterampilan' => 0,
                'nilai_pengetahuan' => 0,
                'nilai_akhir' => 0,
                'predikat' => 'E',
                'tanggal_input' => now(),
                'input_by' => auth()->id(),
            ]);
        }

        $nilai->update([
            'catatan_penguji' => $request->catatan_penguji,
            'nilai_penguji' => $request->nilai_penguji,
        ]);

        return redirect()->back()->with('success', 'Catatan & hasil ujian magang berhasil diperbarui oleh Penguji.');
    }
}
