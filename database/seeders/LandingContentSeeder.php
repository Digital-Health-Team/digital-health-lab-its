<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            // ── Hero ──────────────────────────────────────────────────────────
            ['section_key' => 'hero_description',  'content' => 'Repository dan publikasi inovasi rekayasa medis ITS. Dari riset akademis hingga layanan cetak tiga dimensi presisi tinggi.'],
            ['section_key' => 'hero_cta_text',     'content' => 'Jelajahi Lebih Lanjut'],
            ['section_key' => 'hero_bg_image_url', 'content' => '/assets/images/hero_4.jpg'],

            // ── About ─────────────────────────────────────────────────────────
            ['section_key' => 'about_headline',        'content' => 'Menjembatani Inovasi / Kesehatan dan Rekayasa.'],
            ['section_key' => 'about_headline_accent',  'content' => 'Rekayasa.'],
            ['section_key' => 'about_body_1', 'content' => 'Laboratorium Teknologi Medis ITS berdiri sebagai pionir yang menjembatani dunia riset akademis multidisiplin dengan kebutuhan nyata pada sektor layanan kesehatan nasional. Kami berdedikasi penuh untuk menghadirkan berbagai solusi rekayasa biomedis yang inovatif, presisi, serta diproduksi dengan standar kualitas tinggi yang telah tervalidasi secara klinis, terdokumentasi secara komprehensif, dan siap untuk didistribusikan.'],
            ['section_key' => 'about_body_2', 'content' => 'Melalui sinergi kuat antara peneliti, praktisi medis, dan insinyur profesional, kami bertransformasi menjadi pusat unggulan dalam pengembangan prostetik, implan kustom, serta perangkat medis lainnya. Komitmen utama kami adalah mendobrak batas konvensional teknologi manufaktur medis demi meningkatkan kualitas hidup pasien serta mendorong kemandirian fasilitas kesehatan di seluruh Indonesia.'],
            ['section_key' => 'about_capability_1', 'content' => json_encode([
                'tag' => '3D Innovation',
                'title' => 'Cetak Tiga Dimensi Presisi Tinggi',
                'description' => 'Perancangan dan fabrikasi implan, prostetik, serta model anatomi menggunakan teknologi additive manufacturing dengan material biokompatibel.',
                'image_url' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=400&h=400&fit=crop&crop=center',
                'accent' => '#00A8B5',
            ])],
            ['section_key' => 'about_capability_2', 'content' => json_encode([
                'tag' => 'Custom Order',
                'title' => 'Layanan Desain & Produksi Kustom',
                'description' => 'Layanan berbasis pesanan untuk rumah sakit, klinik, dan institusi pendidikan. Dari konsep digital hingga produk fisik siap pakai.',
                'image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=400&fit=crop&crop=center',
                'accent' => '#FFC72C',
            ])],
            ['section_key' => 'about_capability_3', 'content' => json_encode([
                'tag' => 'Digital Repository',
                'title' => 'Repositori Publikasi Terpusat',
                'description' => 'Sentralisasi jurnal, laporan riset, dan dokumentasi teknis dalam satu platform terbuka yang mendukung akses dan kolaborasi lintas disiplin.',
                'image_url' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=400&fit=crop&crop=center',
                'accent' => '#22D3EE',
            ])],

            // ── Services ──────────────────────────────────────────────────────
            ['section_key' => 'services_heading',    'content' => 'Tiga Pilar Inovasi'],
            ['section_key' => 'services_subheading', 'content' => 'Laboratorium Kami.'],
            ['section_key' => 'services_body', 'content' => 'Eksplorasi layanan riset, purwarupa medis, dan agenda strategis yang menjadi motor penggerak ekosistem inovasi teknologi kesehatan kami.'],
            ['section_key' => 'services_card_1', 'content' => json_encode([
                'title' => 'Produk & Layanan',
                'body' => 'Jelajahi desain 3D, purwarupa medis, dan fabrikasi khusus sesuai kebutuhan Anda.',
                'image_url' => '/assets/images/services/Products%20%26%20Services%20-%20Hand%20PNG%20-%20Landing%20Page.png',
                'gradient' => 'bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900',
            ])],
            ['section_key' => 'services_card_2', 'content' => json_encode([
                'title' => 'Riset & Inovasi',
                'body' => 'Temukan informasi dari koleksi jurnal, publikasi, dan penelitian terbaru kami.',
                'image_url' => '/assets/images/services/Research%20Card%20Journal%20PNG%20-%20Landing%20Page.png',
                'gradient' => 'bg-gradient-to-br from-teal-600 via-teal-800 to-slate-900',
            ])],
            ['section_key' => 'services_card_3', 'content' => json_encode([
                'title' => 'Agenda & Acara',
                'body' => 'Ikuti perkembangan terbaru mengenai acara, webinar, dan berita dari komunitas kami.',
                'image_url' => '/assets/images/services/Events%20-%20Booth%20PNG%20-%20Landing%20Page.png',
                'gradient' => 'bg-gradient-to-br from-rose-700 via-rose-900 to-fuchsia-950',
            ])],

            // ── Collaboration ─────────────────────────────────────────────────
            ['section_key' => 'collaboration_heading',    'content' => 'Dalam Kolaborasi'],
            ['section_key' => 'collaboration_subheading', 'content' => 'Bersama Mitra.'],
            ['section_key' => 'collaboration_body', 'content' => 'Setiap kemitraan terdokumentasi — dari validasi klinis hingga program pelatihan, inilah institusi yang membangun inovasi teknologi kesehatan bersama kami.'],
            ['section_key' => 'collaboration_chapter_1', 'content' => json_encode([
                'name' => 'RSUD Dr. Soetomo',
                'name_line_1' => 'RSUD',
                'name_line_2' => 'Dr. Soetomo',
                'type' => 'Kemitraan Klinis',
                'period' => '2023—Sekarang',
                'description' => 'Validasi klinis purwarupa implan dan prostetik — dari uji model anatomi hingga evaluasi perangkat langsung di lingkungan rumah sakit.',
                'images' => [
                    '/assets/images/projects/clinical_3d_printing.png',
                    '/assets/images/projects/prosthetic_limb_3d.png',
                    '/assets/images/projects/craniosynostosis_model_kit.png',
                ],
            ])],
            ['section_key' => 'collaboration_chapter_2', 'content' => json_encode([
                'name' => 'IDIG RCMED — Universitas Airlangga',
                'name_line_1' => 'IDIG RCMED',
                'name_line_2' => 'Universitas Airlangga',
                'type' => 'Kolaborasi Riset',
                'period' => '2024—Sekarang',
                'description' => 'Riset lintas kampus untuk deteksi dini berbasis citra CT dan analisis sinyal biomedis, bersama laboratorium saudara kami di Universitas Airlangga.',
                'images' => [
                    '/assets/images/projects/craniosynostosis_ct_detection.png',
                    '/assets/images/projects/biomedical_signal_ml.png',
                ],
            ])],
            ['section_key' => 'collaboration_chapter_3', 'content' => json_encode([
                'name' => 'ITS Innovation Hub',
                'name_line_1' => 'ITS',
                'name_line_2' => 'Innovation Hub',
                'type' => 'Inkubasi & Hilirisasi',
                'period' => '2025',
                'description' => 'Inkubasi dan hilirisasi karya laboratorium — memamerkan purwarupa medis pada gelaran tahunan dan mempertemukannya dengan mitra industri.',
                'images' => [
                    '/assets/images/projects/its_innovation_hub_annual_2025.png',
                    '/assets/images/projects/stl_medical_devices.png',
                    '/assets/images/projects/medtech_annual_showcase.png',
                ],
            ])],
            ['section_key' => 'collaboration_chapter_4', 'content' => json_encode([
                'name' => 'Dinas Kesehatan Provinsi Jawa Timur',
                'name_line_1' => 'Dinas Kesehatan',
                'name_line_2' => 'Provinsi Jawa Timur',
                'type' => 'Program Pelatihan',
                'period' => '2024—Sekarang',
                'description' => 'Program pelatihan alat peraga medis dan keselamatan laboratorium bagi tenaga kesehatan di Jawa Timur.',
                'images' => [
                    '/assets/images/projects/spine_teaching_aid.png',
                    '/assets/images/projects/lab_safety_guide.png',
                ],
            ])],

            // ── Wisdom ────────────────────────────────────────────────────────
            ['section_key' => 'wisdom_heading',              'content' => 'We believe in the art of / Sharing Wisdom.'],
            ['section_key' => 'wisdom_heading_accent',       'content' => 'Sharing Wisdom.'],
            ['section_key' => 'wisdom_quote',                'content' => 'If you are planning for a year, sow rice; if you are planning for a decade, plant trees; if you are planning for a lifetime, educate people.'],
            ['section_key' => 'wisdom_attribution_name',     'content' => 'Djoko Kuswanto, S.T., M.Biotech.'],
            ['section_key' => 'wisdom_attribution_role',     'content' => 'Kepala Laboratorium IDIG HTECH'],
            ['section_key' => 'wisdom_attribution_initials', 'content' => 'DK'],

            // ── Articles ──────────────────────────────────────────────────────
            ['section_key' => 'articles_heading',    'content' => 'Dari Meja Riset'],
            ['section_key' => 'articles_subheading', 'content' => 'Publikasi Terbaru.'],
            ['section_key' => 'articles_body', 'content' => 'Jurnal, paper, dan riset terbaru dari laboratorium — terdokumentasi dan terbuka untuk dipelajari.'],
            ['section_key' => 'articles_featured', 'content' => json_encode([
                'title' => 'Design and Fabrication of a Low-Cost 3D-Printed Prosthetic Arm',
                'author' => 'Andi Pratama, Budi Santoso',
                'category' => 'Journals',
                'year' => '2025',
                'href' => '/publications/low-cost-3d-printed-prosthetic-arm',
                'image_url' => '/assets/images/publications/pub_cover_prosthetic_arm.png',
                'image_alt' => 'Sampul publikasi: purwarupa lengan prostetik cetak 3D berbiaya rendah',
            ])],
            ['section_key' => 'articles_entry_1', 'content' => json_encode([
                'title' => 'Parametric Analysis of FDM Print Parameters on Mechanical Properties of Orthotic Devices',
                'author' => 'Dewi Rahayu, Rizky Fauzan',
                'category' => 'Papers',
                'year' => '2025',
                'href' => '/publications/fdm-parametric-analysis-orthotic-mechanical-properties',
            ])],
            ['section_key' => 'articles_entry_2', 'content' => json_encode([
                'title' => 'IoT-Enabled Remote Rehabilitation Monitoring for Elderly Patients',
                'author' => 'Rini Anggraini, Dimas Setiawan',
                'category' => 'Research',
                'year' => '2025',
                'href' => '/publications/iot-remote-rehabilitation-monitoring-elderly',
            ])],
            ['section_key' => 'articles_entry_3', 'content' => json_encode([
                'title' => 'IMU-Based Gait Analysis System for Rehabilitation Monitoring',
                'author' => 'Siti Nurhaliza, Fajar Wicaksono, Ahmad Yani',
                'category' => 'Research',
                'year' => '2025',
                'href' => '/publications/imu-gait-analysis-rehabilitation-monitoring',
            ])],
            ['section_key' => 'articles_entry_4', 'content' => json_encode([
                'title' => 'Topology Optimization of Ankle–Foot Orthosis Using Generative Design',
                'author' => 'Hendri Kusuma, Ayu Lestari',
                'category' => 'Journals',
                'year' => '2024',
                'href' => '/publications/topology-optimization-ankle-foot-orthosis',
            ])],

            // ── CTA ───────────────────────────────────────────────────────────
            ['section_key' => 'cta_heading',         'content' => 'Masih Ingin Tahu'],
            ['section_key' => 'cta_subheading',      'content' => 'Lebih Dalam?'],
            ['section_key' => 'cta_body', 'content' => 'Buat akun untuk mengarsipkan karya Anda, memesan layanan fabrikasi, dan menjadi bagian dari ekosistem inovasi teknologi kesehatan ITS.'],
            ['section_key' => 'cta_primary_label',   'content' => 'Daftar Sekarang'],
            ['section_key' => 'cta_secondary_label', 'content' => 'Jelajahi Produk'],

            // ── Contact ───────────────────────────────────────────────────────
            ['section_key' => 'contact_copy',      'content' => 'Kami terbuka untuk kolaborasi, pertanyaan, dan pemesanan layanan fabrikasi. Tuliskan pesan Anda.'],
            ['section_key' => 'contact_email',     'content' => 'idig@its.ac.id'],
            ['section_key' => 'contact_whatsapp',  'content' => '+62 31 5994251'],
            ['section_key' => 'contact_instagram', 'content' => '@idig.htech'],

            // ── Footer ────────────────────────────────────────────────────────
            ['section_key' => 'footer_tagline',      'content' => 'Repository & Publication of Medical Engineering Technology ITS. Advancing innovation through science.'],
            ['section_key' => 'footer_address',      'content' => "Dept. of Medical Engineering Technology,\nITS Campus, Surabaya, East Java 60111"],
            ['section_key' => 'footer_phone',        'content' => '+62 31 5994251'],
            ['section_key' => 'footer_email',        'content' => 'idig@its.ac.id'],
            ['section_key' => 'footer_youtube_url',  'content' => '#'],
            ['section_key' => 'footer_instagram_url', 'content' => '#'],
            ['section_key' => 'footer_facebook_url', 'content' => '#'],
            ['section_key' => 'footer_linkedin_url', 'content' => '#'],
        ];

        foreach ($sections as $s) {
            DB::table('page_sections')->updateOrInsert(
                ['page_name' => 'landing', 'section_key' => $s['section_key']],
                ['content' => $s['content'], 'updated_by' => 1, 'updated_at' => now()]
            );
        }
    }
}
