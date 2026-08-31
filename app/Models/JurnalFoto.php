<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalFoto extends Model
{
    use HasFactory;

    protected $table = 'jurnal_fotos';

    protected $fillable = [
        'jurnal_harian_id',
        'file_path',
        'caption',
    ];

    public function jurnalHarian()
    {
        return $this->belongsTo(JurnalHarian::class);
    }
}
