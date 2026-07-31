<?php

namespace App\Actions\Training;

use App\Models\Training;

class ToggleTrainingStatusAction
{
    public function execute(Training $training): void
    {
        $training->update(['is_active' => ! $training->is_active]);
    }
}
