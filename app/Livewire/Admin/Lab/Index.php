<?php

namespace App\Livewire\Admin\Lab;

use App\Models\Lab;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    public string $name = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ==========================================
    // CRUD
    // ==========================================

    public function create(): void
    {
        $this->reset(['editingId', 'name']);
        $this->drawerOpen = true;
    }

    public function edit(int $id): void
    {
        $lab = Lab::findOrFail($id);
        $this->editingId = $lab->id;
        $this->name = $lab->name;
        $this->drawerOpen = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:labs,name'.($this->editingId ? ",{$this->editingId}" : ''),
        ]);

        if ($this->editingId) {
            Lab::findOrFail($this->editingId)->update(['name' => $this->name]);
            $this->success(__('Lab updated.'));
        } else {
            Lab::create(['name' => $this->name]);
            $this->success(__('Lab created.'));
        }

        $this->drawerOpen = false;
        $this->reset(['editingId', 'name']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord(): void
    {
        $lab = Lab::withCount(['rawMaterials', 'inventories'])->findOrFail($this->deleteId);

        if ($lab->raw_materials_count > 0 || $lab->inventories_count > 0) {
            $this->error(__('Cannot delete ":name" — it is still used by materials or inventories.', ['name' => $lab->name]));
            $this->deleteModalOpen = false;

            return;
        }

        $lab->delete();
        $this->success(__('Lab deleted.'));
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $labs = Lab::withCount(['rawMaterials', 'inventories'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.lab.index', compact('labs'));
    }
}
