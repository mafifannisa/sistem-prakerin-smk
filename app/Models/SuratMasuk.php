<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'pengirim',
        'tanggal_terima',
        'perihal',
        'file_path',
        'status',
        'catatan',
        'penempatan_magang_id',
        'created_by',
    ];

    protected $casts = [
        'tanggal_terima' => 'date',
    ];

    // Relasi: Punya 1 penempatan_magang
    public function penempatanMagang()
    {
        return $this->belongsTo(PenempatanMagang::class);
    }

    // Relasi: Punya 1 user yang buat
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope: Berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper: Cek apakah sudah diproses
    public function isDiproses()
    {
        return $this->status === 'diproses' || $this->status === 'selesai';
    }
}
