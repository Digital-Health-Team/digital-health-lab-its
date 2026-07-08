<?php

namespace App\Livewire\Admin\OrderCenter;

use App\Actions\Transaction\AddBookingPaymentAction;
use App\Actions\Transaction\AddProgressUpdateAction;
use App\Actions\Transaction\RecordMaterialMovementAction;
use App\Actions\Transaction\SendBookingMessageAction;
use App\Actions\Transaction\SetBookingPriceAction;
use App\Actions\Transaction\UpdateBookingAction;
use App\Actions\Transaction\UploadPaymentProofAction;
use App\Actions\Transaction\VerifyPaymentAction;
use App\DTOs\Transaction\BookingPaymentData;
use App\DTOs\Transaction\MaterialMovementData;
use App\DTOs\Transaction\ProgressUpdateData;
use App\DTOs\Transaction\SendMessageData;
use App\DTOs\Transaction\UpdateBookingData;
use App\Enums\BookingStatus;
use App\Models\RawMaterial;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Show extends Component
{
    use Toast, WithFileUploads;

    public ServiceBooking $booking;

    #[Url(history: true)]
    public string $activeTab = 'brief';

    // --- CHAT ---
    public string $newMessage = '';

    // --- PAYMENT TERMIN ---
    public string $terminName = '';

    public ?int $terminAmount = null;

    public bool $proofModalOpen = false;

    public ?int $proofTargetId = null;

    public $proofFile;

    // --- PROGRESS UPDATE ---
    public string $progressStatus = 'printing';

    public int $progressPercentage = 0;

    public string $progressNotes = '';

    public array $progressFiles = [];

    // --- SIDEBAR: ORDER EDIT ---
    public ?int $edit_service_id = null;

    public string $edit_status = '';

    public ?int $edit_final_price = null;

    // --- SIDEBAR: SLICER & PRICING ---
    public ?int $slicer_weight_grams = null;

    public ?int $slicer_print_time_minutes = null;

    public ?int $final_price = null;

    // --- SIDEBAR: MATERIAL DEDUCTION ---
    public ?int $selectedMaterialId = null;

    public ?int $deductQuantity = null;

    public function mount(ServiceBooking $booking): void
    {
        $this->booking = $booking;
        $this->loadBooking();
        $this->markThreadRead();

        $this->edit_service_id = $booking->service_id;
        $this->edit_status = $booking->current_status->value;
        $this->slicer_weight_grams = $booking->slicer_weight_grams;
        $this->slicer_print_time_minutes = $booking->slicer_print_time_minutes;
        $this->final_price = $booking->agreed_price;

        if ($booking->slicer_weight_grams) {
            $this->deductQuantity = $booking->slicer_weight_grams;
        }
    }

    public function getListeners(): array
    {
        return [
            "echo-private:booking.{$this->booking->id},BookingMessageSent" => 'onMessageReceived',
        ];
    }

    protected function loadBooking(): void
    {
        $this->booking->load([
            'user.profile',
            'service',
            'transaction',
            'progressUpdates.attachments',
            'messages.sender',
            'payments.verifier',
            'materialMovements.material.brand',
            'materialMovements.material.color',
        ]);
    }

    protected function markThreadRead(): void
    {
        $this->booking->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);
    }

    // ==========================================
    // CHAT
    // ==========================================
    public function onMessageReceived(): void
    {
        $this->loadBooking();
        $this->markThreadRead();
        $this->dispatch('chat-new-message');
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => 'required|string|max:5000']);

        app(SendBookingMessageAction::class)->execute(
            new SendMessageData($this->booking->id, $this->newMessage)
        );

        $this->reset('newMessage');
        $this->loadBooking();
    }

    // ==========================================
    // PAYMENT TERMINS
    // ==========================================
    public function addTermin(): void
    {
        $this->validate([
            'terminName' => 'required|string|max:255',
            'terminAmount' => 'required|integer|min:1|max:'.max(1, $this->booking->remaining_balance),
        ], [
            'terminAmount.max' => __('Amount exceeds the remaining balance.'),
        ]);

        app(AddBookingPaymentAction::class)->execute(
            new BookingPaymentData($this->booking->id, $this->terminName, (int) $this->terminAmount)
        );

        $this->success(__('Payment termin added.'));
        $this->reset(['terminName', 'terminAmount']);
        $this->loadBooking();
    }

    public function openProofModal(int $paymentId): void
    {
        $this->reset(['proofFile']);
        $this->proofTargetId = $paymentId;
        $this->proofModalOpen = true;
    }

    public function uploadProof(): void
    {
        $this->validate(['proofFile' => 'required|image|max:20480']);

        $payment = $this->booking->payments()->findOrFail($this->proofTargetId);
        app(UploadPaymentProofAction::class)->execute($payment, $this->proofFile);

        $this->success(__('Payment proof uploaded. Awaiting verification.'));
        $this->proofModalOpen = false;
        $this->reset(['proofFile', 'proofTargetId']);
        $this->loadBooking();
    }

    public function verifyPayment(int $paymentId): void
    {
        $payment = $this->booking->payments()->findOrFail($paymentId);
        app(VerifyPaymentAction::class)->execute($payment, true);

        $this->success(__('Payment verified.'));
        $this->loadBooking();
    }

    public function rejectPayment(int $paymentId): void
    {
        $payment = $this->booking->payments()->findOrFail($paymentId);
        app(VerifyPaymentAction::class)->execute($payment, false);

        $this->warning(__('Payment rejected.'));
        $this->loadBooking();
    }

    // ==========================================
    // PROGRESS UPDATES
    // ==========================================
    public function addProgress(): void
    {
        $this->validate([
            'progressStatus' => 'required|string',
            'progressPercentage' => 'required|integer|min:0|max:100',
            'progressNotes' => 'required|string',
            'progressFiles.*' => 'nullable|image|max:20480',
        ]);

        try {
            app(AddProgressUpdateAction::class)->execute(
                new ProgressUpdateData(
                    $this->booking->id,
                    $this->progressStatus,
                    $this->progressPercentage,
                    $this->progressNotes,
                    $this->progressFiles
                )
            );
        } catch (ValidationException $e) {
            $this->error(collect($e->errors())->flatten()->first());

            return;
        }

        $this->success(__('Production timeline updated. The customer has been notified.'));
        $this->reset(['progressNotes', 'progressFiles']);
        $this->loadBooking();
    }

    public function updatedProgressStatus(string $value): void
    {
        $this->progressPercentage = match ($value) {
            'slicing' => 25,
            'printing' => 50,
            'revising' => 70,
            'finishing' => 80,
            'completed' => 100,
            default => $this->progressPercentage,
        };
    }

    // ==========================================
    // SIDEBAR: ORDER CONTROLS
    // ==========================================
    public function saveOrderData(): void
    {
        $this->validate([
            'edit_service_id' => 'required|exists:services,id',
            'edit_status' => ['required', Rule::enum(BookingStatus::class)],
            'edit_final_price' => 'nullable|integer|min:0',
        ]);

        try {
            app(UpdateBookingAction::class)->execute(
                $this->booking,
                new UpdateBookingData($this->edit_service_id, $this->edit_status, null)
            );
        } catch (ValidationException $e) {
            $this->error(collect($e->errors())->flatten()->first());

            return;
        }

        if ($this->edit_final_price !== null) {
            $this->booking->update(['agreed_price' => $this->edit_final_price]);
            $this->final_price = $this->edit_final_price;

            if ($this->booking->transaction) {
                $this->booking->transaction->update(['total_amount' => $this->edit_final_price]);
            }
        }

        $this->success(__('Order updated successfully.'));
        $this->loadBooking();
        $this->edit_status = $this->booking->current_status->value;
        $this->edit_final_price = null;
    }

    // ==========================================
    // SIDEBAR: SLICER & PRICING
    // ==========================================
    public function saveCalculation(): void
    {
        $this->validate([
            'slicer_weight_grams' => 'nullable|integer|min:1',
            'slicer_print_time_minutes' => 'nullable|integer|min:1',
            'final_price' => 'required|integer|min:0',
        ]);

        $this->booking->update([
            'slicer_weight_grams' => $this->slicer_weight_grams,
            'slicer_print_time_minutes' => $this->slicer_print_time_minutes,
        ]);

        // Sets agreed_price, syncs total_amount, moves the booking to
        // Awaiting DP and enforces the mandatory 30% down-payment termin.
        try {
            app(SetBookingPriceAction::class)->execute($this->booking, (int) $this->final_price);
        } catch (ValidationException $e) {
            $this->error(collect($e->errors())->flatten()->first());

            return;
        }

        $this->success(__('Price confirmed. A 30% down payment termin awaits the customer.'));
        $this->loadBooking();
        $this->edit_status = $this->booking->current_status->value;
    }

    // ==========================================
    // SIDEBAR: MATERIAL DEDUCTION
    // ==========================================
    public function deductMaterial(): void
    {
        $this->validate([
            'selectedMaterialId' => 'required|exists:raw_materials,id',
            'deductQuantity' => 'required|integer|min:1',
        ]);

        try {
            $invoiceRef = 'INV-'.str_pad($this->booking->id, 4, '0', STR_PAD_LEFT);

            app(RecordMaterialMovementAction::class)->execute(
                new MaterialMovementData(
                    raw_material_id: $this->selectedMaterialId,
                    service_booking_id: $this->booking->id,
                    movement_type: 'out',
                    quantity: $this->deductQuantity,
                    notes: 'Production deduction for Order #'.$invoiceRef
                )
            );

            $this->success(__('Material stock deducted successfully.'));
            $this->reset(['selectedMaterialId']);
            $this->loadBooking();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.order-center.show', [
            'availableServices' => Service::all(),
            'availableMaterials' => RawMaterial::with(['brand'])
                ->withSum('stocks as total_stock', 'quantity')
                ->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM item_stocks WHERE item_stocks.raw_material_id = raw_materials.id) > 0')
                ->get()
                ->map(function ($m) {
                    $m->display_name = "{$m->brand->name} {$m->name} (Stock: {$m->total_stock} {$m->unit})";

                    return $m;
                }),
        ]);
    }
}
