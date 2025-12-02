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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(); // Ürün silinse de kalsın istiyorsan burayı güncellemek gerekir (soft delete)
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Sipariş anındaki fiyat
            $table->decimal('sub_total', 10, 2);
            $table->enum('status', ['waiting', 'cooking', 'ready', 'served'])->default('waiting'); // Mutfak ekranı için
            $table->text('note')->nullable(); // "Soğansız"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
