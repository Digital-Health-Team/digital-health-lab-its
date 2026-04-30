<?php

namespace App\Livewire\Admin\Event\Team;

use Livewire\Component;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use App\Models\Role;
use App\DTOs\Event\TeamMemberData;
use App\DTOs\Event\ProjectData;
use App\Actions\Event\AddTeamMemberAction;
use App\Actions\Event\RemoveTeamMemberAction;
use App\Actions\Event\Project\CreateProjectAction;
use App\Actions\Event\Project\UpdateProjectAction;
use App\Actions\Event\Project\UpdateProjectStatusAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public Team $team;

    // Form Members
    public ?int $selectedUserId = null;
    public string $roleInTeam = '';

    // Form Project
    public bool $projectModalOpen = false;
    public ?int $editingProjectId = null;
    public string $projTitle = '';
    public string $projCategory = '';

    public function mount(Team $team)
    {
        $this->team = $team->load(['members.profile', 'event', 'projects']);
    }

    // --- MEMBERS LOGIC ---
    public function addMember()
    {
        $this->validate([
            'selectedUserId' => 'required',
            'roleInTeam' => 'required|string|max:255',
        ]);

        try {
            $dto = new TeamMemberData($this->team->id, $this->selectedUserId, $this->roleInTeam);
            app(AddTeamMemberAction::class)->execute(null, $dto);
            $this->success(__('Member added successfully.'));
            $this->reset(['selectedUserId', 'roleInTeam']);
            $this->team->load('members.profile');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function removeMember($userId)
    {
        try {
            app(RemoveTeamMemberAction::class)->execute($this->team, $userId);
            $this->success(__('Member removed.'));
            $this->team->load('members.profile');
        } catch (\Exception $e) {
            $this->error(__('Failed to remove member.'));
        }
    }

    // --- PROJECT LOGIC ---
    public function createProject()
    {
        $this->reset(['projTitle', 'projCategory', 'editingProjectId']);
        $this->projectModalOpen = true;
    }

    public function editProject(Project $project)
    {
        $this->editingProjectId = $project->id;
        $this->projTitle = $project->title;
        $this->projCategory = $project->category;
        $this->projectModalOpen = true;
    }

    public function saveProject()
    {
        $this->validate([
            'projTitle' => 'required|string|max:255',
            'projCategory' => 'required|string|max:255',
        ]);

        $dto = new ProjectData($this->team->id, $this->projTitle, $this->projCategory);

        if ($this->editingProjectId) {
            app(UpdateProjectAction::class)->execute(Project::find($this->editingProjectId), $dto);
            $this->success(__('Project updated.'));
        } else {
            app(CreateProjectAction::class)->execute($dto);
            $this->success(__('Project submitted.'));
        }

        $this->projectModalOpen = false;
        $this->team->load('projects');
    }

    public function updateProjectStatus(int $projectId, string $status)
    {
        app(UpdateProjectStatusAction::class)->execute(Project::find($projectId), $status);
        $this->success(__('Project status updated to :status', ['status' => strtoupper($status)]));
        $this->team->load('projects');
    }

    public function render()
    {
        $eligibleRoles = Role::whereIn('name', ['mahasiswa', 'user_publik'])->pluck('id');
        $availableUsers = User::with('profile')
            ->whereIn('role_id', $eligibleRoles)
            ->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => ($u->profile?->full_name ?? $u->email)]);

        return view('livewire.admin.event.team.index', [
            'availableUsers' => $availableUsers
        ]);
    }
}
