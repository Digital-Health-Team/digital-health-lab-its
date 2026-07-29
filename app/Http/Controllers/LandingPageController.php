<?php

namespace App\Http\Controllers;

use App\Models\LabTeamSection;
use App\Models\PageSection;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        return static::index();
    }

    public static function index()
    {
        $teamSections = LabTeamSection::with(['leader', 'members', 'attachments'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($s) {
                // members() carries no is_active filter — the CMS needs to list inactive
                // people, but the public page must not link to rows that 404 on click.
                $active = $s->members->where('is_active', true);

                // A role held by a strict majority is the section's default and carries no
                // signal — printing "Anggota Riset IDIG" sixteen times is exactly the noise
                // the roster row is meant to remove. Only one role can pass a >50% test,
                // so this is deterministic, unlike taking the most common and breaking ties.
                $defaultRole = $active->pluck('role_id')
                    ->countBy()
                    ->filter(fn ($n) => $n > $active->count() / 2)
                    ->keys()
                    ->first();

                return [
                    'label_id' => $s->label_id,
                    'label_en' => $s->label_en,
                    'leader' => $s->leader ? [
                        'full' => $s->leader->name_full,
                        'display' => [$s->leader->display_line_1, $s->leader->display_line_2],
                        'roleId' => $s->leader->role_id,
                        'roleEn' => $s->leader->role_en,
                        'desc' => $s->leader->bio,
                        'initials' => $s->leader->initials,
                        'image' => $s->leader->photo_url
                            ? asset('storage/'.$s->leader->photo_url)
                            : null,
                        'href' => $s->leader->slug ? route('team.show', $s->leader->slug) : null,
                    ] : null,
                    'members' => $active->map(fn ($m) => [
                        // The roster row shows the short name; the full one rides along
                        // for the native title= tooltip.
                        'name' => $m->display_line_1,
                        'fullName' => $m->name_full,
                        'initials' => $m->initials,
                        'units' => $m->units ?? [],
                        'departments' => $m->departments ?? [],
                        'role' => $m->role_id === $defaultRole
                            ? null
                            : (app()->getLocale() === 'en' && $m->role_en ? $m->role_en : $m->role_id),
                        // Second-line fallback for CMS-created sections, whose members have
                        // no units or departments and would otherwise render a blank line.
                        'desc' => $m->expertise[0]
                            ?? (app()->getLocale() === 'en' && $m->role_en ? $m->role_en : $m->role_id),
                        'image' => $m->photo_url
                            ? asset('storage/'.$m->photo_url)
                            : null,
                        'href' => $m->slug ? route('team.show', $m->slug) : null,
                    ])->values()->all(),
                    'collage' => $s->attachments->map(fn ($a) => [
                        'url' => $a->file_url,
                        'sort_order' => $a->sort_order,
                        'is_primary' => (bool) $a->is_primary,
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();

        $landingContent = static::landingContentForLocale();

        return Inertia::render('Features/Landing/Pages/LandingPage', [
            'teamSections' => $teamSections,
            'landingContent' => $landingContent,
        ]);
    }

    /**
     * CMS copy for the active locale.
     *
     * Indonesian lives in the base rows; English lives in `<key>_en` rows, so no
     * migration is needed (page_sections is unique on page_name + section_key).
     *
     * ponytail: English overlays the base set rather than replacing it, so
     * locale-independent rows — images, gradients, phone, social URLs — survive.
     * The cost is that a text key with no `_en` row falls back to Indonesian; the
     * seeder ships `_en` for every text key, and an untranslated string is a far
     * better failure than an admin's custom image silently reverting to the bundled one.
     */
    public static function landingContentForLocale(): Collection
    {
        $rows = PageSection::where('page_name', 'landing')->pluck('content', 'section_key');

        $base = $rows->reject(fn ($v, $k) => str_ends_with($k, '_en'));

        if (app()->getLocale() !== 'en') {
            return $base;
        }

        return $base->merge(
            $rows->filter(fn ($v, $k) => str_ends_with($k, '_en'))
                ->mapWithKeys(fn ($v, $k) => [substr($k, 0, -3) => $v])
        );
    }
}
