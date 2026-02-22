<?php

namespace App\Livewire\Staff\Revision;

use App\Models\Jobdesk;
use App\Models\RevisionThread;
use App\Models\Attendance;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Task Details')]
class Index extends Component
{
    use WithFileUploads, Toast;

    public Jobdesk $jobdesk;
    public bool $canSubmit = false;

    // Form States
    public string $replyContent = '';
    public $replyFiles = [];
    public bool $markAsFixed = false;

    public function mount(Jobdesk $jobdesk)
    {
        // Security Check
        if ($jobdesk->assigned_to !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // [UPDATE] Load relasi reports.details dan reports.attachments untuk Work Log
        $this->jobdesk = $jobdesk->load([
            'revisionThreads.attachments',
            'project',
            'creator',
            'reports.details',
            'reports.attachments'
        ]);

        // Cek Sesi Aktif untuk mengizinkan komentar
        $activeSession = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->whereNull('check_out')
            ->exists();

        $this->canSubmit = $activeSession;
    }

    public function sendReply()
    {
        $activeSession = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->whereNull('check_out')
            ->exists();

        if (!$activeSession) {
            $this->error('Anda harus Check-in (Start Shift) terlebih dahulu!');
            return;
        }

        $this->validate([
            'replyContent' => 'required|string|min:2',
            'replyFiles.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,zip|max:10240',
        ]);

        DB::transaction(function () {
            $thread = RevisionThread::create([
                'jobdesk_id' => $this->jobdesk->id,
                'user_id' => auth()->id(),
                'content' => $this->replyContent,
                'is_staff_reply' => true,
            ]);

            foreach ($this->replyFiles as $file) {
                $path = $file->store('revisions', 'public');
                $thread->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'uploader_id' => auth()->id(),
                ]);
            }

            if ($this->markAsFixed) {
                $this->jobdesk->update(['status' => 'review']);
            }
        });

        $this->success($this->markAsFixed ? 'Task marked as fixed!' : 'Reply sent successfully.');
        $this->reset(['replyContent', 'replyFiles', 'markAsFixed']);
        $this->jobdesk->refresh();
    }

    public function render()
    {
        return view('livewire.staff.revision.index');
    }
}
