<?php

namespace App\Actions\CMS\StructuralMember;

use App\Models\StructuralMember;

class ToggleStructuralMemberStatusAction
{
    public function execute(StructuralMember $member): void
    {
        $member->update(['is_active' => !$member->is_active]);
    }
}
