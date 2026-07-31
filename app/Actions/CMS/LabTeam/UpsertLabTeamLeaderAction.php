<?php

namespace App\Actions\CMS\LabTeam;

use App\DTOs\CMS\LabTeamPersonData;
use App\Models\LabTeamPerson;
use App\Models\LabTeamSection;

class UpsertLabTeamLeaderAction
{
    public function execute(LabTeamSection $section, LabTeamPersonData $data): LabTeamPerson
    {
        $leader = $section->leader;

        $attributes = [
            'section_id' => $section->id,
            'is_leader' => true,
            'name_full' => $data->name_full,
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
            'initials' => $data->initials,
            'sort_order' => 0,
            'is_active' => true,
        ];

        if ($data->photo_url !== null) {
            $attributes['photo_url'] = $data->photo_url;
        }

        if ($leader) {
            if ($data->name_full !== $leader->name_full) {
                $attributes['slug'] = LabTeamPerson::generateUniqueSlug($data->name_full, $leader->id);
            }

            $leader->update($attributes);

            return $leader;
        }

        $attributes['slug'] = LabTeamPerson::generateUniqueSlug($data->name_full);

        return LabTeamPerson::create($attributes);
    }
}
