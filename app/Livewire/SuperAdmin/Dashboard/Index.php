<?php

namespace App\Livewire\SuperAdmin\Dashboard;

use App\Models\OpenSourceProject;
use App\Models\RawMaterial;
use App\Models\ServiceBooking;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $stats = [
            'total_users' => User::count(),
            'pending_orders' => ServiceBooking::where('current_status', 'negotiating')->count(),
            'active_orders' => ServiceBooking::whereIn('current_status', ['in_progress', 'printing', 'finishing'])->count(),
            'pending_projects' => OpenSourceProject::where('status', 'pending')->count(),
            'low_stock' => RawMaterial::whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM item_stocks WHERE item_stocks.raw_material_id = raw_materials.id) <= 100')->count(),
            'total_revenue' => Transaction::where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recentOrders = ServiceBooking::with(['transaction.user', 'service'])
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

        $lowStockItems = RawMaterial::with(['brand'])
            ->withSum('stocks as total_stock', 'quantity')
            ->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM item_stocks WHERE item_stocks.raw_material_id = raw_materials.id) <= 100')
            ->orderByRaw('(SELECT COALESCE(SUM(quantity), 0) FROM item_stocks WHERE item_stocks.raw_material_id = raw_materials.id) ASC')
            ->take(5)
            ->get();

        return view('livewire.super-admin.dashboard.index', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'pendingProjects' => $pendingProjects,
            'lowStockItems' => $lowStockItems,
        ]);
    }
}
