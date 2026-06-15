<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeMagang extends Model
{
    protected $table = 'periode_magangs';

    protected $fillable = [
        'nama',
        'tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function penempatanMagangs()
    {
        return $this->hasMany(PenempatanMagang::class, 'periode_magang_id');
    }
}
