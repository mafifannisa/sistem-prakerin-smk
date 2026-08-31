<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\JurnalFoto;
use App\Models\JurnalHarian;
use App\Models\PenempatanMagang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JurnalApiController extends Controller
{
    /**
     * Ambil Daftar Jurnal Harian Siswa (Paginated + Filter).
     */
    public function index(Request $request): JsonResponse
    {
        $siswa = $request->user();
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        $status = $request->query('status');

        $query = JurnalHarian::with(['fotos', 'disetujuiOleh'])
            ->where('siswa_id', $siswa->id);

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        if ($status && in_array($status, ['pending', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $jurnals = $query->orderBy('tanggal', 'desc')->paginate($request->query('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $jurnals->currentPage(),
                'last_page' => $jurnals->lastPage(),
                'total' => $jurnals->total(),
                'jurnals' => $jurnals->map(fn($j) => [
                    'id' => $j->id,
                    'tanggal' => $j->tanggal->format('Y-m-d'),
                    'minggu_ke' => $j->minggu_ke,
                    'kegiatan' => $j->kegiatan,
                    'durasi_jam' => $j->durasi_jam,
                    'status' => $j->status,
                    'catatan_pembimbing' => $j->catatan_pembimbing,
                    'disetujui_oleh' => $j->disetujuiOleh?->nama_lengkap,
                    'fotos' => $j->fotos->map(fn($f) => [
                        'id' => $f->id,
                        'url' => asset('storage/' . $f->file_path),
                        'caption' => $f->caption,
                    ]),
                ]),
            ],
        ]);
    }

    /**
     * Tambah Jurnal Harian Baru (Support Multi-Foto).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'minggu_ke' => 'required|integer|min:1|max:30',
            'kegiatan' => 'required|string|min:20|max:2000',
            'durasi_jam' => 'required|integer|min:1|max:16',
            'foto_dokumentasi' => 'nullable|array|max:3',
            'foto_dokumentasi.*' => 'image|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $siswa = $request->user();
        $penempatan = PenempatanMagang::where('siswa_id', $siswa->id)
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->first();

        if (!$penempatan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki penempatan magang aktif.',
            ], 403);
        }

        // Cek duplikasi jurnal pada tanggal yang sama
        $existing = JurnalHarian::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Jurnal untuk tanggal ini sudah pernah dibuat. Silakan edit jurnal yang ada jika perlu perbaikan.',
            ], 422);
        }

        // Simpan Foto Utama jika ada
        $mainPhotoPath = null;
        $allUploadedPaths = [];

        if ($request->hasFile('foto_dokumentasi')) {
            $files = $request->file('foto_dokumentasi');
            foreach ($files as $index => $file) {
                $path = $file->store('jurnal/' . date('Y-m'), 'public');
                $allUploadedPaths[] = $path;
                if ($index === 0) {
                    $mainPhotoPath = $path;
                }
            }
        }

        $jurnal = JurnalHarian::create([
            'siswa_id' => $siswa->id,
            'penempatan_magang_id' => $penempatan->id,
            'tanggal' => $request->tanggal,
            'minggu_ke' => $request->minggu_ke,
            'kegiatan' => $request->kegiatan,
            'durasi_jam' => $request->durasi_jam,
            'status' => 'pending',
        ]);

        // Simpan ke tabel jurnal_fotos
        foreach ($allUploadedPaths as $path) {
            JurnalFoto::create([
                'jurnal_harian_id' => $jurnal->id,
                'file_path' => $path,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jurnal harian berhasil disimpan dan menunggu verifikasi pembimbing.',
            'data' => [
                'id' => $jurnal->id,
                'tanggal' => $jurnal->tanggal->format('Y-m-d'),
                'status' => $jurnal->status,
            ],
        ], 201);
    }

    /**
     * Detail Jurnal Harian.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $siswa = $request->user();
        $jurnal = JurnalHarian::with(['fotos', 'disetujuiOleh'])
            ->where('siswa_id', $siswa->id)
            ->where('id', $id)
            ->first();

        if (!$jurnal) {
            return response()->json([
                'success' => false,
                'message' => 'Jurnal tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $jurnal->id,
                'tanggal' => $jurnal->tanggal->format('Y-m-d'),
                'minggu_ke' => $jurnal->minggu_ke,
                'kegiatan' => $jurnal->kegiatan,
                'durasi_jam' => $jurnal->durasi_jam,
                'status' => $jurnal->status,
                'catatan_pembimbing' => $jurnal->catatan_pembimbing,
                'disetujui_oleh' => $jurnal->disetujuiOleh?->nama_lengkap,
                'fotos' => $jurnal->fotos->map(fn($f) => [
                    'id' => $f->id,
                    'url' => asset('storage/' . $f->file_path),
                ]),
            ],
        ]);
    }

    /**
     * Update Jurnal Harian (Hanya bisa jika status pending atau ditolak).
     */
    public function update(Request $request, $id): JsonResponse
    {
        $siswa = $request->user();
        $jurnal = JurnalHarian::where('siswa_id', $siswa->id)->where('id', $id)->first();

        if (!$jurnal) {
            return response()->json(['success' => false, 'message' => 'Jurnal tidak ditemukan.'], 404);
        }

        if ($jurnal->status === 'disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'Jurnal yang sudah disetujui tidak dapat diubah.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'kegiatan' => 'required|string|min:20|max:2000',
            'durasi_jam' => 'required|integer|min:1|max:16',
            'minggu_ke' => 'nullable|integer|min:1|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jurnal->update([
            'kegiatan' => $request->kegiatan,
            'durasi_jam' => $request->durasi_jam,
            'minggu_ke' => $request->minggu_ke ?: $jurnal->minggu_ke,
            'status' => 'pending', // Reset ke pending jika sebelumnya ditolak
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jurnal harian berhasil diperbarui.',
            'data' => $jurnal,
        ]);
    }

    /**
     * Hapus Jurnal Harian (Hanya bisa jika status pending).
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $siswa = $request->user();
        $jurnal = JurnalHarian::with('fotos')->where('siswa_id', $siswa->id)->where('id', $id)->first();

        if (!$jurnal) {
            return response()->json(['success' => false, 'message' => 'Jurnal tidak ditemukan.'], 404);
        }

        if ($jurnal->status === 'disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'Jurnal yang sudah disetujui tidak dapat dihapus.',
            ], 422);
        }

        // Hapus file foto terkait
        foreach ($jurnal->fotos as $foto) {
            if (Storage::disk('public')->exists($foto->file_path)) {
                Storage::disk('public')->delete($foto->file_path);
            }
        }

        $jurnal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jurnal harian berhasil dihapus.',
        ]);
    }
}
