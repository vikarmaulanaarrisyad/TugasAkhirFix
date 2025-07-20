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
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('bahan_variant_id');
            $table->unsignedBigInteger('jenis_sablon_id');
            $table->integer('quantity')->default(0);
            $table->string('desain_sablon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropColumn([
                'bahan_variant_id',
                'sablon_id',
                'quantity',
                'desain_sablon',
            ]);
        });
    }
};
