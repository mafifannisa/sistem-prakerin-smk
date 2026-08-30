@echo off
setlocal enabledelayedexpansion

echo ===================================================
echo     SETUP SISTEM PRAKERIN SMK NEGERI 3 TUBAN (LOCAL)
echo ===================================================
echo.

:: 1. Copy .env jika belum ada
if not exist .env (
    echo [1/6] Menyalin .env.example ke .env...
    copy .env.example .env >nul
    set FIRST_TIME=1
) else (
    echo [1/6] File .env sudah ada. Melanjutkan...
    set FIRST_TIME=0
)

:: 2. Install Composer dependencies
echo [2/6] Menginstall PHP dependencies via Composer...
call composer install --no-interaction

:: 3. Generate App Key
echo [3/6] Menyiapkan Application Key...
call php artisan key:generate

:: 4. Run Migrations & Seeders
echo [4/6] Menjalankan Database Migrations ^& Seeders...
echo PENTING: Pastikan MySQL (Laragon/XAMPP) sudah aktif dan database di .env sudah dibuat.
call php artisan migrate --seed --force

:: 5. Install NPM dependencies & Build Assets
echo [5/6] Membangun Frontend Assets (Vite/Tailwind)...
call npm install
call npm run build

:: 6. Create Storage Link
echo [6/6] Menghubungkan storage (storage:link)...
call php artisan storage:link

:: 7. Clear Cache for Local Development
echo Membersihkan cache aplikasi untuk development...
call php artisan optimize:clear

echo.
echo ===================================================
echo ✅ SETUP LOKAL SELESAI DENGAN SUKSES!
echo ===================================================
echo Untuk mulai menjalankan aplikasi:
echo   1. Akses melalui Laragon (Virtual Host / Localhost)
echo   ATAU
echo   2. Jalankan perintah: php artisan serve
echo ===================================================
pause
