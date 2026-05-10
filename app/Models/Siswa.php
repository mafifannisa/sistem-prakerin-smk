<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'nisn',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jurusan_id',
        'kelas',
        'no_wa',
        'email',
        'alamat',
        'nama_wali',
        'no_wa_wali',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

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
        return $this->tanggal_lahir->age;
    }

    // Helper: Get nama lengkap dengan NISN
    public function getNamaLengkapAttribute()
    {
        return "{$this->nama} ({$this->nisn})";
    }
}
