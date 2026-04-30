<?php

namespace App\Actions\CMS\StructuralMember;

use App\DTOs\CMS\StructuralMemberData;
use App\Models\StructuralMember;

class CreateStructuralMemberAction
{
    public function execute(StructuralMemberData $data): StructuralMember
    {
        return StructuralMember::create([
            'user_id' => $data->user_id,
            'name' => $data->name,
            'position' => $data->position,
            'display_order' => $data->display_order,
            'is_active' => $data->is_active,
        ]);
    }
}
