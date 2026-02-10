<?php

namespace App\Livewire\Admin\News\Approval;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;
use Mary\Traits\Toast;

#[Layout('layouts.app.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    // --- UI State ---
    public bool $detailDrawer = false;
    public ?News $selectedNews = null;

    // --- Modal Confirm & Form State ---
    public bool $approveModal = false;
    public bool $rejectModal = false;
    public ?int $targetId = null;

    // Properti untuk setting saat approval
    public bool $setHeadline = false;
    public bool $setBreaking = false;

    // --- Filter ---
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Tampilkan Detail Berita di Drawer
    public function showDetail($id)
    {
        // Eager load images menggantikan thumbnail
        $this->selectedNews = News::with(['author', 'category', 'tags', 'images'])->find($id);
        $this->detailDrawer = true;
    }

    // Buka Modal Approve
    public function confirmApprove($id)
    {
        $this->targetId = $id;

        // Ambil default value dari apa yang diinput user (kontributor)
        $news = News::find($id);
        if ($news) {
            $this->setHeadline = (bool) $news->is_headline;
            $this->setBreaking = (bool) $news->is_breaking;
        }

        $this->approveModal = true;
    }

    // Eksekusi Approve (Publish)
    public function approve()
    {
        if ($this->targetId) {
            $news = News::find($this->targetId);
            if ($news) {
                $news->update([
                    'status' => 'published',
                    'published_at' => now(),
                    // Update flags sesuai settingan admin di modal
                    'is_headline' => $this->setHeadline,
                    'is_breaking' => $this->setBreaking,
                ]);

                $this->success('Berita diterbitkan!', 'Status berhasil diubah menjadi Published.');
            }
        }
        $this->resetUI();
    }

    // Buka Modal Reject
    public function confirmReject($id)
    {
        $this->targetId = $id;
        $this->rejectModal = true;
    }

    // Eksekusi Reject
    public function reject()
    {
        if ($this->targetId) {
            $news = News::find($this->targetId);
            if ($news) {
                $news->update(['status' => 'archived']);
                $this->error('Berita ditolak.', 'Status diubah menjadi Archived.');
            }
        }
        $this->resetUI();
    }

    private function resetUI()
    {
        $this->approveModal = false;
        $this->rejectModal = false;
        $this->detailDrawer = false;
        $this->targetId = null;
        $this->selectedNews = null;
        $this->setHeadline = false;
        $this->setBreaking = false;
    }

    public function render()
    {
        $drafts = News::with(['author', 'category', 'images'])
            ->where('status', 'draft')
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('livewire.admin.news.approval.index', [
            'drafts' => $drafts
        ]);
    }
}
