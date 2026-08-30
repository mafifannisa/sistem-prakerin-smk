# 🗄️ RANCANGAN SKEMA DATABASE (DATABASE MIGRATION PLAN) - ENHANCED
## Schema Updates for Geolocation, Biometrics, Multi-Zone & Emergency Fail-Safe

Dokumen ini mendefinisikan perubahan skema database MySQL/MariaDB yang diperlukan untuk mendukung fitur Geolocation, Face Verification, Multi-Zone Geofencing, dan Emergency Koreksi Presensi.

---

## 1. Perubahan Tabel `industris` (Titik Geofence Industri)

```sql
ALTER TABLE `industris`
  ADD COLUMN `latitude` DECIMAL(10, 8) NULL AFTER `website`,
  ADD COLUMN `longitude` DECIMAL(11, 8) NULL AFTER `latitude`,
  ADD COLUMN `radius_toleransi_meter` INT UNSIGNED NOT NULL DEFAULT 300 AFTER `longitude`,
  ADD COLUMN `jam_masuk` TIME NOT NULL DEFAULT '08:00:00' AFTER `radius_toleransi_meter`,
  ADD COLUMN `jam_pulang` TIME NOT NULL DEFAULT '16:00:00' AFTER `jam_masuk`;
```

---

## 2. Tabel Baru: `industri_locations` (Multi-Zone untuk Kawasan Pabrik Besar)

Untuk industri dengan luas puluhan hektar atau memiliki lebih dari 1 gedung/plant/posko:

```sql
CREATE TABLE `industri_locations` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `industri_id` BIGINT(20) UNSIGNED NOT NULL,
  `nama_lokasi` VARCHAR(255) NOT NULL, -- Contoh: "Gerbang Utama", "Plant 2 Gudang"
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `radius_meter` INT UNSIGNED NOT NULL DEFAULT 300,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `industri_locations_industri_id_foreign` (`industri_id`),
  CONSTRAINT `industri_locations_industri_id_foreign` FOREIGN KEY (`industri_id`) REFERENCES `industris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 3. Perubahan Tabel `siswas` (Device Binding & Face Master)

```sql
ALTER TABLE `siswas`
  ADD COLUMN `device_id` VARCHAR(255) NULL AFTER `password`,
  ADD COLUMN `device_model` VARCHAR(100) NULL AFTER `device_id`,
  ADD COLUMN `fcm_token` TEXT NULL AFTER `device_model`,
  ADD COLUMN `is_face_enrolled` TINYINT(1) NOT NULL DEFAULT 0 AFTER `fcm_token`,
  ADD COLUMN `foto_master_wajah` VARCHAR(255) NULL AFTER `is_face_enrolled`,
  ADD COLUMN `face_embedding_json` LONGTEXT NULL AFTER `foto_master_wajah`;
```

---

## 4. Perubahan Tabel `absensis` (Detail Geofence & Liveness Log)

```sql
ALTER TABLE `absensis`
  ADD COLUMN `latitude` DECIMAL(10, 8) NULL AFTER `tanggal`,
  ADD COLUMN `longitude` DECIMAL(11, 8) NULL AFTER `latitude`,
  ADD COLUMN `gps_accuracy` DECIMAL(6, 2) NULL AFTER `longitude`,
  ADD COLUMN `jarak_meter` DECIMAL(8, 2) NULL AFTER `gps_accuracy`,
  ADD COLUMN `foto_pulang` VARCHAR(255) NULL AFTER `bukti_foto`,
  ADD COLUMN `is_mock_location` TINYINT(1) NOT NULL DEFAULT 0 AFTER `foto_pulang`,
  ADD COLUMN `device_id` VARCHAR(255) NULL AFTER `is_mock_location`,
  ADD COLUMN `liveness_score` DECIMAL(4, 3) NULL AFTER `device_id`;
```

---

## 5. Tabel Baru: `koreksi_absensis` (Emergency Fail-Safe / Koreksi Presensi)

Tabel permohonan koreksi kehadiran jika perangkat siswa rusak atau terkendala teknis:

```sql
CREATE TABLE `koreksi_absensis` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` BIGINT(20) UNSIGNED NOT NULL,
  `penempatan_magang_id` BIGINT(20) UNSIGNED NOT NULL,
  `tanggal` DATE NOT NULL,
  `jenis_koreksi` ENUM('masuk', 'pulang', 'masuk_pulang') NOT NULL DEFAULT 'masuk',
  `jam_diajukan` TIME NOT NULL,
  `alasan` TEXT NOT NULL,
  `bukti_lampiran` VARCHAR(255) NULL,
  `status` ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending',
  `catatan_pembimbing` TEXT NULL,
  `disetujui_oleh` BIGINT(20) UNSIGNED NULL,
  `disetujui_pada` DATETIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `koreksi_absensis_siswa_id_foreign` (`siswa_id`),
  KEY `koreksi_absensis_penempatan_id_foreign` (`penempatan_magang_id`),
  KEY `koreksi_absensis_approver_foreign` (`disetujui_oleh`),
  CONSTRAINT `koreksi_absensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `koreksi_absensis_penempatan_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `koreksi_absensis_approver_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 6. Tabel Baru: `jurnal_fotos` (Dukungan Multi-Foto Jurnal)

```sql
CREATE TABLE `jurnal_fotos` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jurnal_harian_id` BIGINT(20) UNSIGNED NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_fotos_jurnal_id_foreign` (`jurnal_harian_id`),
  CONSTRAINT `jurnal_fotos_jurnal_id_foreign` FOREIGN KEY (`jurnal_harian_id`) REFERENCES `jurnal_harians` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 7. Multi-Zone Haversine Distance Helper (Laravel Service)

Helper yang mengecek jarak terpendek dari seluruh zona lokasi industri siswa:

```php
namespace App\Services;

use App\Models\Industri;

class GeofenceService
{
    public static function checkGeofence(Industri $industri, float $userLat, float $userLng): array
    {
        $zones = [];
        
        // Zona Utama
        if ($industri->latitude && $industri->longitude) {
            $zones[] = [
                'name' => $industri->nama_industri,
                'lat' => (float) $industri->latitude,
                'lng' => (float) $industri->longitude,
                'radius' => (int) ($industri->radius_toleransi_meter ?: 300),
            ];
        }

        // Sub-Zona (Multi-lokasi)
        if ($industri->relationLoaded('locations') || $industri->locations()->exists()) {
            foreach ($industri->locations as $loc) {
                $zones[] = [
                    'name' => $loc->nama_lokasi,
                    'lat' => (float) $loc->latitude,
                    'lng' => (float) $loc->longitude,
                    'radius' => (int) $loc->radius_meter,
                ];
            }
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
            'is_within_radius' => $isWithin,
            'nearest_distance_meters' => $nearestDistance,
            'zone_name' => $matchedZone['name'] ?? $industri->nama_industri,
            'allowed_radius' => $matchedZone['radius'] ?? 300,
        ];
    }

    public static function calculateHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        return round($earthRadius * $c, 2);
    }
}
```
