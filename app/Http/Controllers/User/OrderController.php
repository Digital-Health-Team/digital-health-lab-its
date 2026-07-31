<?php

namespace App\Http\Controllers\User;

use App\Actions\Transaction\CreateBookingAction;
use App\Actions\Transaction\SendBookingMessageAction;
use App\Actions\Transaction\UploadPaymentProofAction;
use App\DTOs\Transaction\CreateBookingData;
use App\DTOs\Transaction\SendMessageData;
use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\BookingMessage;
use App\Models\BookingPayment;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * Service catalog — entry point for placing a new order.
     */
    public function catalog(): Response
    {
        $services = Service::all()->map(fn (Service $s) => [
            'id' => $s->id,
            'name' => $s->name,
            'description' => $s->description,
            'priceLabel' => 'Rp '.number_format($s->base_price, 0, ',', '.'),
            'hasContact' => filled($s->whatsapp_number) || filled(config('services.admin.whatsapp')),
        ]);

        return inertia('Features/Orders/Pages/ServiceCatalogPage', [
            'services' => $services,
        ]);
    }

    /**
     * Create a booking from a user-submitted service request form.
     */
    public function store(Request $request, CreateBookingAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'brief_description' => 'required|string|min:5|max:2000',
            'reference_photo' => 'nullable|image|max:10240',
            'model_file' => 'nullable|file|mimes:stl,obj|max:51200',
            'material_preference' => 'nullable|string|max:255',
            'filament_width' => 'nullable|string|max:255',
            'scan_purpose' => 'nullable|string|max:255',
            'object_dimensions' => 'nullable|array',
            'object_dimensions.length' => 'nullable|string|max:50',
            'object_dimensions.width' => 'nullable|string|max:50',
            'object_dimensions.height' => 'nullable|string|max:50',
        ]);

        $referencePhotoPath = null;
        if ($request->hasFile('reference_photo')) {
            $referencePhotoPath = $request->file('reference_photo')
                ->store('service_requests/photos', 'public');
        }

        $modelFilePath = null;
        if ($request->hasFile('model_file')) {
            $modelFilePath = $request->file('model_file')
                ->store('service_requests/models', 'public');
        }

        $booking = $action->execute(new CreateBookingData(
            user_id: (int) auth()->id(),
            service_id: (int) $validated['service_id'],
            status: 'review_brief',
            brief_description: $validated['brief_description'],
            reference_photo_path: $referencePhotoPath,
            model_file_path: $modelFilePath,
            material_preference: $validated['material_preference'] ?? null,
            filament_width: $validated['filament_width'] ?? null,
            scan_purpose: $validated['scan_purpose'] ?? null,
            object_dimensions: $validated['object_dimensions'] ?? null,
        ));

        return redirect()
            ->route('orders.show', $booking)
            ->with('success', __('Order created. Contact the admin via WhatsApp to discuss the price.'));
    }

    /**
     * Transaction history — the authenticated user's own orders.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('profile.show');
    }

    /**
     * Order detail + production progress timeline.
     */
    public function show(ServiceBooking $booking): Response
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        // A consultation thread is not an order — it lives at /services/consultation.
        abort_if($booking->current_status === BookingStatus::Consultation, 404);

        // Mark admin messages as read now that the customer is viewing the thread.
        $booking->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        $booking->load([
            'transaction',
            'service',
            'progressUpdates' => fn ($q) => $q->latest('created_at'),
            'progressUpdates.attachments',
            'payments' => fn ($q) => $q->oldest('id'),
            'messages' => fn ($q) => $q->oldest('id'),
            'messages.sender',
        ]);

        $progress = $booking->progressUpdates->map(fn ($p) => [
            'id' => $p->id,
            'statusLabel' => $p->status_label,
            'percentage' => $p->percentage,
            'notes' => $p->notes,
            'createdAt' => $p->created_at?->toDateTimeString(),
            'attachments' => $p->attachments
                ->map(fn ($a) => Storage::disk('public')->url($a->file_url))
                ->values(),
        ]);

        $payments = $booking->payments->map(fn (BookingPayment $p) => [
            'id' => $p->id,
            'terminName' => $p->termin_name,
            'amountLabel' => 'Rp '.number_format($p->amount, 0, ',', '.'),
            'status' => $p->status,
            'paidAt' => $p->paid_at?->toDateString(),
            'proofUrl' => $p->payment_proof ? Storage::disk('public')->url($p->payment_proof) : null,
        ]);

        $messages = $booking->messages->map(fn (BookingMessage $m) => [
            'id' => $m->id,
            'body' => $m->body,
            'senderId' => $m->sender_id,
            'senderName' => $m->sender?->name,
            'isMine' => $m->sender_id === auth()->id(),
            'createdAt' => $m->created_at?->toDateTimeString(),
        ]);

        return inertia('Features/Orders/Pages/OrderDetailPage', [
            'order' => [
                'id' => $booking->id,
                'invoice' => 'INV-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT),
                'serviceName' => $booking->service?->localized('name') ?? '—',
                'serviceType' => $booking->service?->service_type,
                'briefDescription' => $booking->brief_description,
                'referencePhotoUrl' => $booking->reference_photo_path
                    ? Storage::disk('public')->url($booking->reference_photo_path)
                    : null,
                'modelFileUrl' => $booking->model_file_path
                    ? Storage::disk('public')->url($booking->model_file_path)
                    : null,
                'materialPreference' => $booking->material_preference,
                'filamentWidth' => $booking->filament_width,
                'scanPurpose' => $booking->scan_purpose,
                'objectDimensions' => $booking->object_dimensions,
                'status' => $booking->current_status->value,
                'customerStage' => $booking->current_status->customerStage()->value,
                'customerStageLabel' => $booking->current_status->customerStage()->label(),
                'priceLabel' => $booking->agreed_price
                    ? 'Rp '.number_format($booking->agreed_price, 0, ',', '.')
                    : null,
                'paymentStatus' => $booking->transaction?->payment_status,
                'createdAt' => $booking->created_at?->toDateString(),
                'progress' => $progress,
                'payments' => $payments,
                'paymentSummary' => [
                    'totalLabel' => 'Rp '.number_format((int) $booking->agreed_price, 0, ',', '.'),
                    'paidLabel' => 'Rp '.number_format($booking->total_paid, 0, ',', '.'),
                    'remainingLabel' => 'Rp '.number_format($booking->remaining_balance, 0, ',', '.'),
                ],
                'messages' => $messages,
            ],
            'whatsappUrl' => $this->buildWhatsAppUrl($booking),
        ]);
    }

    /**
     * Customer posts a consultation message to the admin for this order.
     * Reuses the action that creates the message and broadcasts it live.
     */
    public function sendMessage(
        Request $request,
        ServiceBooking $booking,
        SendBookingMessageAction $action
    ): RedirectResponse {
        abort_unless($booking->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $action->execute(new SendMessageData($booking->id, $validated['body']));

        return back()->with('success', __('Message sent.'));
    }

    /**
     * Customer uploads bank-transfer proof for a single payment termin,
     * flagging it for admin verification.
     */
    public function uploadPaymentProof(
        Request $request,
        ServiceBooking $booking,
        BookingPayment $payment,
        UploadPaymentProofAction $action
    ): RedirectResponse {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless($payment->service_booking_id === $booking->id, 404);
        abort_if($payment->status === 'paid', 422);

        $request->validate([
            'proof' => 'required|image|max:20480',
        ]);

        $action->execute($payment, $request->file('proof'));

        return back()->with('success', __('Payment proof uploaded. Awaiting admin verification.'));
    }

    /**
     * Build a wa.me deep link to the service's contact (or the global fallback),
     * pre-filled with the order details.
     */
    private function buildWhatsAppUrl(ServiceBooking $booking): ?string
    {
        $number = $booking->service?->whatsapp_number ?: config('services.admin.whatsapp');

        if (blank($number)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $number);
        $invoice = 'INV-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT);

        $message = "Hello Admin, I'd like to discuss my order {$invoice}.\n"
            ."Service: {$booking->service?->localized('name')}\n"
            .'Name: '.(auth()->user()->name ?? '')."\n"
            ."Brief: {$booking->brief_description}";

        return "https://wa.me/{$digits}?text=".rawurlencode($message);
    }
}
