<?php

use App\Models\LabTeamPerson;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the IDIG org-chart taxonomy (unit / department / PIC) to team people, then brings
 * an existing roster in line with the 2026 chart: 11 people updated in place, 5 added.
 *
 * The roster itself lives in database/data/idig_roster.php, shared with
 * LabTeamSectionSeeder so the migrate path and the migrate:fresh --seed path cannot drift.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_team_people', function (Blueprint $table) {
            $table->json('units')->nullable()->after('education');
            $table->json('departments')->nullable()->after('units');
            // ponytail: PIC is one flat list — the hospital is just the first entry.
            // Split to its own column when something actually keys off it.
            $table->json('pic')->nullable()->after('departments');
        });

        $roster = require database_path('data/idig_roster.php');

        // Resolve the section through a person we know rather than matching the section
        // label: labels are admin-editable, slugs are not. The bail also covers a fresh
        // or test database, where the seeder owns this data — without it, RefreshDatabase
        // (migrate, then seed) would insert the five new people twice.
        $sectionId = LabTeamPerson::where('slug', 'muhammad-iqbal-putra-subekti')->value('section_id');

        if (! $sectionId) {
            return;
        }

        DB::transaction(function () use ($roster, $sectionId) {
            foreach ($roster['members'] as $i => $row) {
                $attributes = [
                    'display_line_1' => $row['short'],
                    'initials' => $row['code'],
                    'role_id' => $row['role_id'],
                    'role_en' => $row['role_en'],
                    'units' => $row['units'],
                    'departments' => $row['departments'],
                    'pic' => $row['pic'],
                    'sort_order' => $i + 1,
                    // slug is never written — /team/{slug} must keep resolving.
                    // bio/expertise/education/completed_projects are never written either:
                    // an admin may have edited them through the CMS since.
                ];

                if ($row['slug']) {
                    LabTeamPerson::where('slug', $row['slug'])->update($attributes);

                    continue;
                }

                // New to the chart. Match on name within the section, NOT on a generated
                // slug: generateUniqueSlug() appends -2 when the base is taken, so on a
                // second run it would mint `raihan-2`, match nothing, and insert a
                // duplicate person. name_full is the stable key here.
                $existing = LabTeamPerson::where('section_id', $sectionId)
                    ->where('name_full', $row['name_full'])
                    ->first();

                if ($existing) {
                    $existing->update($attributes);

                    continue;
                }

                LabTeamPerson::create($attributes + [
                    'section_id' => $sectionId,
                    'slug' => LabTeamPerson::generateUniqueSlug($row['name_full']),
                    'is_leader' => false,
                    'name_full' => $row['name_full'],
                    // NOT NULL and required by the CMS form; renders nowhere for
                    // non-leaders, so the short name is a fine placeholder.
                    'display_line_2' => $row['short'],
                    'is_active' => true,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('lab_team_people', function (Blueprint $table) {
            $table->dropColumn(['units', 'departments', 'pic']);
        });

        // The roster rewrite itself is irreversible: up() overwrites display names, codes
        // and roles without recording the previous values, and creates five people it
        // cannot tell apart from ones an admin added. Recovery means restoring a backup.
    }
};
