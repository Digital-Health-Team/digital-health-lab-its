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
                'category' => 'Competition',
                'theme_en' => 'Post-Pandemic Health Technology Innovation',
                'subtitle_en' => 'The first edition, born out of what hospitals actually needed during the pandemic.',
                'description_en' => 'Innovatech Medika 2021 brought ITS Health Technology students together with hospital practitioners to design assistive devices that could be produced quickly in a crisis.

Ten teams worked three full days in the Health Technology Laboratory, from framing the clinical problem through to 3D-printed prototypes tested directly by physiotherapists.',
                'location_en' => 'Health Technology Laboratory — ITS Sukolilo', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2022', 'year' => 2022,
                'theme' => 'Digitalisasi Fasilitas Kesehatan Indonesia',
                'subtitle' => 'Dari rekam medis kertas ke sistem yang benar-benar dipakai perawat.',
                'description' => "Edisi kedua memindahkan fokus dari perangkat keras ke sistem. Tim diminta merancang solusi digital yang bisa dijalankan puskesmas dengan koneksi terbatas.\n\nJuri dari Dinas Kesehatan Kota Surabaya menilai langsung kelayakan penerapan, bukan sekadar kelengkapan fitur.",
                'thumbnail' => '/assets/images/projects/patient_monitoring_iot.png',
                'starts' => Carbon::create(2022, 9, 20, 8), 'ends' => Carbon::create(2022, 9, 22, 17),
                'location' => 'Gedung Riset Center — ITS Sukolilo',
                'category' => 'Competition',
                'theme_en' => 'Digitalising Indonesian Health Facilities',
                'subtitle_en' => 'From paper medical records to a system nurses actually use.',
                'description_en' => 'The second edition shifted focus from hardware to systems. Teams were asked to design digital solutions a community health centre could run on a limited connection.

Judges from the Surabaya City Health Office assessed real-world viability rather than feature completeness.',
                'location_en' => 'Research Center Building — ITS Sukolilo', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2023', 'year' => 2023,
                'theme' => 'Alat Bantu Disabilitas Berbasis 3D Printing',
                'subtitle' => 'Purwarupa yang diukur, dicetak, dan dicoba pengguna dalam satu pekan.',
                'description' => "Tim bekerja berpasangan dengan pengguna alat bantu dari komunitas disabilitas Surabaya. Setiap purwarupa harus melewati sesi uji pakai sebelum dinilai.\n\nDelapan dari sepuluh rancangan diteruskan ke tahap produksi terbatas oleh laboratorium.",
                'thumbnail' => '/assets/images/projects/prosthetic_limb_3d.png',
                'starts' => Carbon::create(2023, 9, 11, 8), 'ends' => Carbon::create(2023, 9, 15, 17),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Competition',
                'theme_en' => '3D-Printed Disability Assistive Devices',
                'subtitle_en' => 'Prototypes measured, printed, and user-tested inside one week.',
                'description_en' => "Teams worked in pairs with assistive-device users from Surabaya's disability community. Every prototype had to pass a wear-test session before being judged.

Eight of the ten designs went on to limited production by the laboratory.",
                'location_en' => 'Health Technology Laboratory — ITS Sukolilo', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2024', 'year' => 2024,
                'theme' => 'Sensor Wearable untuk Monitoring Pasien',
                'subtitle' => 'Perangkat yang harus tetap akurat setelah dipakai tujuh hari berturut-turut.',
                'description' => "Tantangan edisi ini bukan membuat sensor bekerja, melainkan membuatnya tetap bekerja. Setiap tim menjalani uji pakai berkelanjutan selama seminggu sebelum presentasi akhir.\n\nHasilnya menjadi dasar dua publikasi laboratorium pada tahun berikutnya.",
                'thumbnail' => '/assets/images/projects/biomedical_signal_ml.png',
                'starts' => Carbon::create(2024, 10, 7, 8), 'ends' => Carbon::create(2024, 10, 11, 17),
                'location' => 'Gedung Riset Center — ITS Sukolilo',
                'category' => 'Competition',
                'theme_en' => 'Wearable Sensors for Patient Monitoring',
                'subtitle_en' => 'Devices that had to stay accurate after seven days of continuous wear.',
                'description_en' => 'The challenge this edition was not getting a sensor to work, but keeping it working. Every team ran a week-long continuous wear test before the final presentation.

The results became the basis for two laboratory publications the following year.',
                'location_en' => 'Research Center Building — ITS Sukolilo', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2025', 'year' => 2025,
                'theme' => 'Kecerdasan Buatan dalam Diagnostik Medis',
                'subtitle' => 'Model yang dinilai bukan dari akurasi, tapi dari apa yang dilakukan saat salah.',
                'description' => "Edisi 2025 menuntut setiap tim menjelaskan keputusan modelnya kepada dokter, bukan hanya melaporkan metrik.\n\nDataset disediakan bersama RSUD Dr. Soetomo dengan anotasi radiolog, dan seluruh purwarupa dievaluasi pada kasus yang belum pernah dilihat model.",
                'thumbnail' => '/assets/images/projects/craniosynostosis_detection.png',
                'starts' => Carbon::create(2025, 10, 6, 8), 'ends' => Carbon::create(2025, 10, 10, 17),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Competition',
                'theme_en' => 'Artificial Intelligence in Medical Diagnostics',
                'subtitle_en' => 'Models judged not on accuracy, but on what they do when they are wrong.',
                'description_en' => "The 2025 edition required every team to explain its model's decisions to a clinician, not merely report metrics.

The dataset was provided jointly with RSUD Dr. Soetomo with radiologist annotations, and every prototype was evaluated on cases the model had never seen.",
                'location_en' => 'Health Technology Laboratory — ITS Sukolilo', 'featured' => false, 'registration' => null,
            ],
            [
                'name' => 'Innovatech Medika 2026', 'year' => 2026,
                'theme' => 'Manufaktur Aditif untuk Perawatan Titik Layanan',
                'subtitle' => 'Sedang berlangsung — dua belas tim mencetak di lantai laboratorium minggu ini.',
                'description' => "Edisi keenam memindahkan produksi ke titik layanan: alat harus dirancang, dicetak, dan diserahkan dalam radius satu fasilitas kesehatan.\n\nTim bekerja langsung di lantai laboratorium dengan enam printer FDM dan satu printer resin, didampingi teknisi laboratorium sepanjang acara.",
                'thumbnail' => '/assets/images/projects/medtech_annual_showcase.png',
                'starts' => now()->subDay()->setTime(8, 0), 'ends' => now()->addDays(2)->setTime(17, 0),
                'location' => 'Laboratorium Teknologi Kesehatan — ITS Sukolilo',
                'category' => 'Competition',
                'theme_en' => 'Additive Manufacturing for Point-of-Care Treatment',
                'subtitle_en' => 'Running now — twelve teams printing on the laboratory floor this week.',
                'description_en' => 'The sixth edition moves production to the point of care: a device must be designed, printed, and handed over within the radius of a single health facility.

Teams work directly on the laboratory floor with six FDM printers and one resin printer, supported by lab technicians throughout.',
                'location_en' => 'Health Technology Laboratory — ITS Sukolilo', 'featured' => true,
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
                'category' => 'Exhibition',
                'theme_en' => 'The Laboratory Open to the Public and Industry Partners',
                'subtitle_en' => 'One day seeing first-hand how medical devices are designed and printed.',
                'description_en' => 'Open Day opens the whole laboratory to students, clinicians, and industry partners: 3D scanning, the print room, and the mechanical test bench.

The programme includes an orthosis printed from scratch, a Q&A with researchers, and an exhibition of student work from the five previous Innovatech editions.',
                'location_en' => 'Research Center Building, 3rd Floor — ITS Sukolilo', 'featured' => false,
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

        // Parallel to $teamNames — backs the `teams.name_en` overlay column.
        $teamNamesEn = [
            ['Nusantara Prosthetics Team', 'Independent Rehab Team'],
            ['Smart Biomed Team', 'Medical Signal Team'],
            ['Orthosis Printing Team', 'Biomedical Fab Team'],
            ['Vital Sensor Team', 'IoHealth Team'],
            ['Diagnostic AI Team', 'Medical Vision Team'],
            ['Clinical Printing Team', 'Rapid Orthotics Team'],
            ['Lab Exhibition Team', 'Print Demo Team'],
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

        // Parallel to $projectTitles — backs the `projects.title_en` overlay column.
        $projectTitlesEn = [
            ['Design and Build of a Low-Cost Finger Prosthesis', 'Passive Wrist Rehabilitation System'],
            ['IoT Platform for Outpatient Monitoring', 'EEG Signal Processing for Seizure Detection'],
            ['Lightweight 3D-Printed Ankle-Foot Orthosis', 'CT-Based Surgical Jig for the Spine'],
            ['ESP32-Based Heart Rate Monitoring Band', 'Wireless Blood Pressure Alert System'],
            ['Brain Tumour Classification via Deep Learning', 'Automatic Chest CT Segmentation'],
            ['Rapid Point-of-Care Printed Wrist Splint', 'Single-Visit Modular Prosthetic Socket'],
            ['3D-Printed Anatomical Model for Public Education', 'Limb 3D Scanning Demonstration Station'],
        ];

        foreach ($eventThemes as $e => $theme) {
            $eventId = DB::table('events')->insertGetId([
                'name' => $theme['name'],
                'slug' => Str::slug($theme['name']),
                'year' => $theme['year'],
                'theme_title' => $theme['theme'],
                'theme_title_en' => $theme['theme_en'],
                'subtitle' => $theme['subtitle'],
                'subtitle_en' => $theme['subtitle_en'],
                'description' => $theme['description'],
                'description_en' => $theme['description_en'],
                'thumbnail_url' => $theme['thumbnail'],
                'starts_at' => $theme['starts'],
                'ends_at' => $theme['ends'],
                'location' => $theme['location'],
                'location_en' => $theme['location_en'],
                'category' => $theme['category'],
                'registration_url' => $theme['registration'],
                'is_featured' => $theme['featured'],
                'is_active' => true,
            ]);

            for ($t = 0; $t < 2; $t++) {
                $teamId = DB::table('teams')->insertGetId([
                    'event_id' => $eventId,
                    'name' => $teamNames[$e][$t],
                    'name_en' => $teamNamesEn[$e][$t],
                    'course_name' => 'Perancangan Alat Medis',
                    'course_name_en' => 'Medical Device Design',
                    'created_at' => now(),
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
                    'team_id' => $teamId,
                    'title' => $projectTitles[$e][$t],
                    'title_en' => $projectTitlesEn[$e][$t],
                    'category' => '3d_products', 'status' => 'approved',
                    'validated_by' => 1, 'created_at' => Carbon::create($eventThemes[$e]['year'], 6, 15),
                ]);
            }
        }
    }
}
