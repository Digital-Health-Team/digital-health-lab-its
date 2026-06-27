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
                'title' => 'Craniosynostosis Detection via CT Analysis',
                'slug' => 'craniosynostosis-ct-detection',
                'caption' => 'AI-assisted cranial suture analysis from CT scans.',
                'category' => 'medical_device',
                'listing_type' => 'journals',
                'status' => 'approved',
                'validated_by' => 1,
                'is_featured' => true,
                'description' => json_encode([
                    'This open-source project presents a computer-aided detection system for craniosynostosis — a condition in which one or more of the fibrous sutures in an infant\'s skull prematurely fuses. Early and accurate detection is critical to prevent complications with brain growth.',
                    'Using volumetric CT scan data as input, the pipeline applies multi-atlas segmentation and morphometric analysis to identify abnormal suture closure patterns. The algorithm was validated against a dataset of 120 anonymised paediatric scans with expert radiologist annotations.',
                    'All source files, trained model weights, and a sample dataset are included in the repository. The code is written in Python 3.10 and is compatible with both CPU and GPU environments.',
                ]),
                'highlights' => json_encode([
                    'Multi-atlas CT segmentation pipeline',
                    'Morphometric shape analysis of cranial sutures',
                    'Python 3.10 + PyTorch implementation',
                    'Pre-trained model weights included',
                    '120-scan validated dataset reference',
                    'GPU and CPU compatible inference',
                ]),
                'cover_color' => 'bg-gradient-to-br from-amber-100 to-amber-300',
                'license' => 'MIT',
                'version' => 'v1.2.0',
                'format' => 'ZIP (Python project)',
                'includes' => json_encode([
                    'Full Python source code',
                    'Pre-trained model weights (.pt)',
                    'Sample anonymised CT dataset',
                    'Setup & usage documentation',
                    'Jupyter notebook walkthrough',
                ]),
                'cover' => 'https://picsum.photos/seed/osp-cranio-ct/600/600',
                'cover_file_name' => 'craniosynostosis-ct-detection-cover.jpg',
                'cover_file_size' => '184 KB',
            ],
            [
                'user_id' => 5,
                'title' => 'Advanced IoT System for Patient Monitoring',
                'slug' => 'patient-monitoring-iot',
                'caption' => 'A real-time IoT architecture for continuous vital-sign monitoring.',
                'category' => 'iot_system',
                'listing_type' => 'journals',
                'status' => 'approved',
                'validated_by' => 1,
                'is_featured' => true,
                'description' => json_encode([
                    'A comprehensive Internet-of-Things architecture for continuous, real-time patient vital-sign monitoring in clinical and home-care environments. The system collects heart rate, SpO2, temperature, and blood pressure readings from bedside sensor nodes and streams them to a central dashboard via MQTT over a secured Wi-Fi network.',
                    'The firmware is built on ESP32 microcontrollers with FreeRTOS task scheduling. The cloud backend uses Node-RED for flow-based data processing, InfluxDB for time-series storage, and Grafana for live visualisation. Alert thresholds are configurable per patient profile.',
                    'This repository contains all firmware source, backend configuration files, 3D-printable sensor enclosure models, and a step-by-step deployment guide for the full stack.',
                ]),
                'highlights' => json_encode([
                    'ESP32 + FreeRTOS firmware for 4 vital signs',
                    'MQTT broker with TLS encryption',
                    'Node-RED flow-based data processing',
                    'InfluxDB + Grafana real-time dashboard',
                    'Configurable per-patient alert thresholds',
                    '3D-printable sensor enclosure included',
                ]),
                'cover_color' => 'bg-gradient-to-br from-indigo-200 to-indigo-400',
                'license' => 'Apache 2.0',
                'version' => 'v2.0.1',
                'format' => 'ZIP (firmware + config)',
                'includes' => json_encode([
                    'ESP32 firmware source (C++)',
                    'Node-RED flow export (.json)',
                    'Grafana dashboard template',
                    'STL enclosure files for sensor nodes',
                    'Full deployment & wiring guide',
                ]),
                'cover' => 'https://picsum.photos/seed/osp-vitals-iot/600/600',
                'cover_file_name' => 'patient-monitoring-iot-cover.jpg',
                'cover_file_size' => '164 KB',
            ],
            [
                'user_id' => 6,
                'title' => 'Open-Source STL Files — Medical Devices',
                'slug' => 'stl-medical-devices',
                'caption' => 'A curated library of printable medical device STL files.',
                'category' => '3d_model',
                'listing_type' => 'downloadable',
                'status' => 'approved',
                'validated_by' => 1,
                'is_featured' => false,
                'description' => json_encode([
                    'A curated, peer-reviewed library of 3D-printable STL files for medical devices and anatomical teaching aids, produced by students and faculty of the ITS Biomedical Engineering department. Every model is validated for dimensional accuracy against clinical reference standards.',
                    'The collection spans prosthetic limb components, surgical instrument handles, anatomical teaching models (spine, skull, hand), patient-specific implant templates, and sensor enclosures for clinical IoT devices. Files are organised by category and include recommended print settings for common FDM and resin printers.',
                    'This release (v3.1) adds 14 new models contributed during the 2025–2026 academic year, bringing the total to 68 validated STL files. Contributions are welcome via the GitHub repository.',
                ]),
                'highlights' => json_encode([
                    '68 clinically validated STL models',
                    'Organised by category and print method',
                    'Includes recommended FDM & resin settings',
                    'Prosthetics, surgical tools, and anatomy models',
                    'Sensor enclosures for IoT clinical devices',
                    'Open contributions via GitHub',
                ]),
                'cover_color' => 'bg-gradient-to-br from-indigo-200 to-indigo-400',
                'license' => 'CC BY 4.0',
                'version' => 'v3.1.0',
                'format' => 'ZIP (STL files)',
                'includes' => json_encode([
                    '68 validated STL model files',
                    'Recommended print settings per model',
                    'Category index & search spreadsheet',
                    'Contribution & licensing guide',
                    'Preview renders for each model',
                ]),
                'cover' => 'https://picsum.photos/seed/osp-stl-devices/600/600',
                'cover_file_name' => 'stl-medical-devices-cover.jpg',
                'cover_file_size' => '201 KB',
            ],
        ];

        foreach ($projects as $data) {
            $projectId = DB::table('open_source_projects')->insertGetId([
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'slug' => $data['slug'],
                'caption' => $data['caption'],
                'category' => $data['category'],
                'listing_type' => $data['listing_type'],
                'status' => $data['status'],
                'validated_by' => $data['validated_by'],
                'is_featured' => $data['is_featured'],
                'description' => $data['description'],
                'highlights' => $data['highlights'],
                'cover_color' => $data['cover_color'],
                'license' => $data['license'],
                'version' => $data['version'],
                'format' => $data['format'],
                'includes' => $data['includes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('attachments')->insert([
                'attachable_type' => 'App\Models\OpenSourceProject',
                'attachable_id' => $projectId,
                'file_url' => $data['cover'],
                'file_name' => $data['cover_file_name'],
                'file_size' => $data['cover_file_size'],
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
