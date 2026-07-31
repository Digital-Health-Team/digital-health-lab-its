<?php

/**
 * The IDIG research roster — the single source of truth for both
 * LabTeamSectionSeeder (fresh installs) and 2026_07_29_000002_* (existing databases).
 *
 * Two readers, one file, on purpose: the previous merge migration had to carry a comment
 * asking the next developer to keep its data in sync with the seeder by hand. This is
 * what removes that class of drift.
 *
 * `slug` identifies a person who already exists in the database — the migration matches
 * on it and never writes it, so /team/{slug} keeps resolving. `slug => null` means the
 * migration inserts them.
 *
 * Everything from `name_full` down is the *profile* payload and is written by the seeder
 * only. The migration deliberately never touches bio/expertise/education/projects on
 * existing rows: an admin may have edited them through the CMS since.
 *
 * `units` and `departments` must draw from the fixed taxonomy below — LandingPageTest
 * asserts it, so a typo here fails the suite rather than shipping a stray tag.
 *
 * Unit (IDIG RCMed)      : Manekin, Implant, Rehab, InTech, Administrative
 * Department (IDIG HTech): CAD, CAM, CAE, Electronics, Informatics, Publikasi, Patent,
 *                          Social Media, Sekretariat
 */

// Locals, not top-level const: this file is require()d by both the seeder and the
// migration, and re-executing a const definition raises "already defined".
$roleId = 'Anggota Riset IDIG';   // the role shared by everyone who isn't a team lead
$roleEn = 'IDIG Research Member'; // suppressed in the landing row — see LandingPageController

return [
    'units' => ['Manekin', 'Implant', 'Rehab', 'InTech', 'Administrative'],

    'departments' => [
        'CAD', 'CAM', 'CAE', 'Electronics', 'Informatics',
        'Publikasi', 'Patent', 'Social Media', 'Sekretariat',
    ],

    'member_role' => ['id' => $roleId, 'en' => $roleEn],

    'members' => [
        [
            'slug' => 'muhammad-iqbal-putra-subekti',
            'short' => 'Iqbal',
            'code' => 'IQB',
            'role_id' => 'Ketua Tim HTech',
            'role_en' => 'Team Lead HTech',
            'units' => ['Manekin', 'Rehab'],
            'departments' => ['CAD', 'CAM', 'Electronics'],
            'pic' => null,
            'name_full' => 'Muhammad Iqbal Putra Subekti.',
            'bio' => "Designs and prototypes the enclosures and sensor housings behind the lab's wearable diagnostic hardware.\n\nHe spent two years prototyping wearable sensor housings, and now works across every hardware-facing project in the lab.",
            'expertise' => ['Hardware Prototyping', 'Wearable Systems'],
            'projects' => [['title' => 'HTECH Sensor Housing Line', 'description' => "Redesigned enclosure family for the lab's wearable biosensors, cutting assembly time in half.", 'url' => null]],
            'education' => ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember'],
        ],
        [
            'slug' => 'ray-louie-dangelito',
            'short' => 'Ray',
            'code' => 'RAY',
            'role_id' => 'Ketua Tim RCMed',
            'role_en' => 'Team Lead RCMed',
            'units' => ['Manekin', 'Implant', 'InTech'],
            'departments' => ['CAD', 'CAM', 'CAE', 'Electronics'],
            'pic' => ['RSUA', 'dr. Putri', 'dr. Gamma', 'dr. Khildan', 'Bu Evi'],
            'name_full' => "Ray Louie D'Angelito.",
            'bio' => "Works on clinical data methodology, shaping how the lab's imaging, NLP, and predictive-modelling studies are designed and validated.\n\nHe came from a clinical data background, and helps set the research agenda across the lab's computational projects.",
            'expertise' => ['Clinical Data Science', 'Research Methodology'],
            'projects' => [['title' => 'RCMED Research Roadmap', 'description' => 'Three-year research agenda now guiding every project intake.', 'url' => null]],
            'education' => ['S.T. Informatika, Institut Teknologi Sepuluh Nopember'],
        ],
        [
            'slug' => 'jordan-jonathan-susanto',
            'short' => 'Jordan',
            'code' => 'JOR',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Rehab', 'InTech'],
            'departments' => ['CAD', 'CAM', 'CAE', 'Informatics'],
            'pic' => ['RSO', 'Pak Ardian', 'Pak Dhany'],
            'name_full' => 'Jordan Jonathan Susanto',
            'bio' => "Develops low-noise signal-acquisition circuits for wearable biometric sensors used in continuous patient monitoring.\n\nHis work powers the lab's continuous-monitoring wearables, from early breadboard prototypes through to the enclosures now used in clinical pilots.",
            'expertise' => ['Biosignal Acquisition', 'Wearable Sensors'],
            'email' => 'jordan.susanto@its.ac.id',
            'projects' => [['title' => 'Low-Noise ECG Front-End', 'description' => 'Signal-acquisition board for a wearable cardiac monitor, reducing baseline noise by 30%.', 'url' => null]],
            'education' => ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember (2024)'],
        ],
        [
            'slug' => 'varrel-septian-bawole',
            'short' => 'Varel',
            'code' => 'VRL',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'InTech'],
            'departments' => ['CAD', 'CAE', 'Informatics', 'Publikasi'],
            'pic' => ['RSGM', 'dr. Reza', 'Prof. Agung', 'dr. Wilson', 'dr. Tara'],
            'name_full' => 'Varrel Septian Bawole',
            'bio' => "Writes embedded firmware for the lab's custom diagnostic hardware, specializing in real-time biosignal processing pipelines.\n\nHe got into embedded systems through amateur robotics, and now owns the firmware layer for every diagnostic device the lab ships.",
            'expertise' => ['Embedded Firmware', 'Real-Time Signal Processing'],
            'projects' => [['title' => 'Real-Time Biosignal Pipeline', 'description' => "Firmware pipeline processing biosignals at 1kHz on the lab's diagnostic hardware.", 'url' => null]],
            'education' => ['S.T. Teknik Komputer, Institut Teknologi Sepuluh Nopember (2024)'],
        ],
        [
            'slug' => 'talita-dian-anggraini',
            'short' => 'Talita',
            'code' => 'TAL',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Implant', 'InTech', 'Administrative'],
            'departments' => ['CAD', 'Informatics', 'Publikasi', 'Sekretariat'],
            'pic' => ['RSCM', 'Prof. Ria', 'dr. Ariel', 'dr. Gwen'],
            'name_full' => 'Talita Dian Anggraini',
            'bio' => "Validates clinical-grade circuits against regulatory standards, handling EMI testing and reliability analysis across prototype iterations.\n\nShe built the lab's internal EMI testing rig from scratch, and every clinical-grade prototype now passes through her validation process before deployment.",
            'expertise' => ['EMI Testing', 'Regulatory Compliance'],
            'projects' => [['title' => 'Internal EMI Test Rig', 'description' => 'Low-cost electromagnetic interference testing setup used to pre-validate prototypes before external certification.', 'url' => null]],
            'education' => ['S.T. Teknik Elektro, Institut Teknologi Sepuluh Nopember (2024)'],
        ],
        [
            'slug' => 'agnes-pramesti-veronica',
            'short' => 'Agnes',
            'code' => 'AVE',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'InTech'],
            'departments' => ['CAD', 'CAM', 'CAE'],
            'pic' => null,
            'name_full' => 'Agnes Pramesti Veronica',
            'bio' => "Leads biomechanical material characterization and rapid prototyping for next-generation medical instrumentation housings.\n\nHer material studies directly shaped the housing designs now used across three generations of the lab's medical instrumentation.",
            'expertise' => ['Biomechanical Materials', 'Rapid Prototyping'],
            'instagram' => 'https://instagram.com/agnespramesti',
            'projects' => [['title' => 'Biocompatible Housing Study', 'description' => 'Material characterization project selecting medical-grade polymers for patient-contact enclosures.', 'url' => null]],
            'education' => ['S.T. Teknik Material, Institut Teknologi Sepuluh Nopember (2023)'],
        ],
        [
            'slug' => 'alfa-nailah-ciwandan',
            'short' => 'Alfa',
            'code' => 'ALF',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'Administrative'],
            'departments' => ['Publikasi', 'Patent', 'Sekretariat'],
            'pic' => ['dr. Anny', 'dr. Agnes'],
            'name_full' => "Alfa Na'ilah Ciwandan",
            'bio' => "Maintains bioinformatics pipelines for genomic and proteomics datasets, with emphasis on reproducible omics normalization.\n\nShe maintains the pipelines that keep the lab's genomic datasets reproducible across every downstream analysis.",
            'expertise' => ['Bioinformatics', 'Omics Data Pipelines'],
            'projects' => [['title' => 'Omics Normalization Pipeline', 'description' => 'Reproducible bioinformatics pipeline standardizing genomic and proteomic datasets for lab-wide use.', 'url' => null]],
            'education' => ['S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember (2024)'],
        ],
        [
            'slug' => 'sheila-rahma-azizah',
            'short' => 'Sheila',
            'code' => 'SHL',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'Administrative'],
            'departments' => ['CAD', 'CAM', 'Publikasi', 'Patent', 'Sekretariat'],
            'pic' => ['dr. Rachma'],
            'name_full' => 'Sheila Rahma Azizah',
            'bio' => "Designs clinical NLP systems that extract structured diagnoses and medication events from free-text medical records.\n\nShe built the lab's first Indonesian-language clinical NLP pipeline, now used to structure records across two partner clinics.",
            'expertise' => ['Clinical NLP', 'Health Informatics'],
            'projects' => [['title' => 'Clinical Record Extractor', 'description' => 'NLP pipeline extracting structured diagnoses from Indonesian-language medical notes.', 'url' => null]],
            'education' => ['S.T. Sistem Informasi, Institut Teknologi Sepuluh Nopember (2024)'],
        ],
        [
            'slug' => 'intan-fitri-hardyanti',
            'short' => 'Intan',
            'code' => 'INT',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Administrative', 'InTech'],
            'departments' => ['Informatics', 'Publikasi'],
            'pic' => null,
            'name_full' => 'Intan Fitri Hardyanti',
            'bio' => "Researches federated-learning frameworks that train shared diagnostic models while keeping patient data on-site at each hospital.\n\nHer federated-learning research lets partner hospitals collaborate on shared diagnostic models without ever moving patient data off-site.",
            'expertise' => ['Federated Learning', 'Privacy-Preserving ML'],
            'projects' => [['title' => 'Federated Diagnostic Model', 'description' => 'Privacy-preserving diagnostic model trained across three partner hospitals without centralizing patient data.', 'url' => null]],
            'education' => ['S.T. Teknik Informatika, Institut Teknologi Sepuluh Nopember (2023)'],
        ],
        [
            // Nora and Valinka Nooraisha are the same person — confirmed with the user. She
            // keeps her record, bio and slug; only the display name and code change.
            'slug' => 'valinka-nooraisha',
            'short' => 'Nora',
            'code' => 'NRA',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin'],
            'departments' => ['CAM', 'Social Media'],
            'pic' => null,
            'name_full' => 'Valinka Nooraisha',
            'bio' => "Builds convolutional segmentation pipelines for MRI and CT imaging, bridging deep learning with interpretable clinical readouts.\n\nHer segmentation models are now used as the baseline architecture for every new imaging project the team takes on.",
            'expertise' => ['Medical Image Segmentation', 'Deep Learning'],
            'projects' => [['title' => 'MRI Tumor Segmentation Pipeline', 'description' => 'Convolutional segmentation model reaching 0.89 Dice score on internal validation data.', 'url' => null]],
            'education' => ['S.T. Teknik Informatika, Institut Teknologi Sepuluh Nopember (2024)'],
        ],
        [
            'slug' => 'qonita-alifa-fiddars',
            'short' => 'Alifa',
            'code' => 'QON',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'Administrative'],
            'departments' => ['CAD', 'CAM', 'Publikasi', 'Patent', 'Social Media'],
            'pic' => null,
            'name_full' => 'Qonita Alifa Fiddars',
            'bio' => "Investigates interpretable machine-learning models for diagnostic risk prediction in resource-limited clinical environments.\n\nHer risk-prediction work focuses on models that stay interpretable enough for clinicians to trust and act on.",
            'expertise' => ['Interpretable ML', 'Risk Prediction'],
            'projects' => [['title' => 'Interpretable Risk Scoring Model', 'description' => 'Diagnostic risk model with clinician-readable feature explanations, piloted at a partner clinic.', 'url' => null]],
            'education' => ['S.T. Teknik Informatika, Institut Teknologi Sepuluh Nopember (2023)'],
        ],

        // ── New in the 2026 org chart. TODO(cms): these five were supplied by short name
        //    only. Fill in full names, bios, education and photos via /admin/cms/team-sections;
        //    their slugs are derived from the short name and will not change afterwards.
        [
            'slug' => null,
            'short' => 'Raihan',
            'code' => 'RHN',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['InTech', 'Administrative'],
            'departments' => ['Informatics', 'Publikasi', 'Patent', 'Sekretariat'],
            'pic' => null,
            'name_full' => 'Raihan',
        ],
        [
            'slug' => null,
            'short' => 'Ayu',
            'code' => 'AYU',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'Administrative'],
            'departments' => ['CAD', 'CAM', 'Social Media', 'Sekretariat'],
            'pic' => null,
            'name_full' => 'Ayu',
        ],
        [
            // Normalized from the chart's informal "UNITNYA : INTECH DAN IMPLANT / depart:
            // CAM, informatics" — confirmed with the user.
            'slug' => null,
            'short' => 'Ica',
            'code' => 'ICA',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['InTech', 'Implant'],
            'departments' => ['CAM', 'Informatics'],
            'pic' => null,
            'name_full' => 'Ica',
        ],
        [
            'slug' => null,
            'short' => 'Nadift',
            'code' => 'NDP',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['InTech', 'Manekin'],
            'departments' => ['CAD', 'CAM', 'Electronics', 'Social Media'],
            'pic' => null,
            'name_full' => 'Nadift',
        ],
        [
            // The chart lists Publikasi as a Unit and Administrasi as a Department for Litha,
            // which inverts the taxonomy. Normalized to Unit: Manekin, Administrative /
            // Dept: CAM, Publikasi — confirmed with the user.
            'slug' => null,
            'short' => 'Litha',
            'code' => 'LTH',
            'role_id' => $roleId,
            'role_en' => $roleEn,
            'units' => ['Manekin', 'Administrative'],
            'departments' => ['CAM', 'Publikasi'],
            'pic' => null,
            'name_full' => 'Litha',
        ],
    ],
];
