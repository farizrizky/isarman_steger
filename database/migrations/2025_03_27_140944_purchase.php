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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->string('purchase_supplier');
            $table->text('purchase_receipt_photo')->nullable();
            $table->date('purchase_date');
            $table->integer('purchase_total');
            $table->enum('purchase_status', ['Diterima', 'Belum Diterima'])->default('Belum Diterima');
            $table->date('purchase_accepted_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
