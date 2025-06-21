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
        Schema::create('stock', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('item_id')->references('item_id')->on('item')->onDelete('cascade');
            $table->integer('stock_total');
            $table->integer('stock_available');
            $table->integer('stock_decreased')->default(0);
            $table->integer('stock_rented')->default(0);
            $table->integer('stock_damaged')->default(0);
            $table->integer('stock_on_repair')->default(0);
            $table->integer('stock_lost')->default(0);
            $table->integer('stock_dispute')->default(0);
            $table->integer('stock_unknown')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
