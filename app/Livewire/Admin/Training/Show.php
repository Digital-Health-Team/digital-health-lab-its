<?php

namespace App\Livewire\Admin\Training;

use App\Actions\Training\UpdateRegistrationStatusAction;
use App\Models\Training;
use App\Models\TrainingRegistration;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Show extends Component
{
    use Toast, WithPagination;

    public Training $training;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $filterStatus = '';

    public bool $confirmStatusModalOpen = false;

    public ?int $targetRegistrationId = null;

    public string $pendingStatus = '';

    public bool $proofModalOpen = false;

    public ?string $proofImageUrl = null;

    public bool $confirmPaymentModalOpen = false;

    public ?int $paymentVerifyRegistrationId = null;

    public bool $approvePayment = true;

    public function mount(Training $training): void
    {
        $this->training = $training;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmUpdateStatus(int $id, string $status): void
    {
        $this->targetRegistrationId = $id;
        $this->pendingStatus = $status;
        $this->confirmStatusModalOpen = true;
    }

    public function applyStatusUpdate(): void
    {
        try {
            $reg = TrainingRegistration::findOrFail($this->targetRegistrationId);
            app(UpdateRegistrationStatusAction::class)->execute($reg, $this->pendingStatus);
            $this->success(__('Registration status updated.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->confirmStatusModalOpen = false;
    }

    public function viewProof(int $id): void
    {
        $reg = TrainingRegistration::findOrFail($id);
        $this->proofImageUrl = $reg->payment_proof
            ? Storage::disk('public')->url($reg->payment_proof)
            : null;
        $this->proofModalOpen = true;
    }

    public function confirmPaymentVerification(int $id, bool $approve): void
    {
        $this->paymentVerifyRegistrationId = $id;
        $this->approvePayment = $approve;
        $this->confirmPaymentModalOpen = true;
    }

    public function applyPaymentVerification(): void
    {
        try {
            $reg = TrainingRegistration::findOrFail($this->paymentVerifyRegistrationId);
            $reg->update([
                'payment_status' => $this->approvePayment ? 'paid' : 'rejected',
                'verified_by' => auth()->id(),
            ]);
            $this->success($this->approvePayment
                ? __('Payment verified successfully.')
                : __('Payment proof rejected.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->confirmPaymentModalOpen = false;
    }

    public function render()
    {
        $this->training->loadCount('registrations');

        $query = $this->training->registrations()->with('user');

        if ($this->search) {
            $query->where(fn ($q) => $q
                ->where('full_name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone_number', 'like', "%{$this->search}%")
            );
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.admin.training.show', [
            'registrations' => $query->latest()->paginate(15),
        ]);
    }
}
