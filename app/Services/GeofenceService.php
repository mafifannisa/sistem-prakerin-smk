<?php

namespace App\Services;

use App\Models\Industri;

class GeofenceService
{
    /**
     * Radius bumi dalam meter.
     */
    private const EARTH_RADIUS_METERS = 6371000;

    /**
     * Hitung jarak antara 2 koordinat GPS menggunakan formula Haversine.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float Jarak dalam meter
     */
    public static function calculateHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * asin(sqrt($a));

        return round(self::EARTH_RADIUS_METERS * $c, 2);
    }

    /**
     * Evaluasi posisi siswa terhadap zona-zona industri (Single & Multi-Zone).
     *
     * @param Industri $industri
     * @param float $userLat
     * @param float $userLng
     * @return array
     */
    public static function checkGeofence(Industri $industri, float $userLat, float $userLng): array
    {
        $zones = [];

        // 1. Tambahkan Zona Utama
        if ($industri->latitude !== null && $industri->longitude !== null) {
            $zones[] = [
                'name' => $industri->nama_industri . ' (Pusat)',
                'lat' => (float) $industri->latitude,
                'lng' => (float) $industri->longitude,
                'radius' => (int) ($industri->radius_toleransi_meter ?: 300),
            ];
        }

        // 2. Tambahkan Sub-Zona (Multi-Lokasi / Gedung Lain)
        $industri->loadMissing('locations');
        foreach ($industri->locations as $loc) {
            $zones[] = [
                'name' => $loc->nama_lokasi,
                'lat' => (float) $loc->latitude,
                'lng' => (float) $loc->longitude,
                'radius' => (int) $loc->radius_meter,
            ];
        }

        // Jika industri belum memiliki koordinat GPS sama sekali (fallback default keizinkan/warning)
        if (empty($zones)) {
            return [
                'has_coordinates' => false,
                'is_within_radius' => true,
                'nearest_distance_meters' => 0.0,
                'zone_name' => $industri->nama_industri,
                'allowed_radius' => 300,
                'message' => 'Titik koordinat industri belum diatur. Presensi diizinkan.',
            ];
        }

        $nearestDistance = PHP_INT_MAX;
        $matchedZone = null;
        $isWithin = false;

        foreach ($zones as $zone) {
            $distance = self::calculateHaversine($userLat, $userLng, $zone['lat'], $zone['lng']);

            if ($distance < $nearestDistance) {
                $nearestDistance = $distance;
                $matchedZone = $zone;
            }

            if ($distance <= $zone['radius']) {
                $isWithin = true;
                $matchedZone = $zone;
                $nearestDistance = $distance;
                break;
            }
        }

        return [
            'has_coordinates' => true,
            'is_within_radius' => $isWithin,
            'nearest_distance_meters' => $nearestDistance,
            'zone_name' => $matchedZone['name'] ?? $industri->nama_industri,
            'allowed_radius' => $matchedZone['radius'] ?? 300,
            'message' => $isWithin
                ? "Anda berada di dalam zona magang ({$matchedZone['name']}) dengan jarak {$nearestDistance}m."
                : "Anda berada di luar zona magang ({$nearestDistance}m dari {$matchedZone['name']}, maks: {$matchedZone['radius']}m).",
        ];
    }

    /**
     * Periksa apakah akurasi GPS dalam batas toleransi wajar (maks 50 meter).
     */
    public static function isGpsAccuracyAcceptable(?float $accuracy): bool
    {
        if ($accuracy === null) {
            return true;
        }
        return $accuracy <= 50.0;
    }
}
