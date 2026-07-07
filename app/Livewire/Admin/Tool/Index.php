<?php

namespace App\Livewire\Admin\Tool;

use App\Actions\Tool\CreateToolAction;
use App\Actions\Tool\DeleteToolAction;
use App\Actions\Tool\UpdateToolAction;
use App\DTOs\Tool\ToolData;
use App\Models\Attachment;
use App\Models\Lab;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Tools')]
class Index extends Component
{
    use Toast, WithFileUploads, WithPagination;

    // ==========================================
    // BROWSE STATE
    // ==========================================

    public string $search = '';

    public ?Tool $activeTool = null;

    // ==========================================
    // FORM MODAL STATE
    // ==========================================

    public bool $formModal = false;

    public ?int $editingId = null;

    public string $toolName = '';

    public int $labId = 0;

    public array $photos = [];

    // ==========================================
    // DELETE MODAL STATE
    // ==========================================

    public bool $deleteModal = false;

    public ?int $deleteId = null;

    // ==========================================
    // DETAIL PANEL PHOTO UPLOAD
    // ==========================================

    public array $newPhotos = [];

    public bool $showPhotoUpload = false;

    // ==========================================
    // BROWSE
    // ==========================================

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function viewTool(int $id): void
    {
        $this->activeTool = Tool::with(['lab', 'creator', 'attachments'])->findOrFail($id);
        $this->showPhotoUpload = false;
        $this->newPhotos = [];
    }

    public function clearTool(): void
    {
        $this->activeTool = null;
        $this->showPhotoUpload = false;
        $this->newPhotos = [];
    }

    // ==========================================
    // CRUD
    // ==========================================

    public function create(): void
    {
        $this->reset(['editingId', 'toolName', 'labId', 'photos']);
        $this->formModal = true;
    }

    public function edit(Tool $tool): void
    {
        $this->editingId = $tool->id;
        $this->toolName = $tool->name;
        $this->labId = $tool->lab_id;
        $this->photos = [];
        $this->formModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'toolName' => 'required|string|max:255',
            'labId' => 'required|integer|min:1|exists:labs,id',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $dto = new ToolData(name: $this->toolName, lab_id: $this->labId);

        if ($this->editingId) {
            $tool = Tool::findOrFail($this->editingId);
            app(UpdateToolAction::class)->execute($tool, $dto);

            if (! empty($this->photos)) {
                $this->attachPhotos($tool, $this->photos);
            }

            if ($this->activeTool?->id === $tool->id) {
                $this->activeTool = $tool->fresh(['lab', 'creator', 'attachments']);
            }

            $this->success(__('Tool updated successfully.'));
        } else {
            $tool = app(CreateToolAction::class)->execute($dto, Auth::id());

            if (! empty($this->photos)) {
                $this->attachPhotos($tool, $this->photos);
            }

            $this->success(__('Tool created successfully.'));
        }

        $this->formModal = false;
        $this->reset(['editingId', 'toolName', 'labId', 'photos']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function deleteRecord(): void
    {
        $tool = Tool::findOrFail($this->deleteId);

        if ($this->activeTool?->id === $tool->id) {
            $this->clearTool();
        }

        app(DeleteToolAction::class)->execute($tool);
        $this->success(__('Tool deleted successfully.'));
        $this->deleteModal = false;
    }

    // ==========================================
    // PHOTO MANAGEMENT (from detail panel)
    // ==========================================

    public function addPhotos(): void
    {
        $this->validate([
            'newPhotos' => 'required|array|min:1|max:10',
            'newPhotos.*' => 'required|image|max:5120',
        ]);

        $this->attachPhotos($this->activeTool, $this->newPhotos);
        $this->activeTool = $this->activeTool->fresh(['lab', 'creator', 'attachments']);
        $this->newPhotos = [];
        $this->showPhotoUpload = false;
        $this->success(__('Photos added successfully.'));
    }

    public function deletePhoto(int $attachmentId): void
    {
        $attachment = Attachment::findOrFail($attachmentId);

        $relativePath = str_replace(
            Storage::disk('public')->url(''),
            '',
            $attachment->file_url
        );
        Storage::disk('public')->delete($relativePath);

        $wasPrimary = $attachment->is_primary;
        $attachment->delete();

        if ($wasPrimary && $this->activeTool) {
            $first = $this->activeTool->attachments()->first();
            $first?->update(['is_primary' => true]);
        }

        $this->activeTool = $this->activeTool?->fresh(['lab', 'creator', 'attachments']);
        $this->success(__('Photo removed.'));
    }

    public function setPrimary(int $attachmentId): void
    {
        if (! $this->activeTool) {
            return;
        }

        $this->activeTool->attachments()->update(['is_primary' => false]);
        Attachment::findOrFail($attachmentId)->update(['is_primary' => true]);
        $this->activeTool = $this->activeTool->fresh(['lab', 'creator', 'attachments']);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /** @param array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> $photos */
    private function attachPhotos(Tool $tool, array $photos): void
    {
        $existingCount = $tool->attachments()->count();

        foreach ($photos as $index => $photo) {
            $filename = Str::uuid().'.'.$photo->extension();
            $path = $photo->storeAs("tools/{$tool->id}", $filename, 'public');

            Attachment::create([
                'attachable_type' => Tool::class,
                'attachable_id' => $tool->id,
                'file_url' => Storage::disk('public')->url($path),
                'file_name' => $photo->getClientOriginalName(),
                'file_size' => (string) $photo->getSize(),
                'file_type' => $photo->getMimeType(),
                'is_primary' => $existingCount === 0 && $index === 0,
                'sort_order' => $existingCount + $index,
                'uploaded_by' => Auth::id(),
            ]);
        }
    }

    // ==========================================
    // RENDER
    // ==========================================

    public function render()
    {
        $tools = Tool::query()
            ->with(['lab', 'primaryAttachment'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest('created_at')
            ->paginate(12);

        $labOptions = Lab::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.tool.index', compact('tools', 'labOptions'));
    }
}
