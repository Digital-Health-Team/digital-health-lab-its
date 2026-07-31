<?php

use App\Models\LabTeamPerson;
use App\Models\LabTeamSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Collapses the two research team sections (IDIG HTECH, IDIG RCMED) into one flat
 * "Seluruh Anggota Riset IDIG" roster — no sub-teams, no leader, every researcher a peer.
 *
 * One section is renamed in place and the other's rows are reparented into it, rather
 * than creating a third section: keeping an existing id means its polymorphic gallery
 * attachments stay attached without being touched, and a second run is a no-op.
 */
return new class extends Migration
{
    /**
     * Both ex-leads carried 'Team Leadership' as expertise[0] and a bio opening with
     * "Head of HTECH/RCMED …". The landing ledger now renders expertise[0], so leaving
     * them would reprint the very distinction this merge removes.
     *
     * TODO(cms): placeholder copy — replace with each person's real focus via
     * /admin/cms/team-sections.
     */
    private const LEAD_REWRITES = [
        'muhammad-iqbal-putra-subekti' => [
            'expertise' => ['Hardware Prototyping', 'Wearable Systems'],
            'bio' => "Designs and prototypes the enclosures and sensor housings behind the lab's wearable diagnostic hardware.\n\nHe spent two years prototyping wearable sensor housings, and now works across every hardware-facing project in the lab.",
        ],
        'ray-louie-dangelito' => [
            'expertise' => ['Clinical Data Science', 'Research Methodology'],
            'bio' => "Works on clinical data methodology, shaping how the lab's imaging, NLP, and predictive-modelling studies are designed and validated.\n\nHe came from a clinical data background, and helps set the research agenda across the lab's computational projects.",
        ],
    ];

    public function up(): void
    {
        $find = fn (string $token) => LabTeamSection::where(fn ($q) => $q
            ->where('label_id', 'like', "%{$token}%")
            ->orWhere('label_en', 'like', "%{$token}%")
        )->first();

        $htech = $find('HTECH');
        $rcmed = $find('RCMED');

        // Already merged, a fresh DB, or an admin renamed both past recognition. Labels are
        // the only handle — lab_team_sections has no slug or key column — and guessing by
        // sort_order would mangle whatever happens to sit at position 2/3. Bail instead.
        if (! $htech && ! $rcmed) {
            return;
        }

        DB::transaction(function () use ($htech, $rcmed) {
            $target = $htech ?? $rcmed;
            $donor = ($htech && $rcmed) ? $rcmed : null;

            $target->update([
                'label_id' => 'SELURUH ANGGOTA RISET IDIG',
                'label_en' => 'All IDIG Research Members',
                'sort_order' => 2,
                'is_active' => true,
            ]);

            if ($donor) {
                // People first: section_id is cascadeOnDelete, so deleting the donor
                // before moving them destroys the rows outright.
                DB::table('lab_team_people')
                    ->where('section_id', $donor->id)
                    ->update(['section_id' => $target->id]);

                // attachments is a MorphMany — polymorphic, no foreign key, therefore no
                // cascade. Left alone these rows survive the delete pointing at a dead
                // attachable_id and are invisible forever. Raw query on purpose: Attachment
                // uses RecordsActivity and would log an audit row with no acting user.
                DB::table('attachments')
                    ->where('attachable_type', LabTeamSection::class)
                    ->where('attachable_id', $donor->id)
                    ->update(['attachable_id' => $target->id]);

                $donor->delete();
            }

            // Both galleries numbered from 0, so the merged set now has duplicate
            // sort_orders and orderBy() picks arbitrarily between them — which scrambles
            // which photo lands in which collage slot. Resequence by id so each original
            // gallery stays contiguous and in its authored order.
            DB::table('attachments')
                ->where('attachable_type', LabTeamSection::class)
                ->where('attachable_id', $target->id)
                ->orderBy('id')
                ->pluck('id')
                ->each(fn ($id, $i) => DB::table('attachments')
                    ->where('id', $id)
                    ->update(['sort_order' => $i]));

            // Flatten. Ex-leaders sort first so the resulting order matches the seeder.
            LabTeamPerson::where('section_id', $target->id)
                ->orderByDesc('is_leader')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->each(function (LabTeamPerson $person, int $i) {
                    $person->update([
                        'is_leader' => false,
                        'sort_order' => $i + 1,
                        'role_id' => 'Anggota Riset IDIG',
                        'role_en' => 'IDIG Research Member',
                        // slug is deliberately never written — /team/{slug} must keep resolving.
                        ...(self::LEAD_REWRITES[$person->slug] ?? []),
                    ]);
                });
        });
    }

    public function down(): void
    {
        // Irreversible by design. up() destroys the section boundary, every person's
        // is_leader flag and original sort_order, and both role strings, and nothing
        // records the prior values. Throwing here would break migrate:rollback for the
        // whole batch, so this is a no-op — recovery means restoring a backup.
    }
};
