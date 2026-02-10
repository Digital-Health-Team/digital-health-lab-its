<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    use WithPagination, Toast;

    // --- Form Properties ---
    public $name;

    // --- UI State ---
    public bool $drawer = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    public bool $deleteModal = false;
    public ?int $deleteId = null;
    public int $newsCountToDelete = 0; // Info jumlah berita saat mau hapus

    // --- Filters ---
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearForm()
    {
        $this->reset(['name', 'isEditing', 'editingId']);
    }

    public function create()
    {
        $this->clearForm();
        $this->drawer = true;
    }

    public function edit($id)
    {
        $this->clearForm();
        $category = Category::find($id);

        if ($category) {
            $this->editingId = $category->id;
            $this->isEditing = true;
            $this->name = $category->name;
            $this->drawer = true;
        }
    }

    public function save(CategoryService $service)
    {
        // Validasi Nama harus unik
        $this->validate([
            'name' => [
                'required',
                'min:3',
                Rule::unique('categories', 'name')->ignore($this->editingId)
            ]
        ]);

        try {
            $data = ['name' => $this->name];

            if ($this->isEditing) {
                $category = Category::find($this->editingId);
                $service->update($category, $data);
                $this->success('Kategori berhasil diperbarui.');
            } else {
                $service->create($data);
                $this->success('Kategori baru ditambahkan.');
            }

            $this->drawer = false;
            $this->clearForm();

        } catch (\Exception $e) {
            $this->error('Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;

        // Cek jumlah berita sebelum hapus untuk warning
        $category = Category::withCount('news')->find($id);
        $this->newsCountToDelete = $category ? $category->news_count : 0;

        $this->deleteModal = true;
    }

    public function delete(CategoryService $service)
    {
        if ($this->deleteId) {
            $category = Category::find($this->deleteId);
            if ($category) {
                $service->delete($category);
                $this->success('Kategori berhasil dihapus.');
            }
        }
        $this->deleteModal = false;
        $this->deleteId = null;
    }

    public function render(CategoryService $service)
    {
        $categories = $service->getCategories(['search' => $this->search], 10);

        return view('livewire.admin.category.index', [
            'categories' => $categories
        ]);
    }
}
