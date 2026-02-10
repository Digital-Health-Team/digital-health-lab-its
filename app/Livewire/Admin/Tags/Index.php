<?php

namespace App\Livewire\Admin\Tags;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tag;
use App\Services\Tag\TagService;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

// --- IMPORT REQUEST VALIDATION ---
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;

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
    public int $usageCount = 0;

    // --- Detail State ---
    public bool $detailDrawer = false;
    public ?Tag $selectedTag = null;

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
        $tag = Tag::find($id);

        if ($tag) {
            $this->editingId = $tag->id;
            $this->isEditing = true;
            $this->name = $tag->name;
            $this->drawer = true;
        }
    }

    public function showDetail($id)
    {
        // Ambil Tag beserta relasi News-nya
        // SELECT: slug penting untuk link ke web publik
        $this->selectedTag = Tag::with([
            'news' => function ($q) {
                $q->select('news.id', 'title', 'slug', 'status', 'published_at', 'views_count', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(100); // Batasi 100 berita terakhir demi performa
            }
        ])->find($id);

        $this->detailDrawer = true;
    }

    public function save(TagService $service)
    {
        // 1. VALIDASI MENGGUNAKAN FORM REQUEST
        if ($this->isEditing) {
            // --- EDIT MODE ---
            $request = new UpdateTagRequest();
            $rules = $request->rules();

            // Override Rule Unique agar ignore ID tag yang sedang diedit
            $rules['name'] = [
                'required',
                'min:2',
                Rule::unique('tags', 'name')->ignore($this->editingId)
            ];

            $this->validate($rules, $request->messages());

        } else {
            // --- CREATE MODE ---
            $request = new StoreTagRequest();
            $this->validate($request->rules(), $request->messages());
        }

        // 2. PROSES SIMPAN
        try {
            $data = ['name' => $this->name];

            if ($this->isEditing) {
                $tag = Tag::find($this->editingId);
                $service->update($tag, $data);
                $this->success('Tag berhasil diperbarui.');
            } else {
                $service->create($data);
                $this->success('Tag baru berhasil dibuat.');
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
        $tag = Tag::withCount('news')->find($id);
        $this->usageCount = $tag ? $tag->news_count : 0;
        $this->deleteModal = true;
    }

    public function delete(TagService $service)
    {
        if ($this->deleteId) {
            $tag = Tag::find($this->deleteId);
            if ($tag) {
                $service->delete($tag);
                $this->success('Tag berhasil dihapus.');
            }
        }
        $this->deleteModal = false;
        $this->deleteId = null;
    }

    public function render(TagService $service)
    {
        $tags = $service->getTags(['search' => $this->search], 10);

        return view('livewire.admin.tag.index', [
            'tags' => $tags
        ]);
    }
}
