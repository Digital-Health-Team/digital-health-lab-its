<?php

namespace App\Actions\CMS\LabTeam;

use App\DTOs\CMS\LabTeamSectionData;
use App\Models\LabTeamSection;

class CreateLabTeamSectionAction
{
    public function execute(LabTeamSectionData $data): LabTeamSection
    {
        return LabTeamSection::create([
            'label_id' => $data->label_id,
            'label_en' => $data->label_en,
            'sort_order' => $data->sort_order,
            'is_active' => $data->is_active,
        ]);
    }
}
