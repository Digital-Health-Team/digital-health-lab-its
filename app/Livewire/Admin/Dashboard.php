<?php

namespace App\Livewire\Admin;

use App\Models\OpenSourceProject;
use App\Models\RawMaterial;
use App\Models\ServiceBooking;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'pending_orders' => ServiceBooking::where('current_status', 'negotiating')->count(),
            'active_orders' => ServiceBooking::whereIn('current_status', ['in_progress', 'printing', 'finishing'])->count(),
            'pending_projects' => OpenSourceProject::where('status', 'pending')->count(),
            'low_stock' => RawMaterial::where('current_stock', '<=', 100)->count(),
        ];

        $activeOrders = ServiceBooking::with(['transaction.user', 'service'])
            ->whereIn('current_status', ['negotiating', 'in_progress', 'printing'])
            ->orderByRaw("FIELD(current_status, 'negotiating', 'in_progress', 'printing') ASC")
            ->latest()
            ->take(6)
            ->get();

        $pendingProjects = OpenSourceProject::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'activeOrders' => $activeOrders,
            'pendingProjects' => $pendingProjects,
        ]);
    }
}
