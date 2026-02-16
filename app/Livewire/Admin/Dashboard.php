<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Project;
use App\Models\Jobdesk;
use App\Models\Attendance;
use App\Models\Announcement;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. STATISTIK UTAMA
        $stats = [
            'total_staff' => User::where('role', 'staff')->count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'attendance_today' => Attendance::whereDate('created_at', today())->count(),
            'pending_revisions' => Jobdesk::where('status', 'revision')->count(),
        ];

        // 2. URGENT DEADLINES (Jobdesk yang belum selesai & deadline < 7 hari)
        $urgentTasks = Jobdesk::with(['project', 'assignee'])
            ->whereIn('status', ['pending', 'on_progress', 'revision'])
            ->where('deadline_task', '<=', now()->addDays(7))
            ->orderBy('deadline_task', 'asc')
            ->take(5)
            ->get();

        // 3. USER TERBARU
        $recentUsers = User::latest()->take(5)->get();

        // 4. PENGUMUMAN TERBARU
        $recentAnnouncements = Announcement::with('creator')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'urgentTasks' => $urgentTasks,
            'recentUsers' => $recentUsers,
            'recentAnnouncements' => $recentAnnouncements,
        ]);
    }
}
