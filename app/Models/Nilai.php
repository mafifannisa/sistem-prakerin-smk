<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'penempatan_magang_id',
        'nilai_sikap',
        'nilai_keterampilan',
        'nilai_pengetahuan',
        'nilai_akhir',
        'predikat',
        'catatan_penguji',
        'tanggal_input',
        'input_by',
    ];

    protected $casts = [
        'nilai_sikap' => 'decimal:2',
        'nilai_keterampilan' => 'decimal:2',
        'nilai_pengetahuan' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'tanggal_input' => 'date',
    ];

    // Relasi: Punya 1 penempatan_magang
    public function penempatanMagang()
    {
        return $this->belongsTo(PenempatanMagang::class);
    }

    // Relasi: Punya 1 user yang input
    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    // Scope: Lulus (nilai >= 70)
    public function scopeLulus($query)
    {
        return $query->where('nilai_akhir', '>=', 70);
    }

    // Scope: Tidak lulus
    public function scopeTidakLulus($query)
    {
        return $query->where('nilai_akhir', '<', 70);
    }

    // Helper: Cek apakah lulus
    public function isLulus()
    {
        return $this->nilai_akhir >= 70;
    }

    // Helper: Get predikat otomatis jika belum ada
    public function setPredikatAttribute($value)
    {
        if (!$value && $this->nilai_akhir) {
            if ($this->nilai_akhir >= 90) $this->attributes['predikat'] = 'A';
            elseif ($this->nilai_akhir >= 80) $this->attributes['predikat'] = 'B';
            elseif ($this->nilai_akhir >= 70) $this->attributes['predikat'] = 'C';
            elseif ($this->nilai_akhir >= 60) $this->attributes['predikat'] = 'D';
            else $this->attributes['predikat'] = 'E';
        } else {
            $this->attributes['predikat'] = $value;
        }
    }
}
