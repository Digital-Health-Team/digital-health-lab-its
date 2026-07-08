<?php

namespace App\Notifications;

use App\Models\IssueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class IssueReportSubmitted extends Notification
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
        $report = $this->report->loadMissing(['reporter', 'reportable']);

        return [
            'title' => __('New Issue Report'),
            'message' => __(':name reported: :type — :excerpt', [
                'name' => $report->reporter?->name ?? __('Unknown'),
                'type' => $report->type->label(),
                'excerpt' => str($report->description)->limit(80),
            ]),
            'icon' => 'o-flag',
            'url' => route('admin.reports', ['report' => $report->id]),
            'report_id' => $report->id,
        ];
    }
}
