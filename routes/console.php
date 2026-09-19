<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

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

// -----------------------------------------------------------------------------
// SCHEDULER: CHATBOT KNOWLEDGE INDEX
// -----------------------------------------------------------------------------

// Rebuilds the RAG index so a price or schedule an admin edited during the day is what
// the chatbot answers with tomorrow. Cheap by design: documents whose source_hash has not
// changed are skipped without spending an embedding call, so a quiet day costs nothing.
//
// 03:00 local time is deliberate. Google's daily quota resets at midnight Pacific, which
// is early afternoon in Surabaya — running before dawn puts the ingest in the half of the
// day that still has budget left, and away from the hours people actually ask questions.
Schedule::command('chatbot:index')
    ->dailyAt('03:00')
    // A slow ingest must not stack: two concurrent runs would double the embedding spend
    // and race each other's pruning pass.
    ->withoutOverlapping()
    ->runInBackground();
