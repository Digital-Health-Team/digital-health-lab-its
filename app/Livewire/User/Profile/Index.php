<?php

namespace App\Livewire\User\Profile;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\User\UserService;
use App\Http\Requests\User\UpdateProfileRequest;
use Mary\Traits\Toast;

#[Layout('layouts.app.layout')]
class Index extends Component
{
    use Toast;

    // Properties
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function save(UserService $service)
    {
        // 1. Validasi
        $request = new UpdateProfileRequest();
        $this->validate($request->rules(), $request->messages());

        try {
            // 2. Siapkan Data
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password, // Bisa null/kosong
            ];

            // 3. Update via Service
            $service->updateProfile(Auth::user(), $data);

            // 4. Feedback & Reset Password Fields
            $this->success('Profil berhasil diperbarui.');
            $this->reset(['password', 'password_confirmation']);

        } catch (\Exception $e) {
            $this->error('Gagal mengupdate profil: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.profile.index');
    }
}
