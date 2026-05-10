<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'kepala_jurusan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: Jurusan punya banyak siswa
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    // Relasi: Jurusan punya banyak template_surat
    public function templateSurats()
    {
        return $this->hasMany(TemplateSurat::class);
    }

    // Scope: Hanya jurusan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper: Get nama dengan kode
    public function getNamaLengkapAttribute()
    {
        return "{$this->kode_jurusan} - {$this->nama_jurusan}";
    }
}