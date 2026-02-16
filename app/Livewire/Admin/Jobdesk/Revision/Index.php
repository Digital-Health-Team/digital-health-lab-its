<?php

namespace App\Livewire\Admin\Jobdesk\Revision;

use App\Actions\Jobdesk\ReviseJobdeskAction;
use App\Models\Jobdesk;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Revision Thread')]
class Index extends Component
{
    use WithFileUploads, Toast;

    public Jobdesk $jobdesk; // Model Binding dari Route

    // --- REVISION FORM STATES ---
    public ?int $revisionPmId = null;
    public string $revisionNotes = '';
    public $revisionFiles = [];
    public ?string $revisionDeadline = null;

    // --- PM SEARCH ---
    public string $pmSearch = '';

    public function mount(Jobdesk $jobdesk)
    {
        $this->jobdesk = $jobdesk->load(['revisionThreads.attachments', 'revisionThreads.user', 'project', 'assignee']);

        // Default Values
        $this->revisionPmId = auth()->id();
        $this->revisionDeadline = $jobdesk->deadline_revision
            ? Carbon::parse($jobdesk->deadline_revision)->format('Y-m-d\TH:i')
            : now()->addDays(1)->format('Y-m-d\TH:i');
    }

    public function searchPm(string $value = '')
    {
        $this->pmSearch = trim($value);
    }

    public function submitRevision()
    {
        $this->validate([
            'revisionPmId' => 'required|exists:users,id',
            'revisionNotes' => 'required|string|min:5',
            'revisionFiles.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB
            'revisionDeadline' => 'nullable|date',
        ]);

        app(ReviseJobdeskAction::class)->execute(
            $this->jobdesk,
            $this->revisionPmId,
            $this->revisionNotes,
            $this->revisionFiles,
            $this->revisionDeadline
        );

        $this->success('Revision instruction sent successfully.');

        // Reset Form & Reload Data
        $this->reset(['revisionFiles', 'revisionNotes']);
        $this->jobdesk->refresh(); // Refresh data agar chat baru muncul
    }

    public function render()
    {
        // Cari PM untuk dropdown
        $pms = User::whereIn('role', ['pm', 'super_admin'])
            ->when($this->pmSearch, fn($q) => $q->where('name', 'like', "%{$this->pmSearch}%"))
            ->orderBy('name')->take(20)->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => $u->name . ' (' . ucfirst($u->role) . ')'])
            ->toArray();

        return view('livewire.admin.jobdesk.revision.index', [
            'pmsList' => $pms
        ]);
    }
}
