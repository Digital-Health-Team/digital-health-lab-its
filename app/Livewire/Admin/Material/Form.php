<?php

namespace App\Livewire\Admin\Material;

use App\Actions\RawMaterial\AddInitialStockAction;
use App\Actions\RawMaterial\CreateRawMaterialAction;
use App\Actions\RawMaterial\UpdateRawMaterialAction;
use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Material Form')]
class Form extends Component
{
    use Toast, WithFileUploads;

    private const ENTITY_CONFIG = [
        'category' => ['model' => MaterialCategory::class, 'label' => 'Category', 'relations' => ['brands']],
        'brand' => ['model' => Brand::class, 'label' => 'Brand', 'relations' => ['rawMaterials', 'inventories']],
        'color' => ['model' => Color::class, 'label' => 'Color', 'relations' => ['itemStocks']],
        'lab' => ['model' => Lab::class, 'label' => 'Lab', 'relations' => ['itemStocks', 'inventories']],
    ];

    public ?RawMaterial $material = null;

    // Item definition
    public int $categoryId = 0;

    public int $brandId = 0;

    public string $name = '';

    public string $unit = '';

    // Initial stock (create only)
    public array $colorIds = [];

    public array $colorQuantities = [];

    public array $colorAmounts = [];

    public array $colorNotes = [];

    public array $colorProofs = [];

    public ?int $labId = null;

    // Manage modal state
    public string $manageEntity = '';

    public bool $manageModal = false;

    public ?int $manageEditId = null;

    public string $manageEditName = '';

    public string $manageEditHex = '';

    public function mount(?RawMaterial $material = null): void
    {
        if ($material?->exists) {
            $this->material = $material;
            $material->loadMissing('brand');
            $this->brandId = (int) $material->brand_id;
            $this->categoryId = (int) ($material->brand->material_category_id ?? 0);
            $this->name = $material->name;
            $this->unit = $material->unit;
        }
    }

    public function updatedCategoryId(): void
    {
        $this->brandId = 0;
    }

    public function updatedColorIds(): void
    {
        $selected = array_map('intval', $this->colorIds);

        foreach (['colorQuantities', 'colorAmounts', 'colorNotes', 'colorProofs'] as $property) {
            $this->{$property} = array_filter(
                $this->{$property},
                fn ($key) => in_array((int) $key, $selected, true),
                ARRAY_FILTER_USE_KEY
            );
        }
    }

    // ==========================================
    // SAVE
    // ==========================================

    public function save(): void
    {
        $rules = [
            'brandId' => 'required|integer|min:1|exists:brands,id',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ];

        if (! $this->material) {
            $hasQty = false;

            $rules['colorIds'] = 'array';
            $rules['colorIds.*'] = 'integer|exists:colors,id';
            $rules['colorQuantities.*'] = 'nullable|integer|min:0';

            // A color with a quantity follows the restock flow: amount,
            // notes, and payment proof become mandatory for that color.
            foreach ($this->colorIds as $colorId) {
                if ((int) ($this->colorQuantities[$colorId] ?? 0) > 0) {
                    $hasQty = true;
                    $rules["colorAmounts.{$colorId}"] = 'required|integer|min:1';
                    $rules["colorNotes.{$colorId}"] = 'required|string|max:255';
                    $rules["colorProofs.{$colorId}"] = 'required|file|mimes:jpg,jpeg,png,pdf|max:20480';
                }
            }

            $rules['labId'] = ($hasQty ? 'required' : 'nullable').'|integer|exists:labs,id';
        }

        $this->validate($rules);

        $dto = new RawMaterialData(
            brand_id: $this->brandId,
            name: $this->name,
            unit: $this->unit,
            created_by: $this->material ? null : auth()->id(),
        );

        if ($this->material) {
            app(UpdateRawMaterialAction::class)->execute($this->material, $dto);
            $this->success(__('Material updated successfully.'));
        } else {
            $created = app(CreateRawMaterialAction::class)->execute($dto);

            if ($this->colorIds !== []) {
                $colorRows = collect($this->colorIds)
                    ->mapWithKeys(fn ($id) => [(int) $id => [
                        'quantity' => (int) ($this->colorQuantities[$id] ?? 0),
                        'amount' => (int) ($this->colorAmounts[$id] ?? 0),
                        'notes' => (string) ($this->colorNotes[$id] ?? ''),
                        'proof' => $this->colorProofs[$id] ?? null,
                    ]])
                    ->all();

                app(AddInitialStockAction::class)->execute($created, $colorRows, $this->labId);
            }

            $this->success(__('Material created successfully.'));
        }

        $this->redirectRoute('admin.inventory', navigate: true);
    }

    // ==========================================
    // QUICK CREATE (category / brand / color / lab)
    // ==========================================

    public function quickCreate(string $entity, string $name, ?string $hex = null): void
    {
        abort_unless(isset(self::ENTITY_CONFIG[$entity]), 404);

        $config = self::ENTITY_CONFIG[$entity];
        $name = trim($name);

        if (blank($name)) {
            $this->error(__('Name cannot be empty.'));

            return;
        }

        if ($config['model']::where('name', $name)->exists()) {
            $this->error(__('":name" already exists.', ['name' => $name]));

            return;
        }

        $payload = ['name' => $name];
        if ($entity === 'brand' && $this->categoryId) {
            $payload['material_category_id'] = $this->categoryId;
        }
        if ($entity === 'color' && $hex && preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
            $payload['hex'] = strtoupper($hex);
        }

        $record = $config['model']::create($payload);

        match ($entity) {
            'category' => [$this->categoryId = $record->id, $this->brandId = 0],
            'brand' => $this->brandId = $record->id,
            'color' => $this->colorIds[] = (string) $record->id,
            'lab' => $this->labId = $record->id,
        };

        $this->success(__('":name" created and selected.', ['name' => $name]));
    }

    public function deselectColor(int $id): void
    {
        $this->removeColorSelection($id);
    }

    // ==========================================
    // MANAGE MODAL (rename / delete)
    // ==========================================

    public function openManage(string $entity): void
    {
        abort_unless(isset(self::ENTITY_CONFIG[$entity]), 404);

        $this->manageEntity = $entity;
        $this->manageModal = true;
        $this->resetManageEdit();
    }

    public function closeManage(): void
    {
        $this->manageModal = false;
        $this->manageEntity = '';
        $this->resetManageEdit();
    }

    public function startRename(int $id): void
    {
        abort_unless(isset(self::ENTITY_CONFIG[$this->manageEntity]), 404);

        $record = self::ENTITY_CONFIG[$this->manageEntity]['model']::findOrFail($id);
        $this->manageEditId = $record->id;
        $this->manageEditName = $record->name;
        $this->manageEditHex = $this->manageEntity === 'color' ? ($record->hex ?? '#94A3B8') : '';
    }

    public function cancelRename(): void
    {
        $this->resetManageEdit();
    }

    public function saveRename(): void
    {
        abort_unless(isset(self::ENTITY_CONFIG[$this->manageEntity]), 404);

        $config = self::ENTITY_CONFIG[$this->manageEntity];
        $table = (new $config['model'])->getTable();

        $rules = ['manageEditName' => "required|string|max:255|unique:{$table},name,{$this->manageEditId}"];
        if ($this->manageEntity === 'color') {
            $rules['manageEditHex'] = ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'];
        }
        $this->validate($rules);

        $payload = ['name' => trim($this->manageEditName)];
        if ($this->manageEntity === 'color' && $this->manageEditHex) {
            $payload['hex'] = strtoupper($this->manageEditHex);
        }

        $record = $config['model']::findOrFail($this->manageEditId);
        $record->update($payload);

        $this->success(__('Renamed successfully.'));
        $this->resetManageEdit();
    }

    public function deleteEntity(int $id): void
    {
        abort_unless(isset(self::ENTITY_CONFIG[$this->manageEntity]), 404);

        $config = self::ENTITY_CONFIG[$this->manageEntity];
        $record = $config['model']::findOrFail($id);

        foreach ($config['relations'] as $relation) {
            if ($record->{$relation}()->exists()) {
                $this->error(__('":name" is in use and cannot be deleted.', ['name' => $record->name]));

                return;
            }
        }

        $record->delete();

        match ($this->manageEntity) {
            'category' => $this->categoryId === $id ? [$this->categoryId = 0, $this->brandId = 0] : null,
            'brand' => $this->brandId === $id ? $this->brandId = 0 : null,
            'color' => $this->removeColorSelection($id),
            'lab' => $this->labId === $id ? $this->labId = null : null,
        };

        $this->success(__('":name" deleted.', ['name' => $record->name]));
    }

    private function removeColorSelection(int $id): void
    {
        $this->colorIds = array_values(array_filter($this->colorIds, fn ($colorId) => (int) $colorId !== $id));

        foreach (['colorQuantities', 'colorAmounts', 'colorNotes', 'colorProofs'] as $property) {
            unset($this->{$property}[$id], $this->{$property}[(string) $id]);
        }
    }

    private function resetManageEdit(): void
    {
        $this->manageEditId = null;
        $this->manageEditName = '';
        $this->manageEditHex = '';
        $this->resetErrorBag(['manageEditName', 'manageEditHex']);
    }

    // ==========================================
    // RENDER
    // ==========================================

    public function render(): \Illuminate\View\View
    {
        $categoryOptions = MaterialCategory::orderBy('name')->get(['id', 'name']);

        $brandOptions = $this->categoryId
            ? Brand::where('material_category_id', $this->categoryId)->orderBy('name')->get(['id', 'name'])
            : Brand::orderBy('name')->get(['id', 'name']);

        $colorOptions = Color::orderBy('name')->get(['id', 'name', 'hex']);

        $labOptions = Lab::orderBy('name')->get(['id', 'name']);

        $dbUnits = RawMaterial::distinct()->whereNotNull('unit')->orderBy('unit')->pluck('unit')->toArray();
        $unitOptions = array_unique(array_merge(['gram', 'ml', 'pcs'], $dbUnits));

        $manageRecords = collect();
        $manageLabel = '';
        if ($this->manageModal && isset(self::ENTITY_CONFIG[$this->manageEntity])) {
            $config = self::ENTITY_CONFIG[$this->manageEntity];
            $manageRecords = $config['model']::withCount($config['relations'])->orderBy('name')->get();
            $manageLabel = $config['label'];
        }

        return view('livewire.admin.material.form', compact(
            'categoryOptions',
            'brandOptions',
            'colorOptions',
            'labOptions',
            'unitOptions',
            'manageRecords',
            'manageLabel',
        ));
    }
}
