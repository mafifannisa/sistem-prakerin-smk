<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    // ⬇️ TAMBAHKAN BARIS INI ⬇️
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'isi',
        'prioritas',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Scope untuk pengumuman aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)
                     ->where('tanggal_mulai', '<=', now())
                     ->where(function($q) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', now());
                     });
    }
}