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
        Schema::create('cash_flow', function (Blueprint $table) {
            $table->id('cash_flow_id');
            $table->enum('cash_flow_category', ['Pemasukan', 'Pengeluaran']);
            $table->enum('cash_flow_income_category', ['Penyewaan', 'Pembayaran Denda'])->nullable();
            $table->enum('cash_flow_expense_category', ['Operasional', 'Non Operasional', 'Pengembalian Deposit', 'Perbaikan Item'])->nullable();
            $table->string('cash_flow_description');
            $table->integer('cash_flow_reference_id');
            $table->integer('cash_flow_balance_before');
            $table->integer('cash_flow_income_total_before')->nullable();
            $table->integer('cash_flow_expense_total_before')->nullable();
            $table->integer('cash_flow_amount');
            $table->integer('cash_flow_expense_total_after')->nullable();
            $table->integer('cash_flow_income_total_after')->nullable();
            $table->integer('cash_flow_balance_after');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flow');
    }
};
