<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'siswa_id',
        'penempatan_magang_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
        'bukti_foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // JANGAN masukkan jam_masuk dan jam_pulang di sini agar formatnya tidak rusak
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function penempatanMagang()
    {
        return $this->belongsTo(PenempatanMagang::class);
    }
}