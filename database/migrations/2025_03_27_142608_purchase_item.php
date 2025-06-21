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
        Schema::create('purchase_item', function (Blueprint $table) {
            $table->id('purchase_item_id');
            $table->foreignId('purchase_id')->references('purchase_id')->on('purchase')->onDelete('cascade');
            $table->foreignId('item_id')->references('item_id')->on('item')->onDelete('cascade');
            $table->integer('purchase_item_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_item');
    }
};
