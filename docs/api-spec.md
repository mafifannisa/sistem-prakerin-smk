# 🔌 RESTful API SPECIFICATION (V1) - ENHANCED
## Mobile API Backend (Laravel 11 Sanctum)

Base URL: `https://prakerin.smkn3tuban.sch.id/api/v1`  
Format: `JSON` (Kecuali endpoint upload file menggunakan `multipart/form-data`)  
Authentication: `Authorization: Bearer <SANCTUM_TOKEN>`

---

## 1. Authentication & Profiling

### 1.1 Login Siswa & Device Binding
* **Endpoint:** `POST /auth/login`
* **Request Body (JSON):**
```json
{
  "nisn": "0051234567",
  "password": "password123",
  "device_id": "9f8b4a7d-3c2e-4e8b-8c2f-1a2b3c4d5e6f",
  "device_model": "Samsung Galaxy A54 5G",
  "fcm_token": "fcm_token_device_string"
}
```
* **Response 200 (Success):**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "token": "1|qWeRtYuIoP1234567890abcdef...",
    "user": {
      "id": 1,
      "nisn": "0051234567",
      "nama": "Rofiqul Wahyu Romadhani",
      "kelas": "XII RPL 1",
      "jurusan": "Rekayasa Perangkat Lunak",
      "is_face_enrolled": true,
      "foto_profil": "https://domain.com/storage/siswa/profile.jpg"
    },
    "penempatan": {
      "id": 10,
      "status": "ongoing",
      "industri": {
        "id": 2,
        "nama_industri": "PT. Teknologi Nusantara Abadi",
        "alamat": "Jl. Gatot Subroto No. 12, Tuban",
        "latitude": -6.894520,
        "longitude": 112.058340,
        "radius_toleransi_meter": 300,
        "jam_masuk": "08:00:00",
        "jam_pulang": "16:00:00",
        "zones": [
          { "id": 1, "nama_zone": "Pos Gerbang Utama", "latitude": -6.894520, "longitude": 112.058340, "radius": 300 },
          { "id": 2, "nama_zone": "Plant II Bengkel", "latitude": -6.896100, "longitude": 112.060200, "radius": 400 }
        ]
      },
      "guru_pembimbing": {
        "nama": "Drs. Haryanto, M.Pd",
        "no_wa": "081234500003"
      }
    }
  }
}
```

---

### 1.2 Face Enrollment (Perekaman Wajah Awal)
* **Endpoint:** `POST /auth/face-enroll`
* **Request (Multipart):** `foto_master_1`, `foto_master_2`, `face_embedding` (JSON string).
* **Response 200:**
```json
{
  "success": true,
  "message": "Perekaman data biometrik wajah master berhasil disimpan."
}
```

---

## 2. Geolocation & Presensi (Absensi)

### 2.1 Cek Validasi Lokasi Geofence (Multi-Zone Supported)
* **Endpoint:** `POST /absensi/check-location`
* **Request Body:**
```json
{
  "latitude": -6.894310,
  "longitude": 112.058120,
  "accuracy": 12.5
}
```
* **Response 200:**
```json
{
  "success": true,
  "data": {
    "is_within_radius": true,
    "current_distance_meters": 42.8,
    "active_zone_name": "Pos Gerbang Utama",
    "max_radius_meters": 300,
    "gps_accuracy_meters": 12.5,
    "can_check_in": true,
    "can_check_out": false,
    "message": "Anda berada di dalam zona magang (Jarak: 43 meter)."
  }
}
```

---

### 2.2 Submit Presensi Masuk (Check-In)
* **Endpoint:** `POST /absensi/check-in`
* **Request (Multipart):** `latitude`, `longitude`, `accuracy`, `foto_selfie`, `is_mock_location`, `liveness_score`.
* **Response 201:**
```json
{
  "success": true,
  "message": "Presensi Masuk berhasil dicatat!",
  "data": {
    "id": 145,
    "tanggal": "2026-08-30",
    "jam_masuk": "07:48:15",
    "status": "hadir",
    "jarak_meter": 42.8,
    "foto_url": "https://domain.com/storage/absensi/2026-08/selfie_145.jpg"
  }
}
```

---

### 2.3 Submit Presensi Pulang (Check-Out)
* **Endpoint:** `POST /absensi/check-out`
* **Request (Multipart):** `latitude`, `longitude`, `accuracy`, `foto_selfie`.
* **Response 200:**
```json
{
  "success": true,
  "message": "Presensi Pulang berhasil dicatat!",
  "data": {
    "id": 145,
    "tanggal": "2026-08-30",
    "jam_masuk": "07:48:15",
    "jam_pulang": "16:05:22",
    "durasi_kerja": "8 Jam 17 Menit"
  }
}
```

---

### 2.4 Pengajuan Izin / Sakit
* **Endpoint:** `POST /absensi/izin-sakit`
* **Request (Multipart):** `status` (izin/sakit), `keterangan`, `surat_dokter` (file).
* **Response 201:**
```json
{
  "success": true,
  "message": "Pengajuan Izin/Sakit berhasil dikirim."
}
```

---

### 2.5 Pengajuan Koreksi Presensi Darurat (Emergency Fail-Safe)
* **Endpoint:** `POST /absensi/koreksi`
* **Deskripsi:** Diajukan jika HP rusak, kamera error, atau sinyal GPS drop saat siswa sudah di lokasi industri.
* **Request (Multipart):**
  - `tanggal`: `2026-08-30`
  - `jenis_koreksi`: `masuk` atau `pulang` atau `masuk_pulang`
  - `jam_koreksi`: `08:00:00`
  - `alasan`: `string` (misal: "Baterai HP habis saat tiba di pabrik, dikonfirmasi pembimbing lapangan Pak Budi")
  - `bukti_foto`: File foto di tempat magang / bukti chat pembimbing.
* **Response 201:**
```json
{
  "success": true,
  "message": "Pengajuan koreksi presensi darurat berhasil dikirim ke Guru Pembimbing untuk verifikasi.",
  "data": {
    "id": 12,
    "status": "pending_approval"
  }
}
```

---

## 3. Jurnal Harian (Daily Activity Log)

### 3.1 Ambil Daftar Jurnal
* **Endpoint:** `GET /jurnal?page=1&limit=15`
* **Response 200:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "total": 24,
    "jurnals": [
      {
        "id": 89,
        "tanggal": "2026-08-29",
        "minggu_ke": 4,
        "kegiatan": "Melakukan troubleshooting jaringan LAN di divisi IT.",
        "durasi_jam": 8,
        "status": "disetujui",
        "foto_dokumentasi": [
          "https://domain.com/storage/jurnal/doc_89_1.jpg",
          "https://domain.com/storage/jurnal/doc_89_2.jpg"
        ],
        "catatan_pembimbing": "Pekerjaan rapi.",
        "disetujui_pada": "2026-08-29 19:30:00"
      }
    ]
  }
}
```

---

### 3.2 Tambah Jurnal Harian (Multi-Foto Support)
* **Endpoint:** `POST /jurnal`
* **Request (Multipart):** `tanggal`, `minggu_ke`, `kegiatan`, `durasi_jam`, `foto_dokumentasi[]` (up to 3 files).
* **Response 201:**
```json
{
  "success": true,
  "message": "Jurnal harian berhasil disimpan.",
  "data": {
    "id": 90,
    "status": "pending"
  }
}
```

---

## 4. Guru Pembimbing Review Endpoints (Mobile Web / App)

### 4.1 Approval Koreksi Presensi Darurat
* **Endpoint:** `POST /pembimbing/koreksi/{id}/action`
* **Request Body:**
```json
{
  "action": "approve", // atau "reject"
  "catatan": "Dikonfirmasi langsung via telepon dengan pembimbing lapangan."
}
```
* **Response 200:**
```json
{
  "success": true,
  "message": "Koreksi presensi berhasil disetujui. Status kehadiran siswa diperbarui menjadi Hadir."
}
```
