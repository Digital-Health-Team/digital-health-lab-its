<?php

namespace App\Livewire\Admin\Event\Show;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Event;
use App\Models\Team;
use App\Models\Project;
use App\DTOs\Event\TeamData;
use App\Actions\Event\CreateTeamAction;
use App\Actions\Event\UpdateTeamAction;
use App\Actions\Event\DeleteTeamAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public Event $event;
    #[Url(history: true)] public string $activeTab = 'teams';

    // UI States for Team
    public bool $teamModalOpen = false;
    public bool $deleteTeamModalOpen = false;
    public ?int $editingTeamId = null;
    public ?int $targetTeamId = null;

    // Form Team
    public string $teamName = '';
    public string $teamCourseName = '';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function createTeam()
    {
        $this->reset(['teamName', 'teamCourseName', 'editingTeamId']);
        $this->teamModalOpen = true;
    }

    public function editTeam(Team $team)
    {
        $this->editingTeamId = $team->id;
        $this->teamName = $team->name;
        $this->teamCourseName = $team->course_name;
        $this->teamModalOpen = true;
    }

    public function saveTeam()
    {
        $this->validate([
            'teamName' => 'required|string',
            'teamCourseName' => 'required|string',
        ]);

        $dto = new TeamData($this->event->id, $this->teamName, $this->teamCourseName);

        if ($this->editingTeamId) {
            app(UpdateTeamAction::class)->execute(Team::find($this->editingTeamId), $dto);
            $this->success(__('Team updated.'));
        } else {
            app(CreateTeamAction::class)->execute($dto);
            $this->success(__('Team created.'));
        }
        $this->teamModalOpen = false;
    }

    public function confirmDeleteTeam($id)
    {
        $this->targetTeamId = $id;
        $this->deleteTeamModalOpen = true;
    }

    public function deleteTeam()
    {
        try {
            app(DeleteTeamAction::class)->execute(Team::find($this->targetTeamId));
            $this->success(__('Team deleted.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteTeamModalOpen = false;
    }

    public function render()
    {
        // Load data berdasarkan tab aktif
        $this->event->load(['teams.members']);

        // Ambil semua project yang berada di event ini (melalui relasi teams)
        $projects = Project::whereIn('team_id', $this->event->teams->pluck('id'))
            ->with(['team', 'validator'])
            ->latest()
            ->get();

        return view('livewire.admin.event.show.index', [
            'projects' => $projects
        ]);
    }
}
