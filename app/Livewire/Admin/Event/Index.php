<?php

namespace App\Livewire\Admin\Event;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Event;
use App\DTOs\Event\EventData;
use App\Actions\Event\CreateEventAction;
use App\Actions\Event\UpdateEventAction;
use App\Actions\Event\DeleteEventAction;
use App\Actions\Event\ToggleEventStatusAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    #[Url(history: true)] public string $search = '';
    #[Url(history: true)] public string $filterStatus = '';

    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;
    public bool $toggleModalOpen = false;

    public ?int $editingId = null;
    public ?int $targetId = null;

    public string $name = '';
    public ?int $year = null;
    public string $theme_title = '';

    public function updatedSearch() { $this->resetPage(); }

    public function create()
    {
        $this->reset(['name', 'year', 'theme_title', 'editingId']);
        $this->year = date('Y');
        $this->drawerOpen = true;
    }

    public function edit(Event $event)
    {
        $this->editingId = $event->id;
        $this->name = $event->name;
        $this->year = $event->year;
        $this->theme_title = $event->theme_title;
        $this->drawerOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000',
            'theme_title' => 'required|string|max:255',
        ]);

        $dto = new EventData($this->name, (int) $this->year, $this->theme_title);

        if ($this->editingId) {
            app(UpdateEventAction::class)->execute(Event::find($this->editingId), $dto);
            $this->success(__('Event updated.'));
        } else {
            app(CreateEventAction::class)->execute($dto);
            $this->success(__('Event created.'));
        }
        $this->drawerOpen = false;
    }

    public function confirmToggle($id)
    {
        $this->targetId = $id;
        $this->toggleModalOpen = true;
    }

    public function toggleStatus()
    {
        app(ToggleEventStatusAction::class)->execute(Event::find($this->targetId));
        $this->success(__('Status updated.'));
        $this->toggleModalOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->targetId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        try {
            app(DeleteEventAction::class)->execute(Event::find($this->targetId));
            $this->success(__('Event deleted.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = Event::withCount(['teams', 'teams as projects_count' => function ($query) {
            $query->has('projects');
        }]);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")->orWhere('theme_title', 'like', "%{$this->search}%");
        }
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        return view('livewire.admin.event.index', [
            'events' => $query->latest('year')->latest('id')->paginate(10)
        ]);
    }
}
