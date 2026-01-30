<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Logbook;
use App\Models\InternshipPeriod;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Layout('layouts.app.layout')]
class Dashboard extends Component
{
    use WithFileUploads;

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

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        // Default to current month
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterStatus']);
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
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

    public function render()
    {
        $user = Auth::user();
        $currentMonth = Carbon::parse($this->startDate); // Use start date for month display logic if needed
        
        // Fetch stats (Always for the current month context or distinct logic? 
        // Request says "Rekap Logbook per {this month}". 
        // Let's keep stats bound to the Filter Date Range to make it dynamic or stick to "This Month"?
        // The image "Rekap Logbook per {Month}" suggests it might be independent or follow filter.
        // Let's make stats follow the selected range for better utility, or strictly current month.
        // I will keep stats for the SELECTED range to match the context of data shown.)
        
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
             // Usually stats overlay respects the viewed data.
             $statsQuery = clone $query;
             $statsRaw = $statsQuery->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');
             
             $stats['pending'] = $statsRaw['pending'] ?? 0;
             $stats['validated'] = $statsRaw['validated'] ?? 0;
             $stats['rejected'] = $statsRaw['rejected'] ?? 0;

             // Apply Status Filter to main list ONLY (so stats show distribution of ALL logs in that range)
             if ($this->filterStatus !== 'all') {
                 $query->where('status', $this->filterStatus);
             }
             
             $logbooks = $query->orderBy('date', 'desc')->get();
        }

        return view('livewire.mahasiswa.dashboard', [
            'stats' => $stats,
            'logbooks' => $logbooks,
            'monthName' => $currentMonth->translatedFormat('F Y'), // Display month of start date
        ]);
    }
}
