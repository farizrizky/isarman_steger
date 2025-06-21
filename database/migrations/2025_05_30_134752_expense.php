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
        Schema::create('expense', function (Blueprint $table) {
            $table->id('expense_id');
            $table->date('expense_date');
            $table->enum('expense_category', ['Operasional', 'Non Operasional']);
            $table->string('expense_description');
            $table->integer('expense_amount');
            $table->text('expense_file')->nullable();
            $table->enum('expense_status', ['Draft', 'Diposting'])->default('Draft');
            $table->dateTime('expense_posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};
