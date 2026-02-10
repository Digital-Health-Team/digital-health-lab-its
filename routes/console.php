<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// -----------------------------------------------------------------------------
// SCHEDULER: RESET VIEW COUNTERS
// -----------------------------------------------------------------------------

// 1. Reset Daily Views (Setiap Hari jam 00:00)
Schedule::call(function () {
    // Update query langsung ke database (lebih cepat daripada Eloquent loop)
    DB::table('news')->update(['daily_views' => 0]);

    // Catat log agar kita tahu scheduler berjalan
    Log::info('SCHEDULER: Daily views berhasil di-reset ke 0.');
})->daily(); // Default jam 00:00

// 2. Reset Monthly Views (Setiap Tanggal 1 jam 00:00)
Schedule::call(function () {
    DB::table('news')->update(['monthly_views' => 0]);

    Log::info('SCHEDULER: Monthly views berhasil di-reset ke 0.');
})->monthly(); // Default tanggal 1 jam 00:00

// 3. (Opsional) Hapus cache view jika menggunakan cache driver
// Schedule::command('cache:clear')->daily();
