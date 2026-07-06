<?php

namespace App\Livewire\Admin\Inventory;

use App\Actions\RawMaterial\DeleteRawMaterialAction;
use App\Actions\RawMaterial\RestockMaterialAction;
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

    /** @var array<string, array{model: class-string<Model>, label: string, icon: string, relations: list<string>, with: list<string>}> */
    private const LOOKUP_CONFIG = [
        'labs' => [
            'model' => Lab::class,
            'label' => 'Labs',
            'icon' => 'o-building-office-2',
            'relations' => ['itemStocks', 'inventories'],
            'with' => ['itemStocks.rawMaterial.brand', 'itemStocks.color', 'inventories.brand'],
        ],
        'categories' => [
            'model' => MaterialCategory::class,
            'label' => 'Categories',
            'icon' => 'o-tag',
            'relations' => ['brands'],
            'with' => ['brands.rawMaterials', 'brands.colors'],
        ],
        'brands' => [
            'model' => Brand::class,
            'label' => 'Brands',
            'icon' => 'o-bookmark',
            'relations' => ['rawMaterials', 'inventories'],
            'with' => ['rawMaterials.stocks.lab', 'rawMaterials.stocks.color', 'colors', 'inventories', 'materialCategory'],
        ],
        'colors' => [
            'model' => Color::class,
            'label' => 'Colors',
            'icon' => 'o-swatch',
            'relations' => ['itemStocks'],
            'with' => ['itemStocks.rawMaterial.brand', 'itemStocks.lab'],
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

    public ?int $activeLookupId = null;

    // ==========================================
    // MATERIALS SECTION STATE
    // ==========================================

    #[Url(history: true)]
    public string $search = '';

    public bool $deleteModalOpen = false;

    public bool $showRestockForm = false;

    public ?int $deleteId = null;

    public ?int $restockId = null;

    public ?RawMaterial $activeMaterial = null;

    public int $brandCategoryId = 0;

    public array $brandColorIds = [];

    public int $restockColorId = 0;

    public int $restockLabId = 0;

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
        $this->reset(['lookupSearch', 'lookupEditingId', 'lookupName', 'lookupFormModal', 'lookupDeleteModal', 'lookupDeleteId', 'brandCategoryId', 'brandColorIds', 'activeLookupId']);
        $this->resetPage();
    }

    // ==========================================
    // LOOKUP CRUD (Labs / Categories / Brands / Colors)
    // ==========================================

    public function createLookup(): void
    {
        $this->reset(['lookupEditingId', 'lookupName', 'brandCategoryId', 'brandColorIds']);
        $this->lookupFormModal = true;
    }

    public function editLookup(int $id): void
    {
        $config = $this->getLookupConfig();
        $record = $config['model']::findOrFail($id);
        $this->lookupEditingId = $record->id;
        $this->lookupName = $record->name;

        if ($this->section === 'brands') {
            $record->loadMissing('colors');
            $this->brandCategoryId = $record->material_category_id ?? 0;
            $this->brandColorIds = $record->colors->pluck('id')->toArray();
        }

        $this->lookupFormModal = true;
    }

    public function saveLookup(): void
    {
        $config = $this->getLookupConfig();
        $table = (new $config['model'])->getTable();

        $this->validate([
            'lookupName' => 'required|string|max:255|unique:'.$table.',name'.($this->lookupEditingId ? ",{$this->lookupEditingId}" : ''),
        ]);

        $payload = ['name' => $this->lookupName];
        if ($this->section === 'brands') {
            $payload['material_category_id'] = $this->brandCategoryId ?: null;
        }

        if ($this->lookupEditingId) {
            $record = $config['model']::findOrFail($this->lookupEditingId);
            $record->update($payload);
            $this->success(__(':label updated.', ['label' => $config['label']]));
        } else {
            $record = $config['model']::create($payload);
            $this->success(__(':label created.', ['label' => $config['label']]));
        }

        if ($this->section === 'brands') {
            $record->colors()->sync($this->brandColorIds);
        }

        $this->lookupFormModal = false;
        $this->reset(['lookupEditingId', 'lookupName', 'brandCategoryId', 'brandColorIds']);
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

        if ($this->activeLookupId === $this->lookupDeleteId) {
            $this->activeLookupId = null;
        }
    }

    // ==========================================
    // LOOKUP DETAIL PANEL
    // ==========================================

    public function viewLookup(int $id): void
    {
        $this->activeLookupId = $id;
    }

    public function clearLookup(): void
    {
        $this->activeLookupId = null;
    }

    // ==========================================
    // MATERIALS CRUD
    // ==========================================

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ==========================================
    // RESTOCK + REIMBURSEMENT
    // ==========================================

    public function processRestock(): void
    {
        $this->validate([
            'restockColorId' => 'required|integer|exists:colors,id',
            'restockLabId' => 'required|integer|exists:labs,id',
            'restockQty' => 'required|integer|min:1',
            'restockAmount' => 'required|integer|min:1',
            'restockTitle' => 'required|string|max:255',
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
            'brand.colors',
            'creator',
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
        $this->reset(['restockQty', 'restockNotes', 'restockAmount', 'restockTitle', 'paymentProof', 'restockColorId', 'restockLabId']);
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
                ->when(! empty($config['with']), fn ($q) => $q->with($config['with']))
                ->when($this->lookupSearch, fn ($q) => $q->where('name', 'like', "%{$this->lookupSearch}%"))
                ->orderBy('name')
                ->paginate(15);

            $activeLookup = $this->activeLookupId
                ? $config['model']::with($config['with'])->find($this->activeLookupId)
                : null;

            $extra = [];
            if ($this->section === 'brands') {
                $extra['categoryOptions'] = MaterialCategory::orderBy('name')->get(['id', 'name']);
                $extra['colorOptions'] = Color::orderBy('name')->get(['id', 'name']);
            }

            return view('livewire.admin.inventory.index', compact('records', 'lookupSections', 'activeLookup') + ['lookupConfig' => $config] + $extra);
        }

        $materials = RawMaterial::query()
            ->with(['brand', 'stocks', 'creator'])
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

        $colorOptions = ($this->activeMaterial && $this->activeMaterial->relationLoaded('brand'))
            ? $this->activeMaterial->brand->colors()->orderBy('name')->get(['id', 'name'])
            : Color::orderBy('name')->get(['id', 'name']);
        $labOptions = Lab::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.inventory.index', compact(
            'materials',
            'colorOptions',
            'labOptions',
            'lookupSections',
        ));
    }
}
