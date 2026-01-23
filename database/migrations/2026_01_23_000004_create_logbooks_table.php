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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_period_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->text('activity');
            $table->string('proof_file_path')->nullable();
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->text('feedback')->nullable();
            $table->timestamps();

            // Enforce: One logbook entry per day per internship period
            $table->unique(['internship_period_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
