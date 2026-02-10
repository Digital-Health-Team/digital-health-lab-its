<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\DTOs\Auth\RegisterData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.guest')]
#[Title('Register Page')]
class Register extends Component
{
    use Toast;

    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:6|confirmed')]
    public string $password = '';

    #[Validate('required')]
    public string $password_confirmation = '';

    public function register(RegisterUserAction $action)
    {
        $this->validate();

        // Bungkus data ke DTO
        $data = new RegisterData(
            name: $this->name,
            email: $this->email,
            password: $this->password
        );

        // Eksekusi Action
        $action->execute($data);

        session()->flash('success', 'Account created successfully! Please verify your email.');

        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
