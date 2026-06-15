@echo off
echo ===================================================
echo     SETUP SISTEM PRAKERIN SMK NEGERI 3 TUBAN
echo ===================================================
echo.

:: 1. Copy .env
if not exist .env (
    echo [1/6] Copying .env.example to .env...
    copy .env.example .env
) else (
    echo [1/6] .env already exists. Skipping...
)

:: 2. Install Composer dependencies
echo [2/6] Installing PHP dependencies via Composer...
call composer install --optimize-autoloader --no-dev

:: 3. Generate App Key
echo [3/6] Generating Application Key...
call php artisan key:generate

:: 4. Run Migrations & Seeders
echo [4/6] Running database migrations and seeders...
echo IMPORTANT: Make sure your MySQL is running and the database specified in .env exists!
call php artisan migrate --seed --force

:: 5. Install NPM dependencies & Build Assets
echo [5/6] Building Frontend Assets (Vite/Tailwind)...
call npm install
call npm run build

:: 6. Create Storage Link
echo [6/6] Creating storage link...
call php artisan storage:link

:: 7. Optimize Cache
echo Optimizing application...
call php artisan optimize:clear
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache

echo.
echo ===================================================
echo SETUP COMPLETED SUCCESSFULLY!
echo You can now access the application via Laragon or run:
echo php artisan serve
echo ===================================================
pause
