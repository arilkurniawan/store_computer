<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_code')->unique();

            $table->string('name');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city');
            $table->string('post_code');

            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('discount_amount')->default(0);

            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');

            $table->string('snap_token')->nullable();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
