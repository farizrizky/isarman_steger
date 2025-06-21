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
        Schema::create('item_set', function (Blueprint $table) {
            $table->id('item_set_id');
            $table->foreignId('set_id')->references('set_id')->on('set')->onDelete('cascade');
            $table->foreignId('item_id')->references('item_id')->on('item')->onDelete('cascade');
            $table->integer('item_set_quantity');
            $table->boolean('item_set_optional')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_set');
    }
};
