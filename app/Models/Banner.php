<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'button_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope untuk banner aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Accessor untuk full image URL
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
