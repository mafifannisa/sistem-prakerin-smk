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
        'nama_hr',
        'no_wa_hr',
        'kategori',
        'kapasitas_magang',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas_magang' => 'integer',
    ];

    // Relasi: Industri punya banyak penempatan_magang
    public function penempatanMagangs()
    {
        return $this->hasMany(PenempatanMagang::class);
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
