<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // ============ RELASI ============
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ============ SCOPES ============
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    // ============ HELPER METHODS ============
    
    /**
     * Cek apakah promo masih valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        if ($this->start_date && $this->start_date->isFuture()) return false;
        
        if ($this->end_date && $this->end_date->isPast()) return false;
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    /**
     * Cek apakah user sudah melebihi batas pemakaian
     */
    public function isUsableBYUser(User $user): bool
    {
        if (!$this->usage_limit_per_user) return true;

        $userUsageCount = Order::where('user_id', $user->id)
            ->where('promo_id', $this->id)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        return $userUsageCount < $this->usage_limit_per_user;
    }

    /**
     * Hitung diskon berdasarkan subtotal
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_purchase) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            
            // Terapkan max_discount jika ada
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            // Fixed discount
            $discount = $this->value;
        }

        // Diskon tidak boleh lebih dari subtotal
        return min($discount, $subtotal);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    // ============ ACCESSORS ============
    
    /**
     * Format display diskon
     */
    public function getDiscountLabelAttribute(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}
