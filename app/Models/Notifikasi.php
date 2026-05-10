<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    // ⬇️ TAMBAHKAN BARIS INI ⬇️
    protected $table = 'notifikasis';

    protected $fillable = [
        'siswa_id',
        'judul',
        'pesan',
        'jenis',
        'tipe',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}