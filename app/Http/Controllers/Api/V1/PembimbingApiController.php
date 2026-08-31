<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KoreksiAbsensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembimbingApiController extends Controller
{
    /**
     * Ambil Daftar Tiket Koreksi Presensi Siswa Bimbingan.
     */
    public function koreksiList(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = KoreksiAbsensi::with(['siswa.kelas', 'siswa.jurusan', 'penempatanMagang.industri'])
            ->whereHas('penempatanMagang', function ($q) use ($user) {
                // Jika role guru pembimbing, filter hanya siswa bimbingannya
                if ($user->role === 'guru_pembimbing') {
                    $q->where('guru_pembimbing_id', $user->id);
                }
            });

        $status = $request->query('status');
        if ($status && in_array($status, ['pending', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $list = $query->orderBy('created_at', 'desc')->paginate($request->query('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $list->currentPage(),
                'total' => $list->total(),
                'items' => $list->map(fn($item) => [
                    'id' => $item->id,
                    'siswa' => [
                        'id' => $item->siswa?->id,
                        'nama' => $item->siswa?->nama,
                        'nisn' => $item->siswa?->nisn,
                        'kelas' => $item->siswa?->kelas?->nama_kelas,
                        'industri' => $item->penempatanMagang?->industri?->nama_industri,
                    ],
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'jenis_koreksi' => $item->jenis_koreksi,
                    'jam_diajukan' => $item->jam_diajukan,
                    'alasan' => $item->alasan,
                    'bukti_lampiran_url' => $item->bukti_lampiran ? asset('storage/' . $item->bukti_lampiran) : null,
                    'status' => $item->status,
                    'catatan_pembimbing' => $item->catatan_pembimbing,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ]),
            ],
        ]);
    }

    /**
     * Setujui / Tolak Tiket Koreksi Presensi Siswa.
     */
    public function koreksiAction(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $koreksi = KoreksiAbsensi::with('penempatanMagang')->where('id', $id)->first();

        if (!$koreksi) {
            return response()->json(['success' => false, 'message' => 'Tiket koreksi tidak ditemukan.'], 404);
        }

        if ($user->role === 'guru_pembimbing' && $koreksi->penempatanMagang?->guru_pembimbing_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk memverifikasi siswa ini.',
            ], 403);
        }

        if ($koreksi->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => "Tiket koreksi ini sudah diproses sebelumnya dengan status: {$koreksi->status}.",
            ], 422);
        }

        if ($request->action === 'approve') {
            $koreksi->update([
                'status' => 'disetujui',
                'catatan_pembimbing' => $request->catatan,
                'disetujui_oleh' => $user->id,
                'disetujui_pada' => now(),
            ]);

            // Sinkronisasi ke tabel Absensi secara otomatis
            $absensi = Absensi::where('siswa_id', $koreksi->siswa_id)
                ->whereDate('tanggal', $koreksi->tanggal)
                ->first();

            if (!$absensi) {
                Absensi::create([
                    'siswa_id' => $koreksi->siswa_id,
                    'penempatan_magang_id' => $koreksi->penempatan_magang_id,
                    'tanggal' => $koreksi->tanggal,
                    'status' => 'hadir',
                    'jam_masuk' => $koreksi->jam_diajukan,
                    'keterangan' => 'Presensi disahkan via Koreksi Darurat (Disetujui: ' . $user->nama_lengkap . ')',
                    'bukti_foto' => $koreksi->bukti_lampiran,
                ]);
            } else {
                $updateData = ['status' => 'hadir'];
                if ($koreksi->jenis_koreksi === 'masuk') {
                    $updateData['jam_masuk'] = $koreksi->jam_diajukan;
                } elseif ($koreksi->jenis_koreksi === 'pulang') {
                    $updateData['jam_pulang'] = $koreksi->jam_diajukan;
                } else {
                    $updateData['jam_masuk'] = $koreksi->jam_diajukan;
                    $updateData['jam_pulang'] = '16:00:00';
                }
                $updateData['keterangan'] = 'Diperbarui via Koreksi Darurat (Disetujui: ' . $user->nama_lengkap . ')';
                $absensi->update($updateData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan koreksi presensi berhasil disetujui. Data absensi siswa telah diperbarui.',
            ]);
        } else {
            $koreksi->update([
                'status' => 'ditolak',
                'catatan_pembimbing' => $request->catatan ?: 'Pengajuan ditolak oleh pembimbing.',
                'disetujui_oleh' => $user->id,
                'disetujui_pada' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan koreksi presensi ditolak.',
            ]);
        }
    }
}
