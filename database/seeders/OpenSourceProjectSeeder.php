<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpenSourceProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'user_id' => 4,
                'title' => 'Adaptive Prosthetic Hand with Myoelectric Control',
                'category' => 'medical_device',
                'status' => 'approved',
                'validated_by' => 1,
                'cover' => 'https://picsum.photos/seed/osp-prosthetic/600/600',
            ],
            [
                'user_id' => 5,
                'title' => 'IoT-Based Patient Vital Signs Monitor',
                'category' => 'iot_system',
                'status' => 'approved',
                'validated_by' => 1,
                'cover' => 'https://picsum.photos/seed/osp-vitals-iot/600/600',
            ],
            [
                'user_id' => 6,
                'title' => '3D-Printed Anatomical Heart Model for Surgical Planning',
                'category' => '3d_model',
                'status' => 'approved',
                'validated_by' => 1,
                'cover' => 'https://picsum.photos/seed/osp-heart-3d/600/600',
            ],
            [
                'user_id' => 7,
                'title' => 'Smart Knee Brace with Real-Time Gait Analysis',
                'category' => 'iot_system',
                'status' => 'approved',
                'validated_by' => 1,
                'cover' => 'https://picsum.photos/seed/osp-knee-brace/600/600',
            ],
            [
                'user_id' => 8,
                'title' => 'Telehealth Dashboard for Remote Patient Management',
                'category' => 'software',
                'status' => 'approved',
                'validated_by' => 1,
                'cover' => 'https://picsum.photos/seed/osp-telehealth/600/600',
            ],
            [
                'user_id' => 4,
                'title' => 'Parametric Skull Implant Design Using CT Scan Data',
                'category' => '3d_model',
                'status' => 'approved',
                'validated_by' => 1,
                'cover' => 'https://picsum.photos/seed/osp-skull-implant/600/600',
            ],
        ];

        foreach ($projects as $data) {
            $projectId = DB::table('open_source_projects')->insertGetId([
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'category' => $data['category'],
                'status' => $data['status'],
                'validated_by' => $data['validated_by'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('attachments')->insert([
                'attachable_type' => 'App\Models\OpenSourceProject',
                'attachable_id' => $projectId,
                'file_url' => $data['cover'],
                'file_type' => 'image/jpeg',
                'is_primary' => true,
                'sort_order' => 0,
                'uploaded_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
