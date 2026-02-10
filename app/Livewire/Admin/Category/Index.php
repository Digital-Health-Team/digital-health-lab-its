<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

use App\Http\Requests\News\StoreCategoryRequest;
use App\Http\Requests\News\UpdateCategoryRequest;

#[Layout('layouts.app.layout')]
class Index extends Component
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
    public int $newsCountToDelete = 0;

    // --- UI State: Detail (NEW) ---
    public bool $detailDrawer = false;
    public ?Category $selectedCategory = null;

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

    // --- Method Baru: Show Detail ---
    public function showDetail($id)
    {
        // Ambil Kategori beserta relasi News-nya
        // Kita urutkan berita terbaru dan select kolom penting saja untuk performa
        $this->selectedCategory = Category::with([
            'news' => function ($q) {
                $q->select('id', 'category_id', 'title', 'slug', 'status', 'published_at', 'views_count', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(100); // Batasi 100 berita terakhir agar tidak berat
            }
        ])->find($id);

        $this->detailDrawer = true;
    }

    public function save(CategoryService $service)
    {
        // 1. Tentukan Rules berdasarkan kondisi (Edit / Create)
        if ($this->isEditing) {
            // Instansiasi Request Update
            $request = new UpdateCategoryRequest();
            $rules = $request->rules();

            // PENTING: Override rule 'unique' untuk Livewire
            // Karena FormRequest biasanya mengambil ID dari route URL, sedangkan di Livewire ID ada di property $this->editingId
            $rules['name'] = [
                'required',
                'min:3',
                Rule::unique('categories', 'name')->ignore($this->editingId)
            ];

            // Jalankan validasi dengan custom message dari Request file
            $this->validate($rules, $request->messages());

        } else {
            // Instansiasi Request Store (Create)
            $request = new StoreCategoryRequest();

            // Jalankan validasi
            $this->validate($request->rules(), $request->messages());
        }

        // 2. Proses Simpan ke Database
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

        return view('livewire.admin.category.index', [ // Pastikan path view sesuai
            'categories' => $categories
        ]);
    }
}
