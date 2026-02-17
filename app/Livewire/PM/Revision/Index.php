<?php

namespace App\Livewire\PM\Revision;

use App\Models\Jobdesk;
use App\Models\RevisionThread;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Discussion & Revision')]
class Index extends Component
{
    use Toast, WithFileUploads;

    public Jobdesk $jobdesk;

    // Form Input
    public $content;
    public $attachments = [];

    public function mount(Jobdesk $jobdesk)
    {
        // Security check: Pastikan PM ini pembuat project/task atau admin
        if ($jobdesk->project->created_by !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->jobdesk = $jobdesk->load(['revisionThreads.user', 'revisionThreads.attachments']);
    }

    public function sendInstruction()
    {
        $this->validate([
            'content' => 'required|min:3',
            'attachments.*' => 'image|max:10240', // 10MB max per image
        ]);

        // 1. Update Status Jobdesk jadi Revision
        $this->jobdesk->update(['status' => 'revision']);

        // 2. Buat Thread (Is Staff Reply = False karena ini PM)
        $thread = RevisionThread::create([
            'jobdesk_id' => $this->jobdesk->id,
            'user_id' => auth()->id(),
            'content' => $this->content,
            'is_staff_reply' => false,
        ]);

        // 3. Simpan Attachments
        foreach ($this->attachments as $file) {
            $path = $file->store('revisions', 'public');
            $thread->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'uploader_id' => auth()->id(),
            ]);
        }

        $this->reset(['content', 'attachments']);
        $this->success('Instruction sent! Status updated to REVISION.');
    }

    public function markAsApproved()
    {
        $this->jobdesk->update([
            'status' => 'approved',
            'completed_at' => now()
        ]);
        $this->success('Task approved successfully!');
        return redirect()->route('pm.dashboard');
    }

    public function render()
    {
        return view('livewire.pm.revision.show');
    }
}
