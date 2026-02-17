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
        // Inisialisasi Faker
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
        // 3. JOBDESKS (100 Data with Lateness Logic)
        // ==========================================
        echo "3. Creating 100 Jobdesks (Simulating Lateness)...\n";

        $jobdeskDetails = [];
        for ($j = 0; $j < 100; $j++) {
            // Naikkan kemungkinan status revision agar data thread lebih banyak
            $status = $faker->randomElement(['pending', 'on_progress', 'review', 'revision', 'revision', 'approved']);
            $assignedStaff = $faker->randomElement($staffIds);
            $pmCreator = $faker->randomElement($pmIds);

            $deadline = Carbon::instance($faker->dateTimeBetween('-1 month', '+1 month'));
            $submittedAt = null;
            $latenessMinutes = 0;

            if (in_array($status, ['review', 'approved'])) {
                if ($faker->boolean(30)) {
                    $submittedAt = $deadline->copy()->addMinutes(rand(60, 4320));
                    $latenessMinutes = $deadline->diffInMinutes($submittedAt);
                } else {
                    $submittedAt = $deadline->copy()->subMinutes(rand(60, 2000));
                }
            }

            $jobdeskId = DB::table('jobdesks')->insertGetId([
                'project_id' => $faker->randomElement($projectIds),
                'assigned_to' => $assignedStaff,
                'created_by' => $pmCreator,
                'title' => json_encode([
                    'id' => "Tugas #" . ($j + 1) . " " . $faker->sentence(3),
                    'en' => "Task #" . ($j + 1) . " " . $fakerEn->sentence(3)
                ]),
                'description' => json_encode([
                    'id' => $faker->sentence,
                    'en' => $fakerEn->sentence
                ]),
                'deadline_task' => $deadline,
                'submitted_at' => $submittedAt,
                'lateness_minutes' => $latenessMinutes,
                'status' => $status,
                'created_at' => now()->subDays(rand(30, 60)),
                'updated_at' => now(),
            ]);

            // Simpan detail untuk seed berikutnya
            $jobdeskDetails[] = [
                'id' => $jobdeskId,
                'staff_id' => $assignedStaff,
                'pm_id' => $pmCreator, // Kita simpan PM nya siapa
                'status' => $status
            ];
        }

        // ==========================================
        // 4. ATTENDANCES, SELFIE & GPS
        // ==========================================
        echo "4. Creating Attendances with GPS & Selfies...\n";

        foreach ($staffIds as $staffId) {
            for ($d = 0; $d < 5; $d++) {
                $date = Carbon::now()->subDays($d);

                $latIn = -6.175392 + ($faker->randomFloat(6, -0.001, 0.001));
                $longIn = 106.827153 + ($faker->randomFloat(6, -0.001, 0.001));
                $latOut = -6.175392 + ($faker->randomFloat(6, -0.001, 0.001));
                $longOut = 106.827153 + ($faker->randomFloat(6, -0.001, 0.001));

                $checkInTime = $date->copy()->setTime(8, rand(0, 59));
                $checkOutTime = $date->copy()->setTime(17, rand(0, 59));

                $attendanceId = DB::table('attendances')->insertGetId([
                    'user_id' => $staffId,
                    'check_in' => $checkInTime,
                    'check_in_latitude' => $latIn,
                    'check_in_longitude' => $longIn,
                    'check_out' => $checkOutTime,
                    'check_out_latitude' => $latOut,
                    'check_out_longitude' => $longOut,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $this->seedAttachment($attendanceId, 'App\Models\Attendance', $staffId, "checkin_{$attendanceId}.webp");
                $this->seedAttachment($attendanceId, 'App\Models\Attendance', $staffId, "checkout_{$attendanceId}.webp");

                $myTasks = array_filter($jobdeskDetails, fn($task) => $task['staff_id'] == $staffId);

                if (!empty($myTasks)) {
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
                            'content' => "Pengerjaan fitur " . $faker->word . " hari ini lancar. Progress 80%.",
                            'created_at' => $date,
                        ]);

                        $this->seedAttachment($reportId, 'App\Models\JobdeskReport', $staffId, "proof_{$reportId}.jpg");
                    }
                }
            }
        }

        // ==========================================
        // 5. ANNOUNCEMENTS
        // ==========================================
        echo "5. Creating Announcements...\n";

        for ($a = 1; $a <= 10; $a++) {
            $isGlobal = $faker->boolean(40);
            $announcementId = DB::table('announcements')->insertGetId([
                'created_by' => $faker->randomElement($pmIds),
                'title' => "INFO #" . $a . ": " . $faker->sentence(4),
                'content' => $faker->paragraphs(2, true),
                'is_published' => true,
                'is_global' => $isGlobal,
                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now(),
            ]);

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
        // 6. REVISION THREADS (DIBANYAKIN & PING-PONG)
        // ==========================================
        echo "6. Creating Rich Revision Histories...\n";

        // Ambil semua task yang statusnya 'revision' atau 'review' agar threadnya banyak
        $revisionPool = array_filter($jobdeskDetails, fn($t) => in_array($t['status'], ['revision']));

        foreach ($revisionPool as $revTask) {
            $pmId = $revTask['pm_id'];
            $staffId = $revTask['staff_id'];

            // Generate percakapan ping-pong (3 sampai 8 pesan per task)
            $chatCount = rand(3, 8);

            for ($k = 0; $k < $chatCount; $k++) {
                // Tentukan siapa yang kirim pesan (Ganjil = PM, Genap = Staff)
                // Pesan terakhir harus sesuai status. Kalau 'revision', pesan terakhir dari PM (minta revisi).

                $isStaffReply = ($k % 2 != 0); // False (PM), True (Staff), False (PM)...
                $senderId = $isStaffReply ? $staffId : $pmId;

                // Teks Dummy yang lebih variatif
                if ($isStaffReply) {
                    $content = $faker->randomElement([
                        "Baik pak, segera saya perbaiki.",
                        "Sudah saya update sesuai arahan, mohon dicek.",
                        "Ini file yang diminta.",
                        "Maaf pak, bagian ini agak tricky, butuh waktu tambahan.",
                        "Revisi done. Tolong review kembali."
                    ]);
                } else {
                    $content = $faker->randomElement([
                        "Tolong perbaiki bug di bagian header.",
                        "Warnanya kurang pas dengan brand guideline, tolong diganti.",
                        "Masih ada typo di paragraf kedua.",
                        "Fitur login error saat diakses via mobile.",
                        "Screenshot terlampir, tolong sesuaikan padding-nya."
                    ]);
                }

                $threadId = DB::table('revision_threads')->insertGetId([
                    'jobdesk_id' => $revTask['id'],
                    'user_id' => $senderId,
                    'content' => $content,
                    'is_staff_reply' => $isStaffReply,
                    // Buat tanggal mundur agar urut
                    'created_at' => now()->subHours($chatCount - $k),
                    'updated_at' => now()->subHours($chatCount - $k),
                ]);

                // Tambahkan attachment secara acak (30% chance per pesan)
                if ($faker->boolean(30)) {
                    $this->seedAttachment(
                        $threadId,
                        'App\Models\RevisionThread',
                        $senderId,
                        "rev_attach_{$threadId}.png"
                    );
                }
            }
        }

        echo "\nDONE! Database Seeded Successfully.\n";
        echo "Login Super Admin: admin@gretiva.com / password\n";
    }

    /**
     * Helper polymorphic attachment
     */
    private function seedAttachment($attachableId, $attachableType, $uploaderId, $fileName)
    {
        $dummyImages = ['sample1.jpg', 'sample2.png', 'sample3.jpg'];
        $path = 'dummy/' . $dummyImages[array_rand($dummyImages)];

        DB::table('media_attachments')->insert([
            'attachable_id' => $attachableId,
            'attachable_type' => $attachableType,
            'file_path' => $path,
            'file_name' => $fileName,
            'file_type' => 'image/jpeg',
            'uploader_id' => $uploaderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
