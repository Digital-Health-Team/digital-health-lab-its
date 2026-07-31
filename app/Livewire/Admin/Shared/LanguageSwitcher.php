<?php

namespace App\Livewire\Admin\Shared;

use Livewire\Attributes\Computed;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    private const LOCALES = [
        'en' => ['label' => 'EN', 'native' => 'English'],
        'id' => ['label' => 'ID', 'native' => 'Indonesia'],
    ];

    #[Computed]
    public function current(): string
    {
        return app()->getLocale();
    }

    #[Computed]
    public function locales(): array
    {
        return self::LOCALES;
    }

    public function switchTo(string $locale): void
    {
        if (! array_key_exists($locale, self::LOCALES)) {
            return;
        }

        session()->put('locale', $locale);

        if (auth()->check() && array_key_exists('locale', auth()->user()->getAttributes())) {
            auth()->user()->update(['locale' => $locale]);
        }

        $this->redirect(request()->header('Referer') ?: route('admin.dashboard'));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.shared.language-switcher');
    }
}
