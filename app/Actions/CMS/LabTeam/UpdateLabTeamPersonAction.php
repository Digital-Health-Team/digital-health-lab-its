<?php

namespace App\Actions\CMS\LabTeam;

use App\DTOs\CMS\LabTeamPersonData;
use App\Models\LabTeamPerson;

class UpdateLabTeamPersonAction
{
    public function execute(LabTeamPerson $person, LabTeamPersonData $data): LabTeamPerson
    {
        $attributes = [
            'name_full' => $data->name_full,
            'display_line_1' => $data->display_line_1,
            'display_line_2' => $data->display_line_2,
            'role_id' => $data->role_id,
            'role_en' => $data->role_en,
            'bio' => $data->bio,
            'initials' => $data->initials,
            'sort_order' => $data->sort_order,
            'is_active' => $data->is_active,
        ];

        if ($data->photo_url !== null) {
            $attributes['photo_url'] = $data->photo_url;
        }

        $person->update($attributes);

        return $person;
    }
}
