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
        Schema::create('purchase_accepted_evidence', function (Blueprint $table) {
            $table->id('purchase_accepted_evidence_id');
            $table->foreignId('purchase_id')->references('purchase_id')->on('purchase')->onDelete('cascade');
            $table->string('purchase_accepted_evidence_courier_name');
            $table->string('purchase_accepted_evidence_vehicle_number');
            $table->text('purchase_accepted_evidence_courier_photo');
            $table->text('purchase_accepted_evidence_courier_identity_photo');
            $table->text('purchase_accepted_evidence_vehicle_identity_photo');
            $table->text('purchase_accepted_evidence_vehicle_photo');
            $table->text('purchase_accepted_evidence_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_accepted_evidence');
    }
};
