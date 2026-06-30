<?php

namespace App\Livewire\Admin\RawMaterial;

use App\Actions\RawMaterial\CreateRawMaterialAction;
use App\Actions\RawMaterial\DeleteRawMaterialAction;
use App\Actions\RawMaterial\RestockMaterialAction;
use App\Actions\RawMaterial\UpdateRawMaterialAction;
use App\DTOs\RawMaterial\RawMaterialData;
use App\DTOs\RawMaterial\RestockMaterialData;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithFileUploads, WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterLabId = '';

    // --- UI STATES ---
    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public bool $historyDrawerOpen = false;

    public bool $showRestockForm = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    public ?int $restockId = null;

    public ?RawMaterial $activeMaterial = null;

    // --- FORM DATA: FK-based selects ---
    public int $lab_id = 0;

    public int $category_id = 0;

    public int $brand_id = 0;

    public int $color_id = 0;

    public string $unit = '';

    public int $current_stock = 0;

    // --- FORM DATA: RESTOCK + REIMBURSEMENT ---
    public ?int $restockQty = null;

    public string $restockNotes = '';

    public ?int $restockAmount = null;

    public string $restockTitle = '';

    public $paymentProof = null;

    // --- DATA VISUALIZATION ---
    public int $totalIn = 0;

    public int $totalOut = 0;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterLabId(): void
    {
        $this->resetPage();
    }

    // ==========================================
    // CRUD MASTER ACTIONS
    // ==========================================
    public function create(): void
    {
        $this->reset(['lab_id', 'category_id', 'brand_id', 'color_id', 'unit', 'current_stock', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(RawMaterial $material): void
    {
        $this->editingId = $material->id;
        $this->lab_id = $material->lab_id;
        $this->category_id = $material->material_category_id;
        $this->brand_id = $material->brand_id;
        $this->color_id = $material->color_id;
        $this->unit = $material->unit;
        $this->drawerOpen = true;
    }

    public function save(): void
    {
        $rules = [
            'lab_id' => 'required|integer|exists:labs,id',
            'category_id' => 'required|integer|exists:material_categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'color_id' => 'required|integer|exists:colors,id',
            'unit' => 'required|string|max:50',
        ];

        if (! $this->editingId) {
            $rules['current_stock'] = 'required|integer|min:0';
        }

        $this->validate($rules);

        $dto = new RawMaterialData(
            lab_id: $this->lab_id,
            category_id: $this->category_id,
            brand_id: $this->brand_id,
            color_id: $this->color_id,
            unit: $this->unit,
            current_stock: (int) $this->current_stock,
        );

        if ($this->editingId) {
            $material = RawMaterial::findOrFail($this->editingId);
            app(UpdateRawMaterialAction::class)->execute($material, $dto);
            $this->success(__('Material updated successfully.'));
        } else {
            app(CreateRawMaterialAction::class)->execute($dto);
            $this->success(__('Material created successfully.'));
        }

        $this->drawerOpen = false;
    }

    // ==========================================
    // RESTOCK + REIMBURSEMENT (Integrated)
    // ==========================================
    public function processRestock(): void
    {
        $this->validate([
            'restockQty' => 'required|integer|min:1',
            'restockAmount' => 'required|integer|min:1',
            'restockTitle' => 'required|string|max:255',
            'restockNotes' => 'required|string|max:255',
            'paymentProof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480',
        ]);

        try {
            $dto = new RestockMaterialData(
                raw_material_id: $this->restockId,
                quantity: $this->restockQty,
                total_amount: $this->restockAmount,
                reimbursement_title: $this->restockTitle,
                notes: $this->restockNotes,
                payment_proof: $this->paymentProof
            );

            app(RestockMaterialAction::class)->execute($dto);

            $this->success(__('Stock added and reimbursement recorded.'));

            // Collapse restock form and refresh history data in real-time
            $this->showRestockForm = false;
            $this->viewHistory(RawMaterial::findOrFail($this->restockId));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // ==========================================
    // DELETE
    // ==========================================
    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord(): void
    {
        try {
            app(DeleteRawMaterialAction::class)->execute(RawMaterial::find($this->deleteId));
            $this->success(__('Material deleted successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->deleteModalOpen = false;
    }

    // ==========================================
    // HISTORY & VISUALIZATION
    // ==========================================
    public function viewHistory(RawMaterial $material): void
    {
        // Eager load movements with creator (for "Logged By") and reimbursement
        $this->activeMaterial = $material->load([
            'lab', 'materialCategory', 'brand', 'color',
            'movements' => fn ($q) => $q->latest(),
            'movements.creator',
            'movements.reimbursement',
        ]);

        $this->restockId = $material->id;

        $this->totalIn = $this->activeMaterial->movements->where('type', 'in')->sum('quantity');
        $this->totalOut = $this->activeMaterial->movements->where('type', 'out')->sum('quantity');

        // Reset restock form state
        $this->showRestockForm = false;
        $this->reset(['restockQty', 'restockNotes', 'restockAmount', 'restockTitle', 'paymentProof']);

        $this->historyDrawerOpen = true;
    }

    public function render()
    {
        $materials = RawMaterial::query()
            ->with(['lab', 'materialCategory', 'brand', 'color'])
            ->when(
                $this->search,
                fn ($q) => $q->where(
                    fn ($q2) => $q2
                        ->whereHas('brand', fn ($q3) => $q3->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('materialCategory', fn ($q3) => $q3->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('color', fn ($q3) => $q3->where('name', 'like', "%{$this->search}%"))
                )
            )
            ->when($this->filterLabId, fn ($q) => $q->where('lab_id', $this->filterLabId))
            ->latest('created_at')
            ->paginate(10);

        $labOptions = Lab::orderBy('name')->get(['id', 'name']);
        $categoryOptions = MaterialCategory::orderBy('name')->get(['id', 'name']);
        $brandOptions = Brand::orderBy('name')->get(['id', 'name']);
        $colorOptions = Color::orderBy('name')->get(['id', 'name']);

        // Merge DB units with defaults
        $dbUnits = RawMaterial::query()->distinct()->whereNotNull('unit')->orderBy('unit')->pluck('unit')->toArray();
        $unitOptions = array_unique(array_merge(['gram', 'ml', 'pcs'], $dbUnits));

        return view('livewire.admin.raw-material.index', compact(
            'materials',
            'labOptions',
            'categoryOptions',
            'brandOptions',
            'colorOptions',
            'unitOptions'
        ));
    }
}
