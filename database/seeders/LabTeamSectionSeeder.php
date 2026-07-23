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
            'slug' => LabTeamPerson::generateUniqueSlug('Djoko Kuswanto, S.T., M.Biotech.'),
            'display_line_1' => 'Djoko',
            'display_line_2' => 'Kuswanto.',
            'role_id' => 'Kepala Laboratorium IDIG',
            'role_en' => 'Head of IDIG Laboratory',
            'bio' => "Leading the strategic vision and research initiatives at IDIG Health Tech, bridging engineering and medical innovation.\n\nWith over a decade in biomedical instrumentation research, he has guided IDIG from a single fabrication bench into a multidisciplinary hub spanning hardware and computational medicine — mentoring the students who now lead both HTECH and RCMED.",
            'email' => 'djoko.kuswanto@its.ac.id',
            'linkedin_url' => 'https://linkedin.com/in/djoko-kuswanto',
            'expertise' => ['Research Strategy', 'Biomedical Engineering'],
            'completed_projects' => [
                ['title' => 'IDIG Diagnostic Toolkit', 'description' => "A modular hardware framework reused across the lab's prosthetic and diagnostic prototypes.", 'url' => null],
                ['title' => 'Lab-wide 3D Printing SOP', 'description' => 'Standardized fabrication and QA workflow now used by every service booking.', 'url' => null],
            ],
            'education' => ['S.T. Teknik Biomedis · M.Biotech., Institut Teknologi Sepuluh Nopember'],
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
            'slug' => LabTeamPerson::generateUniqueSlug('Muhammad Iqbal Putra Subekti.'),
            'display_line_1' => 'Muhammad',
            'display_line_2' => 'Iqbal P. S.',
            'role_id' => 'Ketua Tim HTECH',
            'role_en' => 'IDIG HTECH Lead',
            'bio' => "Head of HTECH in IDIG, responsible for guiding and leading the HTECH research and development team.\n\nBefore leading the team, he spent two years prototyping wearable sensor housings, and now sets HTECH's technical direction across every hardware-facing project in the lab.",
            'expertise' => ['Team Leadership', 'R&D Management'],
            'completed_projects' => [
                ['title' => 'HTECH Sensor Housing Line', 'description' => "Redesigned enclosure family for the lab's wearable biosensors, cutting assembly time in half.", 'url' => null],
            ],
            'education' => ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember'],
            'initials' => 'MI',
            'photo_url' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $htechMembers = [
            ['Jordan Jonathan Susanto', 'JJ', 'Anggota Tim IDIG HTECH', 'Develops low-noise signal-acquisition circuits for wearable biometric sensors used in continuous patient monitoring.', 'His work powers the lab\'s continuous-monitoring wearables, from early breadboard prototypes through to the enclosures now used in clinical pilots.', ['Biosignal Acquisition', 'Wearable Sensors'], 'jordan.susanto@its.ac.id', null, [['title' => 'Low-Noise ECG Front-End', 'description' => 'Signal-acquisition board for a wearable cardiac monitor, reducing baseline noise by 30%.', 'url' => null]], ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember (2024)']],
            ['Varrel Septian Bawole', 'VS', 'Anggota Tim IDIG HTECH', 'Writes embedded firmware for the lab\'s custom diagnostic hardware, specializing in real-time biosignal processing pipelines.', 'He got into embedded systems through amateur robotics, and now owns the firmware layer for every diagnostic device the lab ships.', ['Embedded Firmware', 'Real-Time Signal Processing'], null, null, [['title' => 'Real-Time Biosignal Pipeline', 'description' => "Firmware pipeline processing biosignals at 1kHz on the lab's diagnostic hardware.", 'url' => null]], ['S.T. Teknik Komputer, Institut Teknologi Sepuluh Nopember (2024)']],
            ['Agnes Pramesti Veronica', 'AV', 'Anggota Tim IDIG HTECH', 'Leads biomechanical material characterization and rapid prototyping for next-generation medical instrumentation housings.', "Her material studies directly shaped the housing designs now used across three generations of the lab's medical instrumentation.", ['Biomechanical Materials', 'Rapid Prototyping'], null, 'https://instagram.com/agnespramesti', [['title' => 'Biocompatible Housing Study', 'description' => 'Material characterization project selecting medical-grade polymers for patient-contact enclosures.', 'url' => null]], ['S.T. Teknik Material, Institut Teknologi Sepuluh Nopember (2023)']],
            ['Talita Dian Anggraini', 'TA', 'Anggota Tim IDIG HTECH', 'Validates clinical-grade circuits against regulatory standards, handling EMI testing and reliability analysis across prototype iterations.', 'She built the lab\'s internal EMI testing rig from scratch, and every clinical-grade prototype now passes through her validation process before deployment.', ['EMI Testing', 'Regulatory Compliance'], null, null, [['title' => 'Internal EMI Test Rig', 'description' => 'Low-cost electromagnetic interference testing setup used to pre-validate prototypes before external certification.', 'url' => null]], ['S.T. Teknik Elektro, Institut Teknologi Sepuluh Nopember (2024)']],
        ];

        foreach ($htechMembers as $i => [$name, $initials, $role, $bio, $bio2, $expertise, $email, $instagram, $projects, $education]) {
            LabTeamPerson::create([
                'section_id' => $sec2->id,
                'is_leader' => false,
                'name_full' => $name,
                'slug' => LabTeamPerson::generateUniqueSlug($name),
                'display_line_1' => explode(' ', $name)[0],
                'display_line_2' => implode(' ', array_slice(explode(' ', $name), 1)),
                'role_id' => $role,
                'role_en' => $role,
                'bio' => "{$bio}\n\n{$bio2}",
                'email' => $email,
                'instagram_url' => $instagram,
                'expertise' => $expertise,
                'completed_projects' => $projects,
                'education' => $education,
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
            'slug' => LabTeamPerson::generateUniqueSlug("Ray Louie D'Angelito."),
            'display_line_1' => 'Ray Louie',
            'display_line_2' => "D'Angelito.",
            'role_id' => 'Ketua Tim RCMED',
            'role_en' => 'IDIG RCMED Lead',
            'bio' => "Head of RCMED in IDIG, responsible for guiding and leading the RCMED research and development team.\n\nHe came to RCMED from a clinical data background, and now steers the team's research agenda across imaging, NLP, and predictive modeling.",
            'expertise' => ['Team Leadership', 'Clinical Research'],
            'completed_projects' => [
                ['title' => 'RCMED Research Roadmap', 'description' => 'Three-year research agenda now guiding every RCMED project intake.', 'url' => null],
            ],
            'education' => ['S.T. Informatika, Institut Teknologi Sepuluh Nopember'],
            'initials' => 'RA',
            'photo_url' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $rcmedMembers = [
            ['Valinka Nooraisha', 'VN', 'Anggota Tim IDIG RCMED', 'Builds convolutional segmentation pipelines for MRI and CT imaging, bridging deep learning with interpretable clinical readouts.', 'Her segmentation models are now used as the baseline architecture for every new imaging project the team takes on.', ['Medical Image Segmentation', 'Deep Learning'], [['title' => 'MRI Tumor Segmentation Pipeline', 'description' => 'Convolutional segmentation model reaching 0.89 Dice score on internal validation data.', 'url' => null]], ['S.T. Teknik Informatika, Institut Teknologi Sepuluh Nopember (2024)']],
            ['Sheila Rahma Azizah', 'SR', 'Anggota Tim IDIG RCMED', 'Designs clinical NLP systems that extract structured diagnoses and medication events from free-text medical records.', "She built the lab's first Indonesian-language clinical NLP pipeline, now used to structure records across two partner clinics.", ['Clinical NLP', 'Health Informatics'], [['title' => 'Clinical Record Extractor', 'description' => 'NLP pipeline extracting structured diagnoses from Indonesian-language medical notes.', 'url' => null]], ['S.T. Sistem Informasi, Institut Teknologi Sepuluh Nopember (2024)']],
            ['Qonita Alifa Fiddars', 'QF', 'Anggota Tim IDIG RCMED', 'Investigates interpretable machine-learning models for diagnostic risk prediction in resource-limited clinical environments.', 'Her risk-prediction work focuses on models that stay interpretable enough for clinicians to trust and act on.', ['Interpretable ML', 'Risk Prediction'], [['title' => 'Interpretable Risk Scoring Model', 'description' => 'Diagnostic risk model with clinician-readable feature explanations, piloted at a partner clinic.', 'url' => null]], ['S.T. Teknik Informatika, Institut Teknologi Sepuluh Nopember (2023)']],
            ["Alfa Na'ilah Ciwandan", 'AN', 'Anggota Tim IDIG RCMED', 'Maintains bioinformatics pipelines for genomic and proteomics datasets, with emphasis on reproducible omics normalization.', "She maintains the pipelines that keep the lab's genomic datasets reproducible across every downstream analysis.", ['Bioinformatics', 'Omics Data Pipelines'], [['title' => 'Omics Normalization Pipeline', 'description' => 'Reproducible bioinformatics pipeline standardizing genomic and proteomic datasets for lab-wide use.', 'url' => null]], ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember (2024)']],
            ['Intan Fitri Hardyanti', 'IF', 'Anggota Tim IDIG RCMED', 'Researches federated-learning frameworks that train shared diagnostic models while keeping patient data on-site at each hospital.', 'Her federated-learning research lets partner hospitals collaborate on shared diagnostic models without ever moving patient data off-site.', ['Federated Learning', 'Privacy-Preserving ML'], [['title' => 'Federated Diagnostic Model', 'description' => 'Privacy-preserving diagnostic model trained across three partner hospitals without centralizing patient data.', 'url' => null]], ['S.T. Teknik Informatika, Institut Teknologi Sepuluh Nopember (2023)']],
        ];

        foreach ($rcmedMembers as $i => [$name, $initials, $role, $bio, $bio2, $expertise, $projects, $education]) {
            LabTeamPerson::create([
                'section_id' => $sec3->id,
                'is_leader' => false,
                'name_full' => $name,
                'slug' => LabTeamPerson::generateUniqueSlug($name),
                'display_line_1' => explode(' ', $name)[0],
                'display_line_2' => implode(' ', array_slice(explode(' ', $name), 1)),
                'role_id' => $role,
                'role_en' => $role,
                'bio' => "{$bio}\n\n{$bio2}",
                'expertise' => $expertise,
                'completed_projects' => $projects,
                'education' => $education,
                'initials' => $initials,
                'photo_url' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
