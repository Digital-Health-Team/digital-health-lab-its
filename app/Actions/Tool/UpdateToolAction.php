<?php

namespace App\Actions\Tool;

use App\DTOs\Tool\ToolData;
use App\Models\Tool;

class UpdateToolAction
{
    public function execute(Tool $tool, ToolData $data): void
    {
        $tool->update([
            'name' => $data->name,
            'lab_id' => $data->lab_id,
        ]);
    }
}
