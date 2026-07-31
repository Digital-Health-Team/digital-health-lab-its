<?php

namespace App\Livewire\Gudang\Orders;

use App\Actions\Warehouse\FlagBookingMaterialAction;
use App\Actions\Warehouse\VerifyBookingMaterialAction;
use App\Enums\BookingStatus;
use App\Models\ItemStock;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('layouts.app')]
#[Title('Incoming Orders')]
class Index extends Component
{
    use Toast, WithPagination;

    public string $search = '';

    public ?ServiceBooking $activeBooking = null;

    public string $stockSearch = '';

    // --- FLAG MODAL ---
    public bool $flagModal = false;

    public string $flagNote = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function viewBooking(int $id): void
    {
        $this->activeBooking = ServiceBooking::with(['user.profile', 'service', 'materialVerifier'])
            ->findOrFail($id);
        $this->stockSearch = (string) ($this->activeBooking->material_preference ?? '');
        $this->reset(['flagNote']);
    }

    public function clearBooking(): void
    {
        $this->activeBooking = null;
        $this->reset(['stockSearch', 'flagNote']);
    }

    public function verify(int $bookingId): void
    {
        $booking = ServiceBooking::findOrFail($bookingId);
        $this->authorize('verifyMaterial', $booking);

        try {
            app(VerifyBookingMaterialAction::class)->execute($booking, Auth::id());
        } catch (ValidationException $e) {
            $this->error(collect($e->errors())->flatten()->first());

            return;
        }

        $this->success(__('Materials verified. The lab team has been notified.'));

        if ($this->activeBooking?->id === $booking->id) {
            $this->viewBooking($booking->id);
        }
    }

    public function openFlagModal(int $bookingId): void
    {
        $this->viewBooking($bookingId);
        $this->flagModal = true;
    }

    public function flag(): void
    {
        $this->validate(['flagNote' => 'required|string|max:1000']);

        $booking = ServiceBooking::findOrFail($this->activeBooking->id);
        $this->authorize('verifyMaterial', $booking);

        try {
            app(FlagBookingMaterialAction::class)->execute($booking, $this->flagNote, Auth::id());
        } catch (ValidationException $e) {
            $this->error(collect($e->errors())->flatten()->first());

            return;
        }

        $this->warning(__('Order flagged as materials unavailable. The lab team has been notified.'));
        $this->flagModal = false;
        $this->viewBooking($booking->id);
    }

    public function render()
    {
        $bookings = ServiceBooking::query()
            ->with(['user.profile', 'service'])
            ->whereIn('current_status', [
                BookingStatus::ReviewBrief->value,
                BookingStatus::CheckMaterial->value,
                BookingStatus::Pending->value,
            ])
            ->when($this->search, function ($q) {
                $q->where(fn ($q2) => $q2
                    ->where('id', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn ($q3) => $q3->where('name', 'like', "%{$this->search}%")));
            })
            ->latest()
            ->paginate(10);

        $stocks = collect();
        if ($this->activeBooking) {
            $stocks = ItemStock::query()
                ->with(['rawMaterial.brand', 'color', 'lab'])
                ->when($this->stockSearch, function ($q) {
                    $q->whereHas('rawMaterial', fn ($q2) => $q2
                        ->where('name', 'like', "%{$this->stockSearch}%")
                        ->orWhereHas('brand', fn ($q3) => $q3->where('name', 'like', "%{$this->stockSearch}%")));
                })
                ->orderByDesc('quantity')
                ->take(20)
                ->get();
        }

        return view('livewire.gudang.orders.index', [
            'bookings' => $bookings,
            'stocks' => $stocks,
        ]);
    }
}
