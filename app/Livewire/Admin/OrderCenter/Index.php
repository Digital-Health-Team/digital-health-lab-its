<?php

namespace App\Livewire\Admin\OrderCenter;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\ServiceBooking;
use App\Models\RawMaterial;
use App\Models\Service;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\DTOs\Transaction\CreateBookingData;
use App\DTOs\Transaction\UpdateBookingData;
use App\DTOs\Transaction\SlicerCalculationData;
use App\DTOs\Transaction\ProgressUpdateData;
use App\DTOs\Transaction\MaterialMovementData;
use App\Actions\Transaction\CreateBookingAction;
use App\Actions\Transaction\UpdateBookingAction;
use App\Actions\Transaction\DeleteBookingAction;
use App\Actions\Transaction\UpdateBookingCalculationAction;
use App\Actions\Transaction\AddProgressUpdateAction;
use App\Actions\Transaction\RecordMaterialMovementAction;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, WithFileUploads, Toast;

    // --- FILTERS ---
    #[Url(history: true)] public string $search = '';
    #[Url(history: true)] public string $filterStatus = '';
    #[Url(history: true)] public string $filterService = '';
    #[Url(history: true)] public $startDate;
    #[Url(history: true)] public $endDate;

    // --- UI STATES ---
    public bool $manageDrawerOpen = false;
    public bool $crudDrawerOpen = false;
    public bool $deleteModalOpen = false;
    public string $drawerTab = 'pricing';

    public ?ServiceBooking $activeBooking = null;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    // --- FORM: CRUD INTI ---
    public bool $isNewUser = false;
    public ?int $crud_user_id = null;
    public string $newUserName = '';
    public string $newUserEmail = '';
    public string $newUserPhone = '';
    public ?int $crud_service_id = null;
    public string $crud_status = 'pending';
    public ?int $crud_final_price = null;

    // --- FORM: SLICER & PRICING ---
    public ?int $slicer_weight_grams = null;
    public ?int $slicer_print_time_minutes = null;
    public ?int $final_price = null;

    // --- FORM: PROGRESS UPDATE ---
    public string $progressStatus = '';
    public int $progressPercentage = 0;
    public string $progressNotes = '';
    public array $progressFiles = [];

    // --- FORM: MATERIAL DEDUCTION ---
    public ?int $selectedMaterialId = null;
    public ?int $deductQuantity = null;

    // Deteksi jika filter diubah, reset paginasi ke halaman 1
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterStatus', 'filterService', 'startDate', 'endDate'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterService', 'startDate', 'endDate']);
        $this->resetPage();
    }

    // ==========================================
    // 1. CORE CRUD METHODS
    // ==========================================
    public function createOrder()
    {
        $this->reset(['crud_user_id', 'crud_service_id', 'crud_status', 'crud_final_price', 'editingId', 'isNewUser', 'newUserName', 'newUserEmail', 'newUserPhone']);
        $this->crud_status = 'pending';
        $this->crudDrawerOpen = true;
    }

    public function editOrder(ServiceBooking $booking)
    {
        $this->editingId = $booking->id;
        $this->crud_user_id = $booking->user_id;
        $this->crud_service_id = $booking->service_id;
        $this->crud_status = $booking->current_status;
        $this->crud_final_price = $booking->agreed_price;
        $this->isNewUser = false;
        $this->crudDrawerOpen = true;
    }

    public function saveCoreOrder()
    {
        if ($this->editingId) {
            $this->validate([
                'crud_service_id' => 'required|exists:services,id',
                'crud_status' => 'required|string',
                'crud_final_price' => 'nullable|integer|min:0',
            ]);

            $dto = new UpdateBookingData($this->crud_service_id, $this->crud_status, $this->crud_final_price);
            app(UpdateBookingAction::class)->execute(ServiceBooking::find($this->editingId), $dto);
            $this->success(__('Order data updated.'));
        } else {
            $userIdToUse = null;

            if ($this->isNewUser) {
                $this->validate([
                    'newUserName' => 'required|string|max:255',
                    'newUserEmail' => 'required|email|unique:users,email',
                    'newUserPhone' => 'required|string|max:20',
                    'crud_service_id' => 'required|exists:services,id',
                    'crud_status' => 'required|string',
                ]);

                $role = Role::where('name', 'user_publik')->first();
                $user = User::create([
                    'name' => $this->newUserName,
                    'email' => $this->newUserEmail,
                    'password' => Hash::make('password123'),
                    'role_id' => $role->id,
                ]);

                $user->profile()->create([
                    'full_name' => $this->newUserName,
                    'phone' => $this->newUserPhone,
                ]);

                $userIdToUse = $user->id;
                $this->success(__('New customer account created. Default password: password123'));
            } else {
                $this->validate([
                    'crud_user_id' => 'required|exists:users,id',
                    'crud_service_id' => 'required|exists:services,id',
                    'crud_status' => 'required|string',
                ]);
                $userIdToUse = $this->crud_user_id;
            }

            $dto = new CreateBookingData($userIdToUse, $this->crud_service_id, $this->crud_status);
            app(CreateBookingAction::class)->execute($dto);
            $this->success(__('New order created successfully.'));
        }

        $this->crudDrawerOpen = false;
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id;
        $this->deleteModalOpen = true;
    }

    public function deleteRecord()
    {
        try {
            app(DeleteBookingAction::class)->execute(ServiceBooking::find($this->deleteId));
            $this->success(__('Order deleted securely.'));
        } catch (\Exception $e) {
            $this->error(__('Failed to delete order.'));
        }
        $this->deleteModalOpen = false;
    }

    // ==========================================
    // 2. OPERATIONAL METHODS (MANAGE)
    // ==========================================
    public function manageOrder(ServiceBooking $booking)
    {
        $this->activeBooking = $booking->load([
            'transaction',
            'user.profile',
            'service',
            'progressUpdates.attachments',
            'materialMovements.material'
        ]);

        $this->slicer_weight_grams = $booking->slicer_weight_grams;
        $this->slicer_print_time_minutes = $booking->slicer_print_time_minutes;
        $this->final_price = $booking->agreed_price;

        $this->reset(['progressNotes', 'progressFiles', 'selectedMaterialId', 'deductQuantity']);

        $this->drawerTab = 'pricing';
        if ($booking->agreed_price > 0 && in_array($booking->current_status, ['in_progress', 'printing', 'finishing'])) {
            $this->drawerTab = 'timeline';
        }

        $lastProgress = $this->activeBooking->progressUpdates->sortByDesc('created_at')->first();
        $this->progressStatus = $lastProgress->status_label ?? 'slicing';
        $this->progressPercentage = $lastProgress->percentage ?? 0;

        if ($booking->slicer_weight_grams) {
            $this->deductQuantity = $booking->slicer_weight_grams;
        }

        $this->manageDrawerOpen = true;
    }

    public function saveCalculation()
    {
        $this->validate([
            'slicer_weight_grams' => 'nullable|integer|min:1',
            'slicer_print_time_minutes' => 'nullable|integer|min:1',
            'final_price' => 'required|integer|min:0',
        ]);

        $this->activeBooking->update([
            'slicer_weight_grams' => $this->slicer_weight_grams,
            'slicer_print_time_minutes' => $this->slicer_print_time_minutes,
            'agreed_price' => $this->final_price,
        ]);

        if ($this->activeBooking->transaction) {
            $this->activeBooking->transaction->update([
                'total_amount' => $this->final_price
            ]);
        }

        $this->success(__('Price confirmed. Invoice is ready for the user to pay.'));
        $this->activeBooking->refresh();
    }

    public function addProgress()
    {
        $this->validate([
            'progressStatus' => 'required|string',
            'progressPercentage' => 'required|integer|min:0|max:100',
            'progressNotes' => 'required|string',
            'progressFiles.*' => 'nullable|image|max:5120'
        ]);

        $dto = new ProgressUpdateData(
            $this->activeBooking->id,
            $this->progressStatus,
            $this->progressPercentage,
            $this->progressNotes,
            $this->progressFiles
        );

        app(AddProgressUpdateAction::class)->execute($dto);

        if ($this->activeBooking->current_status === 'pending' || $this->activeBooking->current_status === 'negotiating') {
            $this->activeBooking->update(['current_status' => 'in_progress']);
        }

        $this->success(__('Production timeline updated.'));
        $this->reset(['progressNotes', 'progressFiles']);
        $this->activeBooking->refresh();
    }

    public function deductMaterial()
    {
        $this->validate([
            'selectedMaterialId' => 'required|exists:raw_materials,id',
            'deductQuantity' => 'required|integer|min:1',
        ]);

        try {
            $invoiceFallback = 'INV-' . str_pad($this->activeBooking->id, 4, '0', STR_PAD_LEFT);
            $dto = new MaterialMovementData(
                raw_material_id: $this->selectedMaterialId,
                service_booking_id: $this->activeBooking->id,
                movement_type: 'out',
                quantity: $this->deductQuantity,
                notes: 'Production deduction for Order #' . $invoiceFallback
            );

            app(RecordMaterialMovementAction::class)->execute($dto);
            $this->success(__('Material stock deducted successfully.'));
            $this->reset(['selectedMaterialId']);
            $this->activeBooking->refresh();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        // 1. REKAP KEUANGAN (KPIs)
        $revenueToday = ServiceBooking::whereNotNull('agreed_price')
            ->whereIn('current_status', ['completed', 'finishing'])
            ->whereDate('created_at', Carbon::today())
            ->sum('agreed_price');

        $revenueThisMonth = ServiceBooking::whereNotNull('agreed_price')
            ->whereIn('current_status', ['completed', 'finishing'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('agreed_price');

        $projectedRevenue = ServiceBooking::whereNotNull('agreed_price')
            ->whereIn('current_status', ['in_progress', 'printing', 'slicing', 'revising', 'negotiating'])
            ->sum('agreed_price');

        // 2. QUERY UTAMA DENGAN FILTER
        $query = ServiceBooking::with(['transaction', 'service', 'user']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($sq) => $sq->where('email', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%"));
            });
        }
        if ($this->filterStatus !== '') {
            $query->where('current_status', $this->filterStatus);
        }
        if ($this->filterService !== '') {
            $query->where('service_id', $this->filterService);
        }
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $bookings = $query->latest()->paginate(10);
        $totalFilterRevenue = $bookings->sum('agreed_price');

        return view('livewire.admin.order-center.index', [
            'bookings' => $bookings,
            'totalFilterRevenue' => $totalFilterRevenue,
            'revenueToday' => $revenueToday,
            'revenueThisMonth' => $revenueThisMonth,
            'projectedRevenue' => $projectedRevenue,
            'availableMaterials' => RawMaterial::where('current_stock', '>', 0)->get()->map(function ($m) {
                $m->display_name = "{$m->name} (Stock: {$m->current_stock} {$m->unit})";
                return $m;
            }),
            'availableServices' => Service::all(),
            'availableUsers' => User::with('profile')->whereHas('role', fn($q) => $q->whereIn('name', ['mahasiswa', 'user_publik']))->get()->map(fn($u) => ['id' => $u->id, 'name' => ($u->profile?->full_name ?? $u->name) . " ({$u->email})"]),
        ]);
    }
}