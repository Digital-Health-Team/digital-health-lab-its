<?php

namespace App\Livewire\Gudang\Dashboard;

use App\Models\Inventory;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use App\Models\Reimbursement;
use Livewire\Component;

class Index extends Component
{
    private const LOW_STOCK_THRESHOLD = 100;

    private static function lowStockSubquery(): string
    {
        return '(SELECT COALESCE(SUM(quantity), 0) FROM item_stocks WHERE item_stocks.raw_material_id = raw_materials.id)';
    }

    public function render()
    {
        $stats = [
            'low_stock' => RawMaterial::whereRaw(self::lowStockSubquery().' <= ?', [self::LOW_STOCK_THRESHOLD])->count(),
            'total_materials' => RawMaterial::count(),
            'pending_reimbursements' => Reimbursement::where('status', 'pending')->count(),
            'total_inventories' => Inventory::count(),
        ];

        $lowStockItems = RawMaterial::with(['brand'])
            ->withSum('stocks as total_stock', 'quantity')
            ->whereRaw(self::lowStockSubquery().' <= ?', [self::LOW_STOCK_THRESHOLD])
            ->orderByRaw(self::lowStockSubquery().' ASC')
            ->take(8)
            ->get();

        $recentMovements = RawMaterialMovement::with(['material.brand', 'creator'])
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
