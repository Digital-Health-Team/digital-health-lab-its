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
use App\Actions\RawMaterial\RestockMaterialAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    #[Url(history: true)] public string $search = '';

    // --- UI STATES ---
    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;
    public bool $historyDrawerOpen = false;
    public bool $showRestockForm = false; // Toggle form restock di dalam history drawer

    public ?int $editingId = null;
    public ?int $deleteId = null;
    public ?int $restockId = null;
    public ?RawMaterial $activeMaterial = null;

    // --- FORM DATA: MASTER ---
    public string $name = '';
    public string $category = '';
    public string $unit = '';
    public int $current_stock = 0;

    // --- FORM DATA: RESTOCK ---
    public ?int $restockQty = null;
    public string $restockNotes = '';

    // --- DATA VISUALISASI ---
    public int $totalIn = 0;
    public int $totalOut = 0;

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

    public function updatedSearch() { $this->resetPage(); }

    // --- CRUD MASTER ACTIONS ---
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
        $this->drawerOpen = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
        ];

        if (!$this->editingId) {
            $rules['current_stock'] = 'required|integer|min:0';
        }

        $this->validate($rules);

        if ($this->editingId) {
            $material = RawMaterial::find($this->editingId);
            $dto = new RawMaterialData($this->name, $this->category, $this->unit, $material->current_stock);
            app(UpdateRawMaterialAction::class)->execute($material, $dto);
            $this->success(__('Material updated successfully.'));
        } else {
            $dto = new RawMaterialData($this->name, $this->category, $this->unit, (int) $this->current_stock);
            app(CreateRawMaterialAction::class)->execute($dto);
            $this->success(__('Material created successfully.'));
        }

        $this->drawerOpen = false;
    }

    // --- RESTOCK ACTIONS (Dipindah ke dalam History Drawer) ---
    public function processRestock()
    {
        $this->validate([
            'restockQty' => 'required|integer|min:1',
            'restockNotes' => 'required|string|max:255',
        ]);

        try {
            $material = RawMaterial::findOrFail($this->restockId);
            app(RestockMaterialAction::class)->execute($material, $this->restockQty, $this->restockNotes);

            $this->success(__('Stock successfully added.'));

            // Sembunyikan form dan refresh data riwayat secara real-time
            $this->showRestockForm = false;
            $this->viewHistory($material);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // --- DELETE ---
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

    // --- HISTORY & VISUALIZATION ---
    public function viewHistory(RawMaterial $material)
    {
        $this->activeMaterial = $material->load(['movements' => fn($q) => $q->latest(), 'movements.creator']);
        $this->restockId = $material->id; // Set ID untuk fungsi restock

        $this->totalIn = $this->activeMaterial->movements->where('type', 'in')->sum('quantity');
        $this->totalOut = $this->activeMaterial->movements->where('type', 'out')->sum('quantity');

        // Reset form state saat membuka drawer
        $this->showRestockForm = false;
        $this->reset(['restockQty', 'restockNotes']);

        $this->historyDrawerOpen = true;
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
