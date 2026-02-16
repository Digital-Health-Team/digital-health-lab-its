<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tabel Absensi (Check In/Out)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Laporan Per-Jobdesk (Bridge: Absensi <-> Tugas)
        Schema::create('jobdesk_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete();
            $table->foreignId('jobdesk_id')->constrained('jobdesks'); // Tugas mana yg dikerjakan

            // Opsi: Jika ini pengerjaan revisi, link ke thread revisi spesifik
            $table->foreignId('revision_thread_id')->nullable()->constrained('revision_threads');

            $table->string('status_at_report'); // on_progress, completed
            $table->timestamps();
        });

        // 3. Tabel Detail Laporan (Isi Teks)
        Schema::create('report_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobdesk_report_id')->constrained('jobdesk_reports')->cascadeOnDelete();
            $table->text('content'); // Deskripsi: "Saya slicing bagian header..."
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_details');
        Schema::dropIfExists('jobdesk_reports');
        Schema::dropIfExists('attendances');
    }
};
