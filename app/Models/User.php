<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============ FILAMENT ============
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    // ============ RELASI ============
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ============ HELPER METHODS ============
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Hitung total item di keranjang
     */
    public function getCartCountAttribute(): int
    {
        return $this->carts()->sum('quantity');
    }

    /**
     * Hitung total harga keranjang
     */
    public function getCartTotalAttribute(): float
    {
        return $this->carts()->with('product')->get()->sum(function ($cart) {
            return $cart->quantity * $cart->product->price;
        });
    }
}
