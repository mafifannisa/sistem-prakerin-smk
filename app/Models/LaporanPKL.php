<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPKL extends Model
{
    use HasFactory;

    // ⬇️ TAMBAHKAN BARIS INI ⬇️
    protected $table = 'laporan_pkls';

    protected $fillable = [
        'siswa_id',
        'penempatan_magang_id',
        'judul_laporan',
        'abstrak',
        'jenis',
        'file_path',
        'tanggal_submit',
        'catatan_pembimbing',
        'status',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_submit' => 'date',
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
}