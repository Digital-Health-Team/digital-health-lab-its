<?php

namespace App\Actions\CMS\LabTeam;

use App\DTOs\CMS\LabTeamPersonData;
use App\Models\LabTeamPerson;

class CreateLabTeamPersonAction
{
    public function execute(LabTeamPersonData $data): LabTeamPerson
    {
        return LabTeamPerson::create([
            'section_id' => $data->section_id,
            'is_leader' => false,
            'name_full' => $data->name_full,
            'slug' => LabTeamPerson::generateUniqueSlug($data->name_full),
            'display_line_1' => $data->display_line_1,
            'display_line_2' => $data->display_line_2,
            'role_id' => $data->role_id,
            'role_en' => $data->role_en,
            'bio' => $data->bio,
            'email' => $data->email,
            'linkedin_url' => $data->linkedin_url,
            'instagram_url' => $data->instagram_url,
            'expertise' => $data->expertise,
            'completed_projects' => $data->completed_projects,
            'education' => $data->education,
            'units' => $data->units,
            'departments' => $data->departments,
            'pic' => $data->pic,
            'initials' => $data->initials,
            'photo_url' => $data->photo_url,
            'sort_order' => $data->sort_order,
            'is_active' => $data->is_active,
        ]);
    }
}
