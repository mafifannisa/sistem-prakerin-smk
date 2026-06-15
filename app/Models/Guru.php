<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'jurusan_id',
        'kelas_id',
        'no_telp',
        'jabatan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: Guru belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Guru belongs to Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi: Guru belongs to Jurusan (for Kepala Jurusan)
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Relasi: Guru Pembimbing has many PenempatanMagang
    public function penempatanMagangs()
    {
        return $this->hasMany(PenempatanMagang::class, 'guru_pembimbing_id');
    }

    // Scope: Hanya guru aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Berdasarkan jabatan
    public function scopeJabatan($query, $jabatan)
    {
        return $query->where('jabatan', $jabatan);
    }

    // Helper: Cek jabatan
    public function isKepalaJurusan()
    {
        return $this->jabatan === 'kepala_jurusan';
    }

    public function isGuruPembimbing()
    {
        return $this->jabatan === 'guru_pembimbing';
    }

    public function isGuruPenguji()
    {
        return $this->jabatan === 'guru_penguji';
    }
}
