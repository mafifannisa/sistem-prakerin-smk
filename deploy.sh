#!/bin/bash
echo "==================================================="
echo "    DEPLOYMENT SISTEM PRAKERIN SMK NEGERI 3 TUBAN  "
echo "==================================================="
echo ""

# 1. Update repository
echo "[1/7] Pulling latest changes from Git..."
git pull origin main

# 2. Setup .env
if [ ! -f .env ]; then
    echo "[2/7] Creating .env from .env.example..."
    cp .env.example .env
    echo "PLEASE UPDATE YOUR .env FILE CREDENTIALS AFTER SCRIPT COMPLETES!"
else
    echo "[2/7] .env already exists. Skipping..."
fi

# 3. Install Composer dependencies
echo "[3/7] Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 4. Generate Key
echo "[4/7] Generating App Key..."
php artisan key:generate --force

# 5. Migrate & Seed
echo "[5/7] Running Database Migrations..."
php artisan migrate --force

echo "Do you want to run database seeders to insert demo data? (y/n)"
read -r seed_choice
if [ "$seed_choice" = "y" ] || [ "$seed_choice" = "Y" ]; then
    php artisan db:seed --force
fi

# 6. Build Assets
echo "[6/7] Building NPM Assets..."
npm install
npm run build

# 7. Setup Storage & Optimize
echo "[7/7] Optimizing Application & Permissions..."
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo ""
echo "==================================================="
echo "DEPLOYMENT COMPLETED SUCCESSFULLY!"
echo "Next Steps:"
echo "1. Configure your .env database & API credentials"
echo "2. Add Cron Job for Laravel Scheduler:"
echo "   * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
echo "==================================================="
