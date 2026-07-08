<?php

namespace App\Actions\Report;

use App\DTOs\Report\IssueReportData;
use App\Models\IssueReport;
use App\Models\User;
use App\Notifications\IssueReportSubmitted;
use Illuminate\Support\Facades\Notification;

class CreateIssueReportAction
{
    public function execute(IssueReportData $data, int $reporterId): IssueReport
    {
        $report = IssueReport::create([
            'reporter_id' => $reporterId,
            'type' => $data->type,
            'description' => $data->description,
            'reportable_type' => $data->reportable_type,
            'reportable_id' => $data->reportable_id,
        ]);

        // Reports are handled by the warehouse admins (role_id 3).
        Notification::send(
            User::where('role_id', 3)->get(),
            new IssueReportSubmitted($report)
        );

        return $report;
    }
}
