<?php

namespace App\Actions\Jobdesk;

use App\Models\Jobdesk;

class DeleteJobdeskAction
{
    public function execute(Jobdesk $jobdesk): void
    {
        $jobdesk->delete();
    }
}
