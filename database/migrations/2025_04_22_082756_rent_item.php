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
        Schema::create('rent_item', function (Blueprint $table) {
            $table->id('rent_item_id');
            $table->foreignId('rent_id')->reference('rent_id')->on('rent')->onDelete('cascade');
            $table->foreignId('item_id')->reference('item_id')->on('item')->onDelete('cascade');
            $table->foreignId('rent_set_id')->reference('rent_set_id')->on('rent_set')->onDelete('cascade')->nullable();
            $table->integer('rent_item_quantity');
            $table->integer('rent_item_price')->nullable();
            $table->integer('rent_item_subtotal_price')->nullable();
            $table->integer('rent_item_total_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_item');
    }
};
