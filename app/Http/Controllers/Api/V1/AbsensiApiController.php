<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KoreksiAbsensi;
use App\Models\PenempatanMagang;
use App\Services\GeofenceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsensiApiController extends Controller
{
    /**
     * Cek apakah koordinat GPS siswa saat ini berada di dalam zona industri magang.
     */
    public function checkLocation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi lokasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $siswa = $request->user();
        $penempatan = PenempatanMagang::with(['industri.locations'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->first();

        if (!$penempatan || !$penempatan->industri) {
            return response()->json([
                'success' => false,
                'error_code' => 'NO_ACTIVE_PLACEMENT',
                'message' => 'Anda belum memiliki penempatan magang aktif yang disetujui.',
            ], 403);
        }

        $result = GeofenceService::checkGeofence(
            $penempatan->industri,
            (float) $request->latitude,
            (float) $request->longitude
        );

        $tanggalHariIni = now()->toDateString();
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        $canCheckIn = false;
        $canCheckOut = false;

        if (!$absensiHariIni) {
            $canCheckIn = $result['is_within_radius'];
        } elseif ($absensiHariIni->status === 'hadir' && empty($absensiHariIni->jam_pulang)) {
            $canCheckOut = $result['is_within_radius'];
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($result, [
                'gps_accuracy_meters' => $request->accuracy ? (float) $request->accuracy : null,
                'can_check_in' => $canCheckIn,
                'can_check_out' => $canCheckOut,
                'today_status' => $absensiHariIni ? [
                    'status' => $absensiHariIni->status,
                    'jam_masuk' => $absensiHariIni->jam_masuk,
                    'jam_pulang' => $absensiHariIni->jam_pulang,
                ] : null,
            ]),
        ]);
    }

    /**
     * Presensi Masuk (Check-In) Berbasis GPS Geofencing & Face Verification.
     */
    public function checkIn(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'foto_selfie' => 'required|image|max:4096',
            'is_mock_location' => 'nullable|boolean',
            'liveness_score' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ==================== ANTI-CHEAT: FAKE GPS CHECK ====================
        if ($request->boolean('is_mock_location')) {
            return response()->json([
                'success' => false,
                'error_code' => 'MOCK_LOCATION_DETECTED',
                'message' => 'Presensi ditolak. Terdeteksi aplikasi Fake GPS / Mock Location aktif pada perangkat Anda.',
            ], 403);
        }

        $siswa = $request->user();
        $penempatan = PenempatanMagang::with(['industri.locations'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->first();

        if (!$penempatan || !$penempatan->industri) {
            return response()->json([
                'success' => false,
                'error_code' => 'NO_ACTIVE_PLACEMENT',
                'message' => 'Anda belum memiliki penempatan magang aktif yang disetujui.',
            ], 403);
        }

        $tanggalHariIni = now()->toDateString();

        // Cek apakah sudah absen hari ini
        $existing = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'error_code' => 'ALREADY_CHECKED_IN',
                'message' => 'Anda sudah melakukan presensi untuk hari ini.',
                'data' => $existing,
            ], 422);
        }

        // ==================== SERVER-SIDE GEOFENCE VERIFICATION ====================
        $geofence = GeofenceService::checkGeofence(
            $penempatan->industri,
            (float) $request->latitude,
            (float) $request->longitude
        );

        if (!$geofence['is_within_radius']) {
            return response()->json([
                'success' => false,
                'error_code' => 'OUT_OF_GEOFENCE_RADIUS',
                'message' => "Presensi ditolak. Anda berada di luar radius magang (Jarak: {$geofence['nearest_distance_meters']}m, Maks: {$geofence['allowed_radius']}m).",
                'data' => $geofence,
            ], 422);
        }

        // Simpan Foto Selfie
        $fotoPath = $request->file('foto_selfie')->store('absensi/' . date('Y-m'), 'public');

        $absensi = Absensi::create([
            'siswa_id' => $siswa->id,
            'penempatan_magang_id' => $penempatan->id,
            'tanggal' => $tanggalHariIni,
            'latitude' => (float) $request->latitude,
            'longitude' => (float) $request->longitude,
            'gps_accuracy' => $request->accuracy ? (float) $request->accuracy : null,
            'jarak_meter' => (float) $geofence['nearest_distance_meters'],
            'status' => 'hadir',
            'jam_masuk' => now()->format('H:i:s'),
            'bukti_foto' => $fotoPath,
            'is_mock_location' => false,
            'device_id' => $siswa->device_id,
            'liveness_score' => $request->liveness_score ? (float) $request->liveness_score : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi Masuk berhasil dicatat!',
            'data' => [
                'id' => $absensi->id,
                'tanggal' => $absensi->tanggal->format('Y-m-d'),
                'jam_masuk' => $absensi->jam_masuk,
                'status' => $absensi->status,
                'jarak_meter' => $absensi->jarak_meter,
                'foto_url' => asset('storage/' . $absensi->bukti_foto),
            ],
        ], 201);
    }

    /**
     * Presensi Pulang (Check-Out) Berbasis GPS Geofencing.
     */
    public function checkOut(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'foto_selfie' => 'required|image|max:4096',
            'is_mock_location' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->boolean('is_mock_location')) {
            return response()->json([
                'success' => false,
                'error_code' => 'MOCK_LOCATION_DETECTED',
                'message' => 'Presensi ditolak. Terdeteksi aplikasi Fake GPS aktif.',
            ], 403);
        }

        $siswa = $request->user();
        $penempatan = PenempatanMagang::with(['industri.locations'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->first();

        if (!$penempatan || !$penempatan->industri) {
            return response()->json([
                'success' => false,
                'error_code' => 'NO_ACTIVE_PLACEMENT',
                'message' => 'Anda belum memiliki penempatan magang aktif.',
            ], 403);
        }

        $tanggalHariIni = now()->toDateString();
        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'error_code' => 'NOT_CHECKED_IN_YET',
                'message' => 'Anda belum melakukan presensi masuk hari ini.',
            ], 422);
        }

        if ($absensi->status !== 'hadir') {
            return response()->json([
                'success' => false,
                'error_code' => 'INVALID_STATUS',
                'message' => "Presensi pulang tidak dapat dilakukan untuk status: {$absensi->status}.",
            ], 422);
        }

        if (!empty($absensi->jam_pulang)) {
            return response()->json([
                'success' => false,
                'error_code' => 'ALREADY_CHECKED_OUT',
                'message' => 'Anda sudah melakukan presensi pulang hari ini.',
                'data' => $absensi,
            ], 422);
        }

        // ==================== GEOFENCE CHECK FOR CHECK-OUT ====================
        $geofence = GeofenceService::checkGeofence(
            $penempatan->industri,
            (float) $request->latitude,
            (float) $request->longitude
        );

        if (!$geofence['is_within_radius']) {
            return response()->json([
                'success' => false,
                'error_code' => 'OUT_OF_GEOFENCE_RADIUS',
                'message' => "Presensi pulang ditolak. Anda berada di luar radius magang ({$geofence['nearest_distance_meters']}m).",
                'data' => $geofence,
            ], 422);
        }

        $fotoPulangPath = $request->file('foto_selfie')->store('absensi/' . date('Y-m'), 'public');

        $absensi->update([
            'jam_pulang' => now()->format('H:i:s'),
            'foto_pulang' => $fotoPulangPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi Pulang berhasil dicatat!',
            'data' => [
                'id' => $absensi->id,
                'tanggal' => $absensi->tanggal->format('Y-m-d'),
                'jam_masuk' => $absensi->jam_masuk,
                'jam_pulang' => $absensi->jam_pulang,
                'status' => $absensi->status,
                'foto_pulang_url' => asset('storage/' . $absensi->foto_pulang),
            ],
        ]);
    }

    /**
     * Pengajuan Izin / Sakit.
     */
    public function izinSakit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:izin,sakit',
            'keterangan' => 'required|string|min:5|max:500',
            'surat_keterangan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
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

        $tanggalHariIni = now()->toDateString();
        $existing = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi untuk hari ini sudah ada.',
            ], 422);
        }

        $filePath = null;
        if ($request->hasFile('surat_keterangan')) {
            $filePath = $request->file('surat_keterangan')->store('surat_izin/' . date('Y-m'), 'public');
        }

        $absensi = Absensi::create([
            'siswa_id' => $siswa->id,
            'penempatan_magang_id' => $penempatan->id,
            'tanggal' => $tanggalHariIni,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan ' . ucfirst($request->status) . ' berhasil dikirim.',
            'data' => $absensi,
        ], 201);
    }

    /**
     * Pengajuan Koreksi Presensi Darurat (Emergency Fail-Safe).
     */
    public function ajukanKoreksi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jenis_koreksi' => 'required|in:masuk,pulang,masuk_pulang',
            'jam_diajukan' => 'required|date_format:H:i:s,H:i',
            'alasan' => 'required|string|min:10|max:500',
            'bukti_lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
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

        // ==================== ANTI-ABUSE LIMIT: MAKS 3X / BULAN ====================
        $currentMonth = Carbon::parse($request->tanggal)->month;
        $currentYear = Carbon::parse($request->tanggal)->year;

        $countMonthlyKoreksi = KoreksiAbsensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->count();

        if ($countMonthlyKoreksi >= 3) {
            return response()->json([
                'success' => false,
                'error_code' => 'KOREKSI_LIMIT_EXCEEDED',
                'message' => 'Batas pengajuan koreksi presensi darurat telah mencapai batas maksimal (3 kali per bulan). Silakan hubungi Guru Pembimbing secara langsung.',
            ], 422);
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_lampiran')) {
            $buktiPath = $request->file('bukti_lampiran')->store('koreksi_absensi/' . date('Y-m'), 'public');
        }

        $koreksi = KoreksiAbsensi::create([
            'siswa_id' => $siswa->id,
            'penempatan_magang_id' => $penempatan->id,
            'tanggal' => $request->tanggal,
            'jenis_koreksi' => $request->jenis_koreksi,
            'jam_diajukan' => strlen($request->jam_diajukan) === 5 ? $request->jam_diajukan . ':00' : $request->jam_diajukan,
            'alasan' => $request->alasan,
            'bukti_lampiran' => $buktiPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket koreksi presensi darurat berhasil dikirim ke Guru Pembimbing.',
            'data' => [
                'id' => $koreksi->id,
                'tanggal' => $koreksi->tanggal->format('Y-m-d'),
                'status' => $koreksi->status,
                'sisa_kuota_bulan_ini' => 3 - ($countMonthlyKoreksi + 1),
            ],
        ], 201);
    }

    /**
     * Ambil Status Presensi Hari Ini.
     */
    public function todayStatus(Request $request): JsonResponse
    {
        $siswa = $request->user();
        $tanggalHariIni = now()->toDateString();

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'tanggal' => $tanggalHariIni,
                'server_time' => now()->format('H:i:s'),
                'sudah_absen' => (bool) $absensi,
                'absensi' => $absensi ? [
                    'id' => $absensi->id,
                    'status' => $absensi->status,
                    'jam_masuk' => $absensi->jam_masuk,
                    'jam_pulang' => $absensi->jam_pulang,
                    'keterangan' => $absensi->keterangan,
                    'foto_masuk_url' => $absensi->bukti_foto ? asset('storage/' . $absensi->bukti_foto) : null,
                    'foto_pulang_url' => $absensi->foto_pulang ? asset('storage/' . $absensi->foto_pulang) : null,
                ] : null,
            ],
        ]);
    }

    /**
     * Rekap Presensi Bulanan & Kalender.
     */
    public function rekapBulanan(Request $request): JsonResponse
    {
        $siswa = $request->user();
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $absensis = Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        $hadir = $absensis->where('status', 'hadir')->count();
        $izin = $absensis->where('status', 'izin')->count();
        $sakit = $absensis->where('status', 'sakit')->count();
        $alpha = $absensis->where('status', 'alpha')->count();
        $total = $absensis->count();

        return response()->json([
            'success' => true,
            'data' => [
                'periode' => [
                    'bulan' => (int) $bulan,
                    'tahun' => (int) $tahun,
                ],
                'summary' => [
                    'total' => $total,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'persentase_kehadiran' => $total > 0 ? round(($hadir / $total) * 100, 1) . '%' : '0%',
                ],
                'records' => $absensis->map(fn($a) => [
                    'id' => $a->id,
                    'tanggal' => $a->tanggal->format('Y-m-d'),
                    'status' => $a->status,
                    'jam_masuk' => $a->jam_masuk,
                    'jam_pulang' => $a->jam_pulang,
                    'jarak_meter' => $a->jarak_meter,
                    'keterangan' => $a->keterangan,
                ]),
            ],
        ]);
    }
}
