<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Logbook;
use App\Models\InternshipPeriod;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


use Mary\Traits\Toast;

#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    use WithFileUploads, WithPagination, Toast;

    // Form Properties
    #[Validate('required|date')]
    public $date;

    #[Validate('required|string|min:10')]
    public $activity;

    #[Validate('nullable|image|max:2048')]
    public $proof;

    // Filter Properties
    public $search = '';
    public $startDate;
    public $endDate;
    public $filterStatus = 'all';

    // Modal States
    public bool $deleteModal = false;
    public $logbookIdToDelete = null;

    // State for editing
    public bool $editDrawer = false;
    public ?int $editingLogbookId = null;
    
    #[Validate('required|date')]
    public $editDate;

    #[Validate('required|string|min:10')]
    public $editActivity;

    #[Validate('nullable|image|max:2048')]
    public $editProof;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        // Default to current month
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'startDate', 'endDate', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterStatus']);
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    public function create(\App\Services\LogbookService $service)
    {
        $this->validateOnly('date');
        $this->validateOnly('activity');
        $this->validateOnly('proof');

        try {
            $service->create(Auth::user(), [
                'date' => $this->date,
                'activity' => $this->activity,
            ], $this->proof);

            $this->reset(['activity', 'proof']);
            $this->dispatch('logbook-saved');
            $this->success('Logbook saved successfully!');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function update(\App\Services\LogbookService $service)
    {
        $this->validateOnly('editDate');
        $this->validateOnly('editActivity');
        $this->validateOnly('editProof');

        try {
            $logbook = Logbook::find($this->editingLogbookId);
            
            if (!$logbook) {
                 $this->error('Logbook not found.');
                 return;
            }

            $service->update($logbook, [
                'date' => $this->editDate,
                'activity' => $this->editActivity,
            ], $this->editProof);
            
            $this->editDrawer = false;
            $this->reset(['editActivity', 'editProof', 'editingLogbookId', 'editDate']);
            
            $this->dispatch('logbook-updated'); // Optional event
            $this->success('Logbook updated successfully!');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function edit($id)
    {
        $logbook = Logbook::find($id);
        if ($logbook) {
            $this->editingLogbookId = $logbook->id;
            $this->editDate = $logbook->date->format('Y-m-d');
            $this->editActivity = $logbook->activity;
            $this->editDrawer = true;
        }
    }

    public function cancelEdit()
    {
        $this->editDrawer = false;
        $this->reset(['editActivity', 'editProof', 'editingLogbookId', 'editDate']);
    }

    public function confirmDelete($id)
    {
        $this->logbookIdToDelete = $id;
        $this->deleteModal = true;
    }

    // Drawer States
    public bool $detailDrawer = false;
    public ?Logbook $selectedLogbook = null;

    public function showLogbook($id)
    {
        $this->selectedLogbook = Logbook::find($id);
        $this->detailDrawer = true;
    }

    public function delete(\App\Services\LogbookService $service)
    {
        if ($this->logbookIdToDelete) {
            $logbook = Logbook::find($this->logbookIdToDelete);

            if ($logbook) {
                // Prevent ModelNotFoundException by clearing selectedLogbook if it matches
                if ($this->selectedLogbook && $this->selectedLogbook->id === $logbook->id) {
                    $this->selectedLogbook = null;
                }
                
                $service->delete($logbook);
                $this->success('Logbook deleted successfully!');
            }
        }
        
        $this->reset(['deleteModal', 'logbookIdToDelete']);
    }

    public function render(\App\Services\LogbookService $service)
    {
        $user = Auth::user();
        $currentMonth = Carbon::parse($this->startDate); 
        
        $filters = [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'search' => $this->search,
            'status' => $this->filterStatus,
        ];

        $logbooks = $service->getLogbooks($user, $filters, 10);
        $stats = $service->getStats($user, $filters);

        return view('livewire.mahasiswa.dashboard', [
            'stats' => $stats,
            'logbooks' => $logbooks,
            'monthName' => $currentMonth->translatedFormat('F Y'),
        ]);
    }
}
