<?php

// database/migrations/xxxx_xx_xx_create_jobdesks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jobdesks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete(); // Staff

            // Kolom Multi-Bahasa
            $table->json('title');
            $table->json('description')->nullable();

            $table->dateTime('deadline_task');

            // Status Flow: Pending -> On Progress -> Review -> Approved / Revision
            $table->enum('status', ['pending', 'on_progress', 'review', 'approved', 'revision'])->default('pending');

            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobdesk_revisions');
        Schema::dropIfExists('jobdesks');
    }
};
