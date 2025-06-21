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
        Schema::create('stock_flow', function (Blueprint $table) {
            $table->id('stock_flow_id');
            $table->foreignId('item_id')->references('item_id')->on('item')->onDelete('cascade');
            $table->enum('stock_flow_action', ['Input','Pembelian', 'Penyewaan', 'Pengembalian', 'Perubahan', 'Penghapusan', 'Perbaikan', 'Kehilangan', 'Kerusakan', 'Bermasalah', 'Selesai Perbaikan', 'Stock Opname']);
            $table->enum('stock_flow_status', ['Masuk', 'Keluar', 'Diubah']);
            $table->integer('stock_flow_total_before');
            $table->integer('stock_flow_available_before');
            $table->integer('stock_flow_decreased_before');
            $table->integer('stock_flow_quantity');
            $table->integer('stock_flow_decreased_after');
            $table->integer('stock_flow_available_after');
            $table->integer('stock_flow_total_after');
            $table->text('stock_flow_reference_model');
            $table->integer('stock_flow_reference_id');
            $table->text('stock_flow_description');
            $table->foreignId('stock_flow_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_flow');
    }
};
