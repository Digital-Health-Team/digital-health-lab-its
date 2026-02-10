<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gunakan Faker bahasa Indonesia
        $faker = Faker::create('id_ID');

        // ==========================================
        // 1. USERS (Super Admin, PM, Staff)
        // ==========================================

        echo "Creating Users...\n";

        // 1.1 Super Admin (Akun Login Anda)
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Super Admin',
            'email' => 'admin@gretiva.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin', // Sesuai enum di Livewire
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1.2 Project Managers (3 Orang)
        $pmIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $pmIds[] = DB::table('users')->insertGetId([
                'name' => 'PM ' . $faker->firstName,
                'email' => "pm{$i}@gretiva.com",
                'password' => Hash::make('password'),
                'role' => 'pm',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 1.3 Staff (10 Orang)
        $staffIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $staffIds[] = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => "staff{$i}@gretiva.com",
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Gabung semua ID user untuk random creator project
        $allUserIds = array_merge([$adminId], $pmIds, $staffIds);

        // ==========================================
        // 2. PROJECTS (Multi-Language JSON)
        // ==========================================

        echo "Creating Projects with Translatable Data...\n";

        // Daftar Template Nama Proyek agar terlihat real
        $projectTemplates = [
            ['id' => 'Pembuatan Website E-Commerce', 'en' => 'E-Commerce Website Development'],
            ['id' => 'Sistem Informasi Sekolah', 'en' => 'School Information System'],
            ['id' => 'Aplikasi Kasir Android', 'en' => 'Android POS Application'],
            ['id' => 'Redesain UI/UX Dashboard', 'en' => 'Dashboard UI/UX Redesign'],
            ['id' => 'Integrasi Payment Gateway', 'en' => 'Payment Gateway Integration'],
            ['id' => 'Company Profile Korporat', 'en' => 'Corporate Company Profile'],
            ['id' => 'Sistem Manajemen Gudang', 'en' => 'Warehouse Management System'],
            ['id' => 'Aplikasi Booking Online', 'en' => 'Online Booking Application'],
            ['id' => 'Maintenance Server Tahunan', 'en' => 'Annual Server Maintenance'],
            ['id' => 'Pengembangan API Logistik', 'en' => 'Logistics API Development'],
        ];

        for ($i = 0; $i < 20; $i++) {
            // Pilih nama proyek acak
            $template = $faker->randomElement($projectTemplates);
            $clientName = $faker->company;

            // Generate Nama Multi Bahasa
            $nameJson = json_encode([
                'id' => $template['id'] . ' - ' . $clientName,
                'en' => $template['en'] . ' - ' . $clientName
            ]);

            // Generate Deskripsi Multi Bahasa
            $descId = $faker->paragraph(3);
            $descEn = "This is a comprehensive project description regarding " . $template['en'] . ". " . $faker->sentence(10);

            $descJson = json_encode([
                'id' => $descId,
                'en' => $descEn
            ]);

            // Tentukan Tanggal
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
            $deadline = $faker->dateTimeBetween('now', '+6 months');

            // Insert Project
            DB::table('projects')->insert([
                'name' => $nameJson,             // Kolom JSON
                'description' => $descJson,      // Kolom JSON
                'deadline_global' => $deadline,
                'status' => $faker->randomElement(['active', 'on_hold', 'completed']),
                'created_by' => $faker->randomElement($allUserIds),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        echo "Seeding Complete!\n";
        echo "Login Super Admin: admin@gretiva.com / password\n";
    }
}
