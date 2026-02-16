<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Actions\User\CreateUserAction; // Sesuaikan namespace action Anda
use App\Actions\User\UpdateUserAction;
use App\Actions\User\DeleteUserAction;
use Mary\Traits\Toast;
use App\DTOs\User\UserData;

class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    public string $search = '';
    public bool $modalOpen = false;
    public bool $deleteModalOpen = false;
    public ?int $editingUserId = null;
    public ?int $userToDeleteId = null;

    // Form data
    public string $name = '';
    public string $email = '';
    public string $role = 'staff';
    public string $password = '';
    public $profile_photo;
    public ?string $existing_photo = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'role' => 'required',
            'password' => $this->editingUserId ? 'nullable|min:6' : 'required|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ];
    }

    public function create()
    {
        $this->reset(['name', 'email', 'role', 'password', 'editingUserId', 'profile_photo', 'existing_photo']);
        $this->modalOpen = true;
    }


    public function edit(User $user)
    {
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';

        // PENTING: Jangan isi $this->profile_photo dengan string path!
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
            profile_photo: $this->profile_photo // Akan berisi null jika admin tidak upload file baru
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
        $this->reset(['profile_photo']); // Reset agar tidak mengganggu request selanjutnya
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

            // Menggunakan DeleteUserAction
            app(DeleteUserAction::class)->execute($user);

            $this->success('User deleted.');
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user.index', [
            'users' => $users
        ]);
    }
}
