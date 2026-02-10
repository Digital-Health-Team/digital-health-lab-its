<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher extends Component
{
    public function changeLocale($locale)
    {
        // Validasi input
        if (!in_array($locale, ['en', 'id'])) {
            return;
        }

        // 1. Simpan ke Session
        Session::put('locale', $locale);
        App::setLocale($locale);

        // 2. Simpan ke Database (Jika user login)
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        // 3. Refresh Halaman agar UI berubah
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
