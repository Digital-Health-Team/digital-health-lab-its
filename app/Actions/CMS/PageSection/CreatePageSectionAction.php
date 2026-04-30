<?php

namespace App\Actions\CMS\PageSection;

use App\DTOs\CMS\PageSectionData;
use App\Models\PageSection;
use Illuminate\Support\Str;

class CreatePageSectionAction
{
    public function execute(PageSectionData $data): PageSection
    {
        return PageSection::create([
            'page_name' => Str::slug($data->page_name, '_'), // Format: about_us
            'section_key' => Str::slug($data->section_key, '_'), // Format: hero_title
            'content' => $data->content,
            'updated_by' => auth()->id(),
        ]);
    }
}
