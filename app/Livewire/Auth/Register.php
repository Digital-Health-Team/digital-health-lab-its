<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\DTOs\Auth\RegisterData;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.guest')]
#[Title('Daftar')]
class Register extends Component
{
    use Toast, WithFileUploads;

    public int $currentStep = 1;

    public string $name = '';

    public string $email = '';

    public string $role_id = '';

    public string $password = '';

    public $profilePhoto = null;

    public ?string $nim = null;

    public ?string $nik = null;

    public ?string $university = null;

    public ?string $faculty = null;

    public ?string $department = null;

    public ?string $phone = null;

    public ?string $address = null;

    private function isMahasiswa(): bool
    {
        if (! $this->role_id) {
            return false;
        }

        return Role::find((int) $this->role_id)?->name === 'mahasiswa';
    }

    protected function rules(): array
    {
        $isMahasiswa = $this->isMahasiswa();

        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:6',
            'profilePhoto' => 'nullable|image|max:2048',
            'nim' => $isMahasiswa ? 'required|string|max:50' : 'nullable|string|max:50',
            'university' => $isMahasiswa ? 'required|string|max:255' : 'nullable|string|max:255',
            'faculty' => $isMahasiswa ? 'required|string|max:255' : 'nullable|string|max:255',
            'nik' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }

    public function nextStep(): void
    {
        $this->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:6',
            'profilePhoto' => 'nullable|image|max:2048',
        ]);

        $this->currentStep = 2;
    }

    public function prevStep(): void
    {
        $this->currentStep = 1;
    }

    public function register(RegisterUserAction $action)
    {
        $this->validate();

        $photoPath = null;
        if ($this->profilePhoto) {
            $photoPath = $this->profilePhoto->store('profile-photos', 'public');
        }

        $data = new RegisterData(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            role_id: (int) $this->role_id,
            nim: $this->nim,
            nik: $this->nik,
            university: $this->university,
            faculty: $this->faculty,
            department: $this->department,
            phone: $this->phone,
            address: $this->address,
            profile_photo: $photoPath,
        );

        $action->execute($data);

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        $roles = Role::whereIn('name', ['mahasiswa', 'user_publik'])->get();
        $isMahasiswaSelected = $this->isMahasiswa();

        return view('livewire.auth.register', compact('roles', 'isMahasiswaSelected'));
    }
}
