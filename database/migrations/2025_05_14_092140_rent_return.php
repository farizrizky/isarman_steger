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
        Schema::create('rent_return', function (Blueprint $table) {
            $table->id('rent_return_id');
            $table->foreignId('rent_id')->reference('rent_id')->on('rent')->onDelete('cascade');
            $table->date('rent_return_date');
            $table->boolean('rent_return_is_complete');
            $table->integer('rent_return_fine_lost')->default(0);
            $table->integer('rent_return_fine_damaged')->default(0);
            $table->integer('rent_return_total_fine')->default(0);
            $table->integer('rent_return_dispensation_fine')->default(0);
            $table->integer('rent_return_grand_total_fine')->default(0);
            $table->integer('rent_return_deposit_saldo')->default(0);
            $table->integer('rent_return_deposit_remains')->default(0);
            $table->integer('rent_return_total_payment')->default(0);
            $table->enum('rent_return_receipt_status', ['Klaim Ganti Rugi', 'Pengembalian Deposit', 'Nihil'])->nullable();
            $table->enum('rent_return_payment_status', ['Lunas', 'Belum Bayar', 'Pending'])->default('Belum Bayar');
            $table->enum('rent_return_payment_method', ['Cash', 'Deposit', 'Deposit & Cash'])->nullable();
            $table->enum('rent_return_status', ['Selesai', 'Lanjut'])->default('Selesai');
            $table->text('rent_return_invoice_photo')->nullable();
            $table->text('rent_return_receipt_photo')->nullable();
            $table->foreignId('rent_return_processed_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('rent_return_finished_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->dateTime('rent_return_finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_return');
    }
};
