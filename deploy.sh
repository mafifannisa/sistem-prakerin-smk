#!/bin/bash
set -e

echo "==================================================="
echo "    DEPLOYMENT SISTEM PRAKERIN SMK NEGERI 3 TUBAN  "
echo "==================================================="
echo ""

# Parse arguments
RUN_SEED=false
for arg in "$@"; do
    case $arg in
        --seed|-s)
            RUN_SEED=true
            shift
            ;;
    esac
done

# 1. Update repository
echo "[1/7] Menarik pembaruan dari Git (git pull)..."
git pull origin main

# 2. Setup .env
FIRST_TIME_SETUP=false
if [ ! -f .env ]; then
    echo "[2/7] Membuat file .env dari .env.example..."
    cp .env.example .env
    FIRST_TIME_SETUP=true
else
    echo "[2/7] File .env sudah ada. Melanjutkan..."
fi

# 3. Install Composer dependencies
echo "[3/7] Menginstall dependensi Composer (Production)..."
composer install --optimize-autoloader --no-dev --no-interaction

# 4. Generate App Key jika belum ada
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY= " .env || [ "$FIRST_TIME_SETUP" = true ]; then
    echo "[4/7] Men-generate Application Key..."
    php artisan key:generate --force
else
    echo "[4/7] Application Key sudah ada. Melewati..."
fi

# Jika setup pertama kali, berikan notifikasi untuk mengisi konfigurasi database
if [ "$FIRST_TIME_SETUP" = true ]; then
    echo ""
    echo "================================================================="
    echo "⚠️  PERHATIAN: File .env baru saja dibuat!"
    echo "Silakan edit file .env dan sesuaikan kredensial Database & Mail:"
    echo "   nano .env"
    echo "Setelah itu jalankan kembali: ./deploy.sh"
    echo "================================================================="
    exit 0
fi

# 5. Database Migration & Optional Seeding
echo "[5/7] Menjalankan Database Migrations..."
php artisan migrate --force

if [ "$RUN_SEED" = true ]; then
    echo "Menjalankan database seeders (--seed flag terdeteksi)..."
    php artisan db:seed --force
elif [ -t 0 ]; then
    echo -n "Apakah Anda ingin menjalankan database seeders untuk demo data? (y/N): "
    read -r seed_choice
    if [ "$seed_choice" = "y" ] || [ "$seed_choice" = "Y" ]; then
        php artisan db:seed --force
    fi
fi

# 6. Build Frontend Assets
echo "[6/7] Membangun Frontend Assets (Vite)..."
if command -v npm >/dev/null 2>&1; then
    npm install --no-audit --no-fund
    npm run build
else
    echo "Peringatan: Node/NPM tidak ditemukan. Melewati npm build."
fi

# 7. Setup Storage & Optimize Cache
echo "[7/7] Mengoptimasi Cache & Hak Akses Folder..."
php artisan storage:link || true
php artisan optimize:clear
php artisan optimize
php artisan view:cache

# Set Permissions
if [ "$EUID" -eq 0 ]; then
    chown -R www-data:www-data storage bootstrap/cache || true
elif command -v sudo >/dev/null 2>&1; then
    sudo chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
fi
chmod -R 775 storage bootstrap/cache || true

echo ""
echo "==================================================="
echo "✅ DEPLOYMENT BERHASIL SELESAI!"
echo "==================================================="
echo "Info Cron Job untuk Laravel Scheduler:"
echo "   * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
echo "==================================================="
