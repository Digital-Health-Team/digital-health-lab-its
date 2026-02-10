<?php

namespace App\Livewire;

use Livewire\Component;
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
    use Toast;

    public $locale;
    public $name;
    public $email;

    // Array notifikasi (Wajib public array)
    public array $notifications = [
        'email' => false,
        'wa' => false
    ];

    public function mount()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;

        // --- PERBAIKAN DISINI ---
        // JANGAN ambil dari $user->locale, karena bisa jadi user sedang preview bahasa lain di session.
        // AMBIL dari app()->getLocale() agar dropdown selalu sinkron dengan teks halaman/navbar.
        $this->locale = app()->getLocale();

        // Ambil preferensi notifikasi
        $prefs = $user->preferences ?? [];
        $this->notifications = array_merge(
            ['email' => false, 'wa' => false],
            $prefs['notifications'] ?? []
        );
    }

    // --- 1. MAGIC METHOD: Trigger saat dropdown bahasa berubah ---
    public function updatedLocale($value)
    {
        // Validasi input agar aman
        if (!in_array($value, ['en', 'id'])) {
            return;
        }

        $user = auth()->user();

        // Simpan ke DB
        $user->locale = $value;
        $user->save();

        // Simpan ke Session & App
        Session::put('locale', $value);
        App::setLocale($value);

        $this->success(__('Language changed successfully'));

        // Refresh halaman seketika agar UI berubah bahasa
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    // --- 2. FUNCTION: Save Profile (Lengkap) ---
    public function saveProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|min:3',
            // Pastikan email unique kecuali punya user ini sendiri
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email
        ]);

        $this->success(__('Profile updated successfully'));
    }

    // --- 3. FUNCTION: Save Preferences (Notifikasi) ---
    public function savePreferences()
    {
        $user = auth()->user();

        // Simpan notifikasi ke kolom JSON 'preferences'
        $preferences = $user->preferences ?? [];
        $preferences['notifications'] = $this->notifications;

        // Kita tidak perlu simpan locale disini lagi karena sudah di-handle updatedLocale
        $user->preferences = $preferences;
        $user->save();

        $this->success(__('Preferences saved successfully'));
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
