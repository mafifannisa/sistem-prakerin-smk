<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');

        View::composer(['layouts.siswa', 'siswa.*'], function ($view) {
            if (session()->has('siswa_id')) {
                $siswaId = session('siswa_id');
                $notifikasis = Notifikasi::where('siswa_id', $siswaId)->latest()->limit(5)->get();
                $notifikasiUnread = Notifikasi::where('siswa_id', $siswaId)->where('is_read', false)->count();
                $view->with('globalNotifikasis', $notifikasis ?: collect());
                $view->with('globalNotifikasiUnread', $notifikasiUnread ?: 0);
            } else {
                $view->with('globalNotifikasis', collect());
                $view->with('globalNotifikasiUnread', 0);
            }
        });

        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            $suratPending = \App\Models\PenempatanMagang::where('status', 'pending')->count();
            $view->with('globalSuratPending', $suratPending ?: 0);
        });
    }
}