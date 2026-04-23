<?php

namespace App\DTOs\CMS;

class PageSectionData
{
    public function __construct(
        public string $page_name,
        public string $section_key,
        public string $content
    ) {}
}
