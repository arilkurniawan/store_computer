<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role,'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Panel Filament Anda id-nya 'admin' (lihat AdminPanelProvider)
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        return true;
    }

    // 1 user punya banyak order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function getCartTotalAttribute(): int
    {
        return $this->carts->sum('subtotal');
    }

    public function getFormattedCartTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->cart_total, 0, ',', '.');
    }

    public function getCartCountAttribute(): int
    {
        return $this->carts->sum('quantity');
    }

    public function getCartWeightAttribute(): int
    {
        return $this->carts->sum('total_weight');
    }
}
