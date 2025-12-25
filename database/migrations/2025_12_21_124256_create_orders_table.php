<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            
            // ============ PRICING ============
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0); 
            $table->decimal('total_amount', 12, 2);
            $table->decimal('total_weight', 10, 2)->default(0);
            
            // ============ COUPON ============
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable();
            
            // ============ PAYMENT MANUAL ============
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'rejected'])->default('unpaid');
            $table->string('payment_proof')->nullable();
            $table->text('payment_notes')->nullable();
            
            // ============ SHIPPING INFO ============
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_province')->nullable();
            $table->string('shipping_postal_code');
            
            // ============ COURIER ============
            $table->string('courier')->nullable();
            $table->string('courier_service')->nullable();
            $table->string('tracking_number')->nullable();
            
            // ============ NOTES & TIMESTAMPS ============
            $table->text('notes')->nullable();                     
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
