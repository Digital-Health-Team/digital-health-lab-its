<?php

namespace App\Livewire\Admin\Announcement;

use App\Actions\Announcement\CreateAnnouncementAction;
use App\Actions\Announcement\UpdateAnnouncementAction;
use App\Actions\Announcement\DeleteAnnouncementAction;
use App\DTOs\Announcement\AnnouncementData;
use App\Models\Announcement;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Manage Announcements')]
class Index extends Component
{
    use WithPagination, Toast;

    // --- STATES ---
    public string $search = '';
    public bool $modalOpen = false;
    public bool $deleteModalOpen = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // --- FORM DATA ---
    public string $title = '';
    public string $content = '';
    public bool $is_published = false;
    public bool $is_global = false; // Checkbox "Send to All"
    public array $recipient_ids = [];

    // --- RULES ---
    protected function rules()
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:10',
            'is_published' => 'boolean',
            'is_global' => 'boolean',
            // Jika is_global FALSE, maka recipient_ids WAJIB diisi minimal 1
            'recipient_ids' => 'exclude_if:is_global,true|required|array|min:1',
            'recipient_ids.*' => 'exists:users,id',
        ];
    }

    // --- ACTIONS ---

    public function create()
    {
        $this->reset(['title', 'content', 'is_published', 'is_global', 'recipient_ids', 'editingId']);
        $this->is_published = true;
        $this->modalOpen = true;
    }

    public function edit(Announcement $announcement)
    {
        $this->editingId = $announcement->id;
        $this->title = $announcement->title;
        $this->content = $announcement->content;
        $this->is_published = $announcement->is_published;
        $this->is_global = $announcement->is_global;

        // Load recipients hanya jika bukan global
        $this->recipient_ids = $announcement->is_global
            ? []
            : $announcement->recipients->pluck('id')->toArray();

        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $dto = new AnnouncementData(
            title: $this->title,
            content: $this->content,
            is_published: $this->is_published,
            is_global: $this->is_global,
            recipient_ids: $this->is_global ? [] : $this->recipient_ids, // Kosongkan jika global
            created_by: auth()->id()
        );

        if ($this->editingId) {
            $announcement = Announcement::findOrFail($this->editingId);
            app(UpdateAnnouncementAction::class)->execute($announcement, $dto);
            $this->success('Announcement updated.');
        } else {
            app(CreateAnnouncementAction::class)->execute($dto);
            $this->success('Announcement created.');
        }

        $this->modalOpen = false;
        $this->reset(['title', 'content', 'recipient_ids', 'is_global']);
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->deletingId) {
            $announcement = Announcement::findOrFail($this->deletingId);
            app(DeleteAnnouncementAction::class)->execute($announcement);
            $this->success('Announcement deleted.');
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $announcements = Announcement::query()
            ->with(['creator', 'recipients'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        $usersList = User::where('role', '!=', 'super_admin')->orderBy('name')->get();

        return view('livewire.admin.announcement.index', [
            'announcements' => $announcements,
            'usersList' => $usersList
        ]);
    }
}
