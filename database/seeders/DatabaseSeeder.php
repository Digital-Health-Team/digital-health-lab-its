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
        // Inisialisasi Faker
        $faker = Faker::create('id_ID');

        // ==========================================
        // USERS (Admin, User)
        // ==========================================
        echo "Creating Users...\n";

        // 1. Super Admin
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@gretiva.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Users (10 Orang)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => "user{$i}@gretiva.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "\nDONE! Users Seeded Successfully.\n";
        echo "Login Super Admin: admin@gretiva.com / password\n";
    }
}
