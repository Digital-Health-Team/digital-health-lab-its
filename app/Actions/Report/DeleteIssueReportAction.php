<?php

namespace App\Actions\Report;

use App\Models\IssueReport;
use Illuminate\Support\Facades\Storage;

class DeleteIssueReportAction
{
    public function execute(IssueReport $report): void
    {
        // Attachments are polymorphic — no FK cascade, clean up manually.
        foreach ($report->attachments as $attachment) {
            $relativePath = str_replace(
                Storage::disk('public')->url(''),
                '',
                $attachment->file_url
            );
            Storage::disk('public')->delete($relativePath);
            $attachment->delete();
        }

        $report->delete();
    }
}
