<?php

namespace App\Livewire\Admin\Project\BatchTask;

use App\Models\Jobdesk;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Roadmap Builder')]
class Index extends Component
{
    use Toast;

    public Project $project;

    // Data Master & Search
    public array $staffList = [];
    public string $staffSearch = '';

    // Struktur Data: [ staff_id => [ task1, task2, ... ] ]
    public array $staffTasks = [];
    public ?int $selectedStaffId = null;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->loadStaff(); // Load awal staff
    }

    // Method untuk meload staff sesuai pencarian
    public function loadStaff()
    {
        $this->staffList = User::where('role', 'staff')
            ->when($this->staffSearch, fn($q) => $q->where('name', 'like', "%{$this->staffSearch}%"))
            ->orderBy('name')
            ->take(20) // Batasi limit agar dropdown tidak berat
            ->get()
            ->toArray();
    }

    public function searchStaff(string $value = '')
    {
        $this->staffSearch = $value;
        $this->loadStaff();
    }

    // 1. Tambahkan Staff ke dalam Roadmap (Kolom Kanban Baru)
    public function addStaffSection()
    {
        $this->validate([
            'selectedStaffId' => 'required|exists:users,id'
        ], [
            'selectedStaffId.required' => 'Please select a staff member first.'
        ]);

        // Cek jika staff belum ada di dalam list roadmap
        if (!isset($this->staffTasks[$this->selectedStaffId])) {
            $this->staffTasks[$this->selectedStaffId] = [
                $this->getEmptyTask() // Berikan 1 tugas kosong default
            ];
        } else {
            $this->warning('This staff is already in the roadmap. You can add tasks inside their section.');
        }

        $this->selectedStaffId = null; // Reset dropdown
        $this->staffSearch = ''; // Reset pencarian
        $this->loadStaff();
    }

    // Template Tugas Kosong
    public function getEmptyTask()
    {
        return [
            'title' => ['id' => '', 'en' => ''],
            'description' => ['id' => '', 'en' => ''],
            'deadline_task' => ''
        ];
    }

    // 2. Tambah Task untuk Staff Tertentu
    public function addTaskForStaff($staffId)
    {
        $this->staffTasks[$staffId][] = $this->getEmptyTask();
    }

    // 3. Hapus Task dari Staff Tertentu
    public function removeTaskForStaff($staffId, $taskIndex)
    {
        unset($this->staffTasks[$staffId][$taskIndex]);
        $this->staffTasks[$staffId] = array_values($this->staffTasks[$staffId]); // Re-index array

        // Jika tidak ada task tersisa, biarkan kolom kosong, atau bisa dikondisikan:
        // if (empty($this->staffTasks[$staffId])) { unset($this->staffTasks[$staffId]); }
    }

    // 4. Hapus Kolom Staff secara keseluruhan
    public function removeStaffSection($staffId)
    {
        unset($this->staffTasks[$staffId]);
    }

    // 5. Submit Semua ke Database
    public function saveRoadmap()
    {
        // Validasi
        $this->validate([
            'staffTasks' => 'required|array|min:1',
            'staffTasks.*.*.title.id' => 'required|string',
            'staffTasks.*.*.deadline_task' => 'nullable|date',
        ], [
            'staffTasks.required' => 'Roadmap is empty. Please add staff and tasks.',
            'staffTasks.*.*.title.id.required' => 'Task Title is required.',
        ]);

        DB::transaction(function () {
            foreach ($this->staffTasks as $staffId => $tasks) {
                foreach ($tasks as $taskData) {

                    // Jangan simpan jika title id kosong (mencegah task kosong masuk DB)
                    if(empty($taskData['title']['id'])) continue;

                    $deadline = !empty($taskData['deadline_task']) ? $taskData['deadline_task'] : null;

                    Jobdesk::create([
                        'project_id' => $this->project->id,
                        'assigned_to' => $staffId,
                        'created_by' => auth()->id(),
                        'title' => $taskData['title'],
                        'description' => $taskData['description'],
                        'deadline_task' => $deadline,
                        'status' => 'pending',
                    ]);
                }
            }
        });

        // Hapus local storage via dispatch event ke frontend
        $this->dispatch('clear-roadmap-storage', url: route('admin.projects.show', $this->project->id));
    }

    public function render()
    {
        return view('livewire.admin.project.batch-task.index');
    }
}
