<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'identity' => 'required',
            'password' => 'required',
            'role' => 'required|in:admin,siswa,pimpinan',
        ]);

        $identity = $request->identity;
        $password = $request->password;
        $role = $request->role;

        // Login untuk Admin/Pimpinan
        if ($role === 'admin' || $role === 'pimpinan') {
            $user = User::where('username', $identity)->where('role', $role)->first();
            
            if ($user && Hash::check($password, $user->password)) {
                if (!$user->is_active) {
                    return back()->withErrors([
                        'identity' => 'Akun Anda tidak aktif. Hubungi administrator.',
                    ]);
                }
                
                Auth::login($user);
                $request->session()->regenerate();
                
                return redirect()->intended($this->getRedirectUrl($user->role));
            }
        }
        
        // Login untuk Siswa (pakai NISN)
        if ($role === 'siswa') {
            $siswa = Siswa::with('jurusan')->where('nisn', $identity)->first();
            
            if ($siswa && Hash::check($password, $siswa->password)) {
                if (!$siswa->is_active) {
                    return back()->withErrors([
                        'identity' => 'Akun Anda tidak aktif. Hubungi administrator.',
                    ]);
                }
                
                // Simpan data siswa di session
                session([
                    'siswa_id' => $siswa->id,
                    'siswa_nisn' => $siswa->nisn,
                    'siswa_nama' => $siswa->nama,
                    'siswa_jurusan' => $siswa->jurusan->nama_jurusan ?? '',
                ]);
                
                return redirect()->intended('/siswa/dashboard');
            }
        }

        // Login gagal
        return back()->withErrors([
            'identity' => 'Username/NISN atau password salah.',
        ])->onlyInput('identity');
    }

    // Get redirect URL berdasarkan role
    private function getRedirectUrl($role)
    {
        switch ($role) {
            case 'admin':
                return '/admin/dashboard';
            case 'pimpinan':
                return '/pimpinan/dashboard';
            default:
                return '/home';
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}