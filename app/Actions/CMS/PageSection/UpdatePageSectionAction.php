<?php

namespace App\Actions\CMS\PageSection;

use App\DTOs\CMS\PageSectionData;
use App\Models\PageSection;
use Illuminate\Support\Str;

class UpdatePageSectionAction
{
    public function execute(PageSection $section, PageSectionData $data): PageSection
    {
        $section->update([
            'page_name' => Str::slug($data->page_name, '_'),
            'section_key' => Str::slug($data->section_key, '_'),
            'content' => $data->content,
            'updated_by' => auth()->id(),
        ]);

        return $section;
    }
}
