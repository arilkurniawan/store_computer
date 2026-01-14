<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',  // ⭐ Sesuai database (bukan discount_amount)
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Helper untuk kompatibilitas
    public function getDiscountAmountAttribute()
    {
        return $this->discount;
    }

        public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedDiscountAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->discount, 0, ',', '.');
    }
}
