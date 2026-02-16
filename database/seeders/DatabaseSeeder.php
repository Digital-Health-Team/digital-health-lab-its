<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Inisialisasi Faker Indonesia dan English untuk menghindari error format missing
        $faker = Faker::create('id_ID');
        $fakerEn = Faker::create('en_US');

        // ==========================================
        // 1. USERS (Admin, PM, Staff)
        // ==========================================
        echo "1. Creating Users...\n";

        $adminId = DB::table('users')->insertGetId([
            'name' => 'Super Admin',
            'email' => 'admin@gretiva.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        // ==========================================
        // 2. PROJECTS (10 Data)
        // ==========================================
        echo "2. Creating 10 Projects...\n";

        $projectIds = [];
        for ($i = 0; $i < 10; $i++) {
            $projectIds[] = DB::table('projects')->insertGetId([
                // Menggunakan format yang lebih aman agar tidak error locale
                'name' => json_encode([
                    'id' => $faker->company . ' Project',
                    'en' => $fakerEn->company . ' System'
                ]),
                'description' => json_encode([
                    'id' => $faker->paragraph,
                    'en' => $fakerEn->paragraph
                ]),
                'deadline_global' => $faker->dateTimeBetween('now', '+6 months'),
                'status' => $faker->randomElement(['active', 'on_hold']),
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 3. JOBDESKS (100 Data)
        // ==========================================
        echo "3. Creating 100 Jobdesks...\n";

        $jobdeskDetails = [];
        for ($j = 0; $j < 100; $j++) {
            $status = $faker->randomElement(['pending', 'on_progress', 'review', 'revision', 'approved']);
            $assignedStaff = $faker->randomElement($staffIds);

            $jobdeskId = DB::table('jobdesks')->insertGetId([
                'project_id' => $faker->randomElement($projectIds),
                'assigned_to' => $assignedStaff,
                'title' => json_encode([
                    'id' => "Tugas #" . ($j + 1) . " " . $faker->sentence(3),
                    'en' => "Task #" . ($j + 1) . " " . $fakerEn->sentence(3)
                ]),
                'description' => json_encode([
                    'id' => $faker->sentence,
                    'en' => $fakerEn->sentence
                ]),
                'deadline_task' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'status' => $status,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);

            $jobdeskDetails[] = [
                'id' => $jobdeskId,
                'staff_id' => $assignedStaff,
                'status' => $status
            ];
        }

        // ==========================================
        // 4. ATTENDANCES & JOBDESK REPORTS
        // ==========================================
        echo "4. Creating Attendances & Daily Reports...\n";

        foreach ($staffIds as $staffId) {
            // Simulasi riwayat kerja 5 hari terakhir per staff
            for ($d = 0; $d < 5; $d++) {
                $date = Carbon::now()->subDays($d);

                $attendanceId = DB::table('attendances')->insertGetId([
                    'user_id' => $staffId,
                    'check_in' => $date->copy()->setTime(8, rand(0, 59)),
                    'check_out' => $date->copy()->setTime(17, rand(0, 59)),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Filter tugas yang memang diberikan ke staff ini
                $myTasks = array_filter($jobdeskDetails, fn($task) => $task['staff_id'] == $staffId);

                if (!empty($myTasks)) {
                    // Staff mengerjakan 1-2 tugas per hari
                    $todayTasks = $faker->randomElements($myTasks, min(count($myTasks), rand(1, 2)));

                    foreach ($todayTasks as $task) {
                        $reportId = DB::table('jobdesk_reports')->insertGetId([
                            'attendance_id' => $attendanceId,
                            'jobdesk_id' => $task['id'],
                            'status_at_report' => $faker->randomElement(['on_progress', 'completed']),
                            'created_at' => $date,
                        ]);

                        DB::table('report_details')->insert([
                            'jobdesk_report_id' => $reportId,
                            'content' => "Pengerjaan modul " . $faker->word . " telah selesai dan masuk tahap pengujian internal.",
                            'created_at' => $date,
                        ]);
                    }
                }
            }
        }

        // ==========================================
        // 5. ANNOUNCEMENTS (10 Data)
        // ==========================================
        echo "5. Creating 10 Announcements with Target Logic...\n";

        for ($a = 1; $a <= 10; $a++) {
            $isGlobal = $faker->boolean(40); // 40% kemungkinan ditujukan ke semua

            $announcementId = DB::table('announcements')->insertGetId([
                'created_by' => $faker->randomElement($pmIds),
                'title' => "UPDATE #" . $a . ": " . $faker->sentence(4),
                'content' => $faker->paragraphs(2, true),
                'is_published' => true,
                'is_global' => $isGlobal,
                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now(),
            ]);

            // Jika tidak global, pasangkan ke beberapa staff spesifik (recipient)
            if (!$isGlobal) {
                $recipients = $faker->randomElements($staffIds, rand(1, 4));
                foreach ($recipients as $recipientId) {
                    DB::table('announcement_recipients')->insert([
                        'announcement_id' => $announcementId,
                        'user_id' => $recipientId,
                        'created_at' => now(),
                    ]);
                }
            }
        }

        // ==========================================
        // 6. REVISION THREADS
        // ==========================================
        echo "6. Creating Revision Histories...\n";

        $revisionPool = array_filter($jobdeskDetails, fn($t) => $t['status'] === 'revision');
        foreach (array_slice($revisionPool, 0, 10) as $revTask) {
            $threadId = DB::table('revision_threads')->insertGetId([
                'jobdesk_id' => $revTask['id'],
                'user_id' => $faker->randomElement($pmIds),
                'content' => "Mohon perbaiki bagian " . $faker->word . " karena masih ada bug visual.",
                'is_staff_reply' => false,
                'created_at' => now()->subDays(1),
            ]);

            // Masukkan attachment dummy ke thread revisi ini
            $this->seedAttachment($threadId, $faker->randomElement($pmIds), 'revision');
        }

        echo "\nDONE! Database Seeded Successfully.\n";
        echo "Admin Login: admin@gretiva.com / password\n";
    }

    /**
     * Helper polymorphic attachment
     */
    private function seedAttachment($attachableId, $uploaderId, $type = 'report')
    {
        $dummyImages = ['sample1.jpg', 'sample2.png', 'sample3.jpg'];
        $attachType = ($type === 'revision') ? 'revision' : 'report';

        DB::table('media_attachments')->insert([
            'attachable_id' => $attachableId,
            'attachable_type' => $attachType,
            'file_path' => 'dummy/' . $dummyImages[array_rand($dummyImages)],
            'file_name' => 'screenshot_' . uniqid() . '.jpg',
            'file_type' => 'image/jpeg',
            'uploader_id' => $uploaderId,
            'created_at' => now(),
        ]);
    }
}
