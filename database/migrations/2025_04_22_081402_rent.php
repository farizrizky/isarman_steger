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
        Schema::create('rent', function (Blueprint $table) {
            $table->id('rent_id');
            $table->integer('rent_number')->default(0);
            $table->foreignId('renter_id')->reference('renter_id')->on('renter')->onDelete('cascade');
            $table->enum('rent_duration', ['2 Minggu', 'Per Bulan']);
            $table->integer('rent_total_duration')->nullable();
            $table->date('rent_start_date');
            $table->date('rent_end_date');
            $table->string('rent_project_name');
            $table->text('rent_project_address');
            $table->string('rent_project_phone');
            $table->boolean('rent_is_extension')->default(0);
            $table->integer('rent_id_extend')->nullable();
            $table->integer('rent_transport_price')->default(0);
            $table->integer('rent_deposit')->default(0);
            $table->integer('rent_last_deposit')->default(0);
            $table->integer('rent_discount')->default(0);
            $table->integer('rent_total_price')->default(0);
            $table->integer('rent_total_payment')->default(0);
            $table->enum('rent_status', ['Draft', 'Berjalan', 'Selesai'])->default('Draft');
            $table->enum('rent_status_payment', ['Lunas', 'Belum Bayar'])->default('Belum Bayar');
            $table->enum('rent_payment_method', ['Cash', 'Deposit', 'Deposit & Cash'])->default('Cash');
            $table->text('rent_invoice_photo')->nullable();
            $table->text('rent_receipt_photo')->nullable();
            $table->text('rent_statement_letter_photo')->nullable();
            $table->text('rent_event_report_photo')->nullable();
            $table->text('rent_transport_letter_photo')->nullable();
            $table->foreignId('rent_created_by')->nullable()->reference('id')->on('users')->onDelete('set null');
            $table->foreignId('rent_approved_by')->nullable()->reference('id')->on('users')->onDelete('set null');
            $table->foreignId('rent_processed_by')->nullable()->reference('id')->on('users')->onDelete('set null');
            $table->dateTime('rent_approved_at')->nullable();
            $table->dateTime('rent_processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent');
    }
};
