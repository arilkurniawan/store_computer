<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'is_active',
    ];

    // 1 promo bisa dipakai di banyak order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
