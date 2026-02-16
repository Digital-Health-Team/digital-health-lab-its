<?php

namespace App\Livewire;

use App\Models\User;
use App\DTOs\User\UserData;
use App\Actions\User\UpdateUserAction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Settings')]
class Settings extends Component
{
    use Toast, WithFileUploads;

    public $locale;
    public $name;
    public $email;
    public $role;
    public $profile_photo; // Mengganti $avatar
    public $existing_photo; // Mengganti $existing_avatar

    public array $notifications = [
        'email' => false,
        'wa' => false
    ];

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->existing_photo = $user->profile_photo; // Mengacu pada kolom profile_photo
        $this->locale = app()->getLocale();

        $prefs = $user->preferences ?? [];
        $this->notifications = array_merge(
            ['email' => false, 'wa' => false],
            $prefs['notifications'] ?? []
        );
    }

    public function saveProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $dto = new UserData(
            name: $this->name,
            email: $this->email,
            role: $this->role,
            password: null,
            profile_photo: $this->profile_photo
        );

        app(UpdateUserAction::class)->execute($user, $dto);

        // Sync data lokal setelah upload berhasil
        $this->existing_photo = $user->fresh()->profile_photo;
        $this->reset('profile_photo');

        $this->success(__('Profile updated successfully'));
    }
    public function updatedLocale($value)
    {
        if (!in_array($value, ['en', 'id']))
            return;

        $user = auth()->user();
        $user->locale = $value;
        $user->save();

        Session::put('locale', $value);
        App::setLocale($value);

        $this->success(__('Language changed successfully'));
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function savePreferences()
    {
        $user = auth()->user();
        $preferences = $user->preferences ?? [];
        $preferences['notifications'] = $this->notifications;

        $user->preferences = $preferences;
        $user->save();

        $this->success(__('Preferences saved successfully'));
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
