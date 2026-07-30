<?php

namespace App\Livewire\Admin\Event;

use App\Actions\Event\CreateEventAction;
use App\Actions\Event\DeleteEventAction;
use App\Actions\Event\ToggleEventStatusAction;
use App\Actions\Event\UpdateEventAction;
use App\DTOs\Event\EventData;
use App\Models\Event;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterStatus = '';

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public bool $toggleModalOpen = false;

    public ?int $editingId = null;

    public ?int $targetId = null;

    public string $name = '';

    public ?int $year = null;

    public string $theme_title = '';

    public string $subtitle = '';

    public string $description = '';

    public string $thumbnail_url = '';

    public string $starts_at = '';

    public string $ends_at = '';

    public string $location = '';

    public ?string $theme_title_en = null;

    public ?string $subtitle_en = null;

    public ?string $description_en = null;

    public ?string $location_en = null;

    public string $category = '';

    public string $registration_url = '';

    public bool $is_featured = false;

    /** Fields cleared between drawer sessions. */
    private const FORM_FIELDS = [
        'name', 'year', 'theme_title', 'subtitle', 'description', 'thumbnail_url',
        'starts_at', 'ends_at', 'location', 'category', 'registration_url',
        'is_featured', 'editingId',
        'theme_title_en', 'subtitle_en', 'description_en', 'location_en',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(self::FORM_FIELDS);
        $this->year = date('Y');
        $this->drawerOpen = true;
    }

    public function edit(Event $event)
    {
        $this->editingId = $event->id;
        $this->name = $event->name;
        $this->year = $event->year;
        // Raw columns on purpose — see the note in Admin\Product\Index::edit().
        $this->theme_title = $event->theme_title;
        $this->theme_title_en = $event->theme_title_en;
        $this->subtitle_en = $event->subtitle_en;
        $this->description_en = $event->description_en;
        $this->location_en = $event->location_en;
        $this->subtitle = $event->subtitle ?? '';
        $this->description = $event->description ?? '';
        $this->thumbnail_url = $event->thumbnail_url ?? '';
        $this->starts_at = $event->starts_at?->format('Y-m-d\TH:i') ?? '';
        $this->ends_at = $event->ends_at?->format('Y-m-d\TH:i') ?? '';
        $this->location = $event->location ?? '';
        $this->category = $event->category ?? '';
        $this->registration_url = $event->registration_url ?? '';
        $this->is_featured = $event->is_featured;
        $this->drawerOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000',
            'theme_title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|in:'.implode(',', Event::CATEGORIES),
            'registration_url' => 'nullable|url|max:255',
            'theme_title_en' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'location_en' => 'nullable|string|max:255',
        ]);

        $dto = new EventData(
            name: $this->name,
            year: (int) $this->year,
            theme_title: $this->theme_title,
            subtitle: $this->subtitle ?: null,
            description: $this->description ?: null,
            thumbnail_url: $this->thumbnail_url ?: null,
            starts_at: $this->starts_at ?: null,
            ends_at: $this->ends_at ?: null,
            location: $this->location ?: null,
            category: $this->category ?: null,
            registration_url: $this->registration_url ?: null,
            is_featured: $this->is_featured,
            theme_title_en: $this->theme_title_en ?: null,
            subtitle_en: $this->subtitle_en ?: null,
            description_en: $this->description_en ?: null,
            location_en: $this->location_en ?: null,
        );

        if ($this->editingId) {
            app(UpdateEventAction::class)->execute(Event::find($this->editingId), $dto);
            $this->success(__('Event updated.'));
        } else {
            app(CreateEventAction::class)->execute($dto);
            $this->success(__('Event created.'));
        }
        $this->drawerOpen = false;
    }

    public function confirmToggle($id)
    {
        $this->targetId = $id;
        $this->toggleModalOpen = true;
    }

    public function toggleStatus()
    {
        app(ToggleEventStatusAction::class)->execute(Event::find($this->targetId));
        $this->success(__('Status updated.'));
        $this->toggleModalOpen = false;
    }

    public function confirmDelete($id)
    {
        $this->targetId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        try {
            app(DeleteEventAction::class)->execute(Event::find($this->targetId));
            $this->success(__('Event deleted.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = Event::withCount(['teams', 'teams as projects_count' => function ($query) {
            $query->has('projects');
        }]);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")->orWhere('theme_title', 'like', "%{$this->search}%");
        }
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        return view('livewire.admin.event.index', [
            'events' => $query->latest('year')->latest('id')->paginate(10),
        ]);
    }
}
