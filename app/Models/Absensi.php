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
        'latitude',
        'longitude',
        'gps_accuracy',
        'jarak_meter',
        'status',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
        'bukti_foto',
        'foto_pulang',
        'is_mock_location',
        'device_id',
        'liveness_score',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
        'gps_accuracy' => 'float',
        'jarak_meter' => 'float',
        'is_mock_location' => 'boolean',
        'liveness_score' => 'float',
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