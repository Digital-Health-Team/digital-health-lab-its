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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('category'); // Journals | Papers | Research
            $table->text('abstract')->nullable();
            $table->json('description')->nullable();
            $table->json('keywords')->nullable();
            $table->string('doi')->nullable();
            $table->string('journal')->nullable();
            $table->string('pmid')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('pdf_file_size')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_free_access')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
