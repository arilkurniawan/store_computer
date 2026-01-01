<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'description', 
        'price', 
        'stock', 
        'is_recommended',
        'image', 
        'is_active'
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'is_recommended' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
        ->where('stock', '>', 0);
    }

    public function scopeRecommended(Builder $query): Builder
    {
        return $query->where('is_recommended', true);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function decreaseStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
    }

    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }
}
