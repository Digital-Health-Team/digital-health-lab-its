<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\ServiceBooking;
use App\Models\OpenSourceProject;
use App\Models\RawMaterial;

class Dashboard extends Component
{
    public function render()
    {
        // 1. STATISTIK UTAMA (Quick Metrics)
        $stats = [
            'pending_orders' => ServiceBooking::where('current_status', 'pending')->count(),
            'processing_orders' => ServiceBooking::whereIn('current_status', ['processing', 'printing', 'finishing'])->count(),
            'pending_projects' => OpenSourceProject::where('status', 'pending')->count(),
            'low_stock_materials' => RawMaterial::where('current_stock', '<=', 100)->count(), // Alert jika stok <= 100
        ];

        // 2. ORDERAN AKTIF (Prioritas Utama)
        // Mengambil pesanan yang masih pending (butuh kalkulasi harga) atau sedang diproses
        $activeOrders = ServiceBooking::with(['transaction.user', 'service'])
            ->whereIn('current_status', ['pending', 'processing', 'printing'])
            ->orderByRaw("FIELD(current_status, 'pending', 'processing', 'printing') ASC") // Urutkan pending paling atas
            ->latest()
            ->take(6)
            ->get();

        // 3. ALERT STOK BAHAN MENTAH
        $lowStockItems = RawMaterial::where('current_stock', '<=', 100)
            ->orderBy('current_stock', 'asc')
            ->take(5)
            ->get();

        // 4. KARYA MENUNGGU MODERASI
        $pendingProjects = OpenSourceProject::with('user.profile')
            ->where('status', 'pending')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'activeOrders' => $activeOrders,
            'lowStockItems' => $lowStockItems,
            'pendingProjects' => $pendingProjects,
        ]);
    }
}
