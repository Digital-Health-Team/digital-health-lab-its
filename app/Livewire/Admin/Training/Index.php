<?php

namespace App\Livewire\Admin\Training;

use App\Actions\Training\CreateTrainingAction;
use App\Actions\Training\DeleteTrainingAction;
use App\Actions\Training\ToggleTrainingStatusAction;
use App\Actions\Training\UpdateTrainingAction;
use App\DTOs\Training\TrainingData;
use App\Models\Training;
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

    #[Url(history: true)]
    public string $filterLevel = '';

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public bool $toggleModalOpen = false;

    public ?int $editingId = null;

    public ?int $targetId = null;

    // Basic Info
    public string $title = '';

    public string $subtitle = '';

    public string $description = '';

    public string $thumbnail_url = '';

    // Schedule
    public string $date = '';

    public string $location = '';

    public ?int $max_participants = null;

    // Details
    public int $price = 0;

    public bool $is_paid = false;

    public bool $is_active = true;

    public bool $is_featured = false;

    public string $level = 'Beginner';

    public string $duration = '';

    public string $language = 'Indonesian';

    // Instructor
    public string $instructor_name = '';

    public string $instructor_title = '';

    public string $instructor_bio = '';

    public string $instructor_avatar_url = '';

    // Dynamic list fields
    public array $what_you_will_learn = [''];

    public array $includes = [''];

    public array $curriculum_modules = [['module' => '', 'lessons' => ['']]];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset([
            'title', 'subtitle', 'description', 'thumbnail_url',
            'date', 'location', 'max_participants',
            'price', 'is_paid', 'is_featured', 'level', 'duration', 'language',
            'instructor_name', 'instructor_title', 'instructor_bio', 'instructor_avatar_url',
            'editingId',
        ]);
        $this->is_active = true;
        $this->level = 'Beginner';
        $this->language = 'Indonesian';
        $this->what_you_will_learn = [''];
        $this->includes = [''];
        $this->curriculum_modules = [['module' => '', 'lessons' => ['']]];
        $this->drawerOpen = true;
    }

    public function edit(int $id): void
    {
        $training = Training::findOrFail($id);
        $this->editingId = $training->id;
        $this->title = $training->title;
        $this->subtitle = $training->subtitle ?? '';
        $this->description = $training->description ?? '';
        $this->thumbnail_url = $training->thumbnail_url ?? '';
        $this->date = $training->date?->format('Y-m-d\TH:i') ?? '';
        $this->location = $training->location ?? '';
        $this->max_participants = $training->max_participants;
        $this->price = $training->price;
        $this->is_paid = $training->is_paid;
        $this->is_active = $training->is_active;
        $this->is_featured = $training->is_featured;
        $this->level = $training->level;
        $this->duration = $training->duration ?? '';
        $this->language = $training->language;
        $this->instructor_name = $training->instructor_name;
        $this->instructor_title = $training->instructor_title ?? '';
        $this->instructor_bio = $training->instructor_bio ?? '';
        $this->instructor_avatar_url = $training->instructor_avatar_url ?? '';

        $this->what_you_will_learn = ! empty($training->what_you_will_learn)
            ? $training->what_you_will_learn
            : [''];

        $this->includes = ! empty($training->includes)
            ? $training->includes
            : [''];

        $this->curriculum_modules = ! empty($training->curriculum)
            ? array_map(fn ($m) => [
                'module' => $m['module'] ?? '',
                'lessons' => ! empty($m['lessons']) ? $m['lessons'] : [''],
            ], $training->curriculum)
            : [['module' => '', 'lessons' => ['']]];

        $this->drawerOpen = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'date' => 'required|string',
            'instructor_name' => 'required|string|max:255',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'price' => 'required|integer|min:0',
            'what_you_will_learn.*' => 'nullable|string',
            'includes.*' => 'nullable|string',
            'curriculum_modules.*.module' => 'nullable|string',
            'curriculum_modules.*.lessons.*' => 'nullable|string',
        ]);

        $whatYouWillLearn = array_values(array_filter(
            $this->what_you_will_learn,
            fn ($v) => trim($v) !== ''
        ));

        $includes = array_values(array_filter(
            $this->includes,
            fn ($v) => trim($v) !== ''
        ));

        $curriculum = array_values(array_filter(
            array_map(fn ($m) => [
                'module' => $m['module'],
                'lessons' => array_values(array_filter($m['lessons'], fn ($v) => trim($v) !== '')),
            ], $this->curriculum_modules),
            fn ($m) => trim($m['module']) !== ''
        ));

        $dto = new TrainingData(
            title: $this->title,
            subtitle: $this->subtitle,
            description: $this->description,
            thumbnail_url: $this->thumbnail_url ?: null,
            price: $this->price,
            is_paid: $this->is_paid,
            is_active: $this->is_active,
            is_featured: $this->is_featured,
            date: $this->date,
            location: $this->location ?: null,
            max_participants: $this->max_participants ?: null,
            level: $this->level,
            duration: $this->duration,
            language: $this->language,
            instructor_name: $this->instructor_name,
            instructor_title: $this->instructor_title ?: null,
            instructor_bio: $this->instructor_bio ?: null,
            instructor_avatar_url: $this->instructor_avatar_url ?: null,
            what_you_will_learn: $whatYouWillLearn,
            includes: $includes,
            curriculum: $curriculum,
        );

        if ($this->editingId) {
            app(UpdateTrainingAction::class)->execute(Training::find($this->editingId), $dto);
            $this->success(__('Training updated.'));
        } else {
            app(CreateTrainingAction::class)->execute($dto);
            $this->success(__('Training created.'));
        }

        $this->drawerOpen = false;
    }

    // ── What You Will Learn ──────────────────────────────────────────

    public function addWhatYouWillLearnItem(): void
    {
        $this->what_you_will_learn[] = '';
    }

    public function removeWhatYouWillLearnItem(int $index): void
    {
        unset($this->what_you_will_learn[$index]);
        $this->what_you_will_learn = array_values($this->what_you_will_learn) ?: [''];
    }

    // ── Includes ─────────────────────────────────────────────────────

    public function addIncludesItem(): void
    {
        $this->includes[] = '';
    }

    public function removeIncludesItem(int $index): void
    {
        unset($this->includes[$index]);
        $this->includes = array_values($this->includes) ?: [''];
    }

    // ── Curriculum ───────────────────────────────────────────────────

    public function addModule(): void
    {
        $this->curriculum_modules[] = ['module' => '', 'lessons' => ['']];
    }

    public function removeModule(int $index): void
    {
        unset($this->curriculum_modules[$index]);
        $this->curriculum_modules = array_values($this->curriculum_modules)
            ?: [['module' => '', 'lessons' => ['']]];
    }

    public function addLesson(int $moduleIndex): void
    {
        $this->curriculum_modules[$moduleIndex]['lessons'][] = '';
    }

    public function removeLesson(int $moduleIndex, int $lessonIndex): void
    {
        unset($this->curriculum_modules[$moduleIndex]['lessons'][$lessonIndex]);
        $this->curriculum_modules[$moduleIndex]['lessons'] =
            array_values($this->curriculum_modules[$moduleIndex]['lessons']) ?: [''];
    }

    // ── Status / Delete ──────────────────────────────────────────────

    public function confirmToggle(int $id): void
    {
        $this->targetId = $id;
        $this->toggleModalOpen = true;
    }

    public function toggleStatus(): void
    {
        app(ToggleTrainingStatusAction::class)->execute(Training::find($this->targetId));
        $this->success(__('Status updated.'));
        $this->toggleModalOpen = false;
    }

    public function confirmDelete(int $id): void
    {
        $this->targetId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord(): void
    {
        try {
            app(DeleteTrainingAction::class)->execute(Training::find($this->targetId));
            $this->success(__('Training deleted.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = Training::withCount('registrations');

        if ($this->search) {
            $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$this->search}%")
                ->orWhere('instructor_name', 'like', "%{$this->search}%")
            );
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        if ($this->filterLevel !== '') {
            $query->where('level', $this->filterLevel);
        }

        return view('livewire.admin.training.index', [
            'trainings' => $query->orderBy('date')->paginate(10),
        ]);
    }
}
