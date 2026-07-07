<?php

namespace App\Actions\CMS\LabTeam;

use App\Models\LabTeamPerson;

class DeleteLabTeamPersonAction
{
    public function execute(LabTeamPerson $person): void
    {
        if ($person->photo_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($person->photo_url);
        }

        $person->delete();
    }
}
