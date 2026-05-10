<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $fillable = [
        'penempatan_magang_id',
        'template_surat_id',
        'jenis_surat',
        'nomor_surat',
        'file_path',
        'status',
        'tanggal_kirim',
        'catatan',
        'created_by', // ✅ PASTIKAN ADA
    ];

    // Relasi ke PenempatanMagang
    public function penempatanMagang()
    {
        return $this->belongsTo(PenempatanMagang::class);
    }

    // Relasi ke Siswa (melalui penempatan_magang)
    public function siswa()
    {
        return $this->hasOneThrough(
            Siswa::class,
            PenempatanMagang::class,
            'id',           // Foreign key on penempatan_magangs table
            'id',           // Foreign key on siswas table
            'penempatan_magang_id', // Local key on surat_keluars table
            'siswa_id'      // Local key on penempatan_magangs table
        );
    }
}
