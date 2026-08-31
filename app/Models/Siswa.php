<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'nisn',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jurusan_id',
        'kelas_id',
        'no_wa',
        'email',
        'alamat',
        'nama_wali',
        'no_wa_wali',
        'password',
        'device_id',
        'device_model',
        'fcm_token',
        'is_face_enrolled',
        'foto_master_wajah',
        'face_embedding_json',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'face_embedding_json',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
        'is_face_enrolled' => 'boolean',
    ];

    // Relasi: Siswa punya 1 kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi: Siswa punya 1 jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Relasi: Siswa punya banyak penempatan_magang
    public function penempatanMagangs()
    {
        return $this->hasMany(PenempatanMagang::class);
    }

    // Relasi: Siswa punya banyak absensi
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // Relasi: Siswa punya banyak jurnal harian
    public function jurnalHarians()
    {
        return $this->hasMany(JurnalHarian::class);
    }

    // Relasi: Siswa punya banyak koreksi absensi
    public function koreksiAbsensis()
    {
        return $this->hasMany(KoreksiAbsensi::class);
    }

    // Relasi: Siswa punya banyak log_wa
    public function logWas()
    {
        return $this->hasMany(LogWa::class);
    }

    // Scope: Hanya siswa aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Berdasarkan jurusan
    public function scopeJurusan($query, $jurusanId)
    {
        return $query->where('jurusan_id', $jurusanId);
    }

    // Helper: Get usia
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    // Helper: Get nama lengkap dengan NISN
    public function getNamaLengkapAttribute()
    {
        return "{$this->nama} ({$this->nisn})";
    }
}
