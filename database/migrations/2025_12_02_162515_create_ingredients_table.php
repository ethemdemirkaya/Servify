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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kıyma, Domates, Kola Şişe
            $table->enum('unit', ['kg', 'g', 'l', 'ml', 'piece']); // Birim
            $table->decimal('stock_quantity', 10, 3)->default(0); // 12.500 kg
            $table->decimal('alert_limit', 10, 3)->default(1); // 1 kg kalınca uyar
            $table->decimal('cost_price', 10, 2)->default(0); // Birim maliyet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
