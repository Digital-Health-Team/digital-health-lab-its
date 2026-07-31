<?php

namespace App\Livewire\Admin\GlobalSearch;

use App\Models\Event;
use App\Models\Inventory;
use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Project;
use App\Models\Publication;
use App\Models\RawMaterial;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\Team;
use App\Models\Training;
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
                ->where(function ($q) use ($like, $term) {
                    $q->where('brief_description', 'like', $like)
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like));

                    if (preg_match('/^INV-?(\d+)$/i', $term, $m)) {
                        $q->orWhere('id', (int) $m[1]);
                    }
                })
                ->with(['user.profile', 'service'])
                ->limit(8)
                ->get();

            $results['materials'] = RawMaterial::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhereHas('brand', fn ($q) => $q->where('name', 'like', $like));
                })
                ->with(['brand'])
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

            $results['open_source_projects'] = OpenSourceProject::query()
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('category', 'like', $like)
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', $like));
                })
                ->with(['user'])
                ->limit(8)
                ->get();

            $results['publications'] = Publication::query()
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('author', 'like', $like)
                        ->orWhere('abstract', 'like', $like)
                        ->orWhere('journal', 'like', $like);
                })
                ->limit(8)
                ->get();

            $results['products'] = Product::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->limit(8)
                ->get();

            $results['services'] = Service::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->limit(8)
                ->get();

            $results['trainings'] = Training::query()
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('instructor_name', 'like', $like)
                        ->orWhere('subtitle', 'like', $like);
                })
                ->limit(8)
                ->get();

            $results['events'] = Event::query()
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('theme_title', 'like', $like);
                })
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
