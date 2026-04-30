<?php

namespace App\Actions\CMS\StructuralMember;

use App\DTOs\CMS\StructuralMemberData;
use App\Models\StructuralMember;

class UpdateStructuralMemberAction
{
    public function execute(StructuralMember $member, StructuralMemberData $data): StructuralMember
    {
        $member->update([
            'user_id' => $data->user_id,
            'name' => $data->name,
            'position' => $data->position,
            'display_order' => $data->display_order,
        ]);

        return $member;
    }
}
