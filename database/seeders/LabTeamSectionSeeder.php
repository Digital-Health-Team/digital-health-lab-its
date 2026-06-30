<?php

namespace Database\Seeders;

use App\Models\LabTeamPerson;
use App\Models\LabTeamSection;
use Illuminate\Database\Seeder;

class LabTeamSectionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Section 1: Kepala Laboratorium ──────────────────────────────────
        $sec1 = LabTeamSection::create([
            'label_id' => 'KEPALA LABORATORIUM',
            'label_en' => 'Head of Laboratory',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        LabTeamPerson::create([
            'section_id' => $sec1->id,
            'is_leader' => true,
            'name_full' => 'Djoko Kuswanto, S.T., M.Biotech.',
            'display_line_1' => 'Djoko',
            'display_line_2' => 'Kuswanto.',
            'role_id' => 'Kepala Laboratorium IDIG',
            'role_en' => 'Head of IDIG Laboratory',
            'bio' => 'Leading the strategic vision and research initiatives at IDIG Health Tech, bridging engineering and medical innovation.',
            'initials' => 'JK',
            'photo_url' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        // ── Section 2: IDIG HTECH ────────────────────────────────────────────
        $sec2 = LabTeamSection::create([
            'label_id' => 'TIM IDIG HTECH',
            'label_en' => 'IDIG HTECH Team',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        LabTeamPerson::create([
            'section_id' => $sec2->id,
            'is_leader' => true,
            'name_full' => 'Muhammad Iqbal Putra Subekti.',
            'display_line_1' => 'Muhammad',
            'display_line_2' => 'Iqbal P. S.',
            'role_id' => 'Ketua Tim HTECH',
            'role_en' => 'IDIG HTECH Lead',
            'bio' => 'Head of HTECH in IDIG, responsible for guiding and leading the HTECH research and development team.',
            'initials' => 'MI',
            'photo_url' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $htechMembers = [
            ['Jordan Jonathan Susanto', 'JJ', 'Anggota Tim IDIG HTECH', 'Develops low-noise signal-acquisition circuits for wearable biometric sensors used in continuous patient monitoring.'],
            ['Varrel Septian Bawole', 'VS', 'Anggota Tim IDIG HTECH', 'Writes embedded firmware for the lab\'s custom diagnostic hardware, specializing in real-time biosignal processing pipelines.'],
            ['Agnes Pramesti Veronica', 'AV', 'Anggota Tim IDIG HTECH', 'Leads biomechanical material characterization and rapid prototyping for next-generation medical instrumentation housings.'],
            ['Talita Dian Anggraini', 'TA', 'Anggota Tim IDIG HTECH', 'Validates clinical-grade circuits against regulatory standards, handling EMI testing and reliability analysis across prototype iterations.'],
        ];

        foreach ($htechMembers as $i => [$name, $initials, $role, $bio]) {
            LabTeamPerson::create([
                'section_id' => $sec2->id,
                'is_leader' => false,
                'name_full' => $name,
                'display_line_1' => explode(' ', $name)[0],
                'display_line_2' => implode(' ', array_slice(explode(' ', $name), 1)),
                'role_id' => $role,
                'role_en' => $role,
                'bio' => $bio,
                'initials' => $initials,
                'photo_url' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        // ── Section 3: IDIG RCMED ────────────────────────────────────────────
        $sec3 = LabTeamSection::create([
            'label_id' => 'TIM IDIG RCMED',
            'label_en' => 'IDIG RCMED Team',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        LabTeamPerson::create([
            'section_id' => $sec3->id,
            'is_leader' => true,
            'name_full' => "Ray Louie D'Angelito.",
            'display_line_1' => 'Ray Louie',
            'display_line_2' => "D'Angelito.",
            'role_id' => 'Ketua Tim RCMED',
            'role_en' => 'IDIG RCMED Lead',
            'bio' => 'Head of RCMED in IDIG, responsible for guiding and leading the RCMED research and development team.',
            'initials' => 'RA',
            'photo_url' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $rcmedMembers = [
            ['Valinka Nooraisha', 'VN', 'Anggota Tim IDIG RCMED', 'Builds convolutional segmentation pipelines for MRI and CT imaging, bridging deep learning with interpretable clinical readouts.'],
            ['Sheila Rahma Azizah', 'SR', 'Anggota Tim IDIG RCMED', 'Designs clinical NLP systems that extract structured diagnoses and medication events from free-text medical records.'],
            ['Qonita Alifa Fiddars', 'QF', 'Anggota Tim IDIG RCMED', 'Investigates interpretable machine-learning models for diagnostic risk prediction in resource-limited clinical environments.'],
            ["Alfa Na'ilah Ciwandan", 'AN', 'Anggota Tim IDIG RCMED', 'Maintains bioinformatics pipelines for genomic and proteomics datasets, with emphasis on reproducible omics normalization.'],
            ['Intan Fitri Hardyanti', 'IF', 'Anggota Tim IDIG RCMED', 'Researches federated-learning frameworks that train shared diagnostic models while keeping patient data on-site at each hospital.'],
        ];

        foreach ($rcmedMembers as $i => [$name, $initials, $role, $bio]) {
            LabTeamPerson::create([
                'section_id' => $sec3->id,
                'is_leader' => false,
                'name_full' => $name,
                'display_line_1' => explode(' ', $name)[0],
                'display_line_2' => implode(' ', array_slice(explode(' ', $name), 1)),
                'role_id' => $role,
                'role_en' => $role,
                'bio' => $bio,
                'initials' => $initials,
                'photo_url' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
