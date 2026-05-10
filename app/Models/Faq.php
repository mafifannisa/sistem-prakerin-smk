<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'keywords',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    // Scope: Hanya FAQ aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Berdasarkan kategori
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope: Urutkan berdasarkan priority
    public function scopePrioritized($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    // Scope: Cari berdasarkan keyword
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('question', 'LIKE', "%{$keyword}%")
              ->orWhere('answer', 'LIKE', "%{$keyword}%")
              ->orWhere('keywords', 'LIKE', "%{$keyword}%");
        });
    }

    // Helper: Get keywords sebagai array
    public function getKeywordsArrayAttribute()
    {
        return $this->keywords ? explode(',', $this->keywords) : [];
    }
}
