<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'weight',
        'image',
        'sold_count',
        'is_recommended',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'weight' => 'integer',
        'sold_count' => 'integer',
        'is_recommended' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ==================== ACCESSORS ====================

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/products/' . $this->image);
        }
        return asset('images/product-placeholder.png');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedWeightAttribute(): string
    {
        if ($this->weight >= 1000) {
            return number_format($this->weight / 1000, 1) . ' kg';
        }
        return $this->weight . ' gram';
    }

    // ==================== METHODS ====================

    public function decreaseStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
        $this->increment('sold_count', $quantity);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }
}
