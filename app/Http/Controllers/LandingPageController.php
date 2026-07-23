<?php

namespace App\Http\Controllers;

use App\Models\LabTeamSection;
use App\Models\PageSection;
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
            ->map(fn ($s) => [
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
                'members' => $s->members->map(fn ($m) => [
                    'name' => $m->name_full,
                    'desc' => $m->role_id,
                    'initials' => $m->initials,
                    'image' => $m->photo_url
                        ? asset('storage/'.$m->photo_url)
                        : null,
                    'bio' => $m->bio,
                    'href' => $m->slug ? route('team.show', $m->slug) : null,
                ])->values()->all(),
                'collage' => $s->attachments->map(fn ($a) => [
                    'url' => $a->file_url,
                    'sort_order' => $a->sort_order,
                    'is_primary' => (bool) $a->is_primary,
                ])->values()->all(),
            ])
            ->values()
            ->all();

        $landingContent = PageSection::where('page_name', 'landing')
            ->get()
            ->keyBy('section_key')
            ->map(fn ($s) => $s->content);

        return Inertia::render('Features/Landing/Pages/LandingPage', [
            'teamSections' => $teamSections,
            'landingContent' => $landingContent,
        ]);
    }
}
