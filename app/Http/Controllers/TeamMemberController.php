<?php

namespace App\Http\Controllers;

use App\Models\LabTeamPerson;
use Inertia\Inertia;
use Inertia\Response;

class TeamMemberController extends Controller
{
    public function show(LabTeamPerson $labTeamPerson): Response
    {
        abort_if(! $labTeamPerson->is_active, 404);

        $labTeamPerson->load('section');

        $teammates = LabTeamPerson::where('section_id', $labTeamPerson->section_id)
            ->where('id', '!=', $labTeamPerson->id)
            ->where('is_active', true)
            ->orderByDesc('is_leader')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (LabTeamPerson $t) => [
                'name' => $t->name_full,
                'roleId' => $t->role_id,
                'initials' => $t->initials,
                'photo' => $t->photo_url ? asset('storage/'.$t->photo_url) : null,
                'href' => route('team.show', $t->slug),
            ])
            ->values();

        return Inertia::render('Features/Team/Pages/TeamMemberShow', [
            'member' => [
                'name' => $labTeamPerson->name_full,
                'display' => [$labTeamPerson->display_line_1, $labTeamPerson->display_line_2],
                'roleId' => $labTeamPerson->role_id,
                'roleEn' => $labTeamPerson->role_en,
                'bio' => $labTeamPerson->bio,
                'initials' => $labTeamPerson->initials,
                'photo' => $labTeamPerson->photo_url ? asset('storage/'.$labTeamPerson->photo_url) : null,
                'email' => $labTeamPerson->email,
                'linkedin' => $labTeamPerson->linkedin_url,
                'instagram' => $labTeamPerson->instagram_url,
                'expertise' => $labTeamPerson->expertise ?? [],
                'completedProjects' => $labTeamPerson->completed_projects ?? [],
                'education' => $labTeamPerson->education ?? [],
                'isLeader' => $labTeamPerson->is_leader,
                'sectionLabelId' => $labTeamPerson->section->label_id,
                'sectionLabelEn' => $labTeamPerson->section->label_en,
            ],
            'teammates' => $teammates,
        ]);
    }
}
