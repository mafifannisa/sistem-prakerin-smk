<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_industri',
        'nib',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telp',
        'email',
        'website',
        'latitude',
        'longitude',
        'radius_toleransi_meter',
        'jam_masuk',
        'jam_pulang',
        'nama_hr',
        'no_wa_hr',
        'pembimbing_magang',
        'kategori',
        'kapasitas_magang',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas_magang' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_toleransi_meter' => 'integer',
    ];

    // Relasi: Industri punya banyak penempatan_magang
    public function penempatanMagangs()
    {
        return $this->hasMany(PenempatanMagang::class);
    }

    // Relasi: Industri punya banyak lokasi / sub-zona (Multi-Zone)
    public function locations()
    {
        return $this->hasMany(IndustriLocation::class);
    }

    // Scope: Hanya industri aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Berdasarkan kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Helper: Get alamat lengkap
    public function getAlamatLengkapAttribute()
    {
        return "{$this->alamat}, {$this->kelurahan}, {$this->kecamatan}, {$this->kota}";
    }

    // Helper: Get kontak HRD
    public function getKontakHrdAttribute()
    {
        return "{$this->nama_hr} - {$this->no_wa_hr}";
    }
}
