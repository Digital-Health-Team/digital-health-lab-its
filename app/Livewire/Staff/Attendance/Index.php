<?php

namespace App\Livewire\Staff\Attendance;

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
        // Keamanan: Pastikan staff hanya bisa melihat absensinya sendiri
        if ($attendance->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this attendance record.');
        }

        // Load relasi yang dibutuhkan
        $this->attendance = $attendance->load([
            'reports.jobdesk.project',
            'reports.details',
            'reports.attachments',
            'attachments' // Untuk ambil foto selfie dari polymorphic
        ]);
    }

    public function render()
    {
        return view('livewire.staff.attendance.index');
    }
}
