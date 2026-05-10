<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'penempatan_magang_id',
        'nilai_id',
        'nomor_sertifikat',
        'file_path',
        'tanggal_terbit',
        'status',
        'catatan',
        'generated_by',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    // Relasi: Punya 1 penempatan_magang
    public function penempatanMagang()
    {
        return $this->belongsTo(PenempatanMagang::class);
    }

    // Relasi: Punya 1 nilai
    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }

    // Relasi: Punya 1 user yang generate
    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // Scope: Berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Sudah terbit
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    // Helper: Cek apakah sudah generate
    public function isGenerated()
    {
        return $this->status === 'generated' || $this->status === 'issued';
    }

    // Helper: Get nomor sertifikat lengkap
    public function getNomorLengkapAttribute()
    {
        return $this->nomor_sertifikat ?? 'Belum ada nomor';
    }
}
