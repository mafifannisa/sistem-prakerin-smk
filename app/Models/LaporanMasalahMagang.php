<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMasalahMagang extends Model
{
    use HasFactory;

    protected $table = 'laporan_masalah_magangs';

    protected $fillable = [
        'siswa_id',
        'industri_id',
        'pelapor_id',
        'permasalahan',
        'solusi',
        'tanggal_lapor',
        'status',
        'catatan_kajur',
        'ditinjau_oleh',
    ];

    protected $casts = [
        'tanggal_lapor' => 'date',
    ];

    // Relasi: Laporan belongs to Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi: Laporan belongs to Industri
    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    // Relasi: Laporan dilaporkan oleh User (guru pembimbing)
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    // Relasi: Laporan ditinjau oleh User (kepala jurusan)
    public function peninjau()
    {
        return $this->belongsTo(User::class, 'ditinjau_oleh');
    }

    // Scope: berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
