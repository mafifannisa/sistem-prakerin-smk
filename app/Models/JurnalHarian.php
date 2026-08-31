<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalHarian extends Model
{
    use HasFactory;

    protected $table = 'jurnal_harians';

    protected $fillable = [
        'siswa_id',
        'penempatan_magang_id',
        'tanggal',
        'minggu_ke',
        'kegiatan',
        'durasi_jam',
        'bukti_foto',
        'catatan_pembimbing',
        'status',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'minggu_ke' => 'integer',
        'durasi_jam' => 'integer',
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

    // Relasi: 1 jurnal bisa memiliki banyak foto dokumentasi
    public function fotos()
    {
        return $this->hasMany(JurnalFoto::class);
    }
}