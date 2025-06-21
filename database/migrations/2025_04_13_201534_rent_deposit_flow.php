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
        Schema::create('rent_deposit_flow', function (Blueprint $table) {
            $table->id('rent_deposit_flow');
            $table->foreignId('rent_deposit_id')->reference('rent_deposit_id')->on('rent_deposit')->onDelete('cascade');
            $table->enum('rent_deposit_flow_action', ['Masuk', 'Keluar']);
            $table->enum('rent_deposit_flow_release', ['Dikembalikan', 'Pembayaran Denda', 'Pembayaran Tunggakan', 'Pembayaran Sewa Lanjutan'])->nullable();
            $table->integer('rent_deposit_flow_amount');
            $table->integer('rent_deposit_flow_balance');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_deposit_flow');
    }
};
