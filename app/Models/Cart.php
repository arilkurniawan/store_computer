<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    public function getSubtotalAttribute(): int
    {
        return $this->product->price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getTotalWeightAttribute(): int
    {
        return $this->product->weight * $this->quantity;
    }

    // ==================== METHODS ====================

    public function increaseQuantity(int $amount = 1): void
    {
        $this->increment('quantity', $amount);
    }

    public function decreaseQuantity(int $amount = 1): void
    {
        if ($this->quantity > $amount) {
            $this->decrement('quantity', $amount);
        } else {
            $this->delete();
        }
    }

    public function updateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            $this->delete();
        } else {
            $this->update(['quantity' => $quantity]);
        }
    }
}
