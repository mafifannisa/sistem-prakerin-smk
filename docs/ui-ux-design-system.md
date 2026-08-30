# 🎨 UI/UX DESIGN SYSTEM & WIREFRAME BLUEPRINT - ENHANCED
## Mobile App Design Specification for SMK Vocational Students

Dokumen ini memuat standar desain antarmuka (*User Interface*) dan pengalaman pengguna (*User Experience*) untuk aplikasi Flutter Presensi & Jurnal Prakerin SMK Negeri 3 Tuban.

---

## 1. Design Principles

1. **One-Tap Simplicity (Cepat & Tanpa Hambatan):** Tindakan utama (Presensi Masuk/Pulang) dapat diakses dalam 1 kali sentuhan dari Dashboard utama.
2. **Instant Visual Feedback (Status Geofence Jelas):** Siswa langsung mengetahui apakah mereka berada di dalam atau di luar radius tanpa harus menunggu proses submit.
3. **Calibrated Outdoor/Indoor Usability:** Dilengkapi tombol kalibrasi sinyal satelit GPS saat berada di dalam bengkel/ruangan berlantai tebal.
4. **Emergency Graceful Fallback:** Kemudahan mengajukan tiket koreksi presensi jika perangkat atau sinyal mengalami kendala di tempat magang.
5. **Gen-Z Aesthetics:** Menggunakan kombinasi warna modern (Deep Navy & Vibrant Emerald Green) dengan aksen *subtle glassmorphism* dan sudut membulat (*rounded corners* 16–24px).

---

## 2. Design Tokens (Colors & Typography)

### 2.1 Color Palette
- **Primary Brand (Deep Navy):** `#0F172A` (Header, Background aksen, Teks utama)
- **Primary Accent (Emerald Green):** `#10B981` (Status di dalam radius, tombol sukses, check-in)
- **Secondary Accent (Indigo Blue):** `#6366F1` (Tombol check-out, navigasi aktif)
- **Warning (Amber Orange):** `#F59E0B` (Menunggu approval jurnal, status izin/sakit)
- **Emergency / Correction (Royal Purple):** `#8B5CF6` (Tiket koreksi presensi darurat)
- **Danger / Alert (Crimson Red):** `#EF4444` (Di luar radius, fake GPS terdeteksi, alpha)
- **Surface / Card (Pure White & Slate):** `#FFFFFF` / `#F8FAFC`
- **Geofence Radar Area (Translucent Green):** `rgba(16, 185, 129, 0.15)`

### 2.2 Typography (Inter / Poppins)
- **Display 1 (Header/Jam):** 32px / Bold (700)
- **Title (Nama Screen):** 20px / SemiBold (600)
- **Body Large:** 16px / Regular (400) & Medium (500)
- **Caption / Status Tag:** 12px / Medium (500) & Bold (700)

---

## 3. Screen Structure & Wireframes

### 3.1 Screen 1: Dashboard Siswa (Home Screen)

```text
+-------------------------------------------------------+
|  SMK Negeri 3 Tuban                   [ 🔔 Notifikasi ]|
|  Halo, Rofiqul Wahyu 👋                               |
|  XII RPL 1 - PT. Teknologi Nusantara Abadi             |
+-------------------------------------------------------+
|  [ KARTU STATUS PRESENSI HARI INI ]                   |
|  📅 Kamis, 30 Agustus 2026                            |
|  ⏰ Waktu Server: 07:45:12 WIB                         |
|                                                       |
|  Masuk: 07:48 WIB (Hadir)     Pulang: --:-- (Belum)   |
|  Status: [🟢 DI DALAM ZONA MAGANG (Jarak: 45m)]       |
|                                                       |
|  +-------------------------+  +---------------------+ |
|  |  🔘 PRESENSI MASUK      |  |  🔘 PRESENSI PULANG | |
|  |     (Sudah Check-In)    |  |     (Tersedia 16:00)| |
|  +-------------------------+  +---------------------+ |
+-------------------------------------------------------+
|  RINGKASAN KEHADIRAN BULAN INI                        |
|  [ 20 Hadir ]   [ 1 Izin ]   [ 1 Sakit ]   [ 0 Alpha ]|
+-------------------------------------------------------+
|  JURNAL HARI INI                                      |
|  Status: ⚠️ Belum Mengisi Jurnal Kegiatan              |
|  [ ✍️ Tulis Jurnal Kegiatan Hari Ini ]                 |
+-------------------------------------------------------+
|  [ 🏠 Home ]   [ 📍 Presensi ]   [ 📖 Jurnal ]   [ 👤 Profil ] |
+-------------------------------------------------------+
```

---

### 3.2 Screen 2: Live Radar Geofencing & GPS Calibrator

```text
+-------------------------------------------------------+
|  < Kembali                 Presensi Lokasi             |
+-------------------------------------------------------+
|                                                       |
|                  /-----------------\                  |
|                 /   ZONA MAGANG     \                 |
|                /   (Radius 300m)     \                |
|               |        🏢 KANTOR     |                |
|               |          •           |                |
|               |                      |                |
|                \      📍 SISWA (45m)/                 |
|                 \------------------/                  |
|                                                       |
|             [ MAP VIEW - OPENSTREETMAP ]              |
|                                                       |
|  [ 🔄 Kalibrasi / Refresh Akurasi GPS (Akurasi: 8m) ] |
+-------------------------------------------------------+
|  INFORMASI LOKASI:                                    |
|  🏢 PT. Teknologi Nusantara Abadi (Zona: Gerbang 1)   |
|  📏 Jarak: 45 Meter (Batas Maksimal: 300 Meter)       |
|  🟢 Status: Di Dalam Zona Magang                      |
+-------------------------------------------------------+
|  [ 📸 LANJUTKAN: VERIFIKASI WAJAH (SELFIE) >>> ]      |
|                                                       |
|  🚨 Sinyal GPS drop / HP bermasalah?                  |
|  [ 🆘 Ajukan Koreksi Presensi Darurat ]               |
+-------------------------------------------------------+
```

---

### 3.3 Screen 3: Liveness Face Verification (Kamera Depan)

```text
+-------------------------------------------------------+
|  Verifikasi Wajah                                  ✕  |
+-------------------------------------------------------+
|                                                       |
|                     . - ~ ~ ~ - .                     |
|                 . '               ' .                 |
|               /                       \               |
|              |     [ CIRCULAR GUIDE ]  |              |
|              |       Posisikan Wajah   |              |
|              |          di Sini        |              |
|               \                       /               |
|                 . _               _ .                 |
|                     ' - ~ ~ ~ - '                     |
|                                                       |
|  TANTANGAN LIVENESS:                                  |
|  👁️  "Silakan BERKEDIP 2 Kali..."                     |
|  Indikator: [ 🟢 Kedipan 1/2 ]                        |
|                                                       |
+-------------------------------------------------------+
|  🛡️ Pastikan pencahayaan cukup & tidak memakai masker |
+-------------------------------------------------------+
```

---

### 3.4 Screen 4: Pengajuan Koreksi Presensi Darurat (Emergency Fail-Safe)

```text
+-------------------------------------------------------+
|  < Batal             Koreksi Presensi Darurat          |
+-------------------------------------------------------+
|  Tanggal: [ 30/08/2026 ]                              |
|  Jenis Koreksi: (*) Masuk  ( ) Pulang                 |
|  Jam Masuk Tiba di Lokasi: [ 07:45 ]                  |
+-------------------------------------------------------+
|  ALASAN KENDALA:                                      |
|  +--------------------------------------------------+ |
|  | HP lowbat saat tiba di lokasi industri pabrik.   | |
|  | Sudah konfirmasi dengan Pak Budi (Pembimbing Lap)| |
|  +--------------------------------------------------+ |
+-------------------------------------------------------+
|  BUKTI FOTO DOKUMENTASI DI LOKASI:                    |
|  [ 📷 Unggah Foto di Kantor / Screenshot Bukti ]      |
|  File terpilih: selfie_kantor_darurat.jpg             |
+-------------------------------------------------------+
|  ℹ️ Pengajuan ini akan diverifikasi oleh Guru         |
|     Pembimbing Anda sebelum status diubah.            |
|                                                       |
|  [ 🚀 KIRIM PENGAJUAN KOREKSI ]                       |
+-------------------------------------------------------+
```

---

### 3.5 Screen 5: Editor Jurnal Harian (Multi-Photo Support)

```text
+-------------------------------------------------------+
|  < Batal                 Tulis Jurnal Harian   [Simpan]|
+-------------------------------------------------------+
|  Tanggal: [ 30/08/2026 ]         Minggu Ke: [ 4 ]     |
|  Durasi Kerja: [ 8 Jam ]                              |
+-------------------------------------------------------+
|  DESKRIPSI KEGIATAN PKL:                              |
|  +--------------------------------------------------+ |
|  | Hari ini saya ditugaskan untuk melakukan        | |
|  | maintenance kabel LAN server dan konfigurasi    | |
|  | switch pada lantai 2 kantor operasional...       | |
|  |                                                  | |
|  | (Minimal 20 karakter)                            | |
|  +--------------------------------------------------+ |
+-------------------------------------------------------+
|  DOKUMENTASI FOTO KEGIATAN (Maksimal 3 Foto):         |
|  +---------+   +---------+   +---------+              |
|  | [Foto 1]|   | [Foto 2]|   | [ + ]   |              |
|  | [Hapus] |   | [Hapus] |   | Tambah  |              |
|  +---------+   +---------+   +---------+              |
+-------------------------------------------------------+
|  [ 💾 SIMPAN SEBAGAI DRAFT ]  [ 🚀 KIRIM KE PEMBIMBING ]|
+-------------------------------------------------------+
```
