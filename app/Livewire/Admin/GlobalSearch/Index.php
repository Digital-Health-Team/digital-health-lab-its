<?php

namespace App\Livewire\Admin\GlobalSearch;

use App\Models\Inventory;
use App\Models\Project;
use App\Models\RawMaterial;
use App\Models\ServiceBooking;
use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Global Search')]
class Index extends Component
{
    #[Url(as: 'q', history: true)]
    public string $search = '';

    public function render(): \Illuminate\View\View
    {
        $term = trim($this->search);
        $results = [];
        $totalCount = 0;

        if (strlen($term) >= 2) {
            $like = "%{$term}%";

            $results['orders'] = ServiceBooking::query()
                ->where('brief_description', 'like', $like)
                ->with(['user', 'service'])
                ->limit(8)
                ->get();

            $results['materials'] = RawMaterial::query()
                ->where(function ($q) use ($like) {
                    $q->whereHas('brand', fn ($q) => $q->where('name', 'like', $like))
                        ->orWhereHas('color', fn ($q) => $q->where('name', 'like', $like))
                        ->orWhereHas('materialCategory', fn ($q) => $q->where('name', 'like', $like))
                        ->orWhereHas('lab', fn ($q) => $q->where('name', 'like', $like));
                })
                ->with(['brand', 'color', 'materialCategory', 'lab'])
                ->limit(8)
                ->get();

            $results['inventories'] = Inventory::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhereHas('lab', fn ($q) => $q->where('name', 'like', $like))
                        ->orWhereHas('brand', fn ($q) => $q->where('name', 'like', $like));
                })
                ->with(['lab', 'brand'])
                ->limit(8)
                ->get();

            $results['users'] = User::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                })
                ->with('role')
                ->limit(8)
                ->get();

            $results['projects'] = Project::query()
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('category', 'like', $like);
                })
                ->with(['team.event'])
                ->limit(8)
                ->get();

            $results['teams'] = Team::query()
                ->where('name', 'like', $like)
                ->with(['event'])
                ->limit(8)
                ->get();

            $totalCount = array_sum(array_map(fn ($c) => $c->count(), $results));
        }

        return view('livewire.admin.global-search.index', [
            'results' => $results,
            'totalCount' => $totalCount,
        ]);
    }
}
