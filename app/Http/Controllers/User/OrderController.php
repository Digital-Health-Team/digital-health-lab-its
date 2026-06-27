<?php

namespace App\Http\Controllers\User;

use App\Actions\Transaction\CreateBookingAction;
use App\Actions\Transaction\SendBookingMessageAction;
use App\Actions\Transaction\UploadPaymentProofAction;
use App\DTOs\Transaction\CreateBookingData;
use App\DTOs\Transaction\SendMessageData;
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
     * Create a booking from a user-submitted brief, then send them to the
     * order detail page where they can contact the admin via WhatsApp.
     */
    public function store(Request $request, CreateBookingAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'brief_description' => 'required|string|min:5|max:2000',
        ]);

        $booking = $action->execute(new CreateBookingData(
            user_id: (int) auth()->id(),
            service_id: (int) $validated['service_id'],
            status: 'pending',
            brief_description: $validated['brief_description'],
        ));

        return redirect()
            ->route('orders.show', $booking)
            ->with('success', 'Order created. Contact the admin via WhatsApp to discuss the price.');
    }

    /**
     * Transaction history — the authenticated user's own orders.
     */
    public function index(): Response
    {
        $orders = ServiceBooking::query()
            ->where('user_id', auth()->id())
            ->with(['transaction', 'service', 'progressUpdates'])
            ->latest('id')
            ->get()
            ->map(fn (ServiceBooking $b) => [
                'id' => $b->id,
                'invoice' => 'INV-'.str_pad((string) $b->id, 4, '0', STR_PAD_LEFT),
                'serviceName' => $b->service?->name ?? '—',
                'status' => $b->current_status,
                'priceLabel' => $b->agreed_price
                    ? 'Rp '.number_format($b->agreed_price, 0, ',', '.')
                    : null,
                'paymentStatus' => $b->transaction?->payment_status,
                'progressPercentage' => $b->progressUpdates->sortByDesc('created_at')->first()?->percentage ?? 0,
                'createdAt' => $b->created_at?->toDateString(),
            ]);

        return inertia('Features/Orders/Pages/OrderHistoryPage', [
            'orders' => $orders,
        ]);
    }

    /**
     * Order detail + production progress timeline.
     */
    public function show(ServiceBooking $booking): Response
    {
        abort_unless($booking->user_id === auth()->id(), 403);

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
                'serviceName' => $booking->service?->name ?? '—',
                'briefDescription' => $booking->brief_description,
                'status' => $booking->current_status,
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
            ."Service: {$booking->service?->name}\n"
            .'Name: '.(auth()->user()->name ?? '')."\n"
            ."Brief: {$booking->brief_description}";

        return "https://wa.me/{$digits}?text=".rawurlencode($message);
    }
}
