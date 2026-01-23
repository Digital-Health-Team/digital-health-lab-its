<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@mbkm.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Lecturer (Pak Budi)
        $lecturerId = DB::table('users')->insertGetId([
            'name' => 'Pak Budi',
            'email' => 'pakbudi@mbkm.test',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('lecturer_profiles')->insert([
            'user_id' => $lecturerId,
            'nidn' => '0012345678',
            'position' => 'Dosen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create Student (Andi)
        $studentId = DB::table('users')->insertGetId([
            'name' => 'Andi Prasetyo',
            'email' => 'andi@mbkm.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('student_profiles')->insert([
            'user_id' => $studentId,
            'nim' => '21010001',
            'competency' => 'Teknik Informatika',
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Create Internship Period (GoTo Financial)
        $periodId = DB::table('internship_periods')->insertGetId([
            'student_id' => $studentId,
            'lecturer_id' => $lecturerId,
            'company_name' => 'GoTo Financial',
            'start_date' => Carbon::now()->subDays(60),
            'end_date' => Carbon::now()->addDays(30),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Create Logbooks
        $logbooks = [
            [
                'internship_period_id' => $periodId,
                'date' => Carbon::now()->subDays(5),
                'activity' => 'Melakukan setup environment development dan instalasi tools yang dibutuhkan.',
                'status' => 'validated',
                'feedback' => 'Bagus, lanjutkan.',
            ],
            [
                'internship_period_id' => $periodId,
                'date' => Carbon::now()->subDays(4),
                'activity' => 'Mempelajari codebase project yang sedang berjalan.',
                'status' => 'validated',
                'feedback' => 'Perhatikan coding standard yang digunakan.',
            ],
            [
                'internship_period_id' => $periodId,
                'date' => Carbon::now()->subDays(3),
                'activity' => 'Memperbaiki bug pada fitur login.',
                'status' => 'validated',
                'feedback' => null,
            ],
            [
                'internship_period_id' => $periodId,
                'date' => Carbon::now()->subDays(2),
                'activity' => 'Membuat unit test untuk fitur login.',
                'status' => 'pending',
                'feedback' => null,
            ],
            [
                'internship_period_id' => $periodId,
                'date' => Carbon::now()->subDays(1),
                'activity' => 'Deploy aplikasi ke staging server.',
                'status' => 'pending',
                'feedback' => null,
            ],
        ];

        foreach ($logbooks as $log) {
            DB::table('logbooks')->insert(array_merge($log, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
