<?php

namespace App\Livewire;

use Livewire\Component;

class GlobalSearchBar extends Component
{
    public string $query = '';

    public function search()
    {
        if (trim($this->query) === '')
            return;

        // Redirect ke halaman hasil pencarian dengan query parameter
        return $this->redirect(route('admin.global-search', ['q' => $this->query]), navigate: true);
    }

    public function render()
    {
        return view('livewire.global-search-bar');
    }
}
