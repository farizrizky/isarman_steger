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
        Schema::create('rent_deposit', function (Blueprint $table) {
            $table->id('rent_deposit_id');
            $table->foreignId('rent_id')->references('rent_id')->on('rent')->onDelete('cascade');
            $table->foreignId('renter_id')->references('renter_id')->on('renter')->onDelete('cascade');
            $table->integer('rent_deposit_balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
