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
        Schema::create('renter', function (Blueprint $table) {
            $table->id('renter_id');
            $table->string('renter_name');
            $table->string('renter_identity');
            $table->string('renter_phone');
            $table->text('renter_address');
            $table->string('renter_job');
            $table->text('renter_identity_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renter');
    }
};
