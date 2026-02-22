<?php

namespace App\Livewire\Admin\Attendance\Show;

use App\Models\Attendance;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Attendance Details')]
class Index extends Component
{
    public Attendance $attendance;

    public function mount(Attendance $attendance)
    {
        // Load semua relasi yang dibutuhkan untuk halaman detail
        $this->attendance = $attendance->load([
            'user',
            'reports.jobdesk.project',
            'reports.details',
            'reports.attachments',
            'attachments' // Untuk foto selfie
        ]);
    }

    public function render()
    {
        return view('livewire.admin.attendance.show.index');
    }
}
