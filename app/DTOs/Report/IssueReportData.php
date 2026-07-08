<?php

namespace App\DTOs\Report;

class IssueReportData
{
    public function __construct(
        public readonly string $type,
        public readonly string $description,
        public readonly ?string $reportable_type = null,
        public readonly ?int $reportable_id = null,
    ) {}
}
