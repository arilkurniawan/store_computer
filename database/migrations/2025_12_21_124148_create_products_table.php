<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->bigInteger('price')->unsigned();
            $table->bigInteger('stock')->unsigned()->default(0);
            $table->integer('weight')->unsigned()->default(0);    // gram (untuk ongkir)
            $table->string('image');
            $table->integer('sold_count')->unsigned()->default(0);
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Index
            $table->index('is_recommended');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
