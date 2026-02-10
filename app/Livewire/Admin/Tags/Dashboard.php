<?php

namespace App\Livewire\Admin\Tags;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tag;
use App\Services\Tag\TagService;
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
    public int $usageCount = 0; // Untuk warning saat hapus

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

    public function save(TagService $service)
    {
        $this->validate([
            'name' => [
                'required',
                'min:2',
                // Pastikan nama unik, kecuali untuk ID yang sedang diedit
                Rule::unique('tags', 'name')->ignore($this->editingId)
            ]
        ]);

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

        // Cek penggunaan tag ini di tabel pivot news_tags
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
