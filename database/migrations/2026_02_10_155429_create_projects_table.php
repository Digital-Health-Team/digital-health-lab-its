<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Integrasi Spatie: Gunakan JSON, bukan String/Text
            $table->json('name');
            $table->json('description')->nullable();

            $table->dateTime('deadline_global');
            $table->enum('status', ['active', 'on_hold', 'completed'])->default('active');

            // Foreign Key
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
