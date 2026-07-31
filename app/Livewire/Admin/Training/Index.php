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

    // ── English overlay ───────────────────────────────────────
    public string $title_en = '';

    public string $subtitle_en = '';

    public string $description_en = '';

    public string $location_en = '';

    public string $instructor_title_en = '';

    public string $instructor_bio_en = '';

    public array $what_you_will_learn_en = [''];

    public array $includes_en = [''];

    public array $curriculum_modules_en = [['module' => '', 'lessons' => ['']]];

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
            'title_en', 'subtitle_en', 'description_en', 'location_en',
            'instructor_title_en', 'instructor_bio_en',
        ]);
        $this->is_active = true;
        $this->level = 'Beginner';
        $this->language = 'Indonesian';
        $this->what_you_will_learn = [''];
        $this->includes = [''];
        $this->curriculum_modules = [['module' => '', 'lessons' => ['']]];
        $this->what_you_will_learn_en = [''];
        $this->includes_en = [''];
        $this->curriculum_modules_en = [['module' => '', 'lessons' => ['']]];
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

        // Raw columns on purpose — see the note in Admin\Product\Index::edit().
        $this->title_en = $training->title_en ?? '';
        $this->subtitle_en = $training->subtitle_en ?? '';
        $this->description_en = $training->description_en ?? '';
        $this->location_en = $training->location_en ?? '';
        $this->instructor_title_en = $training->instructor_title_en ?? '';
        $this->instructor_bio_en = $training->instructor_bio_en ?? '';
        $this->what_you_will_learn_en = $training->what_you_will_learn_en ?: [''];
        $this->includes_en = $training->includes_en ?: [''];
        $this->curriculum_modules_en = ! empty($training->curriculum_en)
            ? array_map(fn ($m) => [
                'module' => $m['module'] ?? '',
                'lessons' => ! empty($m['lessons']) ? $m['lessons'] : [''],
            ], $training->curriculum_en)
            : [['module' => '', 'lessons' => ['']]];

        $this->drawerOpen = true;
    }

    /** Drop blank rows from a repeater list. */
    private static function trimRows(array $rows): array
    {
        return array_values(array_filter($rows, fn ($v) => trim((string) $v) !== ''));
    }

    /** Drop blank lessons, then modules with no title. */
    private static function trimModules(array $modules): array
    {
        return array_values(array_filter(
            array_map(fn ($m) => [
                'module' => $m['module'],
                'lessons' => self::trimRows($m['lessons']),
            ], $modules),
            fn ($m) => trim($m['module']) !== ''
        ));
    }

    // ── English overlay repeaters ─────────────────────────────
    // Whitelisted — the field name arrives from the browser.

    private const EN_LISTS = ['what_you_will_learn_en', 'includes_en'];

    public function addEnItem(string $field): void
    {
        abort_unless(in_array($field, self::EN_LISTS, true), 400);

        $this->{$field}[] = '';
    }

    public function removeEnItem(string $field, int $index): void
    {
        abort_unless(in_array($field, self::EN_LISTS, true), 400);

        unset($this->{$field}[$index]);
        $this->{$field} = array_values($this->{$field}) ?: [''];
    }

    public function addEnModule(): void
    {
        $this->curriculum_modules_en[] = ['module' => '', 'lessons' => ['']];
    }

    public function removeEnModule(int $index): void
    {
        unset($this->curriculum_modules_en[$index]);
        $this->curriculum_modules_en = array_values($this->curriculum_modules_en)
            ?: [['module' => '', 'lessons' => ['']]];
    }

    public function addEnLesson(int $moduleIndex): void
    {
        $this->curriculum_modules_en[$moduleIndex]['lessons'][] = '';
    }

    public function removeEnLesson(int $moduleIndex, int $lessonIndex): void
    {
        unset($this->curriculum_modules_en[$moduleIndex]['lessons'][$lessonIndex]);
        $this->curriculum_modules_en[$moduleIndex]['lessons'] =
            array_values($this->curriculum_modules_en[$moduleIndex]['lessons']) ?: [''];
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
            'title_en' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'location_en' => 'nullable|string|max:255',
            'instructor_title_en' => 'nullable|string|max:255',
            'instructor_bio_en' => 'nullable|string',
            'what_you_will_learn_en.*' => 'nullable|string',
            'includes_en.*' => 'nullable|string',
            'curriculum_modules_en.*.module' => 'nullable|string',
            'curriculum_modules_en.*.lessons.*' => 'nullable|string',
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
            title_en: $this->title_en ?: null,
            subtitle_en: $this->subtitle_en ?: null,
            description_en: $this->description_en ?: null,
            location_en: $this->location_en ?: null,
            instructor_title_en: $this->instructor_title_en ?: null,
            instructor_bio_en: $this->instructor_bio_en ?: null,
            what_you_will_learn_en: self::trimRows($this->what_you_will_learn_en),
            includes_en: self::trimRows($this->includes_en),
            curriculum_en: self::trimModules($this->curriculum_modules_en),
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
