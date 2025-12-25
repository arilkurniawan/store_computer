<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
     use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_recommended',
    ];

    // produk milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // produk bisa muncul di banyak order item
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
