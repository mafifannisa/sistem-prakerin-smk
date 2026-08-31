<?php

use App\Http\Controllers\Api\V1\AbsensiApiController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\JurnalApiController;
use App\Http\Controllers\Api\V1\PembimbingApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - V1 Mobile Application
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ==================== AUTH PUBLIC ====================
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    // ==================== SISWA PROTECTED ROUTES ====================
    Route::middleware(['auth:sanctum'])->group(function () {

        // User & Profiling
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/face-enroll', [AuthController::class, 'faceEnroll']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Geolocation & Absensi
        Route::prefix('absensi')->group(function () {
            Route::post('/check-location', [AbsensiApiController::class, 'checkLocation']);
            Route::post('/check-in', [AbsensiApiController::class, 'checkIn'])->middleware('throttle:15,1');
            Route::post('/check-out', [AbsensiApiController::class, 'checkOut'])->middleware('throttle:15,1');
            Route::post('/izin-sakit', [AbsensiApiController::class, 'izinSakit']);
            Route::post('/koreksi', [AbsensiApiController::class, 'ajukanKoreksi']);
            Route::get('/today', [AbsensiApiController::class, 'todayStatus']);
            Route::get('/rekap', [AbsensiApiController::class, 'rekapBulanan']);
        });

        // Jurnal Harian CRUD
        Route::prefix('jurnal')->group(function () {
            Route::get('/', [JurnalApiController::class, 'index']);
            Route::post('/', [JurnalApiController::class, 'store']);
            Route::get('/{id}', [JurnalApiController::class, 'show']);
            Route::put('/{id}', [JurnalApiController::class, 'update']);
            Route::delete('/{id}', [JurnalApiController::class, 'destroy']);
        });

        // Guru Pembimbing Endpoints (Koreksi Approval)
        Route::prefix('pembimbing')->group(function () {
            Route::get('/koreksi', [PembimbingApiController::class, 'koreksiList']);
            Route::post('/koreksi/{id}/action', [PembimbingApiController::class, 'koreksiAction']);
        });
    });
});
