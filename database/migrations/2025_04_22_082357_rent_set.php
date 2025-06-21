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
        Schema::create('rent_set', function (Blueprint $table) {
            $table->id('rent_set_id');
            $table->foreignId('rent_id')->reference('rent_id')->on('rent')->onDelete('cascade');
            $table->foreignId('set_id')->reference('set_id')->on('set')->onDelete('cascade');
            $table->integer('rent_set_quantity');
            $table->integer('rent_set_price')->nullable();
            $table->integer('rent_set_subtotal_price')->nullable();
            $table->integer('rent_set_total_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_set');
    }
};
