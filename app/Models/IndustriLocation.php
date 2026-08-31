<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustriLocation extends Model
{
    use HasFactory;

    protected $table = 'industri_locations';

    protected $fillable = [
        'industri_id',
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meter' => 'integer',
    ];

    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }
}
