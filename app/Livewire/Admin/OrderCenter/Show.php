<?php

namespace App\Livewire\Admin\OrderCenter;

use App\Actions\Transaction\AddBookingPaymentAction;
use App\Actions\Transaction\AddProgressUpdateAction;
use App\Actions\Transaction\SendBookingMessageAction;
use App\Actions\Transaction\UploadPaymentProofAction;
use App\Actions\Transaction\VerifyPaymentAction;
use App\DTOs\Transaction\BookingPaymentData;
use App\DTOs\Transaction\ProgressUpdateData;
use App\DTOs\Transaction\SendMessageData;
use App\Models\ServiceBooking;
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
    public string $progressStatus = 'slicing';

    public int $progressPercentage = 0;

    public string $progressNotes = '';

    public array $progressFiles = [];

    public function mount(ServiceBooking $booking): void
    {
        $this->booking = $booking;
        $this->loadBooking();
        $this->markThreadRead();
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

        app(AddProgressUpdateAction::class)->execute(
            new ProgressUpdateData(
                $this->booking->id,
                $this->progressStatus,
                $this->progressPercentage,
                $this->progressNotes,
                $this->progressFiles
            )
        );

        $this->success(__('Production timeline updated. The customer has been notified.'));
        $this->reset(['progressNotes', 'progressFiles']);
        $this->loadBooking();
    }

    public function render()
    {
        return view('livewire.admin.order-center.show');
    }
}
