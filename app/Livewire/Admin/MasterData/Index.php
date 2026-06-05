<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Lab;
use App\Models\MaterialCategory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

/**
 * Centralized CRUD for master lookup tables (labs, categories, brands, colors).
 * Uses dynamic model resolution so CRUD logic is written once, not 4 times.
 *
 * @property-read class-string<Model> $modelClass
 */
class Index extends Component
{
    use Toast, WithPagination;

    /** @var array<string, array{model: class-string<Model>, label: string, icon: string, relations: list<string>}> */
    private const TAB_CONFIG = [
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

    #[Url(history: true)]
    public string $activeTab = 'labs';

    public string $search = '';

    // --- Form State ---
    public bool $formModal = false;

    public bool $deleteModal = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    public string $name = '';

    public function updatedActiveTab(): void
    {
        $this->reset(['search', 'editingId', 'name', 'formModal']);
        $this->resetPage();
    }

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
        $this->formModal = true;
    }

    public function edit(int $id): void
    {
        $config = $this->getTabConfig();
        $record = $config['model']::findOrFail($id);

        $this->editingId = $record->id;
        $this->name = $record->name;
        $this->formModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $config = $this->getTabConfig();

        if ($this->editingId) {
            $record = $config['model']::findOrFail($this->editingId);
            $record->update(['name' => $this->name]);
            $this->success(__(':label updated.', ['label' => $config['label']]));
        } else {
            $config['model']::create(['name' => $this->name]);
            $this->success(__(':label created.', ['label' => $config['label']]));
        }

        $this->formModal = false;
        $this->reset(['editingId', 'name']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    /**
     * Safe delete: checks all FK relationships before removing.
     * Shows a graceful toast if the record is still in use.
     */
    public function deleteRecord(): void
    {
        $config = $this->getTabConfig();
        $record = $config['model']::findOrFail($this->deleteId);

        // Check referential integrity across all known relations
        foreach ($config['relations'] as $relation) {
            if ($record->{$relation}()->exists()) {
                $this->error(__('Cannot delete ":name" — it is still used by :relation.', [
                    'name' => $record->name,
                    'relation' => str_replace('_', ' ', $relation),
                ]));
                $this->deleteModal = false;

                return;
            }
        }

        $record->delete();
        $this->success(__('Record deleted.'));
        $this->deleteModal = false;
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /**
     * @return array{model: class-string<Model>, label: string, icon: string, relations: list<string>}
     */
    private function getTabConfig(): array
    {
        return self::TAB_CONFIG[$this->activeTab];
    }

    /**
     * @return array<string, array{model: class-string<Model>, label: string, icon: string, relations: list<string>}>
     */
    public function getTabs(): array
    {
        return self::TAB_CONFIG;
    }

    public function render()
    {
        $config = $this->getTabConfig();

        $query = $config['model']::query()
            ->withCount($config['relations'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name');

        $records = $query->paginate(15);

        return view('livewire.admin.master-data.index', [
            'records' => $records,
            'tabConfig' => $config,
            'tabs' => self::TAB_CONFIG,
        ]);
    }
}
