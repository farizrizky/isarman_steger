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
        Schema::create('rent_return_item', function (Blueprint $table) {
            $table->id('rent_return_item_id');
            $table->foreignId('rent_return_id')->reference('rent_return_id')->on('rent_return')->onDelete('cascade');
            $table->foreignId('item_id')->reference('item_id')->on('item')->onDelete('cascade');
            $table->integer('rent_return_item_lost');
            $table->integer('rent_return_item_damaged');
            $table->integer('rent_return_item_fine_lost')->default(0);
            $table->integer('rent_return_item_fine_damaged')->default(0);
            $table->integer('rent_return_item_total_fine')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_return_item');
    }
};
