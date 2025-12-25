<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'promo_id',
        'order_code',
        'name',
        'phone',
        'address',
        'city',
        'post_code',
        'total_price',
        'discount_amount',
        'status',
        'snap_token',
    ];

    // order milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // order bisa punya promo (optional)
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // 1 order punya banyak item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
