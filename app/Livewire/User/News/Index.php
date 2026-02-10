<?php

namespace App\Livewire\User\News;

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

    // --- Properties Form ---
    public $title, $content, $category_id, $date_occurred;
    public $selectedTags = [];

    // --- Image Management ---
    public $photos = [];        // Upload Baru (Temporary)
    public $existingPhotos = []; // Gambar Lama (Database)

    // --- Hidden State ---
    public $status = 'draft';
    public $is_headline = false;

    // --- UI State ---
    public bool $drawer = false;        // Drawer Form (Create/Edit)
    public bool $detailDrawer = false;  // Drawer Detail (Preview)
    public bool $isEditing = false;
    public ?int $editingId = null;

    // --- Modal State ---
    public bool $deleteModal = false;
    public ?int $deleteId = null;

    public ?News $selectedNews = null;

    // --- Filters ---
    public $search = '', $filterStatus = 'all';
    public $categories = [], $allTags = [];

    public function mount()
    {
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }

    public function updated($prop)
    {
        if (in_array($prop, ['search', 'filterStatus']))
            $this->resetPage();
    }

    public function clearForm()
    {
        $this->reset([
            'title',
            'content',
            'category_id',
            'date_occurred',
            'selectedTags',
            'photos',
            'existingPhotos',
            'isEditing',
            'editingId'
        ]);
        $this->status = 'draft';
    }

    public function create()
    {
        $this->clearForm();
        $this->drawer = true;
    }

    public function edit($id)
    {
        $news = News::where('author_id', Auth::id())->find($id);

        if (!$news || $news->status === 'published') {
            $this->error('Berita tidak dapat diedit (Sudah terbit atau tidak ditemukan).');
            return;
        }

        // --- FIX: Tutup Detail Drawer saat masuk mode Edit ---
        $this->detailDrawer = false;

        $this->editingId = $news->id;
        $this->isEditing = true;

        $this->title = $news->title;
        $this->content = $news->content;
        $this->category_id = $news->category_id;
        // Format tanggal agar sesuai input type="date"
        $this->date_occurred = $news->date_occurred ? $news->date_occurred->format('Y-m-d') : null;
        $this->selectedTags = $news->tags->pluck('id')->toArray();

        // Load Gambar Lama
        $this->existingPhotos = $news->images()->orderBy('sort_order')->get();
        $this->photos = []; // Reset upload baru

        $this->drawer = true;
    }

    // --- LOGIC HAPUS GAMBAR ---

    public function removeUpload($index)
    {
        array_splice($this->photos, $index, 1);
        $this->photos = array_values($this->photos);
    }

    public function deleteExistingImage($imageId)
    {
        // Resolve Service Manual
        $service = app(NewsService::class);
        $image = NewsImage::find($imageId);

        // Security Check
        if ($image && $image->news->author_id === Auth::id()) {
            $service->deleteSingleImage($image);

            // Refresh UI
            $this->existingPhotos = NewsImage::where('news_id', $this->editingId)
                ->orderBy('sort_order')
                ->get();
            $this->success('Gambar dihapus.');
        }
    }

    public function save(NewsService $service)
    {
        // 1. Validasi
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

        // Manual validation for date_occurred if missing in Request
        if (!isset($rules['date_occurred'])) {
            $rules['date_occurred'] = ['required', 'date', 'before_or_equal:today'];
        }

        $this->validate($rules, $request->messages());

        // 2. Cek Limit Gambar
        $totalImages = ($this->isEditing ? count($this->existingPhotos) : 0) + count($this->photos);
        if ($totalImages > 5) {
            $this->addError('photos', "Maksimal 5 gambar total. Saat ini: $totalImages");
            return;
        }

        try {
            $data = [
                'title' => $this->title,
                'content' => $this->content,
                'category_id' => $this->category_id,
                'date_occurred' => $this->date_occurred,
                'status' => 'draft', // User selalu draft
                'is_headline' => false,
            ];

            if ($this->isEditing) {
                $news = News::find($this->editingId);
                $service->update($news, $data, $this->photos, $this->selectedTags);
                $this->success('Revisi tersimpan (Menunggu Persetujuan).');
            } else {
                $service->create(Auth::user(), $data, $this->photos, $this->selectedTags);
                $this->success('Berita dibuat (Menunggu Persetujuan).');
            }

            $this->drawer = false;
            $this->clearForm();

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    public function showDetail($id)
    {
        $this->selectedNews = News::where('author_id', Auth::id())
            ->with(['category', 'tags', 'images'])
            ->find($id);
        $this->detailDrawer = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function delete(NewsService $service)
    {
        if ($this->deleteId) {
            $news = News::where('author_id', Auth::id())->find($this->deleteId);
            if ($news && $news->status !== 'published') {
                $service->delete($news);
                $this->success('Draft dihapus.');
            }
        }
        $this->deleteModal = false;
    }

    public function render(NewsService $service)
    {
        $newsList = $service->getNews(Auth::user(), ['search' => $this->search, 'status' => $this->filterStatus], 10);
        return view('livewire.user.news.index', ['newsList' => $newsList]);
    }
}
