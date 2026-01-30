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


#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    use WithFileUploads, WithPagination;

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

    public function save()
    {
        $this->validate();

        $student = Auth::user();
        // Assuming strictly one active internship per student for now, or pick the first active one
        $period = InternshipPeriod::where('student_id', $student->id)
                                  ->where('status', 'active')
                                  ->first();

        if (!$period) {
            $this->addError('activity', 'No active internship period found.');
            return;
        }

        // Handle file upload (mock for now because storage link might not be set up, but implementing code)
        $path = null;
        if ($this->proof) {
            $path = $this->proof->store('logbooks', 'public');
        }

        Logbook::create([
            'internship_period_id' => $period->id,
            'date' => $this->date,
            'activity' => $this->activity,
            'proof_file_path' => $path,
            'status' => 'pending',
        ]);

        $this->reset(['activity', 'proof']);
        $this->dispatch('logbook-saved');
        session()->flash('success', 'Logbook saved successfully!');
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

    public function delete()
    {
        if ($this->logbookIdToDelete) {
            $logbook = Logbook::find($this->logbookIdToDelete);

            if ($logbook) {
                 // Optional: Check if student owns this logbook
                 // $period = InternshipPeriod::where('student_id', Auth::id())->first();
                 // if($logbook->internship_period_id !== $period->id) { abort(403); }

                $logbook->delete();
                session()->flash('success', 'Logbook deleted successfully!');
            }
        }
        
        $this->reset(['deleteModal', 'logbookIdToDelete']);
    }

    public function render()
    {
        $user = Auth::user();
        $currentMonth = Carbon::parse($this->startDate); // Use start date for month display logic if needed
        
        // Fetch stats
        $stats = [
            'pending' => 0,
            'validated' => 0,
            'rejected' => 0,
        ];
        
        $logbooks = collect(); 

        $period = InternshipPeriod::where('student_id', $user->id)->first();
        if ($period) {
             $query = Logbook::where('internship_period_id', $period->id);

             // Apply Date Range
             if($this->startDate && $this->endDate){
                 $query->whereBetween('date', [$this->startDate, $this->endDate]);
             }

             // Apply Search
             if($this->search){
                 $query->where('activity', 'like', '%'.$this->search.'%');
             }

             // Clone query for stats (respecting date range and search, or maybe just date range?)
             $statsQuery = clone $query;
             $statsRaw = $statsQuery->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');
             
             $stats['pending'] = $statsRaw['pending'] ?? 0;
             $stats['validated'] = $statsRaw['validated'] ?? 0;
             $stats['rejected'] = $statsRaw['rejected'] ?? 0;

             // Apply Status Filter to main list ONLY
             if ($this->filterStatus !== 'all') {
                 $query->where('status', $this->filterStatus);
             }
             
             $logbooks = $query->orderBy('date', 'desc')->paginate(10);
        }

        return view('livewire.mahasiswa.dashboard', [
            'stats' => $stats,
            'logbooks' => $logbooks,
            'monthName' => $currentMonth->translatedFormat('F Y'), // Display month of start date
        ]);
    }
}
