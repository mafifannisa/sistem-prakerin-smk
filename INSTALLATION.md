# 📖 Panduan Lengkap Instalasi Sistem Informasi Prakerin SMK Negeri 3 Tuban

Dokumentasi ini memuat panduan langkah demi langkah (*step-by-step*) untuk menginstall dan menjalankan aplikasi Sistem Informasi Administrasi Praktik Kerja Industri (Prakerin) / PKL SMK Negeri 3 Tuban di berbagai lingkungan:
1. [Persyaratan Sistem](#1-persyaratan-sistem)
2. [Instalasi di Windows (Laragon)](#2-instalasi-di-windows-laragon---rekomendasi)
3. [Instalasi di Windows (XAMPP)](#3-instalasi-di-windows-xampp)
4. [Instalasi di Server Linux (Ubuntu / Debian VPS)](#4-instalasi-di-server-linux-ubuntu--debian)
5. [Konfigurasi Web Server di Linux (Nginx / Apache)](#5-konfigurasi-web-server-di-linux)
6. [Konfigurasi Cron Job (Scheduler Otomatis)](#6-konfigurasi-cron-job-laravel-scheduler)
7. [Daftar Akun Login Demo (Hasil Seeder)](#7-daftar-akun-login-demo)
8. [Troubleshooting / Solusi Masalah Umum](#8-troubleshooting--solusi-masalah)

---

## 1. Persyaratan Sistem

Pastikan perangkat/server Anda memenuhi spesifikasi berikut:

| Komponen | Versi Minimum | Keterangan |
| :--- | :--- | :--- |
| **PHP** | `>= 8.2` | PHP 8.2 atau 8.3 |
| **Ekstensi PHP** | Wajib aktif | `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `xml`, `curl`, `gd`, `zip`, `bcmath`, `ctype` |
| **Database** | MySQL `>= 5.7` / MariaDB `>= 10.3` | Default nama: `db_prakering_smk3` |
| **Composer** | `>= 2.2` | Manajemen dependensi PHP |
| **Node.js & NPM** | Node `>= 18.x`, NPM `>= 9.x` | Untuk build aset Frontend (Vite & Tailwind) |
| **Web Server** | Nginx / Apache | Linux VPS atau Windows Local |

---

## 2. Instalasi di Windows (Laragon) - *Rekomendasi*

Laragon adalah opsi termudah untuk pengembangan lokal di Windows karena sudah menyertakan PHP 8.2+, Composer, MySQL, dan Node.js secara bawaan.

### Langkah 1: Clone Repository
Buka terminal Laragon atau Git Bash, lalu masuk ke folder `www`:
```bash
cd C:\laragon\www
git clone <URL_REPOSITORY_ANDA> sistem-prakerin-smk
cd sistem-prakerin-smk
```

### Langkah 2: Buat Database di MySQL
1. Buka aplikasi Laragon, lalu klik **Start All**.
2. Klik tombol **Database** (HeidiSQL) atau buka phpMyAdmin di browser (`http://localhost/phpmyadmin`).
3. Buat database baru bernama: `db_prakering_smk3` (dengan collation `utf8mb4_unicode_ci`).

### Langkah 3: Eksekusi Setup Otomatis
Cukup jalankan file `setup.bat` dengan cara:
- **Klik ganda** file `setup.bat` di dalam folder proyek, ATAU
- Jalankan via CMD / PowerShell:
```cmd
setup.bat
```

> **Catatan:** Script `setup.bat` akan otomatis:
> 1. Menyalin `.env.example` menjadi `.env`
> 2. Menjalankan `composer install`
> 3. Men-generate `APP_KEY`
> 4. Menjalankan `php artisan migrate --seed --force`
> 5. Menjalankan `npm install && npm run build`
> 6. Menghubungkan `storage:link`
> 7. Membersihkan cache development

### Langkah 4: Akses Aplikasi
- Buka browser dan kunjungi: **`http://sistem-prakerin-smk.test`** (jika fitur Auto Virtual Host Laragon aktif), ATAU
- Jalankan `php artisan serve` lalu buka **`http://localhost:8000`**.

---

## 3. Instalasi di Windows (XAMPP)

### Langkah 1: Clone Repository
```bash
cd C:\xampp\htdocs
git clone <URL_REPOSITORY_ANDA> sistem-prakerin-smk
cd sistem-prakerin-smk
```

### Langkah 2: Buat Database
1. Buka **XAMPP Control Panel**, lalu start **Apache** dan **MySQL**.
2. Buka browser: `http://localhost/phpmyadmin`.
3. Buat database baru dengan nama `db_prakering_smk3`.

### Langkah 3: Setup Manual via Command Prompt
Buka CMD di folder proyek (`C:\xampp\htdocs\sistem-prakerin-smk`):

1. **Salin file Environment:**
   ```cmd
   copy .env.example .env
   ```

2. **Install Dependensi Composer:**
   ```cmd
   composer install
   ```

3. **Generate Application Key:**
   ```cmd
   php artisan key:generate
   ```

4. **Konfigurasi Database di file `.env`:**
   Buka file `.env` dan pastikan konfigurasi sesuai dengan XAMPP Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_prakering_smk3
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migration & Seeder:**
   ```cmd
   php artisan migrate --seed
   ```

6. **Install Node Modules & Build Aset:**
   ```cmd
   npm install
   npm run build
   ```

7. **Hubungkan Storage:**
   ```cmd
   php artisan storage:link
   ```

8. **Jalankan Server Lokal:**
   ```cmd
   php artisan serve
   ```
   Buka browser di: **`http://127.0.0.1:8000`**.

---

## 4. Instalasi di Server Linux (Ubuntu / Debian)

Panduan berikut mengasumsikan Anda menggunakan **Ubuntu 22.04 / 24.04 LTS** pada VPS.

### Langkah 1: Install Paket Pendukung di Server (Jika Belum)
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y git curl unzip zip nginx mysql-server software-properties-common

# Tambah PPA PHP (Ondrej Sury)
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-gd php8.2-zip php8.2-intl

# Install Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js & NPM (Node 20 LTS)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### Langkah 2: Setup Database MySQL di Server
Masuk ke MySQL server:
```bash
sudo mysql
```
Jalankan query SQL berikut (ganti `PasswordKuat123!` dengan password aman Anda):
```sql
CREATE DATABASE db_prakerin_smk3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'prakerin_user'@'localhost' IDENTIFIED BY 'PasswordKuat123!';
GRANT ALL PRIVILEGES ON db_prakerin_smk3.* TO 'prakerin_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Langkah 3: Clone Project ke Folder `/var/www/`
```bash
cd /var/www
sudo git clone <URL_REPOSITORY_ANDA> sistem-prakerin-smk
cd sistem-prakerin-smk
```

### Langkah 4: Setup `.env`
1. Salin `.env.example`:
   ```bash
   cp .env.example .env
   ```
2. Edit file `.env`:
   ```bash
   nano .env
   ```
3. Sesuaikan konfigurasi utama berikut:
   ```env
   APP_NAME="Sistem Prakerin SMK 3 Tuban"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://prakerin.smkn3tuban.sch.id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_prakerin_smk3
   DB_USERNAME=prakerin_user
   DB_PASSWORD=PasswordKuat123!

   # Token API WhatsApp Fonnte (Wajib diisi agar notifikasi WA aktif)
   FONNTE_TOKEN=your_fonnte_token_here
   ```
   Simpan dengan `Ctrl + O` -> `Enter`, lalu keluar dengan `Ctrl + X`.

### Langkah 5: Jalankan Script Deployment
Berikan izin eksekusi pada file `deploy.sh`:
```bash
chmod +x deploy.sh
```
Jalankan deployment (opsi `--seed` menyertakan data awal dan user):
```bash
sudo ./deploy.sh --seed
```

---

## 5. Konfigurasi Web Server di Linux

### Opsi A: Nginx (Sangat Direkomendasikan)
Buat file konfigurasi virtual host Nginx:
```bash
sudo nano /etc/nginx/sites-available/prakerin.conf
```
Tempelkan konfigurasi berikut:
```nginx
server {
    listen 80;
    server_name prakerin.smkn3tuban.sch.id; # Ganti dengan domain / IP server Anda
    root /var/www/sistem-prakerin-smk/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 20M;
}
```

Aktifkan konfigurasi dan restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/prakerin.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Pasang SSL Gratis (HTTPS) dengan Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d prakerin.smkn3tuban.sch.id
```

---

### Opsi B: Apache
Jika menggunakan Apache di server Linux:
1. Pastikan modul `rewrite` aktif:
   ```bash
   sudo a2enmod rewrite
   ```
2. Buat file virtual host:
   ```bash
   sudo nano /etc/apache2/sites-available/prakerin.conf
   ```
   Isi dengan:
   ```apache
   <VirtualHost *:80>
       ServerName prakerin.smkn3tuban.sch.id
       DocumentRoot /var/www/sistem-prakerin-smk/public

       <Directory /var/www/sistem-prakerin-smk/public>
           AllowOverride All
           Require all granted
       </Directory>

       ErrorLog ${APACHE_LOG_DIR}/prakerin_error.log
       CustomLog ${APACHE_LOG_DIR}/prakerin_access.log combined
   </VirtualHost>
   ```
3. Aktifkan site dan restart Apache:
   ```bash
   sudo a2ensite prakerin.conf
   sudo systemctl restart apache2
   ```

---

## 6. Konfigurasi Cron Job (Laravel Scheduler)

Aplikasi memiliki task terjadwal otomatis di latar belakang:
- **Auto-Alpha**: Mengisi absensi "Alpha" otomatis setiap Senin–Jumat pukul 23:55 WIB bagi siswa yang tidak absen.
- **Auto-Update Status Magang**: Mengubah status siswa dari *Approved* -> *Ongoing* -> *Completed* berdasarkan tanggal mulai dan tanggal selesai magang.

Buka crontab server:
```bash
sudo crontab -e -u www-data
```
Tambahkan baris berikut di paling bawah:
```bash
* * * * * cd /var/www/sistem-prakerin-smk && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

---

## 7. Daftar Akun Login Demo

Setelah menjalankan seeder (`php artisan db:seed`), Anda dapat login menggunakan akun default berikut:

| Role | Username / Identifier | Password Default | Keterangan / Akses |
| :--- | :--- | :--- | :--- |
| **Admin (Tata Usaha)** | `admin` | `admin123` | Akses penuh master data, cetak surat, nilai, wa blast, approval |
| **Staff TU** | `tu` | `tu123` | Akses operasional administrasi |
| **Pimpinan (Kepsek)** | `pimpinan` | `pimpinan123` | Approval akhir pengajuan tempat magang & laporan statistik |
| **Guru Pembimbing** | `guru` | `guru123` | Verifikasi jurnal harian, absensi, & laporan PKL siswa bimbingan |
| **Kepala Jurusan (Kajur)** | `kajur` | `kajur123` | Penempatan guru pembimbing, pengawasan jurusan RPL |
| **Guru Penguji** | `penguji` | `penguji123` | Input penilaian teknis & pengujian ujian magang |
| **Siswa (Contoh 1)** | `0051234567` (NISN) | `0051234567` | Siswa RPL: Rofiqul Wahyu Romadhani |
| **Siswa (Contoh 2)** | `0051234568` (NISN) | `0051234568` | Siswa RPL: Ahmad Maulana |
| **Siswa (Contoh 3)** | `0051234569` (NISN) | `0051234569` | Siswa Tata Boga: Siti Nurhaliza |

> 💡 **Info Siswa:** Password default untuk seluruh siswa adalah **Nomor Induk Siswa Nasional (NISN)** masing-masing.

---

## 8. Troubleshooting / Solusi Masalah

### 1. Error `500 Server Error` atau Blank Page
- Pastikan hak akses folder `storage` dan `bootstrap/cache` sudah benar:
  ```bash
  sudo chown -R www-data:www-data /var/www/sistem-prakerin-smk/storage /var/www/sistem-prakerin-smk/bootstrap/cache
  sudo chmod -R 775 /var/www/sistem-prakerin-smk/storage /var/www/sistem-prakerin-smk/bootstrap/cache
  ```
- Cek log error detail di: `storage/logs/laravel.log` atau error log web server `/var/log/nginx/error.log`.

### 2. Error `404 Not Found` pada Menu / Halaman Selain Home
- **Di Nginx:** Pastikan ada baris `try_files $uri $uri/ /index.php?$query_string;` di blok `location /`.
- **Di Apache:** Pastikan modul rewrite aktif (`sudo a2enmod rewrite`) dan `AllowOverride All` sudah terpasang.

### 3. Error Gambar / Foto Absen / Sertifikat Tidak Muncul (Broken Image)
- Buat ulang symlink storage:
  ```bash
  php artisan storage:link
  ```
- Jika di Linux: pastikan folder `public/storage` mengarah ke `/var/www/sistem-prakerin-smk/storage/app/public`.

### 4. Menjalankan Update Kode Baru di Server
Setiap kali ada pembaruan di Git, cukup jalankan:
```bash
cd /var/www/sistem-prakerin-smk
sudo ./deploy.sh
```
Script akan otomatis melakukan `git pull`, migrasi database baru, kompilasi aset, dan pembersihan cache produksi tanpa menghapus data yang ada.
