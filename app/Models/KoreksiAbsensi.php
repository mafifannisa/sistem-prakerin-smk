<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoreksiAbsensi extends Model
{
    use HasFactory;

    protected $table = 'koreksi_absensis';

    protected $fillable = [
        'siswa_id',
        'penempatan_magang_id',
        'tanggal',
        'jenis_koreksi',
        'jam_diajukan',
        'alasan',
        'bukti_lampiran',
        'status',
        'catatan_pembimbing',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function penempatanMagang()
    {
        return $this->belongsTo(PenempatanMagang::class);
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
