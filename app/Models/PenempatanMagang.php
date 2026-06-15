<?php

// app/Models/PenempatanMagang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'industri_id',
        'periode_magang_id',
        'tahun_ajaran',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'alasan_penolakan',
        'posisi_magang',
        'alasan_pemilihan',
        'catatan_industri',
        'tanggal_approval',
        'approved_by',
        'guru_pembimbing_id',
        'guru_penguji_id',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_approval' => 'date',
    ];

    // Relasi: Punya 1 guru pembimbing (User)
    public function guruPembimbing()
    {
        return $this->belongsTo(User::class, 'guru_pembimbing_id');
    }

    // Relasi: Punya 1 guru penguji (User)
    public function guruPenguji()
    {
        return $this->belongsTo(User::class, 'guru_penguji_id');
    }

    // Relasi: Punya 1 siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi: Punya 1 industri
    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    // Relasi: Punya 1 user yang approve
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi: Punya banyak surat_keluar
    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    // Relasi: Punya banyak surat_masuk
    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class);
    }

    // Relasi: Punya 1 periode magang
    public function periodeMagang()
    {
        return $this->belongsTo(PeriodeMagang::class, 'periode_magang_id');
    }

    // Relasi: Punya 1 nilai
    public function nilai()
    {
        return $this->hasOne(Nilai::class);
    }

    // Relasi: Punya 1 sertifikat
    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class);
    }

    // Relasi: Punya banyak laporan_pkl
    public function laporanPkls()
    {
        return $this->hasMany(LaporanPKL::class);
    }

    // Relasi: Punya 1 laporan_pkl
    public function laporanPkl()
    {
        return $this->hasOne(LaporanPKL::class);
    }

    // Scope: Berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Sedang magang (ongoing)
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    // Helper: Cek apakah sudah approve
    public function isApproved()
    {
        return $this->status === 'approved' || $this->status === 'ongoing' || $this->status === 'completed';
    }

    // Helper: Get durasi magang (hari)
    public function getDurasiHariAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    // Helper: Get status label (untuk badge)
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'ongoing' => 'primary',
            'completed' => 'info',
            'cancelled' => 'secondary',
        ];
        return $labels[$this->status] ?? 'secondary';
    }

    // ✅ TAMBAHKAN: Relasi singular untuk surat pengantar
    public function suratKeluar()
    {
        return $this->hasOne(SuratKeluar::class, 'penempatan_magang_id')
                    ->where('jenis_surat', 'pengantar');
    }
}