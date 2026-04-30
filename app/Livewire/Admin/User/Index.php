<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\ToggleUserStatusAction;
use App\DTOs\User\UserData;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    // --- TAB & FILTERS ---
    #[Url(history: true)] public string $activeTab = 'all'; // Menggantikan filterRole
    #[Url(history: true)] public string $search = '';
    #[Url(history: true)] public string $filterStatus = '';
    #[Url(history: true)] public string $sortBy = 'latest';

    // --- UI STATES ---
    public bool $drawerOpen = false;
    public bool $toggleModalOpen = false;
    public ?int $editingUserId = null;
    public ?int $userToToggleId = null;

    // --- FORM DATA ---
    public string $full_name = '';
    public string $email = '';
    public ?int $role_id = null;
    public string $password = '';
    public $profile_photo;
    public ?string $existing_photo = null;

    public ?string $phone = null;
    public ?string $address = null;
    public ?string $nik = null;
    public ?string $nim = null;
    public ?string $department = null;
    public ?string $faculty = null;
    public ?string $university = null;

    protected function rules()
    {
        return [
            'full_name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'role_id' => 'required|exists:roles,id',
            'password' => $this->editingUserId ? 'nullable|min:6' : 'required|min:6',
            'profile_photo' => 'nullable|image|max:2048',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'nik' => 'nullable|string',
            'nim' => 'nullable|string',
            'department' => 'nullable|string',
            'faculty' => 'nullable|string',
            'university' => 'nullable|string',
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['activeTab', 'search', 'filterStatus', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'sortBy']);
        // Tab tidak direset agar pengguna tetap berada di tab yang sedang mereka lihat
        $this->resetPage();
    }

    public function create()
    {
        $this->reset([
            'full_name', 'email', 'role_id', 'password', 'editingUserId',
            'profile_photo', 'existing_photo', 'phone', 'address', 'nik',
            'nim', 'department', 'faculty', 'university'
        ]);

        // Auto-select role_id di form berdasarkan tab yang sedang aktif
        if ($this->activeTab !== 'all') {
            $role = Role::where('name', $this->activeTab)->first();
            if ($role) {
                $this->role_id = $role->id;
            }
        }

        $this->drawerOpen = true;
    }

    public function edit(User $user)
    {
        $user->load(['profile', 'attachments']);
        $this->editingUserId = $user->id;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->password = '';

        $p = $user->profile;
        $this->full_name = $p?->full_name ?? '';
        $this->phone = $p?->phone ?? '';
        $this->address = $p?->address ?? '';
        $this->nik = $p?->nik ?? '';
        $this->nim = $p?->nim ?? '';
        $this->department = $p?->department ?? '';
        $this->faculty = $p?->faculty ?? '';
        $this->university = $p?->university ?? '';

        $primaryPhoto = $user->attachments->where('is_primary', true)->first();
        $this->existing_photo = $primaryPhoto ? $primaryPhoto->file_url : null;
        $this->profile_photo = null;

        $this->drawerOpen = true;
    }

    public function save()
    {
        $this->validate();

        $dto = new UserData(
            full_name: $this->full_name,
            email: $this->email,
            role_id: (int) $this->role_id,
            password: $this->password,
            profile_photo: $this->profile_photo,
            phone: $this->phone,
            address: $this->address,
            nik: $this->nik,
            nim: $this->nim,
            department: $this->department,
            faculty: $this->faculty,
            university: $this->university
        );

        if ($this->editingUserId) {
            app(UpdateUserAction::class)->execute(User::find($this->editingUserId), $dto);
            $this->success(__('User updated successfully.'));
        } else {
            app(CreateUserAction::class)->execute($dto);
            $this->success(__('User created successfully.'));
        }

        $this->drawerOpen = false;
        $this->reset(['profile_photo']);
    }

    public function confirmToggle($id)
    {
        $this->userToToggleId = $id;
        $this->toggleModalOpen = true;
    }

    public function toggleStatus()
    {
        try {
            app(ToggleUserStatusAction::class)->execute(User::find($this->userToToggleId));
            $this->success(__('Status changed successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->toggleModalOpen = false;
    }

    public function render()
    {
        $roles = Role::all();

        $query = User::with(['profile', 'role', 'attachments' => function($q) {
            $q->where('is_primary', true);
        }]);

        // Filter by Active Tab (Role)
        if ($this->activeTab !== 'all') {
            $query->whereHas('role', function($q) {
                $q->where('name', $this->activeTab);
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', "%{$this->search}%")
                  ->orWhereHas('profile', fn($p) => $p->where('full_name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        return view('livewire.admin.user.index', [
            'users' => $query->paginate(10),
            'roles' => $roles
        ]);
    }
}
