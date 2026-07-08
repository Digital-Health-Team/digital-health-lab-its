<?php

namespace App\Notifications;

use App\Models\IssueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class IssueReportStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public IssueReport $report) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload();
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = $this->payload();

        return new BroadcastMessage([
            'title' => $data['title'],
            'message' => $data['message'],
            'url' => $data['url'],
        ]);
    }

    protected function payload(): array
    {
        return [
            'title' => __('Issue Report :status', ['status' => $this->report->status->label()]),
            'message' => __('Your report #:id is now :status.:note', [
                'id' => $this->report->id,
                'status' => $this->report->status->label(),
                'note' => $this->report->resolution_note ? ' '.__('Note: :note', ['note' => $this->report->resolution_note]) : '',
            ]),
            'icon' => 'o-clipboard-document-check',
            'url' => route('admin.reports', ['report' => $this->report->id]),
            'report_id' => $this->report->id,
        ];
    }
}
