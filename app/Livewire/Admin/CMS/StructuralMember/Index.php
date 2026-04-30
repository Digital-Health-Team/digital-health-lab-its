<?php

namespace App\Livewire\Admin\CMS\StructuralMember;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\StructuralMember;
use App\Models\User;
use App\DTOs\CMS\StructuralMemberData;
use App\Actions\CMS\StructuralMember\CreateStructuralMemberAction;
use App\Actions\CMS\StructuralMember\UpdateStructuralMemberAction;
use App\Actions\CMS\StructuralMember\DeleteStructuralMemberAction;
use App\Actions\CMS\StructuralMember\ToggleStructuralMemberStatusAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    #[Url(history: true)] public string $search = '';

    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    // --- FORM DATA ---
    public ?int $user_id = null;
    public string $name = '';
    public string $position = '';
    public int $display_order = 1;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'display_order' => 'required|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    public function create()
    {
        $this->reset(['user_id', 'name', 'position', 'editingId']);
        // Auto set urutan terakhir
        $this->display_order = (StructuralMember::max('display_order') ?? 0) + 1;
        $this->drawerOpen = true;
    }

    public function edit(StructuralMember $member)
    {
        $this->editingId = $member->id;
        $this->user_id = $member->user_id;
        $this->name = $member->name;
        $this->position = $member->position;
        $this->display_order = $member->display_order;
        $this->drawerOpen = true;
    }

    public function updatedUserId($value)
    {
        // Jika memilih user dari sistem, otomatis isi field nama jika kosong
        if ($value && empty($this->name)) {
            $user = User::with('profile')->find($value);
            $this->name = $user->profile?->full_name ?? $user->name;
        }
    }

    public function save()
    {
        $this->validate();

        $dto = new StructuralMemberData(
            user_id: $this->user_id,
            name: $this->name,
            position: $this->position,
            display_order: $this->display_order
        );

        if ($this->editingId) {
            app(UpdateStructuralMemberAction::class)->execute(StructuralMember::find($this->editingId), $dto);
            $this->success(__('Member updated.'));
        } else {
            app(CreateStructuralMemberAction::class)->execute($dto);
            $this->success(__('Member added.'));
        }

        $this->drawerOpen = false;
    }

    public function toggleStatus(int $id)
    {
        app(ToggleStructuralMemberStatusAction::class)->execute(StructuralMember::find($id));
        $this->success(__('Status updated.'));
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        StructuralMember::find($this->deleteId)->delete();
        $this->success(__('Member removed.'));
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $members = StructuralMember::query()
            ->with('user.profile')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('position', 'like', "%{$this->search}%"))
            ->orderBy('display_order', 'asc')
            ->paginate(10);

        $availableUsers = User::with('profile')->get()->map(fn($u) => [
            'id' => $u->id,
            'name' => ($u->profile?->full_name ?? $u->name) . " ({$u->email})"
        ]);

        return view('livewire.admin.cms.structural-member.index', [
            'members' => $members,
            'availableUsers' => $availableUsers
        ]);
    }
}
