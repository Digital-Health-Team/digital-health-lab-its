<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

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
        $roles = ['super_admin', 'admin_lab', 'mahasiswa', 'user_publik'];
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
        for ($i = 3; $i <= 12; $i++) {
            $roleId = $faker->randomElement([3, 3, 3, 4]); // Dominan mahasiswa (3)
            $generatedName = $faker->name;

            DB::table('users')->insert([
                'id' => $i,
                'role_id' => $roleId,
                'name' => $generatedName,
                'email' => "user{$i}@gretiva.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_profiles')->insert([
                'user_id' => $i,
                'full_name' => $generatedName,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'nik' => $roleId == 4 ? $faker->nik : null,
                'nim' => $roleId == 3 ? '50312010' . str_pad($i, 2, '0', STR_PAD_LEFT) : null,
                'department' => $roleId == 3 ? 'Teknologi Kedokteran' : null,
                'faculty' => $roleId == 3 ? 'FTEIC' : null,
                'university' => $roleId == 3 ? 'ITS' : null,
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 3. MASTER INVENTORY & SERVICES
        // ==========================================
        echo "Seeding Raw Materials & Services...\n";

        $materials = [
            ['name' => 'Filamen PLA+ Putih', 'category' => 'filament', 'unit' => 'gram', 'current_stock' => 5000],
            ['name' => 'Resin Standard Grey', 'category' => 'resin', 'unit' => 'ml', 'current_stock' => 2000],
            ['name' => 'Silikon Food Grade', 'category' => 'silicon', 'unit' => 'gram', 'current_stock' => 3000],
        ];
        foreach ($materials as $mat) {
            DB::table('raw_materials')->insert(array_merge($mat, ['created_at' => now()]));
        }

        $services = [
            ['name' => 'Jasa Print 3D (FDM/SLA)', 'description' => 'Layanan cetak 3D dengan akurasi tinggi.', 'base_price' => 2000],
            ['name' => 'Jasa Desain 3D CAD', 'description' => 'Pembuatan model 3D dari sketsa.', 'base_price' => 150000],
        ];
        foreach ($services as $svc) {
            DB::table('services')->insert($svc);
        }

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
                    'name' => 'Tim ' . $faker->word,
                    'course_name' => 'Perancangan Alat Medis',
                    'created_at' => now(),
                ]);

                // Assign random mahasiswa
                DB::table('team_members')->insert([
                    ['team_id' => $teamId, 'user_id' => $faker->numberBetween(3, 7), 'role_in_team' => 'Ketua'],
                    ['team_id' => $teamId, 'user_id' => $faker->numberBetween(8, 12), 'role_in_team' => 'Anggota'],
                ]);

                // Create Project
                DB::table('projects')->insert([
                    'team_id' => $teamId,
                    'title' => 'Alat ' . $faker->words(3, true),
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
                'name' => 'Prototip Medis ' . $faker->word,
                'description' => $faker->paragraph,
                'price_min' => $minPrice,
                'price_max' => $minPrice + $faker->numberBetween(50000, 200000),
                'is_active' => 1,
            ]);
        }

        // ==========================================
        // 6. TRANSACTIONS & SERVICE BOOKINGS
        // ==========================================
        echo "Seeding Transactions & Bookings (Custom Orders)...\n";

        for ($i = 1; $i <= 8; $i++) {
            $amount = $faker->numberBetween(10, 50) * 10000;
            $userId = $faker->numberBetween(3, 12);
            $isPaid = $i > 3; // Beberapa transaksi awal dibikin pending

            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'total_amount' => $amount,
                'payment_status' => $isPaid ? 'paid' : 'pending',
                'payment_proof' => $isPaid ? 'dummy/receipt.jpg' : null,
                'expired_at' => $isPaid ? null : Carbon::now()->addDays(1),
                'created_at' => now()->subDays(10 - $i),
                'updated_at' => now(),
            ]);

            $weight = $faker->numberBetween(50, 500); // 50g - 500g

            $bookingId = DB::table('service_bookings')->insertGetId([
                'transaction_id' => $transactionId,
                'user_id' => $userId,
                'service_id' => 1, // Jasa Print 3D
                'product_reference_id' => $i % 2 == 0 ? $faker->numberBetween(1, 10) : null,
                'brief_description' => 'Tolong buatkan warna ' . $faker->colorName,
                'slicer_weight_grams' => $weight,
                'slicer_print_time_minutes' => $weight * 1.5,
                'agreed_price' => $amount,
                'current_status' => $isPaid ? 'in_progress' : 'negotiating',
                'created_at' => now()->subDays(10 - $i),
                'updated_at' => now(),
            ]);

            // Jika Paid & In Progress, buatkan history timeline & mutasi material
            if ($isPaid) {
                DB::table('service_progress_updates')->insert([
                    ['service_booking_id' => $bookingId, 'status_label' => 'Desain Diterima & Slicing', 'percentage' => 20, 'notes' => 'Slicing aman.', 'updated_by' => 2, 'created_at' => now()->subDays(2)],
                    ['service_booking_id' => $bookingId, 'status_label' => 'Proses Printing', 'percentage' => 60, 'notes' => 'Mesin Ender 3.', 'updated_by' => 2, 'created_at' => now()->subDays(1)],
                ]);

                // Catat Penggunaan Material (Usage)
                DB::table('raw_material_movements')->insert([
                    'raw_material_id' => 1, // Filamen PLA
                    'type' => 'out',
                    'quantity' => $weight, // Potong stok sesuai slicer_weight_grams
                    'service_booking_id' => $bookingId,
                    'notes' => 'Produksi Pesanan #' . $bookingId,
                    'created_by' => 2,
                    'created_at' => now(),
                ]);
            }
        }

        // Simulate Restock Movement (In)
        DB::table('raw_material_movements')->insert([
            'raw_material_id' => 1,
            'type' => 'in',
            'quantity' => 2000,
            'service_booking_id' => null,
            'notes' => 'Restock Filamen dari Supplier',
            'created_by' => 1,
            'created_at' => now()->subDays(15),
        ]);

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
                'name' => $faker->name . ', S.T., M.T.',
                'position' => $i == 1 ? 'Kepala Laboratorium' : 'Staff Ahli',
                'display_order' => $i,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "\nDONE! Database Seeded Successfully.\n";
        echo "========================================\n";
        echo "Login Super Admin : admin@gretiva.com\n";
        echo "Login Admin Lab   : adminlab@gretiva.com\n";
        echo "Password          : password\n";
    }
}
