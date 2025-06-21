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
        Schema::create('repair', function (Blueprint $table) {
            $table->id('repair_id');
            $table->date('repair_date');
            $table->string('repair_provider');
            $table->string('repair_provider_phone');
            $table->integer('repair_price');
            $table->text('repair_receipt_file')->nullable();
            $table->enum('repair_status', ['Draft', 'Dalam Perbaikan', 'Selesai'])->default('Draft');
            $table->enum('repair_payment_status', ['Belum Dibayar', 'Lunas'])->default('Belum Dibayar');
            $table->dateTime('repair_start_at')->nullable();
            $table->dateTime('repair_paid_at')->nullable();
            $table->dateTime('repair_completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair');
    }
};
