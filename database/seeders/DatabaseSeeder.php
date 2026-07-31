<?php

namespace Database\Seeders;

use App\Support\UniqueCodeGenerator;
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

        $brandESUN = DB::table('brands')->insertGetId(['name' => 'eSUN',      'material_category_id' => $catFilament, 'created_at' => now(), 'updated_at' => now()]);
        $brandAnycubic = DB::table('brands')->insertGetId(['name' => 'Anycubic',  'material_category_id' => $catResin,    'created_at' => now(), 'updated_at' => now()]);
        $brandSmoothOn = DB::table('brands')->insertGetId(['name' => 'Smooth-On', 'material_category_id' => $catSilicon,  'created_at' => now(), 'updated_at' => now()]);
        $brandCreality = DB::table('brands')->insertGetId(['name' => 'Creality',  'material_category_id' => null,         'created_at' => now(), 'updated_at' => now()]);
        $brandDekko = DB::table('brands')->insertGetId(['name' => 'Dekko',     'material_category_id' => null,         'created_at' => now(), 'updated_at' => now()]);
        $brandOlympus = DB::table('brands')->insertGetId(['name' => 'Olympus',   'material_category_id' => null,         'created_at' => now(), 'updated_at' => now()]);

        // eSUN filament color catalog + Translucent for resin/silicon
        $colorBlack = DB::table('colors')->insertGetId(['name' => 'Black', 'hex' => '#1C1C1C',          'created_at' => now(), 'updated_at' => now()]);
        $colorWhite = DB::table('colors')->insertGetId(['name' => 'White', 'hex' => '#F4F4F4',          'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Silver', 'hex' => '#C0C0C0',          'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Cold White', 'hex' => '#EDF2F7',      'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Blue', 'hex' => '#2563EB',            'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Fire Engine Red', 'hex' => '#CE2029', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Pink', 'hex' => '#F472B6',            'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Light Grey', 'hex' => '#D1D5DB',      'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Light Blue', 'hex' => '#93C5FD',      'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Natural', 'hex' => '#EDE0CE',         'created_at' => now(), 'updated_at' => now()]);
        $colorGrey = DB::table('colors')->insertGetId(['name' => 'Grey', 'hex' => '#808080',           'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Orange', 'hex' => '#F97316',          'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Purple', 'hex' => '#7C3AED',          'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Green', 'hex' => '#16A34A',           'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Beige', 'hex' => '#E8DCC8',           'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Bone White', 'hex' => '#E3DAC9',      'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Grape Purple', 'hex' => '#6B21A8',    'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Yellow', 'hex' => '#FACC15',          'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Sky Blue', 'hex' => '#38BDF8',        'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Dark Blue', 'hex' => '#1E3A8A',       'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Brown', 'hex' => '#8B5A2B',           'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Barbie Pink', 'hex' => '#E0218A',     'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Red', 'hex' => '#DC2626',             'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Peak Green', 'hex' => '#00A86B',      'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Holly Green', 'hex' => '#0B6E4F',     'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Olive Green', 'hex' => '#708238',     'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Concrete Grey', 'hex' => '#95A5A6',   'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'Aqua', 'hex' => '#00BCD4',            'created_at' => now(), 'updated_at' => now()]);
        DB::table('colors')->insert(['name' => 'RGB Red', 'hex' => '#EF0107',         'created_at' => now(), 'updated_at' => now()]);
        $colorTranslucent = DB::table('colors')->insertGetId(['name' => 'Translucent',    'created_at' => now(), 'updated_at' => now()]);

        // Inventories
        $printerId = DB::table('inventories')->insertGetId(['lab_id' => $labTekkes,    'name' => 'Printer 3D Ender 3 V2',   'brand_id' => $brandCreality, 'total_quantity' => 3, 'available_quantity' => 3, 'created_at' => now()]);
        DB::table('inventories')->insert(['lab_id' => $labTekkes,    'name' => 'Soldering Iron Set',       'brand_id' => $brandDekko,    'total_quantity' => 5, 'available_quantity' => 5, 'created_at' => now()]);
        DB::table('inventories')->insert(['lab_id' => $labPraktikum, 'name' => 'Mikroskop Digital',        'brand_id' => $brandOlympus,  'total_quantity' => 2, 'available_quantity' => 2, 'created_at' => now()]);

        // Raw Materials — unique_code generated explicitly (DB::table bypasses Eloquent boot)
        $filamentId = DB::table('raw_materials')->insertGetId([
            'unique_code' => UniqueCodeGenerator::generate('BAHAN', now(), 'raw_materials'),
            'brand_id' => $brandESUN, 'name' => 'PLA+ 1.75mm 1kg', 'unit' => 'gram',
            'created_by' => 2, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $resinId = DB::table('raw_materials')->insertGetId([
            'unique_code' => UniqueCodeGenerator::generate('BAHAN', now(), 'raw_materials'),
            'brand_id' => $brandAnycubic, 'name' => 'Standard Resin 1L', 'unit' => 'ml',
            'created_by' => 2, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $siliconId = DB::table('raw_materials')->insertGetId([
            'unique_code' => UniqueCodeGenerator::generate('BAHAN', now(), 'raw_materials'),
            'brand_id' => $brandSmoothOn, 'name' => 'Mold Max 30 1kg', 'unit' => 'gram',
            'created_by' => 3, 'created_at' => now(), 'updated_at' => now(),
        ]);

        // Brand ↔ Color declarations (M2M — which colors each brand supports)
        DB::table('brand_colors')->insert([
            ['brand_id' => $brandESUN,     'color_id' => $colorWhite],
            ['brand_id' => $brandESUN,     'color_id' => $colorBlack],
            ['brand_id' => $brandAnycubic, 'color_id' => $colorGrey],
            ['brand_id' => $brandAnycubic, 'color_id' => $colorTranslucent],
            ['brand_id' => $brandSmoothOn, 'color_id' => $colorTranslucent],
        ]);

        // Item stocks — actual quantities per item + color + lab
        DB::table('item_stocks')->insert([
            ['raw_material_id' => $filamentId, 'color_id' => $colorWhite,      'lab_id' => $labTekkes,    'quantity' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['raw_material_id' => $resinId,    'color_id' => $colorGrey,        'lab_id' => $labTekkes,    'quantity' => 2000, 'created_at' => now(), 'updated_at' => now()],
            ['raw_material_id' => $siliconId,  'color_id' => $colorTranslucent, 'lab_id' => $labPraktikum, 'quantity' => 3000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ==========================================
        // 5. TOOLS
        // ==========================================
        echo "Seeding Tools...\n";

        $toolsData = [
            ['name' => 'Bambu Lab X1 Carbon 3D Printer',  'lab_id' => $labTekkes,    'created_by' => 3],
            ['name' => 'Anycubic Wash & Cure Station',     'lab_id' => $labTekkes,    'created_by' => 3],
            ['name' => 'Artec Eva 3D Scanner',             'lab_id' => $labTekkes,    'created_by' => 1],
            ['name' => 'Digital Caliper 0.01mm',           'lab_id' => $labTekkes,    'created_by' => 2],
            ['name' => 'Heat Gun Rion 2000W',              'lab_id' => $labTekkes,    'created_by' => 3],
            ['name' => 'Oscilloscope Rigol DS1054Z',       'lab_id' => $labPraktikum, 'created_by' => 2],
            ['name' => 'Digital Multimeter Fluke 87V',     'lab_id' => $labPraktikum, 'created_by' => 2],
            ['name' => 'EEG Headset OpenBCI Cyton',        'lab_id' => $labPraktikum, 'created_by' => 1],
            ['name' => 'UPS APC 1000VA',                   'lab_id' => $labTekkes,    'created_by' => 3],
            ['name' => 'Vacuum Degassing Chamber',         'lab_id' => $labTekkes,    'created_by' => 3],
        ];

        $firstToolId = null;
        foreach ($toolsData as $tool) {
            $toolId = DB::table('tools')->insertGetId([
                'unique_code' => UniqueCodeGenerator::generate('ALAT', now(), 'tools'),
                'name' => $tool['name'],
                'lab_id' => $tool['lab_id'],
                'created_by' => $tool['created_by'],
                'created_at' => now()->subDays(rand(10, 120)),
                'updated_at' => now(),
            ]);
            if ($firstToolId === null) {
                $firstToolId = $toolId;
            }
        }

        // ==========================================
        // 6. SERVICES
        // ==========================================
        echo "Seeding Services...\n";

        $printingServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Print 3D (FDM/SLA)',
            'name_en' => '3D Printing Service (FDM/SLA)',
            'service_type' => 'printing',
            'description' => 'Layanan cetak 3D dengan akurasi tinggi menggunakan filamen FDM maupun resin SLA. Cocok untuk prototip medis, ortosis, dan prostetik.',
            'description_en' => 'High-accuracy 3D printing using either FDM filament or SLA resin. Suited to medical prototypes, orthoses, and prosthetics.',
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
            'name_en' => '3D CAD Design Service',
            'service_type' => 'design',
            'description' => 'Pembuatan model 3D dari sketsa, foto referensi, atau spesifikasi teknis menggunakan Fusion 360 dan SolidWorks.',
            'description_en' => '3D models built from a sketch, reference photo, or technical specification, using Fusion 360 and SolidWorks.',
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

        // Consultation is a chat thread, not an orderable service — no attachment,
        // no price. ServicesController::consultation() firstOrCreates it anyway.
        DB::table('services')->insert([
            'name' => 'Konsultasi',
            'name_en' => 'Consultation',
            'service_type' => 'consultation',
            'description' => 'Diskusikan ide Anda dengan tim lab sebelum memesan layanan. Admin akan membalas langsung di ruang obrolan.',
            'description_en' => 'Talk your idea through with the lab team before ordering a service. An admin replies directly in the chat room.',
            'base_price' => 0,
        ]);

        $scanningServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Scanning 3D',
            'name_en' => '3D Scanning Service',
            'service_type' => 'scanning',
            'description' => 'Pemindaian objek fisik menjadi model 3D digital. Ideal untuk rekayasa balik, dokumentasi arsip, dan analisis geometri presisi.',
            'description_en' => 'Turns a physical object into a digital 3D model. Ideal for reverse engineering, archival documentation, and precision geometry analysis.',
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
        // 7. EVENTS, TEAMS & PROJECTS
        // ==========================================
        $this->call(EventSeeder::class);

        // ==========================================
        // 8. PRODUCTS (Made-by-Order Catalog)
        // ==========================================
        echo "Seeding Products Portfolio...\n";

        // `name_en`/`desc_en` back the `_en` overlay columns — see
        // App\Traits\HasEnglishOverlay and the add_english_overlay_columns migration.
        $products = [
            ['name' => 'Exoskeleton Jari Fleksibel',      'name_en' => 'Flexible Finger Exoskeleton',        'desc' => 'Exoskeleton jari berbahan TPU untuk rehabilitasi pasca-stroke.',                         'desc_en' => 'A TPU finger exoskeleton for post-stroke rehabilitation.',                                              'min' => 180000,  'max' => 350000],
            ['name' => 'Splint Pergelangan Cetak',         'name_en' => 'Printed Wrist Splint',              'desc' => 'Splint kustom berbasis scan tangan pengguna, material PETG ringan.',                     'desc_en' => 'A custom splint built from a scan of the wearer\'s hand, in lightweight PETG.',                          'min' => 120000,  'max' => 250000],
            ['name' => 'Ortosis Pergelangan Kaki (AFO)',   'name_en' => 'Ankle-Foot Orthosis (AFO)',         'desc' => 'Ankle-foot orthosis ringan dari PLA/PETG, dicetak sesuai ukuran pasien.',               'desc_en' => 'A lightweight PLA/PETG ankle-foot orthosis, printed to the patient\'s measurements.',                    'min' => 200000,  'max' => 400000],
            ['name' => 'Prostetik Jari Tangan Pasif',      'name_en' => 'Passive Finger Prosthesis',         'desc' => 'Prostetik jari fungsional dengan sendi engsel, tahan air, bisa dikostumisasi.',         'desc_en' => 'A functional finger prosthesis with a hinged joint — waterproof and customisable.',                     'min' => 150000,  'max' => 300000],
            ['name' => 'Model Anatomis Tulang Belakang',   'name_en' => 'Anatomical Spine Model',            'desc' => 'Replika vertebra lumbal skala 1:1 dari data MRI/CT-Scan untuk simulasi bedah.',         'desc_en' => 'A 1:1 lumbar vertebrae replica built from MRI/CT data, for surgical simulation.',                       'min' => 500000,  'max' => 1200000],
            ['name' => 'Tempat Implan Gigi Custom',        'name_en' => 'Custom Dental Implant Guide',       'desc' => 'Panduan bedah implan gigi 3D berdasarkan data CBCT pasien.',                             'desc_en' => 'A 3D dental implant surgical guide derived from the patient\'s CBCT data.',                              'min' => 350000,  'max' => 750000],
            ['name' => 'Casing Perangkat Medis Portable',  'name_en' => 'Portable Medical Device Casing',    'desc' => 'Casing ergonomis untuk perangkat diagnostik portabel — desain khusus sesuai kebutuhan.', 'desc_en' => 'An ergonomic casing for portable diagnostic devices — designed to your requirements.',                  'min' => 100000,  'max' => 220000],
            ['name' => 'Jig Pembedahan Ortopedi',          'name_en' => 'Orthopaedic Surgical Jig',          'desc' => 'Jig potong tulang presisi tinggi berdasarkan data 3D scan pasien.',                     'desc_en' => 'A high-precision bone-cutting jig based on a 3D scan of the patient.',                                  'min' => 600000,  'max' => 1500000],
            ['name' => 'Prototip Kursi Roda Ringan',       'name_en' => 'Lightweight Wheelchair Prototype',  'desc' => 'Komponen rangka kursi roda berbahan komposit dicetak 3D, lebih ringan 40% dari besi.',  'desc_en' => '3D-printed composite wheelchair frame components — 40% lighter than steel.',                            'min' => 800000,  'max' => 2000000],
            ['name' => 'Tempat Sterilisasi Alat Bedah',    'name_en' => 'Surgical Instrument Sterilisation Tray', 'desc' => 'Tray sterilisasi cetakan 3D heat-resistant untuk autoclave hingga 134°C.',        'desc_en' => 'A heat-resistant 3D-printed sterilisation tray, autoclave-safe to 134°C.',                              'min' => 80000,   'max' => 180000],
        ];
        foreach ($products as $index => $p) {
            $productId = DB::table('products')->insertGetId([
                'creator_id' => 1, 'name' => $p['name'], 'name_en' => $p['name_en'],
                'description' => $p['desc'], 'description_en' => $p['desc_en'],
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
        // 9. REIMBURSEMENTS (4 rows, varied statuses)
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
        // 10. SERVICE BOOKINGS (12 explicit bookings)
        // ==========================================
        echo "Seeding Service Bookings, Messages & Payments...\n";

        /*
         * Booking definitions using the current 8-stage BookingStatus pipeline:
         * review_brief → check_material → slicing → set_price → awaiting_dp
         *   → printing → finishing → final_payment → completed | cancelled
         *
         * 'material' key covers the material verification fields added 2026-07-07.
         * DP is 30% of agreed_price (mandatory minimum per booking policy).
         */
        $noMaterial = ['verified_at' => null, 'verified_by' => null, 'flagged_at' => null, 'flag_note' => null];
        $matVerified = fn ($daysAgo) => ['verified_at' => now()->subDays($daysAgo), 'verified_by' => 3, 'flagged_at' => null, 'flag_note' => null];

        $bookingDefs = [
            // 1. review_brief — just submitted, admin reviewing brief
            [
                'user_id' => 4, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'review_brief', 'agreed_price' => null, 'days_ago' => 1,
                'weight' => null,
                'brief' => 'Cetak model splint pergelangan tangan kiri, ukuran 18×10×5 cm, material PETG warna putih, 1 unit.',
                'fields' => ['material_preference' => 'PETG', 'object_dimensions' => json_encode(['length' => '18', 'width' => '10', 'height' => '5']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null, 'filament_width' => null, 'scan_purpose' => null],
                'material' => $noMaterial,
                'messages' => [],
                'progress' => [],
            ],
            // 2. check_material — admin reviewed brief, gudang flagged material shortage
            [
                'user_id' => 5, 'service_id' => $designServiceId, 'type' => 'design',
                'status' => 'check_material', 'agreed_price' => null, 'days_ago' => 2,
                'weight' => null,
                'brief' => 'Butuh desain 3D implan gigi untuk pasien dengan kehilangan gigi molar kanan bawah. Referensi foto X-ray sudah saya siapkan.',
                'fields' => ['material_preference' => null, 'filament_width' => '1.75 mm', 'scan_purpose' => null, 'object_dimensions' => json_encode(['length' => '2', 'width' => '1', 'height' => '1']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'material' => ['verified_at' => null, 'verified_by' => null, 'flagged_at' => now()->subDays(1), 'flag_note' => 'Stok filamen putih hampir habis, segera restock sebelum produksi dimulai.'],
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo admin, saya perlu desain 3D implan gigi molar. File X-ray referensi sudah saya lampirkan.', 'daysAgo' => 2],
                    ['sender' => 'admin', 'body' => 'Halo! Brief sudah kami terima dan sedang kami review. Tim gudang akan mengecek ketersediaan material terlebih dahulu.', 'daysAgo' => 1],
                ],
                'progress' => [],
            ],
            // 3. set_price — material verified, negotiating price
            [
                'user_id' => 6, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'set_price', 'agreed_price' => null, 'days_ago' => 4,
                'weight' => 180,
                'brief' => 'Cetak prostetik jari tangan (4 jari) berbahan TPU Shore 95A. Ukuran disesuaikan scan tangan kanan pasien. Warna skin-tone.',
                'fields' => ['material_preference' => 'TPU', 'object_dimensions' => json_encode(['length' => '10', 'width' => '7', 'height' => '4']), 'slicer_weight_grams' => 180, 'slicer_print_time_minutes' => 270, 'filament_width' => null, 'scan_purpose' => null],
                'material' => $matVerified(3),
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo admin, saya ingin mencetak prostetik 4 jari tangan berbahan TPU. Sudah saya lampirkan file STL-nya di email.', 'daysAgo' => 4],
                    ['sender' => 'admin', 'body' => 'Halo Bapak/Ibu! Kami sudah terima email-nya. Material TPU Shore 95A tersedia di lab kami. Untuk ukuran dan bobot yang Anda minta, estimasi harga sekitar Rp 280.000–Rp 320.000. Bisa dikonfirmasi?', 'daysAgo' => 3],
                    ['sender' => 'user',  'body' => 'Apakah bisa lebih murah? Ini untuk pasien dengan kondisi ekonomi terbatas.', 'daysAgo' => 3],
                    ['sender' => 'admin', 'body' => 'Kami bisa berikan harga Rp 280.000. Material sudah dicek tersedia. Apakah setuju?', 'daysAgo' => 2],
                ],
                'progress' => [],
            ],
            // 4. awaiting_dp — price agreed, waiting for 30% DP
            [
                'user_id' => 7, 'service_id' => $scanningServiceId, 'type' => 'scanning',
                'status' => 'awaiting_dp', 'agreed_price' => 250000, 'days_ago' => 5,
                'weight' => null,
                'brief' => 'Scan geometri penopang lutut custom buatan tangan (kayu + busa). Ukuran sekitar 30×20×15 cm. Tujuan: rekayasa balik untuk produksi polimer.',
                'fields' => ['material_preference' => null, 'filament_width' => null, 'scan_purpose' => 'Rekayasa Balik', 'object_dimensions' => json_encode(['length' => '30', 'width' => '20', 'height' => '15']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'material' => $matVerified(4),
                'messages' => [
                    ['sender' => 'user',  'body' => 'Selamat siang. Saya ingin scan knee brace custom buatan tangan untuk keperluan rekayasa balik. Kapan saya bisa membawa objeknya ke lab?', 'daysAgo' => 5],
                    ['sender' => 'admin', 'body' => 'Selamat siang! Silakan datang ke Lab Tekkes pada hari Senin–Jumat pukul 09.00–15.00. Objek dengan ukuran tersebut estimasi waktu scan 2–3 jam. Biaya Rp 250.000. Setuju?', 'daysAgo' => 4],
                    ['sender' => 'user',  'body' => 'Apakah hasil scan berformat STEP atau hanya STL? Saya butuh STEP untuk modifikasi di CAD.', 'daysAgo' => 4],
                    ['sender' => 'admin', 'body' => 'Kami bisa ekspor ke STL, OBJ, dan STEP. Harga sudah mencakup format STEP: Rp 250.000 total. Silakan transfer DP 30% untuk konfirmasi slot.', 'daysAgo' => 3],
                ],
                'progress' => [],
            ],
            // 5. slicing — price agreed, DP paid, file being sliced
            [
                'user_id' => 8, 'service_id' => $designServiceId, 'type' => 'design',
                'status' => 'slicing', 'agreed_price' => 250000, 'days_ago' => 10,
                'weight' => null,
                'brief' => 'Desain 3D casing perangkat oximeter portabel. Dimensi PCB: 60×40×10 mm. Butuh slot baterai 18650, lubang sensor, dan clip penjepit jari.',
                'fields' => ['material_preference' => null, 'filament_width' => '1.75 mm', 'scan_purpose' => null, 'object_dimensions' => json_encode(['length' => '70', 'width' => '50', 'height' => '25']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'material' => $matVerified(9),
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya butuh desain casing oximeter portabel. PCB saya 60×40×10 mm, butuh slot baterai 18650 dan lubang sensor.', 'daysAgo' => 10],
                    ['sender' => 'admin', 'body' => 'Halo! Kebutuhan Anda sudah kami catat. Kami bisa kerjakan dalam Fusion 360 dengan estimasi 3–5 hari. Harga Rp 250.000 sudah termasuk 2x revisi. Setuju?', 'daysAgo' => 9],
                    ['sender' => 'user',  'body' => 'Setuju. Saya akan transfer DP 30% sekarang.', 'daysAgo' => 9],
                    ['sender' => 'admin', 'body' => 'DP sudah kami terima dan dikonfirmasi. Proses desain dimulai. Kami akan kirim progress update dalam 2 hari.', 'daysAgo' => 8],
                ],
                'progress' => [
                    ['label' => 'Konsultasi & Brief', 'pct' => 20, 'notes' => 'Kebutuhan desain sudah dikonfirmasi. Dimensi PCB dicatat.', 'daysAgo' => 9],
                ],
            ],
            // 6. printing — DP paid, actively printing
            [
                'user_id' => 9, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'printing', 'agreed_price' => 180000, 'days_ago' => 8,
                'weight' => 120,
                'brief' => 'Cetak 2 unit ortosis pergelangan kaki anak (AFO). Material PLA warna putih. Ukuran S (panjang 22 cm). File STL terlampir.',
                'fields' => ['material_preference' => 'PLA', 'object_dimensions' => json_encode(['length' => '22', 'width' => '12', 'height' => '8']), 'slicer_weight_grams' => 120, 'slicer_print_time_minutes' => 240, 'filament_width' => null, 'scan_purpose' => null],
                'material' => $matVerified(7),
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
            // 7. finishing — scan done, in post-processing/finishing stage
            [
                'user_id' => 10, 'service_id' => $scanningServiceId, 'type' => 'scanning',
                'status' => 'finishing', 'agreed_price' => 200000, 'days_ago' => 7,
                'weight' => null,
                'brief' => 'Scan tulang rusuk tiruan untuk keperluan simulasi bedah torakoplasti. Ukuran: 25×15×10 cm. Akurasi minimal ±0.5mm.',
                'fields' => ['material_preference' => null, 'filament_width' => null, 'scan_purpose' => 'Simulasi Bedah', 'object_dimensions' => json_encode(['length' => '25', 'width' => '15', 'height' => '10']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'material' => $matVerified(6),
                'messages' => [
                    ['sender' => 'user',  'body' => 'Selamat pagi. Saya butuh scan tulang rusuk untuk simulasi bedah. Objek berupa replika resin yang saya bawa sendiri.', 'daysAgo' => 7],
                    ['sender' => 'admin', 'body' => 'Selamat pagi! Silakan. Untuk akurasi ±0.5mm kami gunakan scanner Artec Eva. Biaya Rp 200.000. Kapan bisa datang?', 'daysAgo' => 6],
                    ['sender' => 'user',  'body' => 'Selasa jam 10 pagi bisa tidak?', 'daysAgo' => 6],
                    ['sender' => 'admin', 'body' => 'Bisa. Kami sudah reservasi slot Selasa 10.00 atas nama Anda. Mohon datang tepat waktu.', 'daysAgo' => 5],
                    ['sender' => 'user',  'body' => 'Terima kasih, DP sudah ditransfer.', 'daysAgo' => 4],
                ],
                'progress' => [
                    ['label' => 'Persiapan Scanning', 'pct' => 25, 'notes' => 'Objek diterima dan dikalibrasi. Scanner Artec Eva siap digunakan.', 'daysAgo' => 4],
                    ['label' => 'Proses Scanning', 'pct' => 55, 'notes' => 'Scanning 100% selesai. Point cloud resolusi tinggi berhasil dibuat.', 'daysAgo' => 2],
                    ['label' => 'Post-Processing', 'pct' => 75, 'notes' => 'Noise filtering dan mesh reconstruction sedang berjalan.', 'daysAgo' => 1],
                ],
            ],
            // 8. final_payment — printing done, waiting for pelunasan
            [
                'user_id' => 11, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'final_payment', 'agreed_price' => 320000, 'days_ago' => 12,
                'weight' => 250,
                'brief' => 'Cetak replika model anatomi jantung skala 1:1 dari data CT-Scan DICOM. Material Resin warna merah transparan. Perlu detail pembuluh darah yang akurat.',
                'fields' => ['material_preference' => 'Resin', 'object_dimensions' => json_encode(['length' => '12', 'width' => '10', 'height' => '14']), 'slicer_weight_grams' => 250, 'slicer_print_time_minutes' => 480, 'filament_width' => null, 'scan_purpose' => null],
                'material' => $matVerified(11),
                'messages' => [
                    ['sender' => 'user',  'body' => 'Halo, saya butuh model anatomi jantung dari data DICOM CT-Scan. Perlu detail pembuluh darah yang akurat untuk presentasi medis.', 'daysAgo' => 12],
                    ['sender' => 'admin', 'body' => 'Halo! Kami terima file DICOM-nya. Setelah review, butuh konversi ke STL dahulu menggunakan 3D Slicer (software). Biaya total Rp 320.000 termasuk konversi dan cetak resin. Setuju?', 'daysAgo' => 11],
                    ['sender' => 'user',  'body' => 'Setuju. Ini untuk presentasi ke dokter spesialis bulan depan, jadi kualitas sangat penting.', 'daysAgo' => 11],
                    ['sender' => 'admin', 'body' => 'Dipahami. Kami gunakan resin transparan merah untuk menonjolkan pembuluh darah. DP sudah terkonfirmasi, proses dimulai.', 'daysAgo' => 10],
                    ['sender' => 'admin', 'body' => 'Update progress: konversi DICOM ke STL selesai. Model siap masuk printer SLA. Estimasi cetak 8 jam.', 'daysAgo' => 7],
                    ['sender' => 'admin', 'body' => 'Cetak selesai! Model sudah melalui proses washing & curing. Kualitas sangat baik. Silakan lakukan pelunasan untuk pengambilan.', 'daysAgo' => 2],
                ],
                'progress' => [
                    ['label' => 'Konversi DICOM ke STL', 'pct' => 25, 'notes' => 'File DICOM berhasil dikonversi ke STL menggunakan 3D Slicer. Mesh di-repair untuk memperbaiki artefak scan.', 'daysAgo' => 9],
                    ['label' => 'Slicing & Setup SLA', 'pct' => 50, 'notes' => 'File di-slice dengan Chitubox. Support structure ditambahkan pada area pembuluh darah tipis.', 'daysAgo' => 7],
                    ['label' => 'Printing SLA', 'pct' => 80, 'notes' => 'Proses cetak resin selesai. Washing & curing sudah dilakukan. Kualitas layer sangat baik.', 'daysAgo' => 3],
                    ['label' => 'Finishing & QC', 'pct' => 95, 'notes' => 'Support dilepas, permukaan dihaluskan. Model sudah siap diserahkan setelah pelunasan.', 'daysAgo' => 2],
                ],
            ],
            // 9. completed — design selesai, all paid
            [
                'user_id' => 12, 'service_id' => $designServiceId, 'type' => 'design',
                'status' => 'completed', 'agreed_price' => 300000, 'days_ago' => 20,
                'weight' => null,
                'brief' => 'Desain 3D bracket penopang alat EEG portabel. Harus bisa dipasang di kepala (adjustable headband). Material ABS, perlu toleransi 0.2mm untuk fitting elektroda.',
                'fields' => ['material_preference' => null, 'filament_width' => '1.75 mm', 'scan_purpose' => null, 'object_dimensions' => json_encode(['length' => '20', 'width' => '15', 'height' => '10']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'material' => $matVerified(19),
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
            // 10. completed — printing selesai, all paid
            [
                'user_id' => 13, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'completed', 'agreed_price' => 150000, 'days_ago' => 15,
                'weight' => 85,
                'brief' => 'Cetak 1 unit casing sensor tekanan darah wearable. PLA hitam. Ukuran 40×30×20 mm. Butuh lubang untuk kabel ribbon dan port USB-C.',
                'fields' => ['material_preference' => 'PLA', 'object_dimensions' => json_encode(['length' => '40', 'width' => '30', 'height' => '20']), 'slicer_weight_grams' => 85, 'slicer_print_time_minutes' => 150, 'filament_width' => null, 'scan_purpose' => null],
                'material' => $matVerified(14),
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
            // 11. completed — scanning selesai, all paid
            [
                'user_id' => 4, 'service_id' => $scanningServiceId, 'type' => 'scanning',
                'status' => 'completed', 'agreed_price' => 220000, 'days_ago' => 25,
                'weight' => null,
                'brief' => 'Scan siku ortosis custom dari kulit sintetis untuk arsip digital dan kemungkinan reproduksi. Ukuran 20×10×8 cm. Format output: STL + STEP.',
                'fields' => ['material_preference' => null, 'filament_width' => null, 'scan_purpose' => 'Arsip Digital', 'object_dimensions' => json_encode(['length' => '20', 'width' => '10', 'height' => '8']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null],
                'material' => $matVerified(24),
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
            // 12. cancelled
            [
                'user_id' => 5, 'service_id' => $printingServiceId, 'type' => 'printing',
                'status' => 'cancelled', 'agreed_price' => null, 'days_ago' => 18,
                'weight' => null,
                'brief' => 'Cetak topeng wajah rehabilitasi berbahan PETG untuk terapi keloid pasca-luka bakar. File STL akan dikirim menyusul.',
                'fields' => ['material_preference' => 'PETG', 'object_dimensions' => json_encode(['length' => '20', 'width' => '15', 'height' => '8']), 'slicer_weight_grams' => null, 'slicer_print_time_minutes' => null, 'filament_width' => null, 'scan_purpose' => null],
                'material' => $noMaterial,
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
            // Statuses where DP has already been paid and production is underway
            $needsPayment = ($def['agreed_price'] !== null) && in_array($def['status'], [
                'slicing', 'printing', 'finishing', 'final_payment', 'completed',
            ]);

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
                'material_verified_at' => $def['material']['verified_at'],
                'material_verified_by' => $def['material']['verified_by'],
                'material_flagged_at' => $def['material']['flagged_at'],
                'material_flag_note' => $def['material']['flag_note'],
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

            // Payment termins — 30% DP mandatory, 70% remainder as pelunasan
            if ($needsPayment) {
                $dp = intdiv($def['agreed_price'] * 30, 100);
                $pelunasan = $def['agreed_price'] - $dp;

                DB::table('booking_payments')->insert([
                    'service_booking_id' => $bookingId,
                    'amount' => $dp,
                    'termin_name' => 'Down Payment (30%)',
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
                    'termin_name' => 'Pelunasan (70%)',
                    'status' => $isCompleted ? 'paid' : 'pending',
                    'payment_proof' => $isCompleted ? 'dummy/proof_lunas_'.$bookingId.'.jpg' : null,
                    'paid_at' => $isCompleted ? now()->subDays($daysAgo - 3) : null,
                    'verified_by' => $isCompleted ? 2 : null,
                    'created_at' => now()->subDays($daysAgo),
                    'updated_at' => $isCompleted ? now()->subDays($daysAgo - 3) : now(),
                ]);
            }

            // Inventory usage for printing bookings once production started
            if ($def['type'] === 'printing' && $needsPayment) {
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

            // Raw material deduction for printing jobs with slicer weight
            if ($def['type'] === 'printing' && $def['weight'] && $needsPayment) {
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
        // 11. ISSUE REPORTS
        // ==========================================
        echo "Seeding Issue Reports...\n";

        DB::table('issue_reports')->insert([
            [
                'reporter_id' => 3,
                'reportable_type' => 'App\Models\RawMaterial',
                'reportable_id' => $filamentId,
                'type' => 'damaged',
                'description' => 'Sebagian roll filamen PLA White ditemukan rusak — filamen kusut di dalam spool, tidak bisa digunakan untuk produksi.',
                'status' => 'open',
                'resolution_note' => null,
                'resolved_by' => null,
                'resolved_at' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'reporter_id' => 3,
                'reportable_type' => 'App\Models\RawMaterial',
                'reportable_id' => $resinId,
                'type' => 'out_of_stock',
                'description' => 'Stok resin Anycubic Standard Grey sudah habis. Perlu segera restock sebelum order berikutnya diproses.',
                'status' => 'in_review',
                'resolution_note' => null,
                'resolved_by' => null,
                'resolved_at' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ],
            [
                'reporter_id' => 2,
                'reportable_type' => 'App\Models\Tool',
                'reportable_id' => $firstToolId,
                'type' => 'faulty',
                'description' => 'Bambu Lab X1 Carbon mengalami error "Nozzle Clogged" berulang kali. Sudah dicoba dibersihkan manual namun tetap error.',
                'status' => 'resolved',
                'resolution_note' => 'Nozzle diganti dengan unit baru. Printer sudah ditest cetak 3 lapisan pertama dan berjalan normal.',
                'resolved_by' => 1,
                'resolved_at' => now()->subDays(1),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(1),
            ],
            [
                'reporter_id' => 2,
                'reportable_type' => null,
                'reportable_id' => null,
                'type' => 'other',
                'description' => 'AC di Lab Tekkes mati. Suhu ruangan naik di atas 30°C yang berdampak pada kualitas hasil cetak resin.',
                'status' => 'open',
                'resolution_note' => null,
                'resolved_by' => null,
                'resolved_at' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);

        // ==========================================
        // 12. CMS DATA
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
        // 13. TRAINING WORKSHOPS
        // ==========================================
        echo "Seeding Training Workshops...\n";
        $this->call(TrainingSeeder::class);

        // ==========================================
        // 14. TRAINING REGISTRATIONS
        // ==========================================
        echo "Seeding Training Registrations...\n";

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
        // 15. PUBLICATIONS
        // ==========================================
        echo "Seeding Publications...\n";
        $this->call(PublicationSeeder::class);

        // ==========================================
        // 16. LAB TEAM SECTIONS (landing page org chart)
        // ==========================================
        echo "Seeding Lab Team Sections...\n";
        $this->call(LabTeamSectionSeeder::class);

        // ==========================================
        // 17. LANDING PAGE CONTENT (CMS)
        // ==========================================
        echo "Seeding Landing Page Content...\n";
        $this->call(LandingContentSeeder::class);

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
