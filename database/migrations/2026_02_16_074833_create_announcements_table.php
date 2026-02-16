<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tabel Utama
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_global')->default(false); // [BARU] True = Semua User
            $table->timestamps();
        });

        // 2. Tabel Pivot (Hanya terisi jika is_global = false)
        Schema::create('announcement_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_recipients');
        Schema::dropIfExists('announcements');
    }
};
