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
        Schema::create('lab_team_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('lab_team_sections')->cascadeOnDelete();
            $table->boolean('is_leader')->default(false);
            $table->string('name_full');
            $table->string('display_line_1');
            $table->string('display_line_2');
            $table->string('role_id');
            $table->string('role_en');
            $table->text('bio')->nullable();
            $table->string('initials', 4);
            $table->string('photo_url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_team_people');
    }
};
