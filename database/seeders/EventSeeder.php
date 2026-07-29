<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Events, their participating teams, and the projects those teams produced.
 *
 * Re-runnable on its own (`php artisan db:seed --class=EventSeeder`) — it clears
 * the whole events → teams → projects chain first, like TrainingSeeder does.
 * It depends on users 4-13 existing, so DatabaseSeeder calls it after the users.
 */
class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('projects')->delete();
        DB::table('team_members')->delete();
        DB::table('teams')->delete();
        DB::table('events')->delete();

        echo "Seeding Events & Projects...\n";

        // Every edition stays public — the archive of past events is the point of
        // /events, not just the one that happens to be running.
        $eventThemes = [
            [
                'name' => 'Innovatech Medika 2021', 'year' => 2021,
                'theme' => 'Inovasi Teknologi Kesehatan Pasca-Pandemi',
                'subtitle' => 'Edisi perdana yang lahir dari kebutuhan nyata rumah sakit selama pandemi.',
                'description' => "Innovatech Medika 2021 mempertemukan mahasiswa Teknologi Kesehatan ITS dengan praktisi rumah sakit untuk merancang alat bantu yang bisa diproduksi cepat di masa krisis.\n\nSepuluh tim bekerja selama tiga hari penuh di Laboratorium Teknologi Kesehatan, dari perumusan masalah klinis sampai purwarupa cetak 3D yang diuji langsung oleh fisioterapis.",
                'thumbnail' => '/assets/images/projects/clinical_3d_printing.png',
                'starts' => Carbon::create(2021, 9, 14, 8), 'ends' => Carbon::create(2021, 9, 16, 17),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Kompetisi', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2022', 'year' => 2022,
                'theme' => 'Digitalisasi Fasilitas Kesehatan Indonesia',
                'subtitle' => 'Dari rekam medis kertas ke sistem yang benar-benar dipakai perawat.',
                'description' => "Edisi kedua memindahkan fokus dari perangkat keras ke sistem. Tim diminta merancang solusi digital yang bisa dijalankan puskesmas dengan koneksi terbatas.\n\nJuri dari Dinas Kesehatan Kota Surabaya menilai langsung kelayakan penerapan, bukan sekadar kelengkapan fitur.",
                'thumbnail' => '/assets/images/projects/patient_monitoring_iot.png',
                'starts' => Carbon::create(2022, 9, 20, 8), 'ends' => Carbon::create(2022, 9, 22, 17),
                'location' => 'Gedung Riset Center — ITS Sukolilo',
                'category' => 'Kompetisi', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2023', 'year' => 2023,
                'theme' => 'Alat Bantu Disabilitas Berbasis 3D Printing',
                'subtitle' => 'Purwarupa yang diukur, dicetak, dan dicoba pengguna dalam satu pekan.',
                'description' => "Tim bekerja berpasangan dengan pengguna alat bantu dari komunitas disabilitas Surabaya. Setiap purwarupa harus melewati sesi uji pakai sebelum dinilai.\n\nDelapan dari sepuluh rancangan diteruskan ke tahap produksi terbatas oleh laboratorium.",
                'thumbnail' => '/assets/images/projects/prosthetic_limb_3d.png',
                'starts' => Carbon::create(2023, 9, 11, 8), 'ends' => Carbon::create(2023, 9, 15, 17),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Kompetisi', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2024', 'year' => 2024,
                'theme' => 'Sensor Wearable untuk Monitoring Pasien',
                'subtitle' => 'Perangkat yang harus tetap akurat setelah dipakai tujuh hari berturut-turut.',
                'description' => "Tantangan edisi ini bukan membuat sensor bekerja, melainkan membuatnya tetap bekerja. Setiap tim menjalani uji pakai berkelanjutan selama seminggu sebelum presentasi akhir.\n\nHasilnya menjadi dasar dua publikasi laboratorium pada tahun berikutnya.",
                'thumbnail' => '/assets/images/projects/biomedical_signal_ml.png',
                'starts' => Carbon::create(2024, 10, 7, 8), 'ends' => Carbon::create(2024, 10, 11, 17),
                'location' => 'Gedung Riset Center — ITS Sukolilo',
                'category' => 'Kompetisi', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2025', 'year' => 2025,
                'theme' => 'Kecerdasan Buatan dalam Diagnostik Medis',
                'subtitle' => 'Model yang dinilai bukan dari akurasi, tapi dari apa yang dilakukan saat salah.',
                'description' => "Edisi 2025 menuntut setiap tim menjelaskan keputusan modelnya kepada dokter, bukan hanya melaporkan metrik.\n\nDataset disediakan bersama RSUD Dr. Soetomo dengan anotasi radiolog, dan seluruh purwarupa dievaluasi pada kasus yang belum pernah dilihat model.",
                'thumbnail' => '/assets/images/projects/craniosynostosis_detection.png',
                'starts' => Carbon::create(2025, 10, 6, 8), 'ends' => Carbon::create(2025, 10, 10, 17),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Kompetisi', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2026', 'year' => 2026,
                'theme' => 'Manufaktur Aditif untuk Perawatan Titik Layanan',
                'subtitle' => 'Sedang berlangsung — dua belas tim mencetak di lantai laboratorium minggu ini.',
                'description' => "Edisi keenam memindahkan produksi ke titik layanan: alat harus dirancang, dicetak, dan diserahkan dalam radius satu fasilitas kesehatan.\n\nTim bekerja langsung di lantai laboratorium dengan enam printer FDM dan satu printer resin, didampingi teknisi laboratorium sepanjang acara.",
                'thumbnail' => '/assets/images/projects/medtech_annual_showcase.png',
                'starts' => now()->subDay()->setTime(8, 0), 'ends' => now()->addDays(2)->setTime(17, 0),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Kompetisi', 'featured' => true,
                'registration' => 'https://its.ac.id/tekkes/innovatech-2026',
            ],
            [
                'name' => 'MedTech Open Day 2026', 'year' => 2026,
                'theme' => 'Laboratorium Terbuka untuk Publik dan Mitra Industri',
                'subtitle' => 'Satu hari melihat langsung bagaimana alat kesehatan dirancang dan dicetak.',
                'description' => "Open Day membuka seluruh fasilitas laboratorium untuk pelajar, klinisi, dan mitra industri: pemindaian 3D, ruang cetak, dan meja uji mekanik.\n\nAgenda mencakup demonstrasi pencetakan ortosis dari nol, sesi tanya jawab dengan peneliti, dan pameran karya mahasiswa dari lima edisi Innovatech sebelumnya.",
                'thumbnail' => '/assets/images/projects/its_innovation_hub_annual_2025.png',
                'starts' => now()->addDays(52)->setTime(9, 0), 'ends' => now()->addDays(52)->setTime(16, 0),
                'location' => 'Gedung Riset Center Lt. 3 — ITS Sukolilo',
                'category' => 'Pameran', 'featured' => false,
                'registration' => 'https://its.ac.id/tekkes/open-day-2026',
            ],
        ];

        $teamNames = [
            ['Tim Prostetik Nusantara', 'Tim Rehab Mandiri'],
            ['Tim Biomed Cerdas', 'Tim Signal Medis'],
            ['Tim Print Ortosis', 'Tim Fab Biomedik'],
            ['Tim Sensor Vital', 'Tim IoHealth'],
            ['Tim AI Diagnostik', 'Tim Vision Medis'],
            ['Tim Cetak Klinis', 'Tim Ortotik Cepat'],
            ['Tim Pameran Lab', 'Tim Demo Cetak'],
        ];

        $projectTitles = [
            ['Rancang Bangun Prostetik Jari Tangan Low-Cost', 'Sistem Rehabilitasi Pasif Pergelangan Tangan'],
            ['Platform IoT Monitoring Pasien Rawat Jalan', 'Pengolahan Sinyal EEG untuk Deteksi Kejang'],
            ['Ortosis Ankle-Foot Cetak 3D Berbobot Ringan', 'Jig Bedah Berbasis CT-Scan untuk Tulang Belakang'],
            ['Gelang Pemantau Detak Jantung Berbasis ESP32', 'Sistem Alert Tekanan Darah Nirkabel'],
            ['Klasifikasi Tumor Otak via Deep Learning', 'Segmentasi Otomatis CT Scan Dada'],
            ['Bidai Pergelangan Cetak Cepat di Titik Layanan', 'Soket Prostetik Modular Sekali Kunjungan'],
            ['Peraga Anatomi Cetak 3D untuk Edukasi Publik', 'Stasiun Demonstrasi Pemindaian 3D Anggota Gerak'],
        ];

        foreach ($eventThemes as $e => $theme) {
            $eventId = DB::table('events')->insertGetId([
                'name' => $theme['name'],
                'slug' => Str::slug($theme['name']),
                'year' => $theme['year'],
                'theme_title' => $theme['theme'],
                'subtitle' => $theme['subtitle'],
                'description' => $theme['description'],
                'thumbnail_url' => $theme['thumbnail'],
                'starts_at' => $theme['starts'],
                'ends_at' => $theme['ends'],
                'location' => $theme['location'],
                'category' => $theme['category'],
                'registration_url' => $theme['registration'],
                'is_featured' => $theme['featured'],
                'is_active' => true,
            ]);

            for ($t = 0; $t < 2; $t++) {
                $teamId = DB::table('teams')->insertGetId([
                    'event_id' => $eventId, 'name' => $teamNames[$e][$t],
                    'course_name' => 'Perancangan Alat Medis', 'created_at' => now(),
                ]);

                $leader = 4 + (($e * 2 + $t) % 8);
                $member = 4 + (($e * 2 + $t + 1) % 10);
                if ($leader === $member) {
                    $member = ($member % 10) + 4;
                }
                DB::table('team_members')->insert([
                    ['team_id' => $teamId, 'user_id' => $leader, 'role_in_team' => 'Ketua'],
                    ['team_id' => $teamId, 'user_id' => $member, 'role_in_team' => 'Anggota'],
                ]);

                DB::table('projects')->insert([
                    'team_id' => $teamId, 'title' => $projectTitles[$e][$t],
                    'category' => '3d_products', 'status' => 'approved',
                    'validated_by' => 1, 'created_at' => Carbon::create($eventThemes[$e]['year'], 6, 15),
                ]);
            }
        }
    }
}
