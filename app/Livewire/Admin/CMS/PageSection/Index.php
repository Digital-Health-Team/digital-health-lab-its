<?php

namespace App\Livewire\Admin\CMS\PageSection;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\PageSection;
use App\DTOs\CMS\PageSectionData;
use App\Actions\CMS\PageSection\CreatePageSectionAction;
use App\Actions\CMS\PageSection\UpdatePageSectionAction;
use App\Actions\CMS\PageSection\DeletePageSectionAction;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    #[Url(history: true)] public string $search = '';
    #[Url(history: true)] public string $filterPage = '';

    public bool $drawerOpen = false;
    public bool $deleteModalOpen = false;

    public ?int $editingId = null;
    public ?int $deleteId = null;

    // --- FORM DATA ---
    public string $page_name = '';
    public string $section_key = '';
    public string $content = '';

    protected function rules()
    {
        return [
            'page_name' => 'required|string|max:255',
            'content' => 'required|string',
            'section_key' => [
                'required',
                'string',
                'max:255',
                // Mencegah duplikasi Key di dalam Page yang sama
                Rule::unique('page_sections')->where(function ($query) {
                    return $query->where('page_name', $this->page_name);
                })->ignore($this->editingId)
            ],
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterPage'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterPage']);
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['page_name', 'section_key', 'content', 'editingId']);
        $this->drawerOpen = true;
    }

    public function edit(PageSection $section)
    {
        $this->editingId = $section->id;
        $this->page_name = $section->page_name;
        $this->section_key = $section->section_key;
        $this->content = $section->content;
        $this->drawerOpen = true;
    }

    public function save()
    {
        $this->validate();

        $dto = new PageSectionData($this->page_name, $this->section_key, $this->content);

        if ($this->editingId) {
            app(UpdatePageSectionAction::class)->execute(PageSection::find($this->editingId), $dto);
            $this->success(__('Page section updated successfully.'));
        } else {
            app(CreatePageSectionAction::class)->execute($dto);
            $this->success(__('Page section created successfully.'));
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
            app(DeletePageSectionAction::class)->execute(PageSection::find($this->deleteId));
            $this->success(__('Page section deleted successfully.'));
        } catch (\Exception $e) {
            $this->error(__('Failed to delete page section.'));
        }
        $this->deleteModalOpen = false;
    }

    public function render()
    {
        $query = PageSection::with('updater');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('section_key', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterPage !== '') {
            $query->where('page_name', $this->filterPage);
        }

        // Dapatkan semua Page Name unik untuk menu filter dropdown
        $availablePages = PageSection::select('page_name')->distinct()->pluck('page_name')
            ->map(fn($page) => ['id' => $page, 'name' => ucwords(str_replace('_', ' ', $page))])
            ->toArray();

        return view('livewire.admin.cms.page-section.index', [
            'sections' => $query->latest('updated_at')->paginate(10),
            'availablePages' => $availablePages,
        ]);
    }
}
