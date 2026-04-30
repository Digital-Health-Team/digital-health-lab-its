<?php

namespace App\Actions\CMS\PageSection;

use App\Models\PageSection;

class DeletePageSectionAction
{
    public function execute(PageSection $section): void
    {
        $section->delete();
    }
}
