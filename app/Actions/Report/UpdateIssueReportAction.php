<?php

namespace App\Actions\Report;

use App\DTOs\Report\IssueReportData;
use App\Models\IssueReport;

class UpdateIssueReportAction
{
    public function execute(IssueReport $report, IssueReportData $data): IssueReport
    {
        $report->update([
            'type' => $data->type,
            'description' => $data->description,
            'reportable_type' => $data->reportable_type,
            'reportable_id' => $data->reportable_id,
        ]);

        return $report;
    }
}
