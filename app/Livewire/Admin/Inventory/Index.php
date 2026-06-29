<?php

namespace App\Livewire\Admin\Inventory;

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
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithFileUploads, WithPagination;

    // ==========================================
    // LOOKUP CONFIGURATION (Labs + Master Data)
    // ==========================================

    /** @var array<string, array{model: class-string<Model>, label: string, icon: string, relations: list<string>}> */
    private const LOOKUP_CONFIG = [
        'labs' => [
            'model' => Lab::class,
            'label' => 'Labs',
            'icon' => 'o-building-office-2',
            'relations' => ['rawMaterials', 'inventories'],
        ],
        'categories' => [
            'model' => MaterialCategory::class,
            'label' => 'Categories',
            'icon' => 'o-tag',
            'relations' => ['rawMaterials'],
        ],
        'brands' => [
            'model' => Brand::class,
            'label' => 'Brands',
            'icon' => 'o-bookmark',
            'relations' => ['rawMaterials', 'inventories'],
        ],
        'colors' => [
            'model' => Color::class,
            'label' => 'Colors',
            'icon' => 'o-swatch',
            'relations' => ['rawMaterials'],
        ],
    ];

    // ==========================================
    // SECTION STATE
    // ==========================================

    #[Url(history: true)]
    public string $section = 'materials';

    // ==========================================
    // LOOKUP SECTION STATE (Labs / Categories / Brands / Colors)
    // ==========================================

    public string $lookupSearch = '';

    public bool $lookupFormModal = false;

    public bool $lookupDeleteModal = false;

    public ?int $lookupEditingId = null;

    public ?int $lookupDeleteId = null;

    public string $lookupName = '';

    // ==========================================
    // MATERIALS SECTION STATE
    // ==========================================

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterLabId = '';

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public bool $showRestockForm = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    public ?int $restockId = null;

    public ?RawMaterial $activeMaterial = null;

    public int $lab_id = 0;

    public int $category_id = 0;

    public int $brand_id = 0;

    public int $color_id = 0;

    public string $unit = '';

    public int $current_stock = 0;

    public ?int $restockQty = null;

    public string $restockNotes = '';

    public ?int $restockAmount = null;

    public string $restockTitle = '';

    public $paymentProof = null;

    public int $totalIn = 0;

    public int $totalOut = 0;

    // ==========================================
    // SECTION SWITCHING
    // ==========================================

    public function updatedSection(): void
    {
        $this->reset(['lookupSearch', 'lookupEditingId', 'lookupName', 'lookupFormModal', 'lookupDeleteModal', 'lookupDeleteId']);
        $this->resetPage();
    }

    // ==========================================
    // LOOKUP CRUD (Labs / Categories / Brands / Colors)
    // ==========================================

    public function createLookup(): void
    {
        $this->reset(['lookupEditingId', 'lookupName']);
        $this->lookupFormModal = true;
    }

    public function editLookup(int $id): void
    {
        $config = $this->getLookupConfig();
        $record = $config['model']::findOrFail($id);
        $this->lookupEditingId = $record->id;
        $this->lookupName = $record->name;
        $this->lookupFormModal = true;
    }

    public function saveLookup(): void
    {
        $config = $this->getLookupConfig();
        $table = (new $config['model'])->getTable();

        $this->validate([
            'lookupName' => 'required|string|max:255|unique:'.$table.',name'.($this->lookupEditingId ? ",{$this->lookupEditingId}" : ''),
        ]);

        if ($this->lookupEditingId) {
            $config['model']::findOrFail($this->lookupEditingId)->update(['name' => $this->lookupName]);
            $this->success(__(':label updated.', ['label' => $config['label']]));
        } else {
            $config['model']::create(['name' => $this->lookupName]);
            $this->success(__(':label created.', ['label' => $config['label']]));
        }

        $this->lookupFormModal = false;
        $this->reset(['lookupEditingId', 'lookupName']);
    }

    public function confirmDeleteLookup(int $id): void
    {
        $this->lookupDeleteId = $id;
        $this->lookupDeleteModal = true;
    }

    public function deleteLookupRecord(): void
    {
        $config = $this->getLookupConfig();
        $record = $config['model']::findOrFail($this->lookupDeleteId);

        foreach ($config['relations'] as $relation) {
            if ($record->{$relation}()->exists()) {
                $this->error(__('Cannot delete ":name" — it is still used by :relation.', [
                    'name' => $record->name,
                    'relation' => str_replace('_', ' ', $relation),
                ]));
                $this->lookupDeleteModal = false;

                return;
            }
        }

        $record->delete();
        $this->success(__('Record deleted.'));
        $this->lookupDeleteModal = false;
    }

    // ==========================================
    // MATERIALS CRUD
    // ==========================================

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterLabId(): void
    {
        $this->resetPage();
    }

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
    // QUICK CREATE (inline from drawer form)
    // ==========================================

    /** @var array<string, array{model: class-string, field: string}> */
    private const QUICK_CREATE_CONFIG = [
        'labs' => ['model' => Lab::class,              'field' => 'lab_id'],
        'categories' => ['model' => MaterialCategory::class, 'field' => 'category_id'],
        'brands' => ['model' => Brand::class,            'field' => 'brand_id'],
        'colors' => ['model' => Color::class,            'field' => 'color_id'],
    ];

    public function quickCreate(string $type, string $name): void
    {
        $config = self::QUICK_CREATE_CONFIG[$type] ?? null;
        if (! $config) {
            return;
        }

        $name = trim($name);

        if (blank($name)) {
            $this->error(__('Name cannot be empty.'));

            return;
        }

        if ($config['model']::where('name', $name)->exists()) {
            $this->error(__('":name" already exists.', ['name' => $name]));

            return;
        }

        $record = $config['model']::create(['name' => $name]);
        $this->{$config['field']} = $record->id;
        $this->success(__('":name" created and selected.', ['name' => $name]));
    }

    // ==========================================
    // RESTOCK + REIMBURSEMENT
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

            $this->showRestockForm = false;
            $this->viewHistory(RawMaterial::findOrFail($this->restockId));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // ==========================================
    // MATERIALS DELETE
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
        $this->activeMaterial = $material->load([
            'lab', 'materialCategory', 'brand', 'color',
            'movements' => fn ($q) => $q->latest(),
            'movements.creator',
            'movements.reimbursement',
        ]);

        $this->restockId = $material->id;

        $this->totalIn = $this->activeMaterial->movements->where('type', 'in')->sum('quantity');
        $this->totalOut = $this->activeMaterial->movements->where('type', 'out')->sum('quantity');

        $this->showRestockForm = false;
        $this->reset(['restockQty', 'restockNotes', 'restockAmount', 'restockTitle', 'paymentProof']);
    }

    public function clearMaterial(): void
    {
        $this->activeMaterial = null;
        $this->restockId = null;
        $this->totalIn = 0;
        $this->totalOut = 0;
        $this->showRestockForm = false;
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /** @return array{model: class-string<Model>, label: string, icon: string, relations: list<string>} */
    private function getLookupConfig(): array
    {
        return self::LOOKUP_CONFIG[$this->section];
    }

    // ==========================================
    // RENDER
    // ==========================================

    public function render()
    {
        $lookupSections = self::LOOKUP_CONFIG;

        if ($this->section !== 'materials') {
            $config = $this->getLookupConfig();

            $records = $config['model']::query()
                ->withCount($config['relations'])
                ->when($this->lookupSearch, fn ($q) => $q->where('name', 'like', "%{$this->lookupSearch}%"))
                ->orderBy('name')
                ->paginate(15);

            return view('livewire.admin.inventory.index', compact('records', 'lookupSections') + ['lookupConfig' => $config]);
        }

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

        $dbUnits = RawMaterial::query()->distinct()->whereNotNull('unit')->orderBy('unit')->pluck('unit')->toArray();
        $unitOptions = array_unique(array_merge(['gram', 'ml', 'pcs'], $dbUnits));

        return view('livewire.admin.inventory.index', compact(
            'materials',
            'labOptions',
            'categoryOptions',
            'brandOptions',
            'colorOptions',
            'unitOptions',
            'lookupSections',
        ));
    }
}
