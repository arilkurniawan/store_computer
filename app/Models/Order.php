<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'promo_id',
        'invoice',  // ⭐ Sesuai database
        'subtotal',
        'discount',
        'total',    // ⭐ Sesuai database
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',        // ⭐ TAMBAHAN
        'shipping_postal_code', // ⭐ TAMBAHAN
        'shipping_province',
        'payment_proof',
        'notes',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
 * Get status options for dropdown
 */
public static function getStatuses(): array
{
    return [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
}


    // Helper: Untuk kompatibilitas view yang pakai order_number
    public function getOrderNumberAttribute()
    {
        return $this->invoice;
    }

    // Helper: Untuk kompatibilitas view yang pakai total_amount
    public function getTotalAmountAttribute()
    {
        return $this->total;
    }
}
