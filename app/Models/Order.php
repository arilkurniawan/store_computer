<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'total_weight',
        'promo_id',
        'promo_code',
        'payment_method',
        'payment_status',
        'payment_proof',
        'payment_notes',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'courier',
        'courier_service',
        'tracking_number',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // ============ RELASI ============
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

    // ============ SCOPES ============
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopeWaitingConfirmation($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // ============ HELPER METHODS ============
    
    /**
     * Generate order number unik
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Konfirmasi pembayaran (oleh admin)
     */
    public function confirmPayment(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Increment promo usage jika pakai promo
        if ($this->promo) {
            $this->promo->incrementUsage();
        }
    }

    /**
     * Reject pembayaran (oleh admin)
     */
    public function rejectPayment(string $reason = null): void
    {
        $this->update([
            'payment_status' => 'rejected',
            'payment_notes' => $reason,
        ]);
    }

    /**
     * Set status processing (sedang dikemas)
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Set status shipped
     */
    public function markAsShipped(string $trackingNumber = null): void
    {
        $this->update([
            'status' => 'shipped',
            'tracking_number' => $trackingNumber,
            'shipped_at' => now(),
        ]);
    }

    /**
     * Set status delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    // ============ ACCESSORS ============
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Dikemas',
            'shipped' => 'Dalam Pengiriman',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'Belum Bayar',
            'pending' => 'Menunggu Konfirmasi',
            'paid' => 'Lunas',
            'rejected' => 'Ditolak',
            default => $this->payment_status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'info',
            'processing' => 'primary',
            'shipped' => 'secondary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
