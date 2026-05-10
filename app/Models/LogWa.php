<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogWa extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'no_wa_tujuan',
        'pesan',
        'jenis',
        'status',
        'message_id',
        'response',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relasi: Punya 1 siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi: Punya 1 user yang kirim
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope: Berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Berdasarkan jenis
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    // Scope: Sudah terkirim
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    // Helper: Cek apakah berhasil terkirim
    public function isSent()
    {
        return $this->status === 'sent' || $this->status === 'delivered';
    }

    // Helper: Get preview pesan (100 karakter pertama)
    public function getPreviewPesanAttribute()
    {
        return substr($this->pesan, 0, 100) . (strlen($this->pesan) > 100 ? '...' : '');
    }
}
