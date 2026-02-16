<?php

namespace App\Livewire\Staff\Revision;

use App\Actions\Jobdesk\StaffReplyRevisionAction;
use App\Models\Jobdesk;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Task Details & Revision')]
class Index extends Component
{
    use WithFileUploads, Toast;

    public Jobdesk $jobdesk;

    // --- FORM STATES ---
    public string $replyContent = '';
    public $replyFiles = [];
    public bool $markAsFixed = false;

    public function mount(Jobdesk $jobdesk)
    {
        // Pastikan staff hanya bisa akses tugas miliknya
        if ($jobdesk->assigned_to !== auth()->id()) {
            abort(403, 'Unauthorized access to this task.');
        }

        $this->jobdesk = $jobdesk->load([
            'revisionThreads.attachments',
            'revisionThreads.user',
            'project',
            'creator'
        ]);
    }

    public function sendReply()
    {
        $this->validate([
            'replyContent' => 'required|string|min:2',
            'replyFiles.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,zip|max:10240',
        ]);

        app(StaffReplyRevisionAction::class)->execute(
            $this->jobdesk,
            auth()->id(),
            $this->replyContent,
            $this->replyFiles,
            $this->markAsFixed
        );

        $this->success($this->markAsFixed ? 'Task submitted for review!' : 'Reply sent successfully.');

        // Reset & Refresh
        $this->reset(['replyContent', 'replyFiles', 'markAsFixed']);
        $this->jobdesk->refresh();
    }

    public function render()
    {
        return view('livewire.staff.revision.index');
    }
}
