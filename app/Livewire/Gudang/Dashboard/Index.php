<?php

namespace App\Livewire\Gudang\Dashboard;

use App\Models\Inventory;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use App\Models\Reimbursement;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $stats = [
            'low_stock' => RawMaterial::where('current_stock', '<=', 100)->count(),
            'total_materials' => RawMaterial::count(),
            'pending_reimbursements' => Reimbursement::where('status', 'pending')->count(),
            'total_inventories' => Inventory::count(),
        ];

        $lowStockItems = RawMaterial::with(['materialCategory', 'brand', 'color'])
            ->where('current_stock', '<=', 100)
            ->orderBy('current_stock', 'asc')
            ->take(8)
            ->get();

        $recentMovements = RawMaterialMovement::with(['material.brand', 'material.materialCategory', 'creator'])
            ->latest()
            ->take(8)
            ->get();

        return view('livewire.gudang.dashboard.index', [
            'stats' => $stats,
            'lowStockItems' => $lowStockItems,
            'recentMovements' => $recentMovements,
        ]);
    }
}
