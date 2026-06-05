<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Siapa yang melakukan aksi (bisa null jika dilakukan oleh sistem/cron)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Jenis aksi: 'created', 'updated', 'deleted', 'read'
            $table->string('action');

            // Polymorphic relation: mencatat model apa yang diubah (contoh: App\Models\ServiceBooking, ID: 15)
            $table->morphs('loggable');

            // Menyimpan perubahan data dalam format JSON
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();

            // Informasi tambahan untuk keamanan
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
