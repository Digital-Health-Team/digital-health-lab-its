<?php

namespace App\Livewire\Admin\Service;

use App\Actions\Services\CreateServiceAction;
use App\Actions\Services\DeleteServiceAction;
use App\Actions\Services\UpdateServiceAction;
use App\DTOs\Service\ServiceData;
use App\Models\Service;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithPagination;

    #[Url(history: true)]
    public string $search = '';

    // --- UI STATES ---
    public bool $drawerOpen = false;

    public bool $deleteModalOpen = false;

    public ?int $editingId = null;

    public ?int $deleteId = null;

    // --- FORM DATA ---
    public string $name = '';

    public string $service_type = 'printing';

    public ?string $description = null;

    public ?int $base_price = null;

    public ?string $whatsapp_number = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'service_type', 'description', 'base_price', 'whatsapp_number', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(Service $service)
    {
        $this->editingId = $service->id;
        $this->name = $service->name;
        $this->service_type = $service->service_type;
        $this->description = $service->description;
        $this->base_price = $service->base_price;
        $this->whatsapp_number = $service->whatsapp_number;
        $this->drawerOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'service_type' => 'required|in:design,printing,scanning',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'whatsapp_number' => 'nullable|string|max:30',
        ]);

        $dto = new ServiceData($this->name, $this->description, (int) $this->base_price, $this->whatsapp_number, $this->service_type);

        if ($this->editingId) {
            app(UpdateServiceAction::class)->execute(Service::find($this->editingId), $dto);
            $this->success(__('Service updated successfully.'));
        } else {
            app(CreateServiceAction::class)->execute($dto);
            $this->success(__('Service created successfully.'));
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
            app(DeleteServiceAction::class)->execute(Service::find($this->deleteId));
            $this->success(__('Service deleted successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $services = Service::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.service.index', compact('services'));
    }
}
