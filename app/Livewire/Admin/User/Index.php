<?php

namespace App\Livewire\Admin\User;

use App\Actions\User\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\DTOs\User\UserData;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
#[Title('User Management')]
class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public bool $modalOpen = false;

    public ?int $editingUserId = null;

    #[Validate]
    public string $name = '';

    #[Validate]
    public string $email = '';

    #[Validate]
    public string $role = 'staff';

    public string $password = '';

    // 1. Tambahkan state untuk Modal Delete
    public bool $deleteModalOpen = false;
    public ?int $userToDeleteId = null; // Menyimpan ID sementara

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->editingUserId)],
            'role' => 'required|in:super_admin,pm,staff',
            'password' => $this->editingUserId ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    // --- FUNCTION HEADERS DIHAPUS ---

    public function create()
    {
        $this->reset(['editingUserId', 'name', 'email', 'role', 'password']);
        $this->modalOpen = true;
    }

    public function edit(User $user)
    {
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $userData = new UserData(
            name: $this->name,
            email: $this->email,
            role: $this->role,
            password: $this->password === '' ? null : $this->password
        );

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            app(UpdateUserAction::class)->execute($user, $userData);
            $message = __('User updated successfully');
        } else {
            app(CreateUserAction::class)->execute($userData);
            $message = __('User created successfully');
        }

        $this->success($message);
        $this->modalOpen = false;
    }

    // 2. Method saat tombol Sampah diklik (Hanya membuka modal)
    public function confirmDelete($userId)
    {
        // Proteksi: Jangan hapus diri sendiri
        if ($userId === auth()->id()) {
            $this->error('Anda tidak bisa menghapus akun sendiri.');
            return;
        }

        $this->userToDeleteId = $userId;
        $this->deleteModalOpen = true;
    }

    // 3. Method Eksekusi Hapus (Dipanggil dari dalam Modal)
    public function delete(DeleteUserAction $action)
    {
        if ($this->userToDeleteId) {
            $user = User::find($this->userToDeleteId);

            if ($user) {
                try {
                    $action->execute($user);
                    $this->success(__('User deleted successfully'));
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
        }

        // Reset & Tutup Modal
        $this->deleteModalOpen = false;
        $this->userToDeleteId = null;
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user.index', [
            'users' => $users
        ]);
    }
}
