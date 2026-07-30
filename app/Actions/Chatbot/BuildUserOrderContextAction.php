<?php

namespace App\Actions\Chatbot;

use App\Enums\BookingStatus;
use App\Models\ServiceBooking;
use App\Models\User;

/**
 * One compact line per recent order, for the signed-in asker only.
 *
 * This never touches `knowledge_chunks`. Order data reaches the prompt through this
 * Eloquent query, filtered by the session user's id — keeping the two paths separate is
 * what makes a cross-user leak impossible rather than merely unlikely.
 *
 * What may be sent, and nothing else: invoice number, service name, customer stage label,
 * latest progress percentage, payment termin statuses, creation date.
 *
 * What is never sent: name, email, NIM, phone, address, brief_description, uploaded file
 * paths, and the whole of booking_messages. On the free tier prompts may be used to
 * improve Google's products, and the fields above are enough to answer "where is my
 * order" without moving a student's identity to a third party.
 */
class BuildUserOrderContextAction
{
    private const MAX_ORDERS = 5;

    /**
     * @return array<int, string> Newest first. Empty when the user has no orders.
     */
    public function execute(User $user): array
    {
        return ServiceBooking::query()
            ->where('user_id', $user->id)
            // A consultation is a standing chat thread, not an order — it has no stage,
            // no price and no progress, so it only muddies the answer.
            ->where('current_status', '!=', BookingStatus::Consultation->value)
            ->with([
                'service',
                'payments',
                // created_at alone is ambiguous: two updates saved in the same second tie,
                // and the database is then free to hand back the older one. The id breaks
                // the tie without overriding a deliberately backdated row.
                'progressUpdates' => fn ($query) => $query->latest()->orderByDesc('id')->limit(1),
            ])
            ->latest()
            ->limit(self::MAX_ORDERS)
            ->get()
            ->map(fn (ServiceBooking $booking): string => implode(' | ', [
                'INV-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT),
                $booking->service?->localized('name') ?? '-',
                $booking->current_status->customerStage()->label(),
                $this->progress($booking),
                $this->payments($booking),
                $booking->created_at?->toDateString() ?? '-',
            ]))
            ->all();
    }

    private function progress(ServiceBooking $booking): string
    {
        $latest = $booking->progressUpdates->first();

        return $latest === null ? '-' : $latest->percentage.'%';
    }

    /**
     * Termin name and status only — never the amounts, which the rules above keep out.
     * Raw status values ('paid', 'awaiting_verification') are deliberate: they are
     * unambiguous, cost few tokens, and the model phrases them in the reply language.
     */
    private function payments(ServiceBooking $booking): string
    {
        if ($booking->payments->isEmpty()) {
            return '-';
        }

        return $booking->payments
            ->map(fn ($payment): string => "{$payment->termin_name}={$payment->status}")
            ->implode(', ');
    }
}
