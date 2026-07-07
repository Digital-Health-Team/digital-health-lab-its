<?php

namespace App\Actions\Report;

use App\Enums\ReportStatus;
use App\Models\IssueReport;
use App\Notifications\IssueReportStatusUpdated;

class UpdateIssueReportStatusAction
{
    /**
     * Warehouse-only status change. Resolver metadata is stamped only on a
     * final status and cleared again if the report is reopened.
     */
    public function execute(IssueReport $report, ReportStatus $status, ?string $resolutionNote, int $resolvedBy): IssueReport
    {
        $report->update([
            'status' => $status,
            'resolution_note' => $resolutionNote,
            'resolved_by' => $status->isFinal() ? $resolvedBy : null,
            'resolved_at' => $status->isFinal() ? now() : null,
        ]);

        if ($report->reporter_id !== $resolvedBy) {
            $report->reporter->notify(new IssueReportStatusUpdated($report));
        }

        return $report;
    }
}
