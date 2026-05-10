<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'jurusan_id',
        'nama_template',
        'jenis_surat',
        'file_path',
        'konten_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: Punya 1 jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Relasi: Punya banyak surat_keluar
    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    // Scope: Hanya template aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Berdasarkan jenis surat
    public function scopeJenisSurat($query, $jenis)
    {
        return $query->where('jenis_surat', $jenis);
    }

    // Helper: Get nama template lengkap
    public function getNamaLengkapAttribute()
    {
        return "{$this->jurusan->nama_jurusan} - {$this->nama_template}";
    }
}
