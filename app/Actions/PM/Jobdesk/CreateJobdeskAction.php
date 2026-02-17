<?php

namespace App\Actions\PM\Jobdesk;

use App\Models\Jobdesk;
use Illuminate\Support\Facades\DB;

class CreateJobdeskAction
{
    public function execute(array $data): Jobdesk
    {
        return DB::transaction(function () use ($data) {
            return Jobdesk::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'project_id' => $data['project_id'],
                'assigned_to' => $data['assigned_to'],
                'created_by' => auth()->id(),
                'deadline_task' => $data['deadline_task'],
                'status' => 'pending',
            ]);
        });
    }
}
