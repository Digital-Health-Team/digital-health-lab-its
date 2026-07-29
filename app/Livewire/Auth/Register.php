<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\DTOs\Auth\RegisterData;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.guest')]
class Register extends Component
{
    use Toast, WithFileUploads;

    public int $currentStep = 1;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public $profilePhoto = null;

    /** '' = not affiliated, 'academic', 'non_academic' */
    public string $affiliation = '';

    public ?string $university = null;

    public ?string $faculty = null;

    public ?string $department = null;

    public ?string $phone = null;

    public ?string $address = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'profilePhoto' => 'nullable|image|max:2048',
            'affiliation' => 'nullable|in:academic,non_academic',
            // required_with is implicit so it still fires past `nullable`; string|max stay skipped on null.
            'university' => 'nullable|required_with:affiliation|string|max:255',
            'faculty' => 'nullable|string|max:255', // holds Major
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }

    public function updatedAffiliation(): void
    {
        // Major only applies to academic institutions; drop anything typed before the switch.
        if ($this->affiliation !== 'academic') {
            $this->faculty = null;
        }
    }

    public function nextStep(): void
    {
        $this->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
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

        $roleId = Role::where('name', $this->affiliation === 'academic' ? 'mahasiswa' : 'user_publik')->value('id');

        $data = new RegisterData(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            role_id: $roleId,
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
        return view('livewire.auth.register')
            ->title(__('Register'));
    }
}
