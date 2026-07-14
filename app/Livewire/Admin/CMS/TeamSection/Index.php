<?php

namespace App\Livewire\Admin\CMS\TeamSection;

use App\Actions\CMS\LabTeam\CreateLabTeamPersonAction;
use App\Actions\CMS\LabTeam\CreateLabTeamSectionAction;
use App\Actions\CMS\LabTeam\DeleteLabTeamPersonAction;
use App\Actions\CMS\LabTeam\UpdateLabTeamPersonAction;
use App\Actions\CMS\LabTeam\UpdateLabTeamSectionAction;
use App\Actions\CMS\LabTeam\UpsertLabTeamLeaderAction;
use App\DTOs\CMS\LabTeamPersonData;
use App\DTOs\CMS\LabTeamSectionData;
use App\Models\Attachment;
use App\Models\LabTeamPerson;
use App\Models\LabTeamSection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithFileUploads;

    // ── Section CRUD ──────────────────────────────────────
    public bool $sectionDrawerOpen = false;

    public ?int $editingSectionId = null;

    public string $labelId = '';

    public string $labelEn = '';

    public int $sortOrder = 1;

    public bool $isActive = true;

    // ── Leader CRUD ───────────────────────────────────────
    public bool $leaderDrawerOpen = false;

    public ?int $leaderSectionId = null;

    public string $leaderNameFull = '';

    public string $leaderLine1 = '';

    public string $leaderLine2 = '';

    public string $leaderRoleId = '';

    public string $leaderRoleEn = '';

    public string $leaderBio = '';

    public string $leaderEmail = '';

    public string $leaderLinkedin = '';

    public string $leaderInstagram = '';

    public string $leaderExpertise = '';

    public string $leaderProjects = '';

    public string $leaderEducation = '';

    public string $leaderInitials = '';

    public $leaderPhoto = null;

    // ── Member CRUD ───────────────────────────────────────
    public bool $memberDrawerOpen = false;

    public ?int $memberSectionId = null;

    public ?int $editingMemberId = null;

    public string $memberNameFull = '';

    public string $memberLine1 = '';

    public string $memberLine2 = '';

    public string $memberRoleId = '';

    public string $memberBio = '';

    public string $memberEmail = '';

    public string $memberLinkedin = '';

    public string $memberInstagram = '';

    public string $memberExpertise = '';

    public string $memberProjects = '';

    public string $memberEducation = '';

    public string $memberInitials = '';

    public $memberPhoto = null;

    public int $memberOrder = 1;

    // ── Gallery ───────────────────────────────────────────
    public bool $galleryDrawerOpen = false;

    public ?int $gallerySectionId = null;

    public array $galleryPhotos = [];

    // ── Deletes ───────────────────────────────────────────
    public bool $deletePersonModalOpen = false;

    public ?int $deletePersonId = null;

    public bool $deletePhotoModalOpen = false;

    public ?int $deletePhotoId = null;

    // ─────────────────────────────────────────────────────
    // Section actions
    // ─────────────────────────────────────────────────────

    public function createSection(): void
    {
        $this->reset(['editingSectionId', 'labelId', 'labelEn', 'isActive']);
        $this->sortOrder = (LabTeamSection::max('sort_order') ?? 0) + 1;
        $this->sectionDrawerOpen = true;
    }

    public function editSection(LabTeamSection $section): void
    {
        $this->editingSectionId = $section->id;
        $this->labelId = $section->label_id;
        $this->labelEn = $section->label_en;
        $this->sortOrder = $section->sort_order;
        $this->isActive = $section->is_active;
        $this->sectionDrawerOpen = true;
    }

    public function saveSection(): void
    {
        $this->validate([
            'labelId' => 'required|string|max:255',
            'labelEn' => 'required|string|max:255',
            'sortOrder' => 'required|integer|min:1',
        ]);

        $dto = new LabTeamSectionData(
            label_id: $this->labelId,
            label_en: $this->labelEn,
            sort_order: $this->sortOrder,
            is_active: $this->isActive,
        );

        if ($this->editingSectionId) {
            app(UpdateLabTeamSectionAction::class)->execute(
                LabTeamSection::findOrFail($this->editingSectionId), $dto
            );
            $this->success(__('Section updated.'));
        } else {
            app(CreateLabTeamSectionAction::class)->execute($dto);
            $this->success(__('Section created.'));
        }

        $this->sectionDrawerOpen = false;
    }

    // ─────────────────────────────────────────────────────
    // Leader actions
    // ─────────────────────────────────────────────────────

    public function editLeader(LabTeamSection $section): void
    {
        $this->leaderSectionId = $section->id;
        $leader = $section->leader;

        $this->leaderNameFull = $leader?->name_full ?? '';
        $this->leaderLine1 = $leader?->display_line_1 ?? '';
        $this->leaderLine2 = $leader?->display_line_2 ?? '';
        $this->leaderRoleId = $leader?->role_id ?? '';
        $this->leaderRoleEn = $leader?->role_en ?? '';
        $this->leaderBio = $leader?->bio ?? '';
        $this->leaderEmail = $leader?->email ?? '';
        $this->leaderLinkedin = $leader?->linkedin_url ?? '';
        $this->leaderInstagram = $leader?->instagram_url ?? '';
        $this->leaderExpertise = $this->expertiseToString($leader?->expertise);
        $this->leaderProjects = $this->projectsToString($leader?->completed_projects);
        $this->leaderEducation = $this->arrayToLines($leader?->education);
        $this->leaderInitials = $leader?->initials ?? '';
        $this->leaderPhoto = null;

        $this->leaderDrawerOpen = true;
    }

    public function saveLeader(): void
    {
        $this->validate([
            'leaderNameFull' => 'required|string|max:255',
            'leaderLine1' => 'required|string|max:255',
            'leaderLine2' => 'required|string|max:255',
            'leaderRoleId' => 'required|string|max:255',
            'leaderRoleEn' => 'required|string|max:255',
            'leaderBio' => 'nullable|string',
            'leaderEmail' => 'nullable|email|max:255',
            'leaderLinkedin' => 'nullable|url|max:255',
            'leaderInstagram' => 'nullable|url|max:255',
            'leaderExpertise' => 'nullable|string',
            'leaderProjects' => 'nullable|string',
            'leaderEducation' => 'nullable|string',
            'leaderInitials' => 'required|string|max:4',
            'leaderPhoto' => 'nullable|image|max:2048',
        ]);

        $photoUrl = null;
        if ($this->leaderPhoto) {
            $photoUrl = $this->leaderPhoto->storeAs(
                'team/photos',
                uniqid('ldr_').'.'.$this->leaderPhoto->extension(),
                'public'
            );
        }

        $section = LabTeamSection::findOrFail($this->leaderSectionId);

        $dto = new LabTeamPersonData(
            section_id: $section->id,
            is_leader: true,
            name_full: $this->leaderNameFull,
            display_line_1: $this->leaderLine1,
            display_line_2: $this->leaderLine2,
            role_id: $this->leaderRoleId,
            role_en: $this->leaderRoleEn,
            bio: $this->leaderBio ?: null,
            email: $this->leaderEmail ?: null,
            linkedin_url: $this->leaderLinkedin ?: null,
            instagram_url: $this->leaderInstagram ?: null,
            expertise: $this->expertiseToArray($this->leaderExpertise),
            completed_projects: $this->projectsToArray($this->leaderProjects),
            education: $this->linesToArray($this->leaderEducation),
            initials: $this->leaderInitials,
            photo_url: $photoUrl,
            sort_order: 0,
            is_active: true,
        );

        app(UpsertLabTeamLeaderAction::class)->execute($section, $dto);
        $this->success(__('Leader saved.'));
        $this->leaderDrawerOpen = false;
    }

    // ─────────────────────────────────────────────────────
    // Member actions
    // ─────────────────────────────────────────────────────

    public function createMember(int $sectionId): void
    {
        $this->reset(['editingMemberId', 'memberNameFull', 'memberLine1', 'memberLine2',
            'memberRoleId', 'memberBio', 'memberEmail', 'memberLinkedin', 'memberInstagram',
            'memberExpertise', 'memberProjects', 'memberEducation', 'memberInitials', 'memberPhoto']);
        $this->memberSectionId = $sectionId;
        $this->memberOrder = (LabTeamPerson::where('section_id', $sectionId)
            ->where('is_leader', false)->max('sort_order') ?? 0) + 1;
        $this->memberDrawerOpen = true;
    }

    public function editMember(LabTeamPerson $person): void
    {
        $this->editingMemberId = $person->id;
        $this->memberSectionId = $person->section_id;
        $this->memberNameFull = $person->name_full;
        $this->memberLine1 = $person->display_line_1;
        $this->memberLine2 = $person->display_line_2;
        $this->memberRoleId = $person->role_id;
        $this->memberBio = $person->bio ?? '';
        $this->memberEmail = $person->email ?? '';
        $this->memberLinkedin = $person->linkedin_url ?? '';
        $this->memberInstagram = $person->instagram_url ?? '';
        $this->memberExpertise = $this->expertiseToString($person->expertise);
        $this->memberProjects = $this->projectsToString($person->completed_projects);
        $this->memberEducation = $this->arrayToLines($person->education);
        $this->memberInitials = $person->initials;
        $this->memberOrder = $person->sort_order;
        $this->memberPhoto = null;
        $this->memberDrawerOpen = true;
    }

    public function saveMember(): void
    {
        $this->validate([
            'memberNameFull' => 'required|string|max:255',
            'memberLine1' => 'required|string|max:255',
            'memberLine2' => 'required|string|max:255',
            'memberRoleId' => 'required|string|max:255',
            'memberBio' => 'nullable|string',
            'memberEmail' => 'nullable|email|max:255',
            'memberLinkedin' => 'nullable|url|max:255',
            'memberInstagram' => 'nullable|url|max:255',
            'memberExpertise' => 'nullable|string',
            'memberProjects' => 'nullable|string',
            'memberEducation' => 'nullable|string',
            'memberInitials' => 'required|string|max:4',
            'memberOrder' => 'required|integer|min:1',
            'memberPhoto' => 'nullable|image|max:2048',
        ]);

        $photoUrl = null;
        if ($this->memberPhoto) {
            $photoUrl = $this->memberPhoto->storeAs(
                'team/photos',
                uniqid('mem_').'.'.$this->memberPhoto->extension(),
                'public'
            );
        }

        $dto = new LabTeamPersonData(
            section_id: $this->memberSectionId,
            is_leader: false,
            name_full: $this->memberNameFull,
            display_line_1: $this->memberLine1,
            display_line_2: $this->memberLine2,
            role_id: $this->memberRoleId,
            role_en: $this->memberRoleId,
            bio: $this->memberBio ?: null,
            email: $this->memberEmail ?: null,
            linkedin_url: $this->memberLinkedin ?: null,
            instagram_url: $this->memberInstagram ?: null,
            expertise: $this->expertiseToArray($this->memberExpertise),
            completed_projects: $this->projectsToArray($this->memberProjects),
            education: $this->linesToArray($this->memberEducation),
            initials: $this->memberInitials,
            photo_url: $photoUrl,
            sort_order: $this->memberOrder,
            is_active: true,
        );

        if ($this->editingMemberId) {
            app(UpdateLabTeamPersonAction::class)->execute(
                LabTeamPerson::findOrFail($this->editingMemberId), $dto
            );
            $this->success(__('Member updated.'));
        } else {
            app(CreateLabTeamPersonAction::class)->execute($dto);
            $this->success(__('Member added.'));
        }

        $this->memberDrawerOpen = false;
    }

    public function confirmDeletePerson(int $id): void
    {
        $this->deletePersonId = $id;
        $this->deletePersonModalOpen = true;
    }

    public function deletePerson(): void
    {
        $person = LabTeamPerson::find($this->deletePersonId);
        if ($person) {
            app(DeleteLabTeamPersonAction::class)->execute($person);
        }
        $this->success(__('Person removed.'));
        $this->deletePersonModalOpen = false;
    }

    // ─────────────────────────────────────────────────────
    // Gallery actions
    // ─────────────────────────────────────────────────────

    public function openGallery(int $sectionId): void
    {
        $this->gallerySectionId = $sectionId;
        $this->galleryPhotos = [];
        $this->galleryDrawerOpen = true;
    }

    public function saveGallery(): void
    {
        $this->validate([
            'galleryPhotos' => 'array',
            'galleryPhotos.*' => 'image|max:4096',
        ]);

        $section = LabTeamSection::findOrFail($this->gallerySectionId);
        $nextOrder = ($section->attachments()->max('sort_order') ?? -1) + 1;

        foreach ($this->galleryPhotos as $photo) {
            $url = $photo->storeAs(
                'team/collage',
                uniqid('col_').'.'.$photo->extension(),
                'public'
            );

            Attachment::create([
                'attachable_type' => LabTeamSection::class,
                'attachable_id' => $section->id,
                'file_url' => Storage::disk('public')->url($url),
                'file_name' => $photo->getClientOriginalName(),
                'file_size' => $photo->getSize(),
                'file_type' => $photo->getMimeType(),
                'is_primary' => $nextOrder === 0,
                'sort_order' => $nextOrder,
                'uploaded_by' => auth()->id(),
            ]);

            $nextOrder++;
        }

        $this->galleryPhotos = [];
        $this->success(__('Photos uploaded.'));
    }

    public function confirmDeletePhoto(int $id): void
    {
        $this->deletePhotoId = $id;
        $this->deletePhotoModalOpen = true;
    }

    public function deletePhoto(): void
    {
        $attachment = Attachment::find($this->deletePhotoId);
        if ($attachment) {
            Storage::disk('public')->delete(
                str_replace(Storage::disk('public')->url(''), '', $attachment->file_url)
            );
            $attachment->delete();
        }
        $this->success(__('Photo deleted.'));
        $this->deletePhotoModalOpen = false;
    }

    // ─────────────────────────────────────────────────────
    // Expertise tags — stored as JSON, edited as a comma list
    // ─────────────────────────────────────────────────────

    private function expertiseToString(?array $expertise): string
    {
        return implode(', ', $expertise ?? []);
    }

    private function expertiseToArray(string $input): ?array
    {
        $tags = array_filter(array_map('trim', explode(',', $input)));

        return $tags ? array_values($tags) : null;
    }

    // ─────────────────────────────────────────────────────
    // Completed projects — one per line, "Title | Description | URL"
    // (URL optional). Education — one entry per line (plain strings;
    // not comma-split since a degree name usually contains a comma).
    // ─────────────────────────────────────────────────────

    private function projectsToString(?array $projects): string
    {
        return collect($projects ?? [])
            ->map(fn ($p) => collect([$p['title'] ?? '', $p['description'] ?? '', $p['url'] ?? ''])->implode(' | '))
            ->implode("\n");
    }

    private function projectsToArray(string $input): ?array
    {
        $lines = array_filter(array_map('trim', explode("\n", $input)));

        $projects = collect($lines)->map(function ($line) {
            $parts = array_map('trim', explode('|', $line));

            return [
                'title' => $parts[0] ?? '',
                'description' => $parts[1] ?? '',
                'url' => ($parts[2] ?? '') !== '' ? $parts[2] : null,
            ];
        })->filter(fn ($p) => $p['title'] !== '')->values()->all();

        return $projects ?: null;
    }

    private function arrayToLines(?array $items): string
    {
        return implode("\n", $items ?? []);
    }

    private function linesToArray(string $input): ?array
    {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $input))));

        return $lines ?: null;
    }

    // ─────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────

    public function render()
    {
        $sections = LabTeamSection::with(['leader', 'members', 'attachments'])
            ->orderBy('sort_order')
            ->get();

        $gallerySection = $this->gallerySectionId
            ? LabTeamSection::with('attachments')->find($this->gallerySectionId)
            : null;

        return view('livewire.admin.cms.team-section.index', [
            'sections' => $sections,
            'gallerySection' => $gallerySection,
        ]);
    }
}
