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
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();

            // The person requesting the reimbursement
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

            $table->string('title');
            $table->integer('total_amount'); // Stored in smallest currency unit (IDR)
            $table->string('status')->default('pending'); // pending, approved, paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
