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
        Schema::create('bahan_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bahan_id');
            $table->string('size');
            $table->integer('price')->default(0);
            $table->integer('min_quantity_discount')->nullable(); // Minimal beli untuk diskon
            $table->decimal('discount_percent', 5, 2)->nullable(); // Diskon dalam persen
            $table->string('image')->nullable(); // gambar kaos polosan (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_variants');
    }
};
