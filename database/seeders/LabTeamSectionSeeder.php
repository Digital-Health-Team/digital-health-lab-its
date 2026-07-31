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
            'bio' => "Leading the strategic vision and research initiatives at IDIG Health Tech, bridging engineering and medical innovation.\n\nWith over a decade in biomedical instrumentation research, he has guided IDIG from a single fabrication bench into a multidisciplinary hub spanning hardware and computational medicine — mentoring the researchers who now drive its work.",
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

        // ── Section 2: Seluruh Anggota Riset IDIG ────────────────────────────
        // One flat roster — no leader, no sub-teams. The roster itself lives in
        // database/data/idig_roster.php, shared with the org-structure migration so a
        // fresh install and an upgraded database cannot drift apart.
        $sec2 = LabTeamSection::create([
            'label_id' => 'SELURUH ANGGOTA RISET IDIG',
            'label_en' => 'All IDIG Research Members',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        foreach ((require database_path('data/idig_roster.php'))['members'] as $i => $row) {
            LabTeamPerson::create([
                'section_id' => $sec2->id,
                'is_leader' => false,
                'name_full' => $row['name_full'],
                'slug' => LabTeamPerson::generateUniqueSlug($row['name_full']),
                'display_line_1' => $row['short'],
                // Renders nowhere for non-leaders, but the column is NOT NULL.
                'display_line_2' => $row['short'],
                'role_id' => $row['role_id'],
                'role_en' => $row['role_en'],
                'bio' => $row['bio'] ?? null,
                'email' => $row['email'] ?? null,
                'instagram_url' => $row['instagram'] ?? null,
                'expertise' => $row['expertise'] ?? null,
                'completed_projects' => $row['projects'] ?? null,
                'education' => $row['education'] ?? null,
                'units' => $row['units'],
                'departments' => $row['departments'],
                'pic' => $row['pic'],
                'initials' => $row['code'],
                'photo_url' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
