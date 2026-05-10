<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek untuk Admin/Pimpinan
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
        }
        
        // Cek untuk Siswa (dari session)
        if (session()->has('siswa_id')) {
            if (in_array('siswa', $roles)) {
                return $next($request);
            }
        }
        
        // Redirect ke login jika tidak punya akses
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
