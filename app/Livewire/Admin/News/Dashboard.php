<?php

namespace App\Livewire\Admin\News;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\Category;
use App\Models\Tag;
use App\Services\News\NewsService;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    use WithFileUploads, WithPagination, Toast;

    // --- Properties Form (Create/Edit) ---
    #[Validate('required|min:5')]
    public $title;

    #[Validate('required|min:20')]
    public $content;

    #[Validate('required|exists:categories,id')]
    public $category_id;

    #[Validate('required|in:draft,published,archived')]
    public $status = 'draft';

    public $is_headline = false;
    public $selectedTags = []; // Array ID Tags

    #[Validate('nullable|image|max:2048')]
    public $thumbnail;

    // --- State UI ---
    public bool $drawer = false; // Untuk Create/Edit
    public bool $isEditing = false;
    public ?int $editingId = null;

    public bool $deleteModal = false;
    public ?int $deleteId = null;

    public bool $detailDrawer = false;
    public ?News $selectedNews = null;

    // --- Filters ---
    public $search = '';
    public $filterCategory = '';
    public $filterStatus = 'all';

    // --- Data Referensi (Dropdowns) ---
    public $categories = [];
    public $allTags = [];

    public function mount()
    {
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }

    // Reset pagination saat filter berubah
    public function updated($prop)
    {
        if (in_array($prop, ['search', 'filterCategory', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    public function clearForm()
    {
        $this->reset(['title', 'content', 'category_id', 'status', 'is_headline', 'selectedTags', 'thumbnail', 'isEditing', 'editingId']);
        $this->status = 'draft'; // Default
    }

    public function create()
    {
        $this->clearForm();
        $this->drawer = true;
    }

    public function edit($id)
    {
        $this->clearForm();
        $news = News::with(['tags', 'thumbnail'])->find($id);

        if ($news) {
            $this->editingId = $news->id;
            $this->isEditing = true;
            $this->title = $news->title;
            $this->content = $news->content;
            $this->category_id = $news->category_id;
            $this->status = $news->status;
            $this->is_headline = $news->is_headline;
            $this->selectedTags = $news->tags->pluck('id')->toArray();

            $this->drawer = true;
        }
    }

    public function save(NewsService $service)
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'content' => $this->content,
                'category_id' => $this->category_id,
                'status' => $this->status,
                'is_headline' => $this->is_headline,
            ];

            if ($this->isEditing) {
                $news = News::find($this->editingId);
                $service->update($news, $data, $this->thumbnail, $this->selectedTags);
                $this->success('Berita berhasil diperbarui.');
            } else {
                $service->create(Auth::user(), $data, $this->thumbnail, $this->selectedTags);
                $this->success('Berita berhasil dibuat.');
            }

            $this->drawer = false;
            $this->clearForm();
            $this->dispatch('news-saved'); // Optional event

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function delete(NewsService $service)
    {
        if ($this->deleteId) {
            $news = News::find($this->deleteId);
            if ($news) {
                $service->delete($news);
                $this->success('Berita dihapus.');
            }
        }
        $this->deleteModal = false;
        $this->deleteId = null;
    }

    public function showDetail($id)
    {
        $this->selectedNews = News::with(['category', 'author', 'tags', 'thumbnail'])->find($id);
        $this->detailDrawer = true;
    }

    public function render(NewsService $service)
    {
        $filters = [
            'search' => $this->search,
            'category_id' => $this->filterCategory,
            'status' => $this->filterStatus
        ];

        $newsList = $service->getNews(Auth::user(), $filters, 10);
        $stats = $service->getStats(Auth::user());

        return view('livewire.admin.news.index', [
            'newsList' => $newsList,
            'stats' => $stats
        ]);
    }
}
