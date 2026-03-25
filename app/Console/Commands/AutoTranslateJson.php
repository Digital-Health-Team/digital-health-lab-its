<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslateJson extends Command
{
    /**
     * Nama command yang akan diketik di terminal
     *
     * @var string
     */
    protected $signature = 'translate:json {locale : Kode bahasa tujuan (contoh: id, en, ja)}';

    /**
     * Deskripsi command
     *
     * @var string
     */
    protected $description = 'Otomatis menerjemahkan string JSON yang kosong menggunakan Google Translate';

    /**
     * Eksekusi logika command
     */
    public function handle()
    {
        $locale = $this->argument('locale');
        $path = lang_path("{$locale}.json");

        // 1. Cek apakah file JSON (hasil scan kkomelin) sudah ada
        if (!File::exists($path)) {
            $this->error("❌ File lang/{$locale}.json tidak ditemukan!");
            $this->info("💡 Silakan jalankan perintah 'php artisan translatable:export {$locale}' terlebih dahulu.");
            return 1;
        }

        $this->info("Membaca file: {$locale}.json ...");

        // 2. Ambil isi file JSON
        $jsonContent = File::get($path);
        $translations = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("❌ Format JSON pada file {$locale}.json tidak valid.");
            return 1;
        }

        $updatedTranslations = [];
        $totalStrings = count($translations);

        if ($totalStrings === 0) {
            $this->info("Tidak ada string di dalam file.");
            return 0;
        }

        $this->info("🚀 Memulai proses terjemahan ke bahasa: " . strtoupper($locale));

        // Setup Progress Bar
        $bar = $this->output->createProgressBar($totalStrings);
        $bar->start();

        // 3. Inisialisasi Google Translate
        $tr = new GoogleTranslate();
        // Asumsi base language di kodingan Blade Anda adalah Bahasa Inggris (en)
        $tr->setSource('en');
        $tr->setTarget($locale);

        $translatedCount = 0;

        // 4. Looping setiap teks
        foreach ($translations as $key => $value) {
            // Logika: Hanya translate jika value masih sama dengan key (belum diterjemahkan)
            // Atau jika value-nya benar-benar kosong
            if ($key === $value || empty(trim($value))) {
                try {
                    // Jeda 0.2 detik agar tidak diblokir oleh Google (Rate Limit Protection)
                    usleep(200000);

                    $translated = $tr->translate($key);
                    $updatedTranslations[$key] = $translated;
                    $translatedCount++;
                } catch (\Exception $e) {
                    // Fallback jika gagal translate (misal koneksi putus), biarkan sama dengan key
                    $updatedTranslations[$key] = $key;
                }
            } else {
                // Jika sudah ada terjemahan manual sebelumnya, PERTAHANKAN (jangan ditimpa)
                $updatedTranslations[$key] = $value;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // 5. Simpan kembali ke file JSON dengan format yang rapi
        File::put($path, json_encode($updatedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ Selesai! {$translatedCount} string baru telah diterjemahkan dan disimpan ke {$locale}.json.");
        return 0;
    }
}
