<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        $news = config('lab-news.articles');

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
                'tag' => 'Inovasi 3D',
                'title' => 'Cetak Tiga Dimensi Presisi Tinggi',
                'description' => 'Perancangan dan fabrikasi implan, prostetik, serta model anatomi menggunakan teknologi additive manufacturing dengan material biokompatibel.',
                'image_url' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=400&h=400&fit=crop&crop=center',
                'accent' => '#00A8B5',
            ])],
            ['section_key' => 'about_capability_2', 'content' => json_encode([
                'tag' => 'Pesanan Kustom',
                'title' => 'Layanan Desain & Produksi Kustom',
                'description' => 'Layanan berbasis pesanan untuk rumah sakit, klinik, dan institusi pendidikan. Dari konsep digital hingga produk fisik siap pakai.',
                'image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=400&fit=crop&crop=center',
                'accent' => '#FFC72C',
            ])],
            ['section_key' => 'about_capability_3', 'content' => json_encode([
                'tag' => 'Repositori Digital',
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
            // Mirrors config/lab-news.php: index 0 = featured, 1..4 = entries.
            ['section_key' => 'articles_heading',    'content' => 'Kabar dari Lab'],
            ['section_key' => 'articles_subheading', 'content' => 'Kegiatan Terbaru.'],
            ['section_key' => 'articles_body', 'content' => 'Liputan workshop, kunjungan, dan momen dari balik meja laboratorium — didokumentasikan langsung oleh tim.'],
            ['section_key' => 'articles_featured', 'content' => json_encode($this->articleJson($news[0]))],
            ['section_key' => 'articles_entry_1', 'content' => json_encode($this->articleJson($news[1]))],
            ['section_key' => 'articles_entry_2', 'content' => json_encode($this->articleJson($news[2]))],
            ['section_key' => 'articles_entry_3', 'content' => json_encode($this->articleJson($news[3]))],
            ['section_key' => 'articles_entry_4', 'content' => json_encode($this->articleJson($news[4]))],

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
            ['section_key' => 'footer_tagline',      'content' => 'Repositori & Publikasi Teknologi Rekayasa Kedokteran ITS. Memajukan inovasi melalui sains.'],
            ['section_key' => 'footer_address',      'content' => "Dept. Teknologi Rekayasa Kedokteran,\nKampus ITS, Surabaya, Jawa Timur 60111"],
            ['section_key' => 'footer_phone',        'content' => '+62 31 5994251'],
            ['section_key' => 'footer_email',        'content' => 'idig@its.ac.id'],
            ['section_key' => 'footer_youtube_url',  'content' => '#'],
            ['section_key' => 'footer_instagram_url', 'content' => '#'],
            ['section_key' => 'footer_facebook_url', 'content' => '#'],
            ['section_key' => 'footer_linkedin_url', 'content' => '#'],
        ];

        foreach (array_merge($sections, $this->englishSections($sections)) as $s) {
            DB::table('page_sections')->updateOrInsert(
                ['page_name' => 'landing', 'section_key' => $s['section_key']],
                ['content' => $s['content'], 'updated_by' => 1, 'updated_at' => now()]
            );
        }
    }

    /**
     * English copy, stored under `<key>_en`. LandingPageController layers these over
     * the Indonesian base rows when the locale is `en` (see landingContentForLocale).
     *
     * Text only — images, gradients, phone, email and social URLs are
     * locale-independent and stay on the base key.
     */
    private function englishSections(array $baseSections): array
    {
        $base = collect($baseSections)->pluck('content', 'section_key');

        $en = [
            'hero_description' => 'A repository and publication hub for ITS medical engineering innovation — from academic research to high-precision 3D printing services.',
            'hero_cta_text' => 'Explore Further',

            'about_headline' => 'Bridging Innovation / Health and Engineering.',
            'about_headline_accent' => 'Engineering.',
            'about_body_1' => 'The ITS Medical Technology Laboratory is a pioneer bridging multidisciplinary academic research with the real needs of the national healthcare sector. We are dedicated to delivering biomedical engineering solutions that are innovative, precise, and produced to a high quality standard — clinically validated, comprehensively documented, and ready for distribution.',
            'about_body_2' => "Through close collaboration between researchers, medical practitioners, and professional engineers, we have grown into a centre of excellence for prosthetics, custom implants, and other medical devices. Our commitment is to push past the conventional limits of medical manufacturing — improving patients' quality of life and strengthening the self-reliance of healthcare facilities across Indonesia.",

            'services_heading' => 'Three Pillars of Innovation',
            'services_subheading' => 'Our Laboratory.',
            'services_body' => 'Explore the research services, medical prototypes, and strategic agenda driving our health technology innovation ecosystem.',

            'collaboration_heading' => 'In Collaboration',
            'collaboration_subheading' => 'With Our Partners.',
            'collaboration_body' => 'Every partnership documented — from clinical validation to training programmes, these are the institutions building health technology innovation with us.',

            'articles_heading' => 'News from the Lab',
            'articles_subheading' => 'Latest Activity.',
            'articles_body' => 'Coverage of workshops, visits, and moments from behind the laboratory bench — documented by the team itself.',

            'cta_heading' => 'Want to Explore',
            'cta_subheading' => 'Even Further?',
            'cta_body' => 'Create an account to archive your work, order fabrication services, and join the ITS health technology innovation ecosystem.',
            'cta_primary_label' => 'Register Now',
            'cta_secondary_label' => 'Explore Products',

            'contact_copy' => 'We are open to collaboration, questions, and fabrication service orders. Write us a message.',

            'footer_tagline' => 'Repository & Publication of Medical Engineering Technology ITS. Advancing innovation through science.',
            'footer_address' => "Dept. of Medical Engineering Technology,\nITS Campus, Surabaya, East Java 60111",
        ];

        // JSON blobs: copy is English, media fields are inherited from the base row
        // by the admin form and by the landing components, so only text lives here.
        $blobs = [
            'about_capability_1' => ['tag' => '3D Innovation', 'title' => 'High-Precision 3D Printing', 'description' => 'Design and fabrication of implants, prosthetics, and anatomical models using additive manufacturing with biocompatible materials.'],
            'about_capability_2' => ['tag' => 'Custom Order', 'title' => 'Custom Design & Production Services', 'description' => 'Made-to-order services for hospitals, clinics, and educational institutions — from digital concept to a finished physical product.'],
            'about_capability_3' => ['tag' => 'Digital Repository', 'title' => 'Centralised Publication Repository', 'description' => 'Journals, research reports, and technical documentation in one open platform that supports cross-disciplinary access and collaboration.'],

            'services_card_1' => ['title' => 'Products & Services', 'body' => 'Explore 3D design, medical prototypes, and bespoke fabrication built around your needs.'],
            'services_card_2' => ['title' => 'Research & Innovation', 'body' => 'Browse our collection of journals, publications, and the latest research.'],
            'services_card_3' => ['title' => 'Agenda & Events', 'body' => 'Keep up with events, webinars, and news from our community.'],

            'collaboration_chapter_1' => ['name' => 'RSUD Dr. Soetomo', 'name_line_1' => 'RSUD', 'name_line_2' => 'Dr. Soetomo', 'type' => 'Clinical Partnership', 'period' => '2023—Present', 'description' => 'Clinical validation of implant and prosthetic prototypes — from anatomical model testing through to device evaluation inside the hospital itself.'],
            'collaboration_chapter_2' => ['name' => 'IDIG RCMED — Universitas Airlangga', 'name_line_1' => 'IDIG RCMED', 'name_line_2' => 'Universitas Airlangga', 'type' => 'Research Collaboration', 'period' => '2024—Present', 'description' => 'Cross-campus research into CT-image-based early detection and biomedical signal analysis, alongside our sister laboratory at Universitas Airlangga.'],
            'collaboration_chapter_3' => ['name' => 'ITS Innovation Hub', 'name_line_1' => 'ITS', 'name_line_2' => 'Innovation Hub', 'type' => 'Incubation & Commercialisation', 'period' => '2025', 'description' => "Incubating and commercialising the laboratory's work — showcasing medical prototypes at the annual exhibition and connecting them with industry partners."],
            'collaboration_chapter_4' => ['name' => 'Dinas Kesehatan Provinsi Jawa Timur', 'name_line_1' => 'Dinas Kesehatan', 'name_line_2' => 'Provinsi Jawa Timur', 'type' => 'Training Programme', 'period' => '2024—Present', 'description' => 'Training programmes on medical teaching aids and laboratory safety for healthcare workers across East Java.'],
        ];

        $rows = [];
        foreach ($en as $key => $content) {
            $rows[] = ['section_key' => $key.'_en', 'content' => $content];
        }

        // Layer the English copy over the base blob so media (image_url, gradient,
        // accent, images[]) is carried across rather than reverting to the bundled
        // default when an admin has customised it.
        foreach ($blobs as $key => $payload) {
            $rows[] = [
                'section_key' => $key.'_en',
                'content' => json_encode(array_merge(
                    json_decode($base[$key] ?? '', true) ?? [],
                    $payload
                )),
            ];
        }

        return $rows;
    }

    /**
     * Shape one config/lab-news.php entry into the JSON blob the landing
     * ArticlesSection parses (see buildEntry/buildFeature in
     * resources/js/Features/Landing/Components/ArticlesSection/ArticlesSection.tsx).
     */
    private function articleJson(array $article): array
    {
        return [
            'title' => $article['title'],
            'category' => $article['category'],
            'date' => $article['date'],
            'excerpt' => $article['excerpt'],
            // Relative on purpose: this seeder runs in a CLI context with no
            // HTTP request, so an absolute route() falls back to config('app.url')
            // and can bake in the wrong host/port for whatever server actually serves the app.
            'href' => route('news.show', $article['slug'], absolute: false),
            'image_url' => $article['image'],
            'image_alt' => $article['image_alt'],
        ];
    }
}
