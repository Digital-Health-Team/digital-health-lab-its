<?php

namespace App\Livewire\Admin\Report;

use App\Actions\Report\CreateIssueReportAction;
use App\Actions\Report\DeleteIssueReportAction;
use App\Actions\Report\UpdateIssueReportAction;
use App\Actions\Report\UpdateIssueReportStatusAction;
use App\DTOs\Report\IssueReportData;
use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Attachment;
use App\Models\Inventory;
use App\Models\IssueReport;
use App\Models\RawMaterial;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Issue Reports')]
class Index extends Component
{
    use Toast, WithFileUploads, WithPagination;

    // --- FILTERS ---
    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterStatus = '';

    #[Url(history: true)]
    public string $filterType = '';

    /** Deep link from the notification bell (?report={id}). */
    #[Url]
    public ?int $report = null;

    public ?IssueReport $activeReport = null;

    // --- FORM MODAL ---
    public bool $formModal = false;

    public ?int $editingId = null;

    public string $reportType = '';

    public string $description = '';

    public string $reportableType = '';

    public ?int $reportableId = null;

    public array $photos = [];

    // --- DELETE MODAL ---
    public bool $deleteModal = false;

    public ?int $deleteId = null;

    // --- RESOLVE PANEL (gudang) ---
    public string $newStatus = '';

    public string $resolutionNote = '';

    public function mount(): void
    {
        if ($this->report) {
            $target = IssueReport::find($this->report);
            if ($target && Auth::user()->can('view', $target)) {
                $this->viewReport($target->id);
            }
        }
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['search', 'filterStatus', 'filterType'])) {
            $this->resetPage();
        }

        // Changing the linked-record type invalidates the chosen record.
        if ($propertyName === 'reportableType') {
            $this->reportableId = null;
        }
    }

    // ==========================================
    // BROWSE
    // ==========================================
    public function viewReport(int $id): void
    {
        $reportModel = IssueReport::with(['reporter', 'resolver', 'reportable', 'attachments'])->findOrFail($id);
        $this->authorize('view', $reportModel);

        $this->activeReport = $reportModel;
        $this->newStatus = $reportModel->status->value;
        $this->resolutionNote = (string) ($reportModel->resolution_note ?? '');
    }

    public function clearReport(): void
    {
        $this->activeReport = null;
        $this->report = null;
        $this->reset(['newStatus', 'resolutionNote']);
    }

    // ==========================================
    // CRUD (reporter)
    // ==========================================
    public function create(): void
    {
        $this->authorize('create', IssueReport::class);
        $this->reset(['editingId', 'reportType', 'description', 'reportableType', 'reportableId', 'photos']);
        $this->formModal = true;
    }

    public function edit(IssueReport $issueReport): void
    {
        $this->authorize('update', $issueReport);

        $this->editingId = $issueReport->id;
        $this->reportType = $issueReport->type->value;
        $this->description = $issueReport->description;
        $this->reportableType = (string) ($issueReport->reportable_type ?? '');
        $this->reportableId = $issueReport->reportable_id;
        $this->photos = [];
        $this->formModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'reportType' => ['required', Rule::enum(ReportType::class)],
            'description' => 'required|string|max:5000',
            'reportableType' => ['nullable', Rule::in(['', Tool::class, RawMaterial::class, Inventory::class])],
            'reportableId' => 'nullable|required_with:reportableType|integer',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'nullable|image|max:20480',
        ]);

        // The linked record must exist in the chosen table.
        if ($this->reportableType && ! $this->reportableType::query()->whereKey($this->reportableId)->exists()) {
            $this->error(__('The selected item no longer exists.'));

            return;
        }

        $dto = new IssueReportData(
            type: $this->reportType,
            description: $this->description,
            reportable_type: $this->reportableType ?: null,
            reportable_id: $this->reportableType ? $this->reportableId : null,
        );

        if ($this->editingId) {
            $reportModel = IssueReport::findOrFail($this->editingId);
            $this->authorize('update', $reportModel);
            app(UpdateIssueReportAction::class)->execute($reportModel, $dto);
            $this->success(__('Report updated.'));
        } else {
            $this->authorize('create', IssueReport::class);
            $reportModel = app(CreateIssueReportAction::class)->execute($dto, Auth::id());
            $this->success(__('Report submitted. The warehouse team has been notified.'));
        }

        if (! empty($this->photos)) {
            $this->attachPhotos($reportModel, $this->photos);
        }

        if ($this->activeReport?->id === $reportModel->id) {
            $this->viewReport($reportModel->id);
        }

        $this->formModal = false;
        $this->reset(['editingId', 'reportType', 'description', 'reportableType', 'reportableId', 'photos']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function deleteRecord(): void
    {
        $reportModel = IssueReport::findOrFail($this->deleteId);
        $this->authorize('delete', $reportModel);

        if ($this->activeReport?->id === $reportModel->id) {
            $this->clearReport();
        }

        app(DeleteIssueReportAction::class)->execute($reportModel);
        $this->success(__('Report deleted.'));
        $this->deleteModal = false;
    }

    // ==========================================
    // RESOLUTION (warehouse admin)
    // ==========================================
    public function updateStatus(): void
    {
        $reportModel = IssueReport::findOrFail($this->activeReport->id);
        $this->authorize('resolve', $reportModel);

        $this->validate([
            'newStatus' => ['required', Rule::enum(ReportStatus::class)],
            'resolutionNote' => 'nullable|string|max:2000',
        ]);

        app(UpdateIssueReportStatusAction::class)->execute(
            $reportModel,
            ReportStatus::from($this->newStatus),
            $this->resolutionNote ?: null,
            Auth::id(),
        );

        $this->success(__('Report status updated.'));
        $this->viewReport($reportModel->id);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /** @param array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> $photos */
    private function attachPhotos(IssueReport $reportModel, array $photos): void
    {
        $existingCount = $reportModel->attachments()->count();

        foreach ($photos as $index => $photo) {
            $filename = Str::uuid().'.'.$photo->extension();
            $path = $photo->storeAs("issue-reports/{$reportModel->id}", $filename, 'public');

            Attachment::create([
                'attachable_type' => IssueReport::class,
                'attachable_id' => $reportModel->id,
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

    private function canResolve(): bool
    {
        return in_array(Auth::user()->activeRoleName(), ['super_admin', 'admin_gudang']);
    }

    // ==========================================
    // RENDER
    // ==========================================
    public function render()
    {
        $reports = IssueReport::query()
            ->with(['reporter', 'reportable'])
            // Warehouse and super admin triage everything; others track their own.
            ->when(! $this->canResolve(), fn ($q) => $q->where('reporter_id', Auth::id()))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        $reportableOptions = match ($this->reportableType) {
            Tool::class => Tool::orderBy('name')->get(['id', 'name'])
                ->map(fn ($t) => ['id' => $t->id, 'name' => $t->name]),
            RawMaterial::class => RawMaterial::with('brand')->orderBy('name')->get()
                ->map(fn ($m) => ['id' => $m->id, 'name' => trim(($m->brand->name ?? '').' '.$m->name)]),
            Inventory::class => Inventory::orderBy('name')->get(['id', 'name'])
                ->map(fn ($i) => ['id' => $i->id, 'name' => $i->name]),
            default => collect(),
        };

        return view('livewire.admin.report.index', [
            'reports' => $reports,
            'reportableOptions' => $reportableOptions,
            'canResolve' => $this->canResolve(),
        ]);
    }
}
