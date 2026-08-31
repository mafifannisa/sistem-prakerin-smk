<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login Siswa dengan NISN, Password, dan Penguncian Perangkat (Device Binding).
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|string',
            'password' => 'required|string',
            'device_id' => 'required|string|max:255',
            'device_model' => 'nullable|string|max:100',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $siswa = Siswa::with(['kelas', 'jurusan'])->where('nisn', $request->nisn)->first();

        if (!$siswa || !Hash::check($request->password, $siswa->password)) {
            return response()->json([
                'success' => false,
                'error_code' => 'INVALID_CREDENTIALS',
                'message' => 'NISN atau password yang Anda masukkan salah.',
            ], 401);
        }

        if (!$siswa->is_active) {
            return response()->json([
                'success' => false,
                'error_code' => 'ACCOUNT_INACTIVE',
                'message' => 'Akun siswa Anda sedang tidak aktif. Silakan hubungi pihak sekolah.',
            ], 403);
        }

        // ==================== DEVICE BINDING LOGIC ====================
        if (empty($siswa->device_id)) {
            // First time login on device: Bind this device
            $siswa->update([
                'device_id' => $request->device_id,
                'device_model' => $request->device_model,
                'fcm_token' => $request->fcm_token,
            ]);
        } elseif ($siswa->device_id !== $request->device_id) {
            // Logged in from a different device!
            return response()->json([
                'success' => false,
                'error_code' => 'DEVICE_MISMATCH',
                'message' => 'Akun Anda sudah terikat pada perangkat lain (' . ($siswa->device_model ?: 'Device Terdaftar') . '). Silakan hubungi Admin TU/Guru Pembimbing untuk reset ikatan perangkat.',
            ], 403);
        } else {
            // Update FCM token & device model if changed
            $siswa->update([
                'device_model' => $request->device_model ?: $siswa->device_model,
                'fcm_token' => $request->fcm_token ?: $siswa->fcm_token,
            ]);
        }

        // Generate Sanctum Token
        $token = $siswa->createToken('siswa-mobile-app')->plainTextToken;

        // Ambil Data Penempatan Magang Aktif
        $penempatan = PenempatanMagang::with(['industri.locations', 'guruPembimbing'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $siswa->id,
                    'nisn' => $siswa->nisn,
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas?->nama_kelas,
                    'jurusan' => $siswa->jurusan?->nama_jurusan,
                    'is_face_enrolled' => (bool) $siswa->is_face_enrolled,
                    'foto_master_wajah' => $siswa->foto_master_wajah ? asset('storage/' . $siswa->foto_master_wajah) : null,
                ],
                'penempatan' => $penempatan ? [
                    'id' => $penempatan->id,
                    'status' => $penempatan->status,
                    'tanggal_mulai' => $penempatan->tanggal_mulai?->format('Y-m-d'),
                    'tanggal_selesai' => $penempatan->tanggal_selesai?->format('Y-m-d'),
                    'industri' => [
                        'id' => $penempatan->industri?->id,
                        'nama_industri' => $penempatan->industri?->nama_industri,
                        'alamat' => $penempatan->industri?->alamat_lengkap,
                        'latitude' => $penempatan->industri?->latitude,
                        'longitude' => $penempatan->industri?->longitude,
                        'radius_toleransi_meter' => $penempatan->industri?->radius_toleransi_meter ?: 300,
                        'jam_masuk' => $penempatan->industri?->jam_masuk ?: '08:00:00',
                        'jam_pulang' => $penempatan->industri?->jam_pulang ?: '16:00:00',
                        'zones' => $penempatan->industri?->locations->map(fn($loc) => [
                            'id' => $loc->id,
                            'nama_lokasi' => $loc->nama_lokasi,
                            'latitude' => $loc->latitude,
                            'longitude' => $loc->longitude,
                            'radius_meter' => $loc->radius_meter,
                        ]),
                    ],
                    'guru_pembimbing' => [
                        'nama' => $penempatan->guruPembimbing?->nama_lengkap,
                        'no_wa' => $penempatan->guruPembimbing?->no_wa,
                    ],
                ] : null,
            ],
        ]);
    }

    /**
     * Ambil Profil Siswa Saat Ini.
     */
    public function me(Request $request): JsonResponse
    {
        $siswa = $request->user()->load(['kelas', 'jurusan']);

        $penempatan = PenempatanMagang::with(['industri.locations', 'guruPembimbing'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $siswa->id,
                    'nisn' => $siswa->nisn,
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas?->nama_kelas,
                    'jurusan' => $siswa->jurusan?->nama_jurusan,
                    'device_id' => $siswa->device_id,
                    'device_model' => $siswa->device_model,
                    'is_face_enrolled' => (bool) $siswa->is_face_enrolled,
                    'foto_master_wajah' => $siswa->foto_master_wajah ? asset('storage/' . $siswa->foto_master_wajah) : null,
                ],
                'penempatan' => $penempatan,
            ],
        ]);
    }

    /**
     * Perekaman Wajah Master Siswa (Face Enrollment).
     */
    public function faceEnroll(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'foto_master_1' => 'required|image|max:4096',
            'face_embedding' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $siswa = $request->user();

        // Hapus foto lama jika ada
        if ($siswa->foto_master_wajah && Storage::disk('public')->exists($siswa->foto_master_wajah)) {
            Storage::disk('public')->delete($siswa->foto_master_wajah);
        }

        $path = $request->file('foto_master_1')->store('face_master', 'public');

        $siswa->update([
            'is_face_enrolled' => true,
            'foto_master_wajah' => $path,
            'face_embedding_json' => $request->face_embedding,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data biometrik wajah master berhasil didaftarkan.',
            'data' => [
                'is_face_enrolled' => true,
                'foto_master_wajah' => asset('storage/' . $path),
            ],
        ]);
    }

    /**
     * Logout & Revoke Access Token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}
