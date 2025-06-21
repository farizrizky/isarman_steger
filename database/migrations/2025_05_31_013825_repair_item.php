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
        Schema::create('repair_item', function (Blueprint $table) {
            $table->id('repair_item_id');
            $table->foreignId('repair_id')->reference('repair_id')->on('repair')->onDelete('cascade');
            $table->foreignId('item_id')->reference('item_id')->on('item')->onDelete('cascade');
            $table->integer('repair_item_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_item');
    }
};
