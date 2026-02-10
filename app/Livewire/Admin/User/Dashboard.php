<?php

namespace App\Livewire\Admin\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    use WithPagination, Toast;

    // --- Form Properties ---
    public $name;
    public $email;
    public $password; // Optional saat edit
    public $role = 'user';

    // --- UI State ---
    public bool $drawer = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    public bool $deleteModal = false;
    public ?int $deleteId = null;

    // --- Filters ---
    public $search = '';
    public $filterRole = 'all';

    // Reset pagination saat search berubah
    public function updated($prop)
    {
        if (in_array($prop, ['search', 'filterRole'])) {
            $this->resetPage();
        }
    }

    public function clearForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'isEditing', 'editingId']);
        $this->role = 'user'; // Default
    }

    public function create()
    {
        $this->clearForm();
        $this->drawer = true;
    }

    public function edit($id)
    {
        $this->clearForm();
        $user = User::find($id);

        if ($user) {
            $this->editingId = $user->id;
            $this->isEditing = true;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            // Password tidak diisi saat edit demi keamanan
            $this->drawer = true;
        }
    }

    public function save(UserService $service)
    {
        // Validasi Dinamis
        $rules = [
            'name' => 'required|min:3',
            'role' => 'required|in:admin,user',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->editingId) // Ignore ID sendiri saat edit
            ],
        ];

        // Password required hanya saat create
        if (!$this->isEditing) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6'; // Boleh kosong saat edit
        }

        $this->validate($rules);

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'role' => $this->role,
            ];

            if ($this->isEditing) {
                $user = User::find($this->editingId);
                $service->update($user, $data);
                $this->success('User berhasil diperbarui.');
            } else {
                $service->create($data);
                $this->success('User baru berhasil dibuat.');
            }

            $this->drawer = false;
            $this->clearForm();

        } catch (\Exception $e) {
            $this->error('Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        if ($id === Auth::id()) {
            $this->error('Anda tidak dapat menghapus akun anda sendiri saat sedang login.');
            return;
        }

        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function delete(UserService $service)
    {
        if ($this->deleteId) {
            $user = User::find($this->deleteId);
            if ($user) {
                $service->delete($user);
                $this->success('User berhasil dihapus.');
            }
        }
        $this->deleteModal = false;
        $this->deleteId = null;
    }

    public function render(UserService $service)
    {
        $filters = [
            'search' => $this->search,
            'role' => $this->filterRole,
        ];

        $users = $service->getUsers($filters, 10);

        return view('livewire.admin.users.index', [
            'users' => $users
        ]);
    }
}
