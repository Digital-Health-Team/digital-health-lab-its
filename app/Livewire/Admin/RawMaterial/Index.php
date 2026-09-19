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

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public bool $historyDrawerOpen = false;

    public bool $showRestockForm = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    public ?int $restockId = null;

    public ?RawMaterial $activeMaterial = null;

    public int $brand_id = 0;

    public string $name = '';

    public string $unit = '';

    public int $restockColorId = 0;

    public int $restockLabId = 0;

    public ?int $restockQty = null;

    public string $restockNotes = '';

    public ?int $restockAmount = null;

    public $paymentProof = null;

    public int $totalIn = 0;

    public int $totalOut = 0;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['brand_id', 'name', 'unit', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(RawMaterial $material): void
    {
        $this->editingId = $material->id;
        $this->brand_id = $material->brand_id;
        $this->name = $material->name;
        $this->unit = $material->unit;
        $this->drawerOpen = true;
    }

    public function save(): void
    {
        $this->validate([
            'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        $dto = new RawMaterialData(
            brand_id: $this->brand_id,
            name: $this->name,
            unit: $this->unit,
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

    public function processRestock(): void
    {
        $this->validate([
            'restockColorId' => 'required|integer|exists:colors,id',
            'restockLabId' => 'required|integer|exists:labs,id',
            'restockQty' => 'required|integer|min:1',
            'restockAmount' => 'required|integer|min:1',
            'restockNotes' => 'required|string|max:255',
            'paymentProof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480',
        ]);

        try {
            $dto = new RestockMaterialData(
                raw_material_id: $this->restockId,
                color_id: $this->restockColorId,
                lab_id: $this->restockLabId,
                quantity: $this->restockQty,
                total_amount: $this->restockAmount,
                notes: $this->restockNotes,
                payment_proof: $this->paymentProof
            );

            app(RestockMaterialAction::class)->execute($dto);

            $this->success(__('Stock added and reimbursement recorded.'));

            $this->showRestockForm = false;
            $this->viewHistory(RawMaterial::findOrFail($this->restockId));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

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

    public function viewHistory(RawMaterial $material): void
    {
        $this->activeMaterial = $material->load([
            'brand',
            'stocks.color',
            'stocks.lab',
            'movements' => fn ($q) => $q->latest(),
            'movements.creator',
            'movements.reimbursement',
        ]);

        $this->restockId = $material->id;

        $this->totalIn = $this->activeMaterial->movements->where('type', 'in')->sum('quantity');
        $this->totalOut = $this->activeMaterial->movements->where('type', 'out')->sum('quantity');

        $this->showRestockForm = false;
        $this->reset(['restockQty', 'restockNotes', 'restockAmount', 'paymentProof', 'restockColorId', 'restockLabId']);

        $this->historyDrawerOpen = true;
    }

    public function render()
    {
        $materials = RawMaterial::query()
            ->with(['brand'])
            ->when(
                $this->search,
                fn ($q) => $q->where(
                    fn ($q2) => $q2
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('brand', fn ($q3) => $q3->where('name', 'like', "%{$this->search}%"))
                )
            )
            ->latest('created_at')
            ->paginate(10);

        $brandOptions = Brand::orderBy('name')->get(['id', 'name']);
        $colorOptions = Color::orderBy('name')->get(['id', 'name']);
        $labOptions = Lab::orderBy('name')->get(['id', 'name']);

        $dbUnits = RawMaterial::query()->distinct()->whereNotNull('unit')->orderBy('unit')->pluck('unit')->toArray();
        $unitOptions = array_unique(array_merge(['gram', 'ml', 'pcs'], $dbUnits));

        return view('livewire.admin.raw-material.index', compact(
            'materials',
            'brandOptions',
            'colorOptions',
            'labOptions',
            'unitOptions'
        ));
    }
}
