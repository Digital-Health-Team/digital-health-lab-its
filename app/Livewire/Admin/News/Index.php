<?php

namespace App\Livewire\Admin\News;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\Category;
use App\Models\Tag;
use App\Services\News\NewsService;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;
use Illuminate\Validation\Rule;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;

#[Layout('layouts.app.layout')]
class Index extends Component
{
    use WithFileUploads, WithPagination, Toast;

    // --- Properties ---
    public $title, $content, $category_id, $date_occurred;
    public $status = 'draft';

    // Flags
    public $is_headline = false;
    public $is_breaking = false;

    public $selectedTags = [];

    // --- Image Management ---
    public $photos = [];        // Upload Baru (Temporary - Create & Edit Mode)
    public $existingPhotos = []; // Gambar Lama (Database - Edit Mode Only)

    // --- UI State ---
    public bool $drawer = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // --- Modals ---
    public bool $deleteModal = false;
    public ?int $deleteId = null;
    public bool $publishConfirmModal = false;
    public bool $detailDrawer = false;
    public ?News $selectedNews = null;

    // Filters
    public $search = '', $filterCategory = '', $filterStatus = 'all';
    public $categories = [], $allTags = [];

    public function mount()
    {
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }

    public function updated($prop)
    {
        if (in_array($prop, ['search', 'filterCategory', 'filterStatus']))
            $this->resetPage();
    }

    public function clearForm()
    {
        $this->reset([
            'title',
            'content',
            'category_id',
            'date_occurred',
            'status',
            'is_headline',
            'is_breaking',
            'selectedTags',
            'photos',
            'existingPhotos',
            'isEditing',
            'editingId',
            'publishConfirmModal',
            'deleteModal',
            'deleteId'
        ]);
        $this->status = 'draft';
    }

    public function create()
    {
        $this->clearForm();
        $this->drawer = true;
    }

    public function showDetail($id)
    {
        // Eager load semua relasi agar hemat query
        $this->selectedNews = News::with(['author', 'category', 'tags', 'images'])
            ->find($id);

        $this->detailDrawer = true;
    }

    public function edit($id)
    {
        $this->clearForm();
        $news = News::with('images')->find($id);

        if ($news) {
            $this->editingId = $news->id;
            $this->isEditing = true;
            $this->title = $news->title;
            $this->content = $news->content;
            $this->category_id = $news->category_id;
            $this->status = $news->status;
            $this->date_occurred = $news->date_occurred ? $news->date_occurred->format('Y-m-d') : null;
            $this->selectedTags = $news->tags->pluck('id')->toArray();

            // Flags
            $this->is_headline = (bool) $news->is_headline;
            $this->is_breaking = (bool) $news->is_breaking;

            // Load gambar lama dari DB
            $this->existingPhotos = $news->images()->orderBy('sort_order')->get();
            $this->photos = []; // Reset upload baru

            $this->drawer = true;
        }
    }

    // ==========================================
    // LOGIC HAPUS GAMBAR (DIPISAH)
    // ==========================================

    /**
     * 1. Hapus Preview / Upload Baru (Create Mode & Edit Mode)
     * Hanya menghapus dari array memory ($this->photos).
     * TIDAK MENGECEK DATABASE.
     */
    public function removeUpload($index)
    {
        // Hapus item dari array berdasarkan index
        array_splice($this->photos, $index, 1);
        // Reset keys array agar urut kembali (0, 1, 2...)
        $this->photos = array_values($this->photos);
    }

    /**
     * 2. Hapus Gambar Lama (Edit Mode Only)
     * Menghapus record dari Database & File dari Storage.
     */
    public function deleteExistingImage($imageId)
    {
        $service = app(NewsService::class);
        $image = NewsImage::find($imageId);

        if ($image) {
            $service->deleteSingleImage($image);

            // Refresh Collection Existing Photos dari DB
            $this->existingPhotos = NewsImage::where('news_id', $this->editingId)
                ->orderBy('sort_order')
                ->get();
            $this->success('Gambar database dihapus.');
        }
    }

    // ==========================================
    // SAVE LOGIC
    // ==========================================

    public function checkBeforeSave()
    {
        $this->validate([
            'title' => 'required|min:5',
            'category_id' => 'required',
            'date_occurred' => 'required|date'
        ]);

        if ($this->status === 'published') {
            $this->publishConfirmModal = true;
        } else {
            $this->processSave();
        }
    }

    public function processSave()
    {
        $service = app(NewsService::class);

        // Validasi Full
        if ($this->isEditing) {
            $request = new UpdateNewsRequest();
            $rules = $request->rules();
            $rules['title'] = ['required', 'min:5', Rule::unique('news', 'title')->ignore($this->editingId)];
        } else {
            $request = new StoreNewsRequest();
            $rules = $request->rules();
        }
        $rules['photos'] = ['nullable', 'array'];
        $rules['photos.*'] = ['image', 'max:1024'];

        $this->validate($rules, $request->messages());

        $totalImages = ($this->isEditing ? count($this->existingPhotos) : 0) + count($this->photos);
        if ($totalImages > 5) {
            $this->addError('photos', "Maksimal 5 gambar (Lama + Baru). Total: $totalImages");
            $this->publishConfirmModal = false;
            return;
        }

        try {
            $data = [
                'title' => $this->title,
                'content' => $this->content,
                'category_id' => $this->category_id,
                'status' => $this->status,
                'date_occurred' => $this->date_occurred,
                'is_headline' => $this->is_headline,
                'is_breaking' => $this->is_breaking,
            ];

            if ($this->isEditing) {
                $news = News::find($this->editingId);
                $service->update($news, $data, $this->photos, $this->selectedTags);
                $this->success('Berita berhasil diperbarui.');
            } else {
                $service->create(Auth::user(), $data, $this->photos, $this->selectedTags);
                $this->success('Berita berhasil dibuat.');
            }

            $this->drawer = false;
            $this->publishConfirmModal = false;
            $this->clearForm();

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DELETE NEWS LOGIC
    // ==========================================

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
                $this->success('Berita berhasil dihapus.');
            }
        }
        $this->deleteModal = false;
        $this->deleteId = null;
    }

    // ==========================================
    // RENDER
    // ==========================================

    public function render(NewsService $service)
    {
        $newsList = $service->getNews(Auth::user(), ['search' => $this->search, 'category_id' => $this->filterCategory, 'status' => $this->filterStatus], 10);
        $stats = $service->getStats(Auth::user());
        return view('livewire.admin.news.index', ['newsList' => $newsList, 'stats' => $stats]);
    }
}
