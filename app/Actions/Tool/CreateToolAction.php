<?php

namespace App\Actions\Tool;

use App\DTOs\Tool\ToolData;
use App\Models\Tool;

class CreateToolAction
{
    public function execute(ToolData $data, int $createdBy): Tool
    {
        return Tool::create([
            'name' => $data->name,
            'lab_id' => $data->lab_id,
            'created_by' => $createdBy,
        ]);
    }
}
