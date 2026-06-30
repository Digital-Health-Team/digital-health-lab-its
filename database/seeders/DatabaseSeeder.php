<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "Menyiapkan Database...\n";

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ==========================================
        // 1. ROLES
        // ==========================================
        echo "Seeding Roles...\n";
        foreach (['super_admin', 'admin_lab', 'mahasiswa', 'user_publik', 'admin_gudang'] as $role) {
            DB::table('roles')->insertOrIgnore(['name' => $role]);
        }

        // ==========================================
        // 2. USERS & PROFILES
        // ==========================================
        echo "Seeding Users & Profiles...\n";

        $admins = [
            ['id' => 1, 'role_id' => 1, 'name' => 'Dr. Fajar Nugroho, M.T.', 'email' => 'admin@idig.com'],
            ['id' => 2, 'role_id' => 2, 'name' => 'Rizky Aditya Pratama', 'email' => 'adminlab@idig.com'],
            ['id' => 3, 'role_id' => 5, 'name' => 'Siti Rahayu', 'email' => 'gudang@idig.com'],
        ];
        foreach ($admins as $a) {
            DB::table('users')->insert([
                'id' => $a['id'], 'role_id' => $a['role_id'], 'name' => $a['name'],
                'email' => $a['email'], 'password' => Hash::make('password'),
                'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('user_profiles')->insert([
                'user_id' => $a['id'], 'full_name' => $a['name'],
                'department' => 'Teknologi Kedokteran', 'faculty' => 'FTEIC',
                'university' => 'ITS', 'updated_at' => now(),
            ]);
        }

        // 10 student/public users with fixed realistic names
        $dummyUsers = [
            ['id' => 4,  'role_id' => 3, 'name' => 'Budi Santoso',          'nim' => '5031201001', 'phone' => '081234567890'],
            ['id' => 5,  'role_id' => 3, 'name' => 'Dewi Rahayu',           'nim' => '5031201002', 'phone' => '082345678901'],
            ['id' => 6,  'role_id' => 3, 'name' => 'Ahmad Fauzan Hidayat',  'nim' => '5031201003', 'phone' => '083456789012'],
            ['id' => 7,  'role_id' => 3, 'name' => 'Siti Nurhaliza',        'nim' => '5031201004', 'phone' => '084567890123'],
            ['id' => 8,  'role_id' => 3, 'name' => 'Hendri Kusuma',         'nim' => '5031201005', 'phone' => '085678901234'],
            ['id' => 9,  'role_id' => 3, 'name' => 'Rini Anggraini',        'nim' => '5031201006', 'phone' => '086789012345'],
            ['id' => 10, 'role_id' => 3, 'name' => 'Dimas Setiawan',        'nim' => '5031201007', 'phone' => '087890123456'],
            ['id' => 11, 'role_id' => 3, 'name' => 'Ayu Lestari',           'nim' => '5031201008', 'phone' => '088901234567'],
            ['id' => 12, 'role_id' => 4, 'name' => 'Wahyu Prabowo',         'nim' => null,          'phone' => '089012345678'],
            ['id' => 13, 'role_id' => 4, 'name' => 'Indah Permatasari',     'nim' => null,          'phone' => '081123456789'],
        ];
        foreach ($dummyUsers as $u) {
            DB::table('users')->insert([
                'id' => $u['id'], 'role_id' => $u['role_id'], 'name' => $u['name'],
                'email' => "user{$u['id']}@idig.com", 'password' => Hash::make('password'),
                'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('user_profiles')->insert([
                'user_id' => $u['id'], 'full_name' => $u['name'], 'phone' => $u['phone'],
                'address' => 'Jl. Keputih Tegal No. '.$u['id'].', Surabaya',
                'nim' => $u['nim'],
                'department' => $u['role_id'] === 3 ? 'Teknologi Kedokteran' : null,
                'faculty' => $u['role_id'] === 3 ? 'FTEIC' : null,
                'university' => $u['role_id'] === 3 ? 'ITS' : null,
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 3. OPEN SOURCE PROJECTS
        // ==========================================
        echo "Seeding Open Source Projects...\n";
        $this->call(OpenSourceProjectSeeder::class);

        // ==========================================
        // 4. MASTER LOOKUP TABLES, INVENTORY & MATERIALS
        // ==========================================
        echo "Seeding Master Tables, Inventories & Raw Materials...\n";

        $labTekkes = DB::table('labs')->insertGetId(['name' => 'Lab Tekkes',    'created_at' => now(), 'updated_at' => now()]);
        $labPraktikum = DB::table('labs')->insertGetId(['name' => 'Lab Praktikum', 'created_at' => now(), 'updated_at' => now()]);

        $catFilament = DB::table('material_categories')->insertGetId(['name' => 'Filament', 'created_at' => now(), 'updated_at' => now()]);
        $catResin = DB::table('material_categories')->insertGetId(['name' => 'Resin',    'created_at' => now(), 'updated_at' => now()]);
        $catSilicon = DB::table('material_categories')->insertGetId(['name' => 'Silicon',  'created_at' => now(), 'updated_at' => now()]);

        $brandESUN = DB::table('brands')->insertGetId(['name' => 'eSUN',      'created_at' => now(), 'updated_at' => now()]);
        $brandAnycubic = DB::table('brands')->insertGetId(['name' => 'Anycubic',  'created_at' => now(), 'updated_at' => now()]);
        $brandSmoothOn = DB::table('brands')->insertGetId(['name' => 'Smooth-On', 'created_at' => now(), 'updated_at' => now()]);
        $brandCreality = DB::table('brands')->insertGetId(['name' => 'Creality',  'created_at' => now(), 'updated_at' => now()]);
        $brandDekko = DB::table('brands')->insertGetId(['name' => 'Dekko',     'created_at' => now(), 'updated_at' => now()]);
        $brandOlympus = DB::table('brands')->insertGetId(['name' => 'Olympus',   'created_at' => now(), 'updated_at' => now()]);

        $colorWhite = DB::table('colors')->insertGetId(['name' => 'White',         'hex' => '#FFFFFF', 'created_at' => now(), 'updated_at' => now()]);
        $colorGrey = DB::table('colors')->insertGetId(['name' => 'Standard Grey', 'hex' => '#9CA3AF', 'created_at' => now(), 'updated_at' => now()]);
        $colorTranslucent = DB::table('colors')->insertGetId(['name' => 'Translucent', 'hex' => '#E8EEF2', 'created_at' => now(), 'updated_at' => now()]);
        $colorBlack = DB::table('colors')->insertGetId(['name' => 'Black',         'hex' => '#1A1A1A', 'created_at' => now(), 'updated_at' => now()]);
        $colorClear = DB::table('colors')->insertGetId(['name' => 'Clear',         'hex' => '#EFF6FF', 'created_at' => now(), 'updated_at' => now()]);
        $colorBlue = DB::table('colors')->insertGetId(['name' => 'Blue',          'hex' => '#2F6BE0', 'created_at' => now(), 'updated_at' => now()]);

        // Filament types for the 3D-printing service request form
        DB::table('filament_types')->insert([
            [
                'code' => 'PLA',
                'name' => 'PLA',
                'scientific_name' => 'Polylactic Acid',
                'price_per_gram' => 1500,
                'description' => 'Biodegradable and easy to print. Best for prototypes, decorative models, and low-stress applications. Low warping and excellent detail reproduction.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PETG',
                'name' => 'PETG',
                'scientific_name' => 'Polyethylene Terephthalate Glycol',
                'price_per_gram' => 2000,
                'description' => 'Strong, durable, and chemical-resistant. Excellent layer adhesion and impact resistance. Ideal for functional parts, mechanical components, and containers requiring heat resistance.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'TPU',
                'name' => 'TPU',
                'scientific_name' => 'Thermoplastic Polyurethane',
                'price_per_gram' => 2500,
                'description' => 'Flexible and highly elastic filament. Excellent abrasion and impact resistance. Perfect for phone cases, gaskets, wearables, and parts that require a soft, rubber-like feel.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Inventories
        $printerId = DB::table('inventories')->insertGetId(['lab_id' => $labTekkes,    'name' => 'Printer 3D Ender 3 V2',   'brand_id' => $brandCreality, 'total_quantity' => 3, 'available_quantity' => 3, 'created_at' => now()]);
        $solderId = DB::table('inventories')->insertGetId(['lab_id' => $labTekkes,    'name' => 'Soldering Iron Set',       'brand_id' => $brandDekko,    'total_quantity' => 5, 'available_quantity' => 5, 'created_at' => now()]);
        $microscopeId = DB::table('inventories')->insertGetId(['lab_id' => $labPraktikum, 'name' => 'Mikroskop Digital',       'brand_id' => $brandOlympus,  'total_quantity' => 2, 'available_quantity' => 2, 'created_at' => now()]);

        // Raw Materials
        $filamentId = DB::table('raw_materials')->insertGetId(['lab_id' => $labTekkes,    'material_category_id' => $catFilament, 'brand_id' => $brandESUN,     'color_id' => $colorWhite,      'unit' => 'gram', 'current_stock' => 5000, 'created_at' => now()]);
        $resinId = DB::table('raw_materials')->insertGetId(['lab_id' => $labTekkes,    'material_category_id' => $catResin,    'brand_id' => $brandAnycubic,  'color_id' => $colorGrey,       'unit' => 'ml',   'current_stock' => 2000, 'created_at' => now()]);
        $siliconId = DB::table('raw_materials')->insertGetId(['lab_id' => $labPraktikum, 'material_category_id' => $catSilicon,  'brand_id' => $brandSmoothOn,  'color_id' => $colorTranslucent, 'unit' => 'gram', 'current_stock' => 3000, 'created_at' => now()]);

        // ==========================================
        // 5. SERVICES
        // ==========================================
        echo "Seeding Services...\n";

        $printingServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Print 3D (FDM/SLA)',
            'service_type' => 'printing',
            'description' => 'Layanan cetak 3D dengan akurasi tinggi menggunakan filamen FDM maupun resin SLA. Cocok untuk prototip medis, ortosis, dan prostetik.',
            'base_price' => 2000,
            'whatsapp_number' => '6281234561001',
        ]);
        DB::table('attachments')->insert([
            'attachable_type' => 'App\Models\Service',
            'attachable_id' => $printingServiceId,
            'file_url' => 'assets/images/services/service_printing.png',
            'file_name' => 'service_printing.png',
            'file_size' => '150 KB',
            'file_type' => 'image/png',
            'is_primary' => true,
            'sort_order' => 0,
            'uploaded_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $designServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Desain 3D CAD',
            'service_type' => 'design',
            'description' => 'Pembuatan model 3D dari sketsa, foto referensi, atau spesifikasi teknis menggunakan Fusion 360 dan SolidWorks.',
            'base_price' => 150000,
            'whatsapp_number' => '6281234561002',
        ]);
        DB::table('attachments')->insert([
            'attachable_type' => 'App\Models\Service',
            'attachable_id' => $designServiceId,
            'file_url' => 'assets/images/services/service_design.png',
            'file_name' => 'service_design.png',
            'file_size' => '150 KB',
            'file_type' => 'image/png',
            'is_primary' => true,
            'sort_order' => 0,
            'uploaded_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $scanningServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Scanning 3D',
            'service_type' => 'scanning',
            'description' => 'Pemindaian objek fisik menjadi model 3D digital. Ideal untuk rekayasa balik, dokumentasi arsip, dan analisis geometri presisi.',
            'base_price' => 100000,
            'whatsapp_number' => '6281234561003',
        ]);
        DB::table('attachments')->insert([
            'attachable_type' => 'App\Models\Service',
            'attachable_id' => $scanningServiceId,
            'file_url' => 'assets/images/services/service_scanning.png',
            'file_name' => 'service_scanning.png',
            'file_size' => '150 KB',
            'file_type' => 'image/png',
            'is_primary' => true,
            'sort_order' => 0,
            'uploaded_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ==========================================
        // 6. EVENTS, TEAMS & PROJECTS
        // ==========================================
        echo "Seeding Events & Projects...\n";

        $eventThemes = [
            ['name' => 'Innovatech Medika 2021', 'year' => 2021, 'theme' => 'Inovasi Teknologi Kesehatan Pasca-Pandemi', 'active' => false],
            ['name' => 'Innovatech Medika 2022', 'year' => 2022, 'theme' => 'Digitalisasi Fasilitas Kesehatan Indonesia',  'active' => false],
            ['name' => 'Innovatech Medika 2023', 'year' => 2023, 'theme' => 'Alat Bantu Disabilitas Berbasis 3D Printing', 'active' => false],
            ['name' => 'Innovatech Medika 2024', 'year' => 2024, 'theme' => 'Sensor Wearable untuk Monitoring Pasien',     'active' => false],
            ['name' => 'Innovatech Medika 2025', 'year' => 2025, 'theme' => 'Kecerdasan Buatan dalam Diagnostik Medis',    'active' => true],
        ];

        $teamNames = [
            ['Tim Prostetik Nusantara', 'Tim Rehab Mandiri'],
            ['Tim Biomed Cerdas', 'Tim Signal Medis'],
            ['Tim Print Ortosis', 'Tim Fab Biomedik'],
            ['Tim Sensor Vital', 'Tim IoHealth'],
            ['Tim AI Diagnostik', 'Tim Vision Medis'],
        ];

        $projectTitles = [
            ['Rancang Bangun Prostetik Jari Tangan Low-Cost', 'Sistem Rehabilitasi Pasif Pergelangan Tangan'],
            ['Platform IoT Monitoring Pasien Rawat Jalan', 'Pengolahan Sinyal EEG untuk Deteksi Kejang'],
            ['Ortosis Ankle-Foot Cetak 3D Berbobot Ringan', 'Jig Bedah Berbasis CT-Scan untuk Tulang Belakang'],
            ['Gelang Pemantau Detak Jantung Berbasis ESP32', 'Sistem Alert Tekanan Darah Nirkabel'],
            ['Klasifikasi Tumor Otak via Deep Learning', 'Segmentasi Otomatis CT Scan Dada'],
        ];

        for ($e = 0; $e < 5; $e++) {
            $eventId = DB::table('events')->insertGetId([
                'name' => $eventThemes[$e]['name'], 'year' => $eventThemes[$e]['year'],
                'theme_title' => $eventThemes[$e]['theme'], 'is_active' => $eventThemes[$e]['active'],
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

        // ==========================================
        // 7. PRODUCTS (Made-by-Order Catalog)
        // ==========================================
        echo "Seeding Products Portfolio...\n";

        $products = [
            ['name' => 'Exoskeleton Jari Fleksibel',      'desc' => 'Exoskeleton jari berbahan TPU untuk rehabilitasi pasca-stroke.',                         'min' => 180000,  'max' => 350000],
            ['name' => 'Splint Pergelangan Cetak',         'desc' => 'Splint kustom berbasis scan tangan pengguna, material PETG ringan.',                     'min' => 120000,  'max' => 250000],
            ['name' => 'Ortosis Pergelangan Kaki (AFO)',   'desc' => 'Ankle-foot orthosis ringan dari PLA/PETG, dicetak sesuai ukuran pasien.',               'min' => 200000,  'max' => 400000],
            ['name' => 'Prostetik Jari Tangan Pasif',      'desc' => 'Prostetik jari fungsional dengan sendi engsel, tahan air, bisa dikostumisasi.',         'min' => 150000,  'max' => 300000],
            ['name' => 'Model Anatomis Tulang Belakang',   'desc' => 'Replika vertebra lumbal skala 1:1 dari data MRI/CT-Scan untuk simulasi bedah.',         'min' => 500000,  'max' => 1200000],
            ['name' => 'Tempat Implan Gigi Custom',        'desc' => 'Panduan bedah implan gigi 3D berdasarkan data CBCT pasien.',                             'min' => 350000,  'max' => 750000],
            ['name' => 'Casing Perangkat Medis Portable',  'desc' => 'Casing ergonomis untuk perangkat diagnostik portabel — desain khusus sesuai kebutuhan.', 'min' => 100000,  'max' => 220000],
            ['name' => 'Jig Pembedahan Ortopedi',          'desc' => 'Jig potong tulang presisi tinggi berdasarkan data 3D scan pasien.',                     'min' => 600000,  'max' => 1500000],
            ['name' => 'Prototip Kursi Roda Ringan',       'desc' => 'Komponen rangka kursi roda berbahan komposit dicetak 3D, lebih ringan 40% dari besi.',  'min' => 800000,  'max' => 2000000],
            ['name' => 'Tempat Sterilisasi Alat Bedah',    'desc' => 'Tray sterilisasi cetakan 3D heat-resistant untuk autoclave hingga 134°C.',               'min' => 80000,   'max' => 180000],
        ];
        foreach ($products as $index => $p) {
            $productId = DB::table('products')->insertGetId([
                'creator_id' => 1, 'name' => $p['name'], 'description' => $p['desc'],
                'price_min' => $p['min'], 'price_max' => $p['max'], 'is_active' => 1,
            ]);

            $imageName = match ($index) {
                0 => 'exoskeleton_jari.png',
                1 => 'splint_pergelangan.png',
                2 => 'ortosis_kaki.png',
                3 => 'prostetik_jari.png',
                4 => 'model_tulang_belakang.png',
                5 => 'implan_gigi.png',
                6 => 'casing_perangkat_medis.png',
                7 => 'jig_ortopedi.png',
                8 => 'kursi_roda.png',
                9 => 'sterilisasi_bedah.png',
                default => null,
            };

            if ($imageName) {
                DB::table('attachments')->insert([
                    'attachable_type' => 'App\Models\Product',
                    'attachable_id' => $productId,
                    'file_url' => 'assets/images/products/'.$imageName,
                    'file_name' => $imageName,
                    'file_size' => '150 KB',
                    'file_type' => 'image/png',
                    'is_primary' => true,
                    'sort_order' => 0,
                    'uploaded_by' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ==========================================
        // 8. REIMBURSEMENTS (4 rows, varied statuses)
        // ==========================================
        echo "Seeding Reimbursements...\n";

        $reimburseFilamentId = DB::table('reimbursements')->insertGetId([
            'user_id' => 2, 'title' => 'Restock Filamen eSUN PLA White 1kg',
            'total_amount' => 450000, 'status' => 'approved',
            'created_at' => now()->subDays(30), 'updated_at' => now()->subDays(28),
        ]);
        $reimburseResinId = DB::table('reimbursements')->insertGetId([
            'user_id' => 2, 'title' => 'Restock Resin Anycubic Standard Grey 1L',
            'total_amount' => 900000, 'status' => 'approved',
            'created_at' => now()->subDays(20), 'updated_at' => now()->subDays(18),
        ]);
        DB::table('reimbursements')->insert([
            'user_id' => 2, 'title' => 'Pembelian Suku Cadang Printer Ender 3 (Nozzle + Belt)',
            'total_amount' => 350000, 'status' => 'pending',
            'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5),
        ]);
        $reimbursePetgId = DB::table('reimbursements')->insertGetId([
            'user_id' => 2, 'title' => 'Restock Filamen eSUN PETG Black 1kg',
            'total_amount' => 520000, 'status' => 'paid',
            'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(8),
        ]);

        // Raw material restock movements
        DB::table('raw_material_movements')->insert([
            ['raw_material_id' => $filamentId, 'type' => 'in', 'quantity' => 2000, 'reimbursement_id' => $reimburseFilamentId, 'notes' => 'Restock filamen PLA White dari Tokopedia.',  'created_by' => 2, 'created_at' => now()->subDays(30)],
            ['raw_material_id' => $resinId,    'type' => 'in', 'quantity' => 1000, 'reimbursement_id' => $reimburseResinId,    'notes' => 'Restock resin Anycubic dari Shopee.',         'created_by' => 2, 'created_at' => now()->subDays(20)],
            ['raw_material_id' => $filamentId, 'type' => 'in', 'quantity' => 1500, 'reimbursement_id' => $reimbursePetgId,    'notes' => 'Restock filamen PETG Black untuk order besar.', 'created_by' => 2, 'created_at' => now()->subDays(10)],
        ]);

        // ==========================================
        // 9. SERVICE BOOKINGS (12 explicit bookings)
        // ==========================================
        echo "Seeding Service Bookings, Messages & Payments...\n";

        /*
         * Booking definitions — each entry maps to a real conversation and payment scenario.
         * status: pending | negotiating | in_progress | completed | cancelled
         */
        $bookingDefs = [
            // 1. Pending — just submitted, no response yet
            [
                'user_id' => 4, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'pending', 'agreed_price' => null, 'days_ago' => 1,
                'weight' => null,
                'brief' => 'Cetak model splint pergelangan tangan kiri, ukuran 18×10×5 cm, material PETG warna putih, 1 unit.',
                'fields' => ['material_preference' => 'PETG', 'object_dimensions' => json_encode(['length' => '18', 'width' => '10', 'height' => '5']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null, 'filament_width' => null, 'scan_purpose' => null],
                'messages' => [],
                'progress' => [],
            ],
            // 2. Pending — design order just submitted
            [
                'user_id' => 5, 'service_id' => $designServiceId, 'type' => 'design',
                'status' => 'pending', 'agreed_price' => null, 'days_ago' => 2,
                'weight' => null,
                'brief' => 'Butuh desain 3D implan gigi untuk pasien dengan kehilangan gigi molar kanan bawah. Referensi foto X-ray sudah saya siapkan.',
                'fields' => ['material_preference' => null, 'filament_width' => '1.75 mm', 'scan_purpose' => null, 'object_dimensions' => json_encode(['length' => '2', 'width' => '1', 'height' => '1']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'messages' => [],
                'progress' => [],
            ],
            // 3. Negotiating — printing, conversation in progress
            [
                'user_id' => 6, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'negotiating', 'agreed_price' => null, 'days_ago' => 4,
                'weight' => 180,
                'brief' => 'Cetak prostetik jari tangan (4 jari) berbahan TPU Shore 95A. Ukuran disesuaikan scan tangan kanan pasien. Warna skin-tone.',
                'fields' => ['material_preference' => 'TPU', 'object_dimensions' => json_encode(['length' => '10', 'width' => '7', 'height' => '4']), 'slicer_weight_grams' => 180, 'slicer_print_time_minutes' => 270, 'filament_width' => null, 'scan_purpose' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo admin, saya ingin mencetak prostetik 4 jari tangan berbahan TPU. Sudah saya lampirkan file STL-nya di email.', 'daysAgo' => 4],
                    ['sender' => 'admin', 'body' => 'Halo Bapak/Ibu! Kami sudah terima email-nya. Material TPU Shore 95A tersedia di lab kami. Untuk ukuran dan bobot yang Anda minta, estimasi harga sekitar Rp 280.000–Rp 320.000. Bisa dikonfirmasi?', 'daysAgo' => 3],
                    ['sender' => 'user',  'body' => 'Apakah bisa lebih murah? Ini untuk pasien dengan kondisi ekonomi terbatas.', 'daysAgo' => 3],
                ],
                'progress' => [],
            ],
            // 4. Negotiating — scanning, discussing scope
            [
                'user_id' => 7, 'service_id' => $scanningServiceId, 'type' => 'scanning',
                'status' => 'negotiating', 'agreed_price' => null, 'days_ago' => 5,
                'weight' => null,
                'brief' => 'Scan geometri penopang lutut custom buatan tangan (kayu + busa). Ukuran sekitar 30×20×15 cm. Tujuan: rekayasa balik untuk produksi polimer.',
                'fields' => ['material_preference' => null, 'filament_width' => null, 'scan_purpose' => 'Rekayasa Balik', 'object_dimensions' => json_encode(['length' => '30', 'width' => '20', 'height' => '15']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Selamat siang. Saya ingin scan knee brace custom buatan tangan untuk keperluan rekayasa balik. Kapan saya bisa membawa objeknya ke lab?', 'daysAgo' => 5],
                    ['sender' => 'admin', 'body' => 'Selamat siang! Silakan datang ke Lab Tekkes pada hari Senin–Jumat pukul 09.00–15.00. Objek dengan ukuran tersebut estimasi waktu scan 2–3 jam. Biaya Rp 200.000. Setuju?', 'daysAgo' => 4],
                    ['sender' => 'user',  'body' => 'Apakah hasil scan berformat STEP atau hanya STL? Saya butuh STEP untuk modifikasi di CAD.', 'daysAgo' => 4],
                    ['sender' => 'admin', 'body' => 'Kami bisa ekspor ke STL, OBJ, dan STEP. Format STEP memerlukan post-processing tambahan, biayanya Rp 250.000 total. Apakah setuju?', 'daysAgo' => 3],
                ],
                'progress' => [],
            ],
            // 5. In Progress — design, DP sudah dibayar
            [
                'user_id' => 8, 'service_id' => $designServiceId, 'type' => 'design',
                'status' => 'in_progress', 'agreed_price' => 250000, 'days_ago' => 10,
                'weight' => null,
                'brief' => 'Desain 3D casing perangkat oximeter portabel. Dimensi PCB: 60×40×10 mm. Butuh slot baterai 18650, lubang sensor, dan clip penjepit jari.',
                'fields' => ['material_preference' => null, 'filament_width' => '1.75 mm', 'scan_purpose' => null, 'object_dimensions' => json_encode(['length' => '70', 'width' => '50', 'height' => '25']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya butuh desain casing oximeter portabel. PCB saya 60×40×10 mm, butuh slot baterai 18650 dan lubang sensor.', 'daysAgo' => 10],
                    ['sender' => 'admin', 'body' => 'Halo! Kebutuhan Anda sudah kami catat. Kami bisa kerjakan dalam Fusion 360 dengan estimasi 3–5 hari. Harga Rp 250.000 sudah termasuk 2x revisi. Setuju?', 'daysAgo' => 9],
                    ['sender' => 'user',  'body' => 'Setuju. Saya akan transfer DP 50% sekarang.', 'daysAgo' => 9],
                    ['sender' => 'admin', 'body' => 'DP sudah kami terima dan dikonfirmasi. Proses desain dimulai. Kami akan kirim progress update dalam 2 hari.', 'daysAgo' => 8],
                    ['sender' => 'user',  'body' => 'Baik, terima kasih. Kalau ada pertanyaan terkait dimensi PCB, silakan langsung tanya.', 'daysAgo' => 8],
                    ['sender' => 'admin', 'body' => 'Siap! Draft pertama sudah selesai, kami kirim preview render-nya via email sekarang. Mohon ditinjau.', 'daysAgo' => 6],
                ],
                'progress' => [
                    ['label' => 'Konsultasi & Brief', 'pct' => 20, 'notes' => 'Kebutuhan desain sudah dikonfirmasi. Dimensi PCB dicatat.', 'daysAgo' => 9],
                    ['label' => 'Pembuatan Draft', 'pct' => 60, 'notes' => 'Draft casing pertama sudah selesai. Preview dikirim ke klien untuk ditinjau.', 'daysAgo' => 6],
                ],
            ],
            // 6. In Progress — printing, sedang dicetak
            [
                'user_id' => 9, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'in_progress', 'agreed_price' => 180000, 'days_ago' => 8,
                'weight' => 120,
                'brief' => 'Cetak 2 unit ortosis pergelangan kaki anak (AFO). Material PLA warna putih. Ukuran S (panjang 22 cm). File STL terlampir.',
                'fields' => ['material_preference' => 'PLA', 'object_dimensions' => json_encode(['length' => '22', 'width' => '12', 'height' => '8']), 'slicer_weight_grams' => 120, 'slicer_print_time_minutes' => 240, 'filament_width' => null, 'scan_purpose' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya perlu cetak 2 unit AFO anak ukuran S berbahan PLA. File STL sudah saya kirim.', 'daysAgo' => 8],
                    ['sender' => 'admin', 'body' => 'Halo! File STL diterima. Untuk 2 unit AFO PLA estimasi Rp 180.000 termasuk finishing. Waktu produksi 4–5 hari. Setuju?', 'daysAgo' => 7],
                    ['sender' => 'user',  'body' => 'Setuju. Apakah bisa siap sebelum Jumat minggu ini?', 'daysAgo' => 7],
                    ['sender' => 'admin', 'body' => 'Kami akan prioritaskan. DP sudah dikonfirmasi, proses printing dimulai besok pagi.', 'daysAgo' => 6],
                    ['sender' => 'admin', 'body' => 'Update: unit pertama selesai dicetak, sedang proses cooling dan finishing. Unit kedua dijadwalkan selesai besok.', 'daysAgo' => 3],
                ],
                'progress' => [
                    ['label' => 'Slicing', 'pct' => 15, 'notes' => 'File STL berhasil di-slice. Estimasi waktu cetak 4 jam per unit dengan layer height 0.2mm.', 'daysAgo' => 6],
                    ['label' => 'Printing Unit 1', 'pct' => 50, 'notes' => 'Unit pertama selesai dicetak. Tidak ada layer error. Sedang proses cooling.', 'daysAgo' => 3],
                ],
            ],
            // 7. In Progress — scanning, sedang berjalan
            [
                'user_id' => 10, 'service_id' => $scanningServiceId, 'type' => 'scanning',
                'status' => 'in_progress', 'agreed_price' => 200000, 'days_ago' => 7,
                'weight' => null,
                'brief' => 'Scan tulang rusuk tiruan untuk keperluan simulasi bedah torakoplasti. Ukuran: 25×15×10 cm. Akurasi minimal ±0.5mm.',
                'fields' => ['material_preference' => null, 'filament_width' => null, 'scan_purpose' => 'Simulasi Bedah', 'object_dimensions' => json_encode(['length' => '25', 'width' => '15', 'height' => '10']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Selamat pagi. Saya butuh scan tulang rusuk untuk simulasi bedah. Objek berupa replika resin yang saya bawa sendiri.', 'daysAgo' => 7],
                    ['sender' => 'admin', 'body' => 'Selamat pagi! Silakan. Untuk akurasi ±0.5mm kami gunakan scanner Artec Eva. Biaya Rp 200.000. Kapan bisa datang?', 'daysAgo' => 6],
                    ['sender' => 'user',  'body' => 'Selasa jam 10 pagi bisa tidak?', 'daysAgo' => 6],
                    ['sender' => 'admin', 'body' => 'Bisa. Kami sudah reservasi slot Selasa 10.00 atas nama Anda. Mohon datang tepat waktu.', 'daysAgo' => 5],
                    ['sender' => 'user',  'body' => 'Terima kasih, DP sudah ditransfer.', 'daysAgo' => 4],
                ],
                'progress' => [
                    ['label' => 'Persiapan Scanning', 'pct' => 25, 'notes' => 'Objek diterima dan dikalibrasi. Scanner Artec Eva siap digunakan.', 'daysAgo' => 4],
                    ['label' => 'Proses Scanning', 'pct' => 55, 'notes' => 'Scanning 80% selesai. Point cloud awal terlihat bagus, akurasi memenuhi spesifikasi.', 'daysAgo' => 2],
                ],
            ],
            // 8. In Progress — printing besar, multi-progress
            [
                'user_id' => 11, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'in_progress', 'agreed_price' => 320000, 'days_ago' => 12,
                'weight' => 250,
                'brief' => 'Cetak replika model anatomi jantung skala 1:1 dari data CT-Scan DICOM. Material Resin warna merah transparan. Perlu detail pembuluh darah yang akurat.',
                'fields' => ['material_preference' => 'Resin', 'object_dimensions' => json_encode(['length' => '12', 'width' => '10', 'height' => '14']), 'slicer_weight_grams' => 250, 'slicer_print_time_minutes' => 480, 'filament_width' => null, 'scan_purpose' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya butuh model anatomi jantung dari data DICOM CT-Scan. Perlu detail pembuluh darah yang akurat untuk presentasi medis.', 'daysAgo' => 12],
                    ['sender' => 'admin', 'body' => 'Halo! Kami terima file DICOM-nya. Setelah review, butuh konversi ke STL dahulu menggunakan 3D Slicer (software). Biaya total Rp 320.000 termasuk konversi dan cetak resin. Setuju?', 'daysAgo' => 11],
                    ['sender' => 'user',  'body' => 'Setuju. Ini untuk presentasi ke dokter spesialis bulan depan, jadi kualitas sangat penting.', 'daysAgo' => 11],
                    ['sender' => 'admin', 'body' => 'Dipahami. Kami gunakan resin transparan merah untuk menonjolkan pembuluh darah. DP sudah terkonfirmasi, proses dimulai.', 'daysAgo' => 10],
                    ['sender' => 'admin', 'body' => 'Update progress: konversi DICOM ke STL selesai. Model siap masuk printer SLA. Estimasi cetak 8 jam.', 'daysAgo' => 7],
                    ['sender' => 'user',  'body' => 'Luar biasa! Apakah hasilnya bisa dikirim atau harus diambil langsung?', 'daysAgo' => 6],
                ],
                'progress' => [
                    ['label' => 'Konversi DICOM ke STL', 'pct' => 25, 'notes' => 'File DICOM berhasil dikonversi ke STL menggunakan 3D Slicer. Mesh di-repair untuk memperbaiki artefak scan.', 'daysAgo' => 9],
                    ['label' => 'Slicing & Setup SLA', 'pct' => 40, 'notes' => 'File di-slice dengan Chitubox. Support structure ditambahkan pada area pembuluh darah tipis.', 'daysAgo' => 7],
                    ['label' => 'Printing SLA', 'pct' => 70, 'notes' => 'Proses cetak resin berjalan 60% dari estimasi. Kualitas layer terlihat sangat baik.', 'daysAgo' => 4],
                ],
            ],
            // 9. Completed — design selesai
            [
                'user_id' => 12, 'service_id' => $designServiceId, 'type' => 'design',
                'status' => 'completed', 'agreed_price' => 300000, 'days_ago' => 20,
                'weight' => null,
                'brief' => 'Desain 3D bracket penopang alat EEG portabel. Harus bisa dipasang di kepala (adjustable headband). Material ABS, perlu toleransi 0.2mm untuk fitting elektroda.',
                'fields' => ['material_preference' => null, 'filament_width' => '1.75 mm', 'scan_purpose' => null, 'object_dimensions' => json_encode(['length' => '20', 'width' => '15', 'height' => '10']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo admin, saya butuh desain bracket untuk headband EEG portabel. Material ABS, adjustable, toleransi 0.2mm untuk fitting elektroda.', 'daysAgo' => 20],
                    ['sender' => 'admin', 'body' => 'Halo! Desain adjustable headband dengan toleransi presisi adalah spesialisasi kami. Estimasi Rp 300.000 termasuk 3x revisi. Waktu pengerjaan 5–7 hari. Setuju?', 'daysAgo' => 19],
                    ['sender' => 'user',  'body' => 'Setuju. DP saya transfer sekarang.', 'daysAgo' => 19],
                    ['sender' => 'admin', 'body' => 'DP dikonfirmasi. Proses desain dimulai. Kami akan kirim draft pertama dalam 3 hari.', 'daysAgo' => 18],
                    ['sender' => 'admin', 'body' => 'Draft pertama sudah selesai dan dikirim via email. Mohon ditinjau dan berikan feedback.', 'daysAgo' => 15],
                    ['sender' => 'user',  'body' => 'Desainnya sudah bagus! Hanya perlu penyesuaian ukuran lingkar kepala maksimal dari 56cm ke 60cm.', 'daysAgo' => 14],
                    ['sender' => 'admin', 'body' => 'Revisi selesai dan sudah dikirim. File final STL + STEP + F3D sudah kami upload ke Drive. Pelunasan bisa dilakukan sekarang.', 'daysAgo' => 13],
                ],
                'progress' => [
                    ['label' => 'Brief & Konsultasi', 'pct' => 10, 'notes' => 'Spesifikasi bracket dikonfirmasi. Ukuran headband 54–60cm adjustable.', 'daysAgo' => 18],
                    ['label' => 'Pembuatan Draft', 'pct' => 50, 'notes' => 'Draft pertama selesai. Desain parametrik dengan 3 size preset.', 'daysAgo' => 15],
                    ['label' => 'Revisi & Finalisasi', 'pct' => 90, 'notes' => 'Revisi ukuran lingkar kepala selesai. File diperiksa dimensi.', 'daysAgo' => 13],
                    ['label' => 'Selesai & Terkirim', 'pct' => 100, 'notes' => 'File final STL, STEP, dan F3D sudah dikirim ke klien. Pesanan selesai.', 'daysAgo' => 12],
                ],
            ],
            // 10. Completed — printing selesai
            [
                'user_id' => 13, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'completed', 'agreed_price' => 150000, 'days_ago' => 15,
                'weight' => 85,
                'brief' => 'Cetak 1 unit casing sensor tekanan darah wearable. PLA hitam. Ukuran 40×30×20 mm. Butuh lubang untuk kabel ribbon dan port USB-C.',
                'fields' => ['material_preference' => 'PLA', 'object_dimensions' => json_encode(['length' => '40', 'width' => '30', 'height' => '20']), 'slicer_weight_grams' => 85, 'slicer_print_time_minutes' => 150, 'filament_width' => null, 'scan_purpose' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya perlu cetak casing sensor wearable kecil, PLA hitam, 40×30×20 mm. Sudah ada lubang di file STL.', 'daysAgo' => 15],
                    ['sender' => 'admin', 'body' => 'Halo! File STL sudah kami cek. Ukuran kecil, estimasi selesai dalam 2 hari. Harga Rp 150.000 termasuk finishing. Setuju?', 'daysAgo' => 14],
                    ['sender' => 'user',  'body' => 'Setuju, DP sudah ditransfer.', 'daysAgo' => 14],
                    ['sender' => 'admin', 'body' => 'DP dikonfirmasi. Proses cetak mulai besok pagi.', 'daysAgo' => 13],
                    ['sender' => 'admin', 'body' => 'Casing sudah selesai dicetak dan sudah melewati QC. Kualitas permukaan baik, toleransi akurat. Pelunasan bisa dilakukan.', 'daysAgo' => 12],
                    ['sender' => 'user',  'body' => 'Pelunasan sudah ditransfer. Bisa dikirim ke alamat lab saya?', 'daysAgo' => 11],
                ],
                'progress' => [
                    ['label' => 'Slicing', 'pct' => 20, 'notes' => 'STL di-slice dengan Cura. Infill 30% gyroid. Layer height 0.15mm untuk detail lubang USB-C.', 'daysAgo' => 13],
                    ['label' => 'Printing', 'pct' => 60, 'notes' => 'Cetak selesai 100%. Tidak ada warping atau layer shifting.', 'daysAgo' => 12],
                    ['label' => 'Finishing & QC', 'pct' => 90, 'notes' => 'Support material dilepas. Lubang kabel ditest fitting — akurat. Permukaan dihaluskan.', 'daysAgo' => 12],
                    ['label' => 'Selesai', 'pct' => 100, 'notes' => 'Pesanan selesai dan siap diambil/dikirim. Pelunasan sudah dikonfirmasi.', 'daysAgo' => 11],
                ],
            ],
            // 11. Completed — scanning selesai
            [
                'user_id' => 4, 'service_id' => $scanningServiceId, 'type' => 'scanning',
                'status' => 'completed', 'agreed_price' => 220000, 'days_ago' => 25,
                'weight' => null,
                'brief' => 'Scan siku ortosis custom dari kulit sintetis untuk arsip digital dan kemungkinan reproduksi. Ukuran 20×10×8 cm. Format output: STL + STEP.',
                'fields' => ['material_preference' => null, 'filament_width' => null, 'scan_purpose' => 'Arsip Digital', 'object_dimensions' => json_encode(['length' => '20', 'width' => '10', 'height' => '8']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya punya ortosis siku custom dari kulit sintetis yang perlu di-scan untuk arsip. Bisa minta format STL dan STEP?', 'daysAgo' => 25],
                    ['sender' => 'admin', 'body' => 'Bisa! Untuk format STL + STEP dengan ukuran tersebut, biaya Rp 220.000. Kapan bisa datang?', 'daysAgo' => 24],
                    ['sender' => 'user',  'body' => 'Rabu jam 11 bisa?', 'daysAgo' => 24],
                    ['sender' => 'admin', 'body' => 'Bisa. Sudah kami reservasi. Silakan bawa objeknya langsung ke Lab Tekkes.', 'daysAgo' => 23],
                    ['sender' => 'admin', 'body' => 'Scanning selesai! File STL dan STEP sudah dikirim via Google Drive. Mohon dikonfirmasi penerimaan dan lakukan pelunasan.', 'daysAgo' => 21],
                ],
                'progress' => [
                    ['label' => 'Penerimaan Objek', 'pct' => 20, 'notes' => 'Ortosis diterima dan dibersihkan. Target point ditempatkan untuk akurasi referensi.', 'daysAgo' => 23],
                    ['label' => 'Proses Scanning', 'pct' => 60, 'notes' => 'Scanning 3 sisi selesai. Point cloud resolusi tinggi berhasil dibuat.', 'daysAgo' => 22],
                    ['label' => 'Post-Processing', 'pct' => 85, 'notes' => 'Noise filtering dan mesh reconstruction selesai. Konversi ke STEP menggunakan FreeCAD.', 'daysAgo' => 21],
                    ['label' => 'Selesai & Terkirim', 'pct' => 100, 'notes' => 'File STL dan STEP dikirim via Google Drive. Pesanan selesai.', 'daysAgo' => 20],
                ],
            ],
            // 12. Cancelled
            [
                'user_id' => 5, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'cancelled', 'agreed_price' => null, 'days_ago' => 18,
                'weight' => null,
                'brief' => 'Cetak topeng wajah rehabilitasi berbahan PETG untuk terapi keloid pasca-luka bakar. File STL akan dikirim menyusul.',
                'fields' => ['material_preference' => 'PETG', 'object_dimensions' => json_encode(['length' => '20', 'width' => '15', 'height' => '8']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null, 'filament_width' => null, 'scan_purpose' => null],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya ingin cetak topeng wajah rehabilitasi keloid, material PETG. File STL sedang disiapkan.', 'daysAgo' => 18],
                    ['sender' => 'admin', 'body' => 'Halo! Kami siap memprosesnya setelah file diterima. Estimasi biaya Rp 200.000–280.000 tergantung ukuran akhir.', 'daysAgo' => 17],
                ],
                'progress' => [],
            ],
        ];

        foreach ($bookingDefs as $def) {
            $daysAgo = $def['days_ago'];
            $isCompleted = $def['status'] === 'completed';
            $isInProgress = $def['status'] === 'in_progress';

            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $def['user_id'],
                'total_amount' => $def['agreed_price'] ?? 0,
                'payment_status' => $isCompleted ? 'paid' : ($def['agreed_price'] ? 'pending' : 'pending'),
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now(),
            ]);

            $bookingId = DB::table('service_bookings')->insertGetId(array_merge([
                'transaction_id' => $transactionId,
                'user_id' => $def['user_id'],
                'service_id' => $def['service_id'],
                'brief_description' => $def['brief'],
                'agreed_price' => $def['agreed_price'],
                'current_status' => $def['status'],
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now(),
            ], $def['fields']));

            // Booking messages
            foreach ($def['messages'] as $msg) {
                $senderId = $msg['sender'] === 'admin' ? 2 : $def['user_id'];
                DB::table('booking_messages')->insert([
                    'service_booking_id' => $bookingId,
                    'sender_id' => $senderId,
                    'body' => $msg['body'],
                    'read_at' => now()->subDays($msg['daysAgo'] - 1),
                    'created_at' => now()->subDays($msg['daysAgo']),
                    'updated_at' => now()->subDays($msg['daysAgo']),
                ]);
            }

            // Progress updates
            foreach ($def['progress'] as $prog) {
                DB::table('service_progress_updates')->insert([
                    'service_booking_id' => $bookingId,
                    'status_label' => $prog['label'],
                    'percentage' => $prog['pct'],
                    'notes' => $prog['notes'],
                    'updated_by' => 2,
                    'created_at' => now()->subDays($prog['daysAgo']),
                    'updated_at' => now()->subDays($prog['daysAgo']),
                ]);
            }

            // Payment termins for in_progress and completed bookings
            if ($def['agreed_price'] && ($isInProgress || $isCompleted)) {
                $dp = intdiv($def['agreed_price'], 2);
                $pelunasan = $def['agreed_price'] - $dp;

                DB::table('booking_payments')->insert([
                    'service_booking_id' => $bookingId,
                    'amount' => $dp,
                    'termin_name' => 'Down Payment (50%)',
                    'status' => 'paid',
                    'payment_proof' => 'dummy/proof_dp_'.$bookingId.'.jpg',
                    'paid_at' => now()->subDays($daysAgo - 1),
                    'verified_by' => 2,
                    'created_at' => now()->subDays($daysAgo),
                    'updated_at' => now()->subDays($daysAgo - 1),
                ]);

                DB::table('booking_payments')->insert([
                    'service_booking_id' => $bookingId,
                    'amount' => $pelunasan,
                    'termin_name' => 'Pelunasan (50%)',
                    'status' => $isCompleted ? 'paid' : 'pending',
                    'payment_proof' => $isCompleted ? 'dummy/proof_lunas_'.$bookingId.'.jpg' : null,
                    'paid_at' => $isCompleted ? now()->subDays($daysAgo - 3) : null,
                    'verified_by' => $isCompleted ? 2 : null,
                    'created_at' => now()->subDays($daysAgo),
                    'updated_at' => $isCompleted ? now()->subDays($daysAgo - 3) : now(),
                ]);
            }

            // Inventory usages for printing bookings that are in_progress or completed
            if ($def['type'] === 'printing' && ($isInProgress || $isCompleted)) {
                DB::table('inventory_usages')->insert([
                    'inventory_id' => $printerId,
                    'user_id' => 2,
                    'service_booking_id' => $bookingId,
                    'started_at' => now()->subDays($daysAgo - 2),
                    'ended_at' => $isCompleted ? now()->subDays($daysAgo - 4) : null,
                    'created_at' => now()->subDays($daysAgo - 2),
                    'updated_at' => now(),
                ]);
            }

            // Raw material movement for printing jobs with weight
            if ($def['type'] === 'printing' && $def['weight'] && ($isInProgress || $isCompleted)) {
                DB::table('raw_material_movements')->insert([
                    'raw_material_id' => $filamentId,
                    'type' => 'out',
                    'quantity' => $def['weight'],
                    'service_booking_id' => $bookingId,
                    'notes' => 'Pemakaian filamen untuk order #'.$bookingId.'.',
                    'created_by' => 2,
                    'created_at' => now()->subDays($daysAgo - 2),
                    'updated_at' => now(),
                ]);
            }
        }

        // ==========================================
        // 10. CMS DATA
        // ==========================================
        echo "Seeding CMS Data...\n";

        $pageSections = [
            ['key' => 'hero_title',    'content' => 'Pusat Inovasi Digital Kesehatan ITS'],
            ['key' => 'hero_subtitle', 'content' => 'Platform arsip digital dan layanan cetak 3D medis untuk civitas akademika ITS dan masyarakat umum.'],
            ['key' => 'about_lab',     'content' => 'Lab IDIG (Innovation in Digital and Integrated Goods) adalah laboratorium di bawah Departemen Teknologi Kedokteran FTEIC ITS yang berfokus pada penelitian, pengembangan, dan inovasi alat kesehatan berbasis teknologi digital dan manufaktur aditif.'],
            ['key' => 'announcements', 'content' => 'Pendaftaran Innovatech Medika 2025 telah dibuka! Daftarkan tim Anda sebelum 31 Juli 2025.'],
        ];
        foreach ($pageSections as $s) {
            DB::table('page_sections')->insert([
                'page_name' => 'landing_page', 'section_key' => $s['key'],
                'content' => $s['content'], 'updated_by' => 1, 'updated_at' => now(),
            ]);
        }

        $members = [
            ['user_id' => 1, 'name' => 'Dr. Fajar Nugroho, S.T., M.T.',  'position' => 'Kepala Laboratorium',   'order' => 1],
            ['user_id' => 2, 'name' => 'Rizky Aditya Pratama, S.T.',      'position' => 'Teknisi Lab Senior',    'order' => 2],
            ['user_id' => null, 'name' => 'Prof. Dr. Ir. Hendra Wibawa, M.Eng.', 'position' => 'Dosen Pembimbing', 'order' => 3],
        ];
        foreach ($members as $m) {
            DB::table('structural_members')->insert([
                'user_id' => $m['user_id'], 'name' => $m['name'], 'position' => $m['position'],
                'display_order' => $m['order'], 'is_active' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 11. TRAINING WORKSHOPS
        // ==========================================
        echo "Seeding Training Workshops...\n";
        $this->call(\Database\Seeders\TrainingSeeder::class);

        // ==========================================
        // 12. TRAINING REGISTRATIONS
        // ==========================================
        echo "Seeding Training Registrations...\n";

        // Each index maps to a set of user IDs — all pairs are unique per training
        $registrationMap = [
            0 => [4, 5, 6, 7],
            1 => [6, 7, 8, 9],
            2 => [4, 8, 10, 11],
            3 => [5, 9, 12, 13],
            4 => [6, 10, 13],
            5 => [4, 7, 11, 12],
            6 => [5, 8, 12, 13],
            7 => [6, 9, 10],
            8 => [4, 10, 12],
            9 => [5, 11, 13, 7],
        ];

        $trainingRows = DB::table('trainings')->orderBy('id')->get();

        foreach ($trainingRows as $idx => $training) {
            $userSet = $registrationMap[$idx] ?? [4, 5, 6];
            $isPaid = (bool) $training->is_paid;

            foreach ($userSet as $uid) {
                $profile = DB::table('user_profiles')->where('user_id', $uid)->first();
                $user = DB::table('users')->where('id', $uid)->first();

                // Vary payment status realistically
                $slot = $uid % 3;
                $paymentStatus = 'unpaid';
                $proof = null;
                $verifiedBy = null;
                $regStatus = 'confirmed';

                if ($isPaid) {
                    if ($slot === 0) {
                        $paymentStatus = 'paid';
                        $proof = 'dummy/training_payment_'.$uid.'.jpg';
                        $verifiedBy = 2;
                    } elseif ($slot === 1) {
                        $paymentStatus = 'awaiting_verification';
                        $proof = 'dummy/training_payment_'.$uid.'.jpg';
                    } else {
                        $paymentStatus = 'unpaid';
                        $regStatus = 'pending';
                    }
                }

                DB::table('training_registrations')->insertOrIgnore([
                    'training_id' => $training->id,
                    'user_id' => $uid,
                    'full_name' => $profile?->full_name ?? $user?->name ?? 'Peserta',
                    'email' => $user?->email ?? "user{$uid}@idig.com",
                    'phone_number' => '0812'.str_pad($uid * 13337 % 100000000, 8, '0', STR_PAD_LEFT),
                    'status' => $regStatus,
                    'payment_status' => $paymentStatus,
                    'payment_proof' => $proof,
                    'verified_by' => $verifiedBy,
                    'created_at' => now()->subDays(rand(3, 14)),
                    'updated_at' => now(),
                ]);
            }
        }

        // ==========================================
        // 13. PUBLICATIONS
        // ==========================================
        echo "Seeding Publications...\n";
        $this->call(\Database\Seeders\PublicationSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "\nDONE! Database Seeded Successfully.\n";
        echo "==========================================\n";
        echo "Login Super Admin  : admin@idig.com\n";
        echo "Login Admin Lab    : adminlab@idig.com\n";
        echo "Login Admin Gudang : gudang@idig.com\n";
        echo "Login User         : user4@idig.com ... user13@idig.com\n";
        echo "Password (semua)   : password\n";
    }
}
