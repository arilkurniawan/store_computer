<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
        'subtotal' => 'integer',
    ];

    // item milik satu order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // item mengacu ke satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
