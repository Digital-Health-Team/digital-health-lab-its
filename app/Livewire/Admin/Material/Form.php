<?php

namespace App\Livewire\Admin\Material;

use App\Actions\RawMaterial\AddInitialStockAction;
use App\Actions\RawMaterial\CreateRawMaterialAction;
use App\Actions\RawMaterial\UpdateRawMaterialAction;
use App\DTOs\RawMaterial\RawMaterialData;
use App\Models\Brand;
use App\Models\Lab;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Material Form')]
class Form extends Component
{
    use Toast;

    public ?RawMaterial $material = null;

    // Item definition
    public int $categoryId = 0;

    public int $brandId = 0;

    public string $name = '';

    public string $unit = '';

    // Initial stock (create only)
    public ?int $colorId = null;

    public ?int $labId = null;

    public ?int $initialQty = null;

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
        $this->colorId = null;
    }

    public function updatedBrandId(): void
    {
        $this->colorId = null;
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
            $rules['initialQty'] = 'nullable|integer|min:1';
            $rules['colorId'] = 'nullable|integer|exists:colors,id';
            $rules['labId'] = 'nullable|integer|exists:labs,id';
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

            if ($this->initialQty > 0 && $this->colorId && $this->labId) {
                app(AddInitialStockAction::class)->execute($created, $this->colorId, $this->labId, $this->initialQty);
            }

            $this->success(__('Material created successfully.'));
        }

        $this->redirectRoute('admin.inventory', navigate: true);
    }

    // ==========================================
    // QUICK CREATE BRAND
    // ==========================================

    public function quickCreateBrand(string $name): void
    {
        $name = trim($name);

        if (blank($name)) {
            $this->error(__('Name cannot be empty.'));

            return;
        }

        if (Brand::where('name', $name)->exists()) {
            $this->error(__('":name" already exists.', ['name' => $name]));

            return;
        }

        $payload = ['name' => $name];
        if ($this->categoryId) {
            $payload['material_category_id'] = $this->categoryId;
        }

        $brand = Brand::create($payload);
        $this->brandId = $brand->id;
        $this->colorId = null;
        $this->success(__('":name" created and selected.', ['name' => $name]));
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

        $colorOptions = $this->brandId
            ? (Brand::find($this->brandId)?->colors()->orderBy('name')->get(['id', 'name']) ?? collect())
            : collect();

        $labOptions = Lab::orderBy('name')->get(['id', 'name']);

        $dbUnits = RawMaterial::distinct()->whereNotNull('unit')->orderBy('unit')->pluck('unit')->toArray();
        $unitOptions = array_unique(array_merge(['gram', 'ml', 'pcs'], $dbUnits));

        return view('livewire.admin.material.form', compact(
            'categoryOptions',
            'brandOptions',
            'colorOptions',
            'labOptions',
            'unitOptions',
        ));
    }
}
