<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public function changeLocale($locale)
    {
        // Validasi input
        if (! in_array($locale, ['en', 'id'])) {
            return;
        }

        // 1. Simpan ke Session
        Session::put('locale', $locale);
        App::setLocale($locale);

        // 2. Simpan ke Database (Jika user login)
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        // 3. Refresh Halaman agar UI berubah.
        // Referer is absent on some direct hits (and on the auth screens behind a redirect),
        // so fall back to home rather than redirecting to null.
        return $this->redirect(request()->header('Referer') ?: route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
