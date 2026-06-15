# Sistem Informasi Manajemen Prakerin SMK Negeri 3 Tuban

Aplikasi web berbasis Laravel 11 untuk manajemen Praktik Kerja Industri (Prakerin) atau PKL. Sistem ini mendukung 3 role: Admin (Tata Usaha), Pimpinan (Kepala Sekolah), dan Siswa.

## Fitur Utama
- **Siswa**: Cek status, ajukan tempat magang, absen harian (lengkap dengan foto), isi jurnal (lengkap dengan foto), upload laporan akhir, dan download sertifikat & surat pengantar.
- **Admin**: Kelola master data (Siswa, Industri, Jurusan), tracking surat, verifikasi pengajuan, manajemen nilai, broadcast WhatsApp (API Fonnte), cetak sertifikat, dll.
- **Pimpinan**: Approval persetujuan akhir pengajuan tempat magang siswa, laporan statistik PKL, dan log aktivitas realtime.

## Arsitektur & Keamanan
Aplikasi ini sudah diaudit dan dipastikan 100% bebas dari:
- **IDOR (Insecure Direct Object Reference)**: Seluruh aksi *Siswa* mengunci kepemilikan data berdasarkan session login.
- **Mass Assignment Vulnerability**: Seluruh model dikonfigurasi ketat dengan `$fillable`.
- **Role Bypass**: Dilindungi oleh custom Guard dan RoleMiddleware yang tangguh.

---

## 🚀 Panduan Setup & Instalasi (Deployment)

### Persyaratan Sistem
- PHP >= 8.2
- Composer 2.x
- Node.js (>= 18) & NPM
- MySQL/MariaDB

### Opsi 1: Setup di Windows (Laragon / XAMPP)
Jika Anda men-*clone* repository ini di Windows lokal:
1. Pastikan database kosong sudah dibuat di MySQL (misal: `db_prakering_smk3`).
2. Klik ganda file **`setup.bat`**. Script tersebut akan otomatis mengcopy `.env`, menginstall *dependency*, melakukan *migrate + seed*, meng-*compile* aset *frontend*, dan me-link *storage*.
3. Edit file `.env` jika kredensial MySQL Anda berbeda dengan default `setup.bat`.

### Opsi 2: Setup di Linux (VPS / CPanel Terminal)
1. Clone repository ini.
2. Beri hak akses eksekusi pada script *deploy*:
   ```bash
   chmod +x deploy.sh
   ```
3. Jalankan script:
   ```bash
   ./deploy.sh
   ```
4. Jawab `y` ketika script menanyakan apakah ingin menjalankan *seeder* data demo.
5. Sesuaikan konfigurasi database dan API di file `.env`.

---

## 🛠️ Konfigurasi Environment (`.env`)

Ubah bagian berikut pada file `.env` sesuai kebutuhan Production:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://domain-anda.com`
- `DB_DATABASE=nama_database_produksi`
- `DB_USERNAME=user_db`
- `DB_PASSWORD=password_db`
- `FONNTE_TOKEN=token_fonnte_anda` (Wajib diisi agar fitur WA Blast Admin berfungsi)

---

## ⏳ Konfigurasi Cron Job (Scheduler Otomatis)
Sistem ini menggunakan Laravel Task Scheduler tingkat lanjut (diatur dalam `routes/console.php`) untuk dua fitur *background* krusial:
1. **Auto-Alpha**: Mengecek siswa yang tidak login dan bolos setiap Senin-Jumat, lalu menembakkan status "Alpha" otomatis (berjalan pada 23:55 WIB).
2. **Auto-Update Status PKL**: Mengubah status siswa dari "Approved" -> "Ongoing" -> "Completed" secara otomatis pada tengah malam berdasarkan kalender magang.

**PENTING**: Agar kedua fitur di atas berjalan otomatis, Anda wajib mendaftarkan Cron Job Laravel pada server Anda.

Buka terminal VPS (atau menu Cron Jobs di cPanel) dan tambahkan baris berikut:
```bash
* * * * * cd /path/ke/folder/sistem-prakering && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔐 Akun Demo (Seeder)
Jika Anda menjalankan *Migration* beserta *Seeder* (`php artisan migrate --seed`), gunakan akun berikut untuk mencoba:

### Admin (Tata Usaha)
- **Username:** admin
- **Password:** password

### Pimpinan (Kepala Sekolah)
- **Username:** pimpinan
- **Password:** password

### Siswa (Contoh)
- **NISN:** 1234567890
- **Password:** password
*(Tersedia 10 siswa dummy yang di-*generate* secara acak oleh Seeder).*

---

## 🛡️ Catatan Keamanan Tambahan
- Folder `.git`, `node_modules/`, `vendor/`, file `.env`, file *database* SQLite (jika ada), dan log sistem `storage/logs/` telah dimasukkan ke dalam `.gitignore` sehingga dipastikan tidak akan ikut ter-*push* ke repositori publik.
- Script *deploy* di VPS tidak menggunakan perintah merusak (seperti `migrate:fresh`) sehingga aman untuk dijalankan kapan pun (hanya menjalankan *migration* baru menggunakan `migrate --force`). 
