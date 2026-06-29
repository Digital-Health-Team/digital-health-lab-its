<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->integer('price')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->dateTime('date');
            $table->string('location')->nullable();
            $table->integer('max_participants')->nullable();
            $table->string('level')->default('Beginner');
            $table->string('duration')->nullable();
            $table->string('language')->default('Indonesian');
            $table->string('instructor_name');
            $table->string('instructor_title')->nullable();
            $table->text('instructor_bio')->nullable();
            $table->string('instructor_avatar_url')->nullable();
            $table->json('what_you_will_learn')->nullable();
            $table->json('includes')->nullable();
            $table->json('curriculum')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->string('category')->nullable();
            $table->unsignedInteger('extra_tags')->default(0);
            $table->unsignedInteger('views')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
