<?php

namespace App\Livewire\Admin\RawMaterial;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\RawMaterial;
use App\DTOs\RawMaterial\RawMaterialData;
use App\Actions\RawMaterial\CreateRawMaterialAction;
use App\Actions\RawMaterial\UpdateRawMaterialAction;
use App\Actions\RawMaterial\DeleteRawMaterialAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    #[Url(history: true)] public string $search = '';

    // --- UI STATES ---
    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;

    public ?int $editingId = null;
    public ?int $deleteId = null;

    // --- FORM DATA ---
    public string $name = '';
    public string $category = '';
    public string $unit = '';
    public int $current_stock = 0;

    // --- MASTER LISTS ---
    public array $categories = [
        ['id' => 'filament', 'name' => 'Filament'],
        ['id' => 'resin', 'name' => 'Resin'],
        ['id' => 'silicon', 'name' => 'Silicon'],
        ['id' => 'powder', 'name' => 'Powder'],
    ];

    public array $units = [
        ['id' => 'gram', 'name' => 'Gram (g)'],
        ['id' => 'ml', 'name' => 'Milliliter (ml)'],
        ['id' => 'pcs', 'name' => 'Pieces (pcs)'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'category', 'unit', 'current_stock', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(RawMaterial $material)
    {
        $this->editingId = $material->id;
        $this->name = $material->name;
        $this->category = $material->category;
        $this->unit = $material->unit;
        $this->current_stock = $material->current_stock;
        $this->drawerOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
            'current_stock' => 'required|integer|min:0',
        ]);

        $dto = new RawMaterialData($this->name, $this->category, $this->unit, (int) $this->current_stock);

        if ($this->editingId) {
            app(UpdateRawMaterialAction::class)->execute(RawMaterial::find($this->editingId), $dto);
            $this->success(__('Material updated successfully.'));
        } else {
            app(CreateRawMaterialAction::class)->execute($dto);
            $this->success(__('Material created successfully.'));
        }

        $this->drawerOpen = false;
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        try {
            app(DeleteRawMaterialAction::class)->execute(RawMaterial::find($this->deleteId));
            $this->success(__('Material deleted successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $materials = RawMaterial::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.admin.raw-material.index', compact('materials'));
    }
}
