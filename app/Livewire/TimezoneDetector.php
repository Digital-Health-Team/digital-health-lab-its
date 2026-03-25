<?php

namespace App\Livewire;

use App\Actions\User\UpdateUserTimezoneAction;
use Livewire\Component;
use Livewire\Attributes\On;

class TimezoneDetector extends Component
{
    // Menerima event dari Alpine.js
    #[On('timezone-detected')]
    public function setTimezone($timezone, UpdateUserTimezoneAction $action)
    {
        // Hindari pemrosesan berulang jika timezone sudah sama
        if (session('timezone') === $timezone) {
            return;
        }

        // Eksekusi Action
        $action->execute(auth()->user(), $timezone);
    }

    public function render()
    {
        // Menggunakan Alpine.js x-init untuk mendeteksi timezone browser
        return <<<'HTML'
        <div x-data="{
            init() {
                // Gunakan Intl API bawaan browser
                const browserTz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                const currentSessionTz = '{{ session('timezone') }}';

                // Jika belum diset, atau browser mendeteksi user pindah negara/zona waktu
                if (browserTz && browserTz !== currentSessionTz) {
                    $wire.dispatch('timezone-detected', { timezone: browserTz });
                }
            }
        }"></div>
        HTML;
    }
}
