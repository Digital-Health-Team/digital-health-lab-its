<?php

namespace Database\Seeders;

use App\Models\Publication;
use Illuminate\Database\Seeder;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        $publications = [
            [
                'title' => 'Design and Fabrication of a Low-Cost 3D-Printed Prosthetic Arm',
                'slug' => 'low-cost-3d-printed-prosthetic-arm',
                'author' => 'Andi Pratama, Budi Santoso',
                'category' => 'Journals',
                'abstract' => 'This study presents the design and fabrication of a low-cost, 3D-printed prosthetic arm using FDM technology with PETG filament. The prototype demonstrates 87% grip strength retention compared to commercial alternatives at 12% of the cost.',
                'description' => [
                    'This paper introduces a novel approach to prosthetic arm fabrication using consumer-grade FDM 3D printing technology. The design prioritizes accessibility and affordability without compromising functional performance.',
                    'Finite element analysis was conducted to validate structural integrity under load conditions representative of daily activities. The PETG material demonstrated excellent biocompatibility and mechanical resilience across 500-cycle fatigue testing.',
                    'Field trials with five participants over three months showed high user satisfaction scores and no significant degradation in structural integrity, validating the long-term viability of the design.',
                ],
                'keywords' => ['3D printing', 'prosthetics', 'PETG', 'FDM', 'biomedical engineering', 'assistive technology'],
                'doi' => '10.1234/its.medtech.2025.0042',
                'journal' => 'Journal of Medical Devices',
                'pmid' => '38100001',
                'is_free_access' => true,
                'is_featured' => true,
                'published_at' => '2025-03-15',
            ],
            [
                'title' => 'Parametric Analysis of FDM Print Parameters on Mechanical Properties of Orthotic Devices',
                'slug' => 'fdm-parametric-analysis-orthotic-mechanical-properties',
                'author' => 'Dewi Rahayu, Rizky Fauzan',
                'category' => 'Papers',
                'abstract' => 'A systematic parametric study examining the influence of layer height, infill density, and print orientation on tensile strength and flexibility of 3D-printed orthotic shells using PLA and TPU filaments.',
                'description' => [
                    'Orthotic devices require precise mechanical properties to provide effective therapeutic support while remaining comfortable for patients. This study systematically varies key FDM print parameters to determine optimal settings for orthotic shell production.',
                    'Tensile testing according to ASTM D638 was performed on 90 specimen combinations. Results show that 45° print orientation combined with 40% gyroid infill provides the best balance of stiffness and impact resistance for ankle orthoses.',
                ],
                'keywords' => ['FDM', 'PLA', 'TPU', 'orthosis', 'parametric study', 'mechanical testing'],
                'doi' => '10.5678/jmme.2025.0117',
                'journal' => 'Journal of Manufacturing and Materials Engineering',
                'is_free_access' => false,
                'is_featured' => false,
                'published_at' => '2025-05-20',
            ],
            [
                'title' => 'IMU-Based Gait Analysis System for Rehabilitation Monitoring',
                'slug' => 'imu-gait-analysis-rehabilitation-monitoring',
                'author' => 'Siti Nurhaliza, Fajar Wicaksono, Ahmad Yani',
                'category' => 'Research',
                'abstract' => 'We present a wearable gait analysis system using inertial measurement units (IMUs) that provides real-time feedback for post-stroke rehabilitation. The system achieves 94.7% accuracy in identifying gait phase transitions.',
                'description' => [
                    'Post-stroke gait rehabilitation requires continuous monitoring to track patient progress and adjust therapy protocols. Existing clinical systems are expensive and confined to laboratory settings, limiting their utility.',
                    'Our wearable system uses four IMU nodes placed on the thigh, shank, and foot of each limb, transmitting data via Bluetooth LE to a mobile application. Machine learning classification achieves 94.7% accuracy for five gait phases.',
                    'A six-week clinical trial with 12 post-stroke patients demonstrated statistically significant improvements in step symmetry and cadence compared to the control group receiving standard physiotherapy alone.',
                ],
                'keywords' => ['IMU', 'gait analysis', 'stroke rehabilitation', 'wearable sensor', 'machine learning'],
                'doi' => '10.9012/sensors.2025.0089',
                'journal' => 'Sensors and Actuators',
                'pmid' => '38200045',
                'is_free_access' => true,
                'is_featured' => false,
                'published_at' => '2025-01-10',
            ],
            [
                'title' => 'Topology Optimization of Ankle–Foot Orthosis Using Generative Design',
                'slug' => 'topology-optimization-ankle-foot-orthosis',
                'author' => 'Hendri Kusuma, Ayu Lestari',
                'category' => 'Journals',
                'abstract' => 'This paper applies topology optimization and generative design to produce an ankle–foot orthosis (AFO) that reduces material usage by 35% while maintaining equivalent mechanical performance to conventional designs.',
                'description' => [
                    'Traditional ankle–foot orthoses are solid shells that add significant weight and restrict natural joint movement. Topology optimization offers a pathway to lattice-structured designs that maintain structural integrity with reduced material.',
                    'Finite element analysis in Ansys Mechanical was used to define boundary conditions representing peak gait loading. Generative design in Autodesk Fusion 360 produced 12 candidate geometries; the highest-ranking was selected for prototyping.',
                ],
                'keywords' => ['topology optimization', 'AFO', 'generative design', 'additive manufacturing', 'lightweight structure'],
                'doi' => '10.3456/ijbm.2024.0203',
                'journal' => 'International Journal of Biomedical Engineering',
                'is_free_access' => false,
                'is_featured' => true,
                'published_at' => '2024-11-05',
            ],
            [
                'title' => 'IoT-Enabled Remote Rehabilitation Monitoring for Elderly Patients',
                'slug' => 'iot-remote-rehabilitation-monitoring-elderly',
                'author' => 'Rini Anggraini, Dimas Setiawan',
                'category' => 'Research',
                'abstract' => 'An IoT-based system integrating smart insoles, wrist motion sensors, and a cloud dashboard to remotely monitor elderly rehabilitation exercises, reducing hospital visits by 60% in a 3-month pilot.',
                'description' => [
                    'Remote patient monitoring is increasingly important in geriatric care, where frequent hospital visits pose significant logistical challenges. This work integrates pressure-sensitive insoles and MEMS accelerometers into a cohesive IoT platform.',
                    'Data is aggregated on an ESP32 gateway and forwarded to AWS IoT Core, where a React dashboard visualizes exercise completion rates and flags deviations from prescribed routines for physiotherapist review.',
                ],
                'keywords' => ['IoT', 'remote monitoring', 'elderly care', 'ESP32', 'rehabilitation', 'wearable'],
                'journal' => 'Indonesian Journal of Medical Technology',
                'is_free_access' => true,
                'is_featured' => false,
                'published_at' => '2025-02-28',
            ],
        ];

        foreach ($publications as $data) {
            Publication::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
