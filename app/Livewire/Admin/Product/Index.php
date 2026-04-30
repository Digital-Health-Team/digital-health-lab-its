<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\Product;
use App\Models\Attachment;
use App\DTOs\Product\ProductData;
use App\Actions\Product\CreateProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Actions\Product\ToggleProductStatusAction;
use App\Actions\Product\DeleteProductAttachmentAction;
use App\Actions\Product\SetPrimaryProductAttachmentAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    #[Url(history: true)] public string $search = '';
    #[Url(history: true)] public string $filterStatus = '';
    #[Url(history: true)] public string $sortBy = 'latest';

    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;
    public bool $toggleModalOpen = false;

    public ?int $editingId = null;
    public ?int $deleteId = null;
    public ?int $toggleId = null;

    // --- FORM DATA ---
    public string $name = '';
    public ?string $description = null;
    public ?int $price_min = null;
    public ?int $price_max = null;

    public array $new_photos = []; // Menerima array multi upload
    public $existing_photos = []; // Koleksi object dari model Attachment

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_min' => 'required|numeric|min:0',
            'price_max' => 'required|numeric|gte:price_min',
            'new_photos.*' => 'image|max:3072', // Max 3MB per foto
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterStatus', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'sortBy']);
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'description', 'price_min', 'price_max', 'new_photos', 'existing_photos', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(Product $product)
    {
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price_min = $product->price_min;
        $this->price_max = $product->price_max;

        $this->new_photos = [];
        $this->existing_photos = $product->attachments()->orderBy('sort_order')->get();

        $this->drawerOpen = true;
    }

    // Menghapus foto yang baru saja dipilih tapi belum di save
    public function removeNewPhoto($index)
    {
        unset($this->new_photos[$index]);
        $this->new_photos = array_values($this->new_photos);
    }

    // Menghapus foto yang sudah ada di database
    public function removeExistingPhoto($attachmentId)
    {
        $attachment = Attachment::find($attachmentId);
        if ($attachment && $attachment->attachable_type === Product::class) {
            app(DeleteProductAttachmentAction::class)->execute($attachment);

            // Refresh data foto
            $this->existing_photos = Product::find($this->editingId)->attachments()->orderBy('sort_order')->get();
            $this->success(__('Photo deleted successfully.'));
        }
    }

    // Menjadikan foto tertentu sebagai thumbnail/utama
    public function setPrimaryPhoto($attachmentId)
    {
        $attachment = Attachment::find($attachmentId);
        if ($attachment && $attachment->attachable_type === Product::class) {
            app(SetPrimaryProductAttachmentAction::class)->execute($attachment);

            // Refresh data foto
            $this->existing_photos = Product::find($this->editingId)->attachments()->orderBy('sort_order')->get();
            $this->success(__('Thumbnail updated successfully.'));
        }
    }

    public function save()
    {
        $this->validate();

        $dto = new ProductData(
            name: $this->name,
            description: $this->description,
            price_min: (int) $this->price_min,
            price_max: (int) $this->price_max,
            creator_id: auth()->id(),
            new_photos: $this->new_photos
        );

        if ($this->editingId) {
            app(UpdateProductAction::class)->execute(Product::find($this->editingId), $dto);
            $this->success(__('Product catalog updated successfully.'));
        } else {
            app(CreateProductAction::class)->execute($dto);
            $this->success(__('Product catalog created successfully.'));
        }

        $this->drawerOpen = false;
        $this->reset(['new_photos']);
    }

    public function confirmToggle($id)
    {
        $this->toggleId = $id;
        $this->toggleModalOpen = true;
    }

    public function toggleStatus()
    {
        app(ToggleProductStatusAction::class)->execute(Product::find($this->toggleId));
        $this->success(__('Product status changed.'));
        $this->toggleModalOpen = false;
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        try {
            app(DeleteProductAction::class)->execute(Product::find($this->deleteId));
            $this->success(__('Product and all associated images deleted successfully.'));
        } catch (\Exception $e) {
            $this->error(__('Cannot delete this product because it is in use by transactions.'));
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = Product::with(['attachments' => fn($q) => $q->where('is_primary', true)]);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest('id'),
            default => $query->latest('id'),
        };

        return view('livewire.admin.product.index', [
            'products' => $query->paginate(10)
        ]);
    }
}
