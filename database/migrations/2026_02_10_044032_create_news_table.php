<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    // xxxx_xx_xx_create_news_table.php
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');

            // Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Ringkasan bisa null
            $table->longText('content'); // Menggunakan longText untuk artikel panjang

            // Status & Flags
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_headline')->default(false);
            $table->boolean('is_breaking')->default(false);

            // Counters
            $table->integer('views_count')->default(0);
            $table->integer('monthly_views')->default(0);
            $table->integer('daily_views')->default(0);

            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes (Sesuai Schema untuk optimasi query)
            $table->index(['status', 'published_at']); // Index Berita Terbaru
            $table->index(['status', 'daily_views']);  // Index Trending Harian
            $table->index(['status', 'monthly_views']); // Index Top Monthly
            $table->index(['status', 'is_headline']);   // Index Pilihan Editor
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
