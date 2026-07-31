<?php

namespace App\Actions\CMS\LabTeam;

use App\DTOs\CMS\LabTeamSectionData;
use App\Models\LabTeamSection;

class UpdateLabTeamSectionAction
{
    public function execute(LabTeamSection $section, LabTeamSectionData $data): LabTeamSection
    {
        $section->update([
            'label_id' => $data->label_id,
            'label_en' => $data->label_en,
            'sort_order' => $data->sort_order,
            'is_active' => $data->is_active,
        ]);

        return $section;
    }
}
