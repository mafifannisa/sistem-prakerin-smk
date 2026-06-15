<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'email',
        'no_wa',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Relasi: User membuat banyak surat_keluar
    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class, 'created_by');
    }

    // Relasi: User membuat banyak surat_masuk
    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class, 'created_by');
    }

    // Relasi: User approve banyak penempatan_magang
    public function penempatanMagangs()
    {
        return $this->hasMany(PenempatanMagang::class, 'approved_by');
    }

    // Relasi: User input banyak nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'input_by');
    }

    // Relasi: User generate banyak sertifikat
    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'generated_by');
    }

    // Relasi: User kirim banyak log_wa
    public function logWas()
    {
        return $this->hasMany(LogWa::class, 'created_by');
    }

    // Relasi: User has one Guru profile
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    // Scope: Hanya user aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Berdasarkan role
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Helper: Cek apakah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Helper: Cek apakah pimpinan
    public function isPimpinan()
    {
        return $this->role === 'pimpinan';
    }

    // Helper: Cek apakah guru pembimbing
    public function isGuruPembimbing()
    {
        return $this->role === 'guru_pembimbing';
    }

    // Helper: Cek apakah kepala jurusan
    public function isKepalaJurusan()
    {
        return $this->role === 'kepala_jurusan';
    }

    // Helper: Cek apakah guru penguji
    public function isGuruPenguji()
    {
        return $this->role === 'guru_penguji';
    }
}
