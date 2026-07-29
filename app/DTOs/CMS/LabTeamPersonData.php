<?php

namespace App\DTOs\CMS;

class LabTeamPersonData
{
    public function __construct(
        public readonly int $section_id,
        public readonly bool $is_leader,
        public readonly string $name_full,
        public readonly string $display_line_1,
        public readonly string $display_line_2,
        public readonly string $role_id,
        public readonly string $role_en,
        public readonly ?string $bio,
        public readonly ?string $email,
        public readonly ?string $linkedin_url,
        public readonly ?string $instagram_url,
        public readonly ?array $expertise,
        public readonly ?array $completed_projects,
        public readonly ?array $education,
        public readonly string $initials,
        public readonly ?string $photo_url,
        public readonly int $sort_order,
        public readonly bool $is_active,
        // Appended with defaults on purpose: every existing call site passes all 18
        // parameters by name, and PHP keeps those valid only while new parameters are
        // trailing and defaulted. Adding these anywhere above breaks all of them at once.
        public readonly ?array $units = null,
        public readonly ?array $departments = null,
        public readonly ?array $pic = null,
    ) {}
}
