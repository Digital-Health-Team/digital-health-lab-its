<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\DeleteUserAction;
use Mary\Traits\Toast;
use App\DTOs\User\UserData;

class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    // --- FILTER PROPERTIES (Tersimpan di URL) ---
    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterRole = '';

    #[Url(history: true)]
    public string $filterDepartment = '';

    #[Url(history: true)]
    public string $sortBy = 'latest';

    // --- MODAL STATES ---
    public bool $modalOpen = false;
    public bool $deleteModalOpen = false;

    // (filterDrawerOpen sudah dihapus)

    public ?int $editingUserId = null;
    public ?int $userToDeleteId = null;

    // --- FORM DATA ---
    public string $name = '';
    public string $email = '';
    public string $role = 'staff';
    public array $departments = [];
    public string $password = '';
    public $profile_photo;
    public ?string $existing_photo = null;

    // --- MASTER DATA ---
    public array $departmentsList = [
        ['id' => 'IT', 'name' => 'IT'],
        ['id' => 'Social Media & Marketing', 'name' => 'Social Media & Marketing'],
        ['id' => 'Creative', 'name' => 'Creative'],
        ['id' => 'AEC', 'name' => 'AEC'],
    ];

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'role' => 'required',
            'departments' => 'nullable|array',
            'password' => $this->editingUserId ? 'nullable|min:6' : 'required|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ];
    }

    // --- FILTER ACTIONS ---
    public function clearFilters()
    {
        $this->reset(['search', 'filterRole', 'filterDepartment', 'sortBy']);
        $this->resetPage(); // Reset pagination ke halaman 1
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterRole', 'filterDepartment', 'sortBy'])) {
            $this->resetPage();
        }
    }

    // --- CRUD ACTIONS ---
    public function create()
    {
        $this->reset(['name', 'email', 'role', 'departments', 'password', 'editingUserId', 'profile_photo', 'existing_photo']);
        $this->departments = [];
        $this->modalOpen = true;
    }

    public function edit(User $user)
    {
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->departments = $user->departments ?? [];
        $this->password = '';
        $this->existing_photo = $user->profile_photo;
        $this->profile_photo = null;
        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $dto = new UserData(
            name: $this->name,
            email: $this->email,
            role: $this->role,
            password: $this->password,
            profile_photo: $this->profile_photo,
            departments: $this->departments
        );

        if ($this->editingUserId) {
            $user = User::find($this->editingUserId);
            app(UpdateUserAction::class)->execute($user, $dto);
            $this->success('User updated successfully.');
        } else {
            app(CreateUserAction::class)->execute($dto);
            $this->success('User created successfully.');
        }

        $this->modalOpen = false;
        $this->reset(['profile_photo']);
    }

    public function confirmDelete($id)
    {
        $this->userToDeleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->userToDeleteId) {
            $user = User::find($this->userToDeleteId);
            app(DeleteUserAction::class)->execute($user);
            $this->success('User deleted.');
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterDepartment) {
            $query->whereJsonContains('departments', $this->filterDepartment);
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest(),
        };

        $users = $query->paginate(10);

        return view('livewire.admin.user.index', [
            'users' => $users
        ]);
    }
}
