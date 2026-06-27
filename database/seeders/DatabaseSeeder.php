<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        echo "Menyiapkan Database...\n";

        // Matikan pengecekan foreign key sementara selama proses seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ==========================================
        // 1. ROLES
        // ==========================================
        echo "Seeding Roles...\n";
        $roles = ['super_admin', 'admin_lab', 'mahasiswa', 'user_publik', 'admin_gudang'];
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore(['name' => $role]);
        }

        // ==========================================
        // 2. USERS & PROFILES
        // ==========================================
        echo "Seeding Users & Profiles...\n";

        $usersData = [
            ['role_id' => 1, 'name' => 'Super Admin', 'email' => 'admin@gretiva.com', 'role_name' => 'Head of Lab'],
            ['role_id' => 2, 'name' => 'Admin Lab 1', 'email' => 'adminlab@gretiva.com', 'role_name' => 'Teknisi Lab'],
            ['role_id' => 5, 'name' => 'Admin Gudang', 'email' => 'gudang@gretiva.com', 'role_name' => 'Petugas Gudang'],
        ];

        // Create Core Admins
        foreach ($usersData as $index => $data) {
            $userId = $index + 1;
            DB::table('users')->insert([
                'id' => $userId,
                'role_id' => $data['role_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_profiles')->insert([
                'user_id' => $userId,
                'full_name' => $data['name'],
                'department' => 'Teknologi Kedokteran',
                'faculty' => 'FTEIC',
                'university' => 'ITS',
                'updated_at' => now(),
            ]);
        }

        // Create 10 Dummy Users (Mahasiswa & Publik)
        for ($i = 4; $i <= 13; $i++) {
            $roleId = $faker->randomElement([3, 3, 3, 4]); // Dominan mahasiswa (3)
            $generatedName = $faker->name;

            DB::table('users')->insert([
                'id' => $i,
                'role_id' => $roleId,
                'name' => $generatedName,
                'email' => "user{$i}@gretiva.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_profiles')->insert([
                'user_id' => $i,
                'full_name' => $generatedName,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'nik' => $roleId == 4 ? $faker->nik : null,
                'nim' => $roleId == 3 ? '50312010'.str_pad($i, 2, '0', STR_PAD_LEFT) : null,
                'department' => $roleId == 3 ? 'Teknologi Kedokteran' : null,
                'faculty' => $roleId == 3 ? 'FTEIC' : null,
                'university' => $roleId == 3 ? 'ITS' : null,
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

        // --- Labs ---
        $labTekkes = DB::table('labs')->insertGetId(['name' => 'Lab Tekkes', 'created_at' => now(), 'updated_at' => now()]);
        $labPraktikum = DB::table('labs')->insertGetId(['name' => 'Lab Praktikum', 'created_at' => now(), 'updated_at' => now()]);

        // --- Material Categories ---
        $catFilament = DB::table('material_categories')->insertGetId(['name' => 'Filament', 'created_at' => now(), 'updated_at' => now()]);
        $catResin = DB::table('material_categories')->insertGetId(['name' => 'Resin', 'created_at' => now(), 'updated_at' => now()]);
        $catSilicon = DB::table('material_categories')->insertGetId(['name' => 'Silicon', 'created_at' => now(), 'updated_at' => now()]);

        // --- Brands ---
        $brandESUN = DB::table('brands')->insertGetId(['name' => 'eSUN', 'created_at' => now(), 'updated_at' => now()]);
        $brandAnycubic = DB::table('brands')->insertGetId(['name' => 'Anycubic', 'created_at' => now(), 'updated_at' => now()]);
        $brandSmoothOn = DB::table('brands')->insertGetId(['name' => 'Smooth-On', 'created_at' => now(), 'updated_at' => now()]);
        $brandCreality = DB::table('brands')->insertGetId(['name' => 'Creality', 'created_at' => now(), 'updated_at' => now()]);
        $brandDekko = DB::table('brands')->insertGetId(['name' => 'Dekko', 'created_at' => now(), 'updated_at' => now()]);
        $brandOlympus = DB::table('brands')->insertGetId(['name' => 'Olympus', 'created_at' => now(), 'updated_at' => now()]);

        // --- Colors ---
        $colorWhite = DB::table('colors')->insertGetId(['name' => 'White', 'created_at' => now(), 'updated_at' => now()]);
        $colorGrey = DB::table('colors')->insertGetId(['name' => 'Standard Grey', 'created_at' => now(), 'updated_at' => now()]);
        $colorTranslucent = DB::table('colors')->insertGetId(['name' => 'Translucent', 'created_at' => now(), 'updated_at' => now()]);

        // Seed Inventories (Tools/Assets) — using FK IDs
        $inventories = [
            ['lab_id' => $labTekkes, 'name' => 'Printer 3D Ender 3 V2', 'brand_id' => $brandCreality, 'total_quantity' => 3, 'available_quantity' => 3],
            ['lab_id' => $labTekkes, 'name' => 'Soldering Iron Set', 'brand_id' => $brandDekko, 'total_quantity' => 5, 'available_quantity' => 5],
            ['lab_id' => $labPraktikum, 'name' => 'Mikroskop Digital', 'brand_id' => $brandOlympus, 'total_quantity' => 2, 'available_quantity' => 2],
        ];
        foreach ($inventories as $inv) {
            DB::table('inventories')->insert(array_merge($inv, ['created_at' => now()]));
        }

        // Seed Raw Materials (Normalized FK references)
        $materials = [
            ['lab_id' => $labTekkes, 'material_category_id' => $catFilament, 'brand_id' => $brandESUN, 'color_id' => $colorWhite, 'unit' => 'gram', 'current_stock' => 5000],
            ['lab_id' => $labTekkes, 'material_category_id' => $catResin, 'brand_id' => $brandAnycubic, 'color_id' => $colorGrey, 'unit' => 'ml', 'current_stock' => 2000],
            ['lab_id' => $labPraktikum, 'material_category_id' => $catSilicon, 'brand_id' => $brandSmoothOn, 'color_id' => $colorTranslucent, 'unit' => 'gram', 'current_stock' => 3000],
        ];
        foreach ($materials as $mat) {
            DB::table('raw_materials')->insert(array_merge($mat, ['created_at' => now()]));
        }

        // Seed Services — capture IDs so bookings can reference the correct service
        $printingServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Print 3D (FDM/SLA)',
            'service_type' => 'printing',
            'description' => 'Layanan cetak 3D dengan akurasi tinggi menggunakan filamen FDM maupun resin SLA.',
            'base_price' => 2000,
        ]);
        $designServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Desain 3D CAD',
            'service_type' => 'design',
            'description' => 'Pembuatan model 3D dari sketsa atau referensi foto dengan software CAD profesional.',
            'base_price' => 150000,
        ]);
        $scanningServiceId = DB::table('services')->insertGetId([
            'name' => 'Jasa Scanning 3D',
            'service_type' => 'scanning',
            'description' => 'Pemindaian objek fisik menjadi model 3D digital untuk keperluan rekayasa balik, arsip, atau analisis.',
            'base_price' => 100000,
        ]);

        // ==========================================
        // 4. EVENTS, TEAMS, & PROJECTS
        // ==========================================
        echo "Seeding Events & Projects...\n";

        for ($i = 1; $i <= 5; $i++) {
            $eventId = DB::table('events')->insertGetId([
                'name' => "Innovatech Medika 202{$i}",
                'year' => 2020 + $i,
                'theme_title' => $faker->sentence(4),
                'is_active' => $i == 5 ? 1 : 0,
            ]);

            for ($t = 1; $t <= 2; $t++) {
                $teamId = DB::table('teams')->insertGetId([
                    'event_id' => $eventId,
                    'name' => 'Tim '.$faker->word,
                    'course_name' => 'Perancangan Alat Medis',
                    'created_at' => now(),
                ]);

                // Assign random mahasiswa
                DB::table('team_members')->insert([
                    ['team_id' => $teamId, 'user_id' => $faker->numberBetween(4, 8), 'role_in_team' => 'Ketua'],
                    ['team_id' => $teamId, 'user_id' => $faker->numberBetween(9, 13), 'role_in_team' => 'Anggota'],
                ]);

                // Create Project
                DB::table('projects')->insert([
                    'team_id' => $teamId,
                    'title' => 'Alat '.$faker->words(3, true),
                    'category' => '3d_products',
                    'status' => 'approved',
                    'validated_by' => 1,
                    'created_at' => now(),
                ]);
            }
        }

        // ==========================================
        // 5. CATALOG PRODUCTS (Made by Order)
        // ==========================================
        echo "Seeding Products Portfolio...\n";

        for ($i = 1; $i <= 10; $i++) {
            $minPrice = $faker->numberBetween(5, 10) * 10000;
            DB::table('products')->insert([
                'creator_id' => 1,
                'name' => 'Prototip Medis '.$faker->word,
                'description' => $faker->paragraph,
                'price_min' => $minPrice,
                'price_max' => $minPrice + $faker->numberBetween(50000, 200000),
                'is_active' => 1,
            ]);
        }

        // ==========================================
        // 6. TRANSACTIONS, BOOKINGS, LOGS & REIMBURSE
        // ==========================================
        echo "Seeding Transactions, Reimbursements & Bookings...\n";

        // Create a dummy reimbursement for the initial restock
        $reimburseId = DB::table('reimbursements')->insertGetId([
            'user_id' => 1,
            'title' => 'Initial Restock Filamen eSUN',
            'total_amount' => 450000,
            'status' => 'approved',
            'created_at' => now()->subDays(15),
        ]);

        // Simulate Restock Movement (In) linked to Reimbursement
        DB::table('raw_material_movements')->insert([
            'raw_material_id' => 1,
            'type' => 'in',
            'quantity' => 2000,
            'service_booking_id' => null,
            'progress_update_id' => null,
            'reimbursement_id' => $reimburseId,
            'notes' => 'Restock Filamen dari Supplier Tokopedia',
            'created_by' => 1,
            'created_at' => now()->subDays(15),
        ]);

        // Seed Custom Orders — rotate through all 3 service types (3 bookings each, 9 total)
        $serviceTypes = [
            ['id' => $printingServiceId, 'type' => 'printing'],
            ['id' => $designServiceId,   'type' => 'design'],
            ['id' => $scanningServiceId, 'type' => 'scanning'],
        ];

        for ($i = 1; $i <= 9; $i++) {
            $svcInfo = $serviceTypes[($i - 1) % 3];
            $type = $svcInfo['type'];
            $serviceId = $svcInfo['id'];
            $amount = $faker->numberBetween(10, 50) * 10000;
            $userId = $faker->numberBetween(4, 13);
            $isPaid = $i > 3; // First 3 stay in negotiating, rest move to in_progress

            // Build type-specific brief + fields
            if ($type === 'printing') {
                $weight = $faker->numberBetween(50, 500);
                $brief = 'Cetak model prostetik warna '.$faker->colorName.', material '.$faker->randomElement(['PLA', 'PETG']).', 1 unit.';
                $typeFields = [
                    'material_preference' => $faker->randomElement(['PLA', 'ABS', 'PETG', 'TPU', 'Resin']),
                    'filament_width' => null,
                    'scan_purpose' => null,
                    'object_dimensions' => json_encode([
                        'length' => (string) $faker->numberBetween(5, 30),
                        'width' => (string) $faker->numberBetween(5, 30),
                        'height' => (string) $faker->numberBetween(5, 30),
                    ]),
                    'slicer_weight_grams' => $weight,
                    'slicer_print_time_minutes' => (int) ($weight * 1.5),
                ];
            } elseif ($type === 'design') {
                $weight = null;
                $brief = 'Buat desain 3D dari sketsa referensi untuk komponen alat medis diagnostik.';
                $typeFields = [
                    'material_preference' => null,
                    'filament_width' => $faker->randomElement(['1.75 mm', '2.85 mm']),
                    'scan_purpose' => null,
                    'object_dimensions' => json_encode([
                        'length' => (string) $faker->numberBetween(5, 20),
                        'width' => (string) $faker->numberBetween(5, 20),
                        'height' => (string) $faker->numberBetween(5, 20),
                    ]),
                    'slicer_weight_grams' => null,
                    'slicer_print_time_minutes' => null,
                ];
            } else { // scanning
                $weight = null;
                $brief = 'Scan objek fisik untuk keperluan rekayasa balik dan dokumentasi arsip digital.';
                $typeFields = [
                    'material_preference' => null,
                    'filament_width' => null,
                    'scan_purpose' => $faker->randomElement(['Rekayasa Balik', 'Arsip Digital', 'Analisis Geometri', 'Reproduksi Suku Cadang']),
                    'object_dimensions' => json_encode([
                        'length' => (string) $faker->numberBetween(3, 25),
                        'width' => (string) $faker->numberBetween(3, 25),
                        'height' => (string) $faker->numberBetween(3, 25),
                    ]),
                    'slicer_weight_grams' => null,
                    'slicer_print_time_minutes' => null,
                ];
            }

            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'total_amount' => $amount,
                'payment_status' => $isPaid ? 'paid' : 'pending',
                'payment_proof' => $isPaid ? 'dummy/receipt.jpg' : null,
                'expired_at' => $isPaid ? null : Carbon::now()->addDays(1),
                'created_at' => now()->subDays(10 - $i),
                'updated_at' => now(),
            ]);

            $bookingId = DB::table('service_bookings')->insertGetId(array_merge([
                'transaction_id' => $transactionId,
                'user_id' => $userId,
                'service_id' => $serviceId,
                'product_reference_id' => $i % 3 == 0 ? $faker->numberBetween(1, 10) : null,
                'brief_description' => $brief,
                'agreed_price' => $amount,
                'current_status' => $isPaid ? 'in_progress' : 'negotiating',
                'created_at' => now()->subDays(10 - $i),
                'updated_at' => now(),
            ], $typeFields));

            // Progress + raw material movement only for paid printing orders
            if ($isPaid && $type === 'printing') {
                DB::table('service_progress_updates')->insert([
                    'service_booking_id' => $bookingId,
                    'status_label' => 'Slicing',
                    'percentage' => 20,
                    'notes' => 'Slicing selesai. Estimasi waktu terkalibrasi.',
                    'updated_by' => 2,
                    'created_at' => now()->subDays(2),
                ]);

                $printProgressId = DB::table('service_progress_updates')->insertGetId([
                    'service_booking_id' => $bookingId,
                    'status_label' => 'Printing',
                    'percentage' => 60,
                    'notes' => 'Mesin Ender 3 mulai mencetak.',
                    'updated_by' => 2,
                    'created_at' => now()->subDays(1),
                ]);

                DB::table('raw_material_movements')->insert([
                    'raw_material_id' => 1, // Filamen eSUN White
                    'type' => 'out',
                    'quantity' => $weight,
                    'service_booking_id' => $bookingId,
                    'progress_update_id' => $printProgressId,
                    'reimbursement_id' => null,
                    'notes' => 'Potong bahan untuk Printing.',
                    'created_by' => 2,
                    'created_at' => now(),
                ]);
            } elseif ($isPaid) {
                // Non-printing paid orders get a simple progress update
                DB::table('service_progress_updates')->insert([
                    'service_booking_id' => $bookingId,
                    'status_label' => $type === 'design' ? 'Modelling' : 'Scanning',
                    'percentage' => 40,
                    'notes' => $type === 'design'
                        ? 'Pembuatan model 3D sedang berlangsung.'
                        : 'Proses scanning objek sedang berjalan.',
                    'updated_by' => 2,
                    'created_at' => now()->subDays(1),
                ]);
            }
        }

        // ==========================================
        // 7. CMS (PAGE SECTIONS & STRUCTURAL)
        // ==========================================
        echo "Seeding CMS Data...\n";

        $sections = ['hero_title', 'hero_subtitle', 'about_lab', 'announcements'];
        foreach ($sections as $section) {
            DB::table('page_sections')->insert([
                'page_name' => 'landing_page',
                'section_key' => $section,
                'content' => $faker->sentence(10),
                'updated_by' => 1,
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            DB::table('structural_members')->insert([
                'user_id' => $i == 1 ? 1 : null,
                'name' => $faker->name.', S.T., M.T.',
                'position' => $i == 1 ? 'Kepala Laboratorium' : 'Staff Ahli',
                'display_order' => $i,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // TRAINING WORKSHOPS
        // ==========================================
        echo "Seeding Training Workshops...\n";
        $this->call(\Database\Seeders\TrainingSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "\nDONE! Database Seeded Successfully.\n";
        echo "========================================\n";
        echo "Login Super Admin  : admin@gretiva.com\n";
        echo "Login Admin Lab    : adminlab@gretiva.com\n";
        echo "Login Admin Gudang : gudang@gretiva.com\n";
        echo "Password           : password\n";
    }
}
