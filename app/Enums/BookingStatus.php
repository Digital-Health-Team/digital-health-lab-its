<?php

namespace App\Enums;

/**
 * Admin-side booking pipeline. The string values are stored in
 * service_bookings.current_status (schema unchanged — plain string column).
 *
 * Legacy cases keep old rows castable without a data migration; they map
 * into the new pipeline via customerStage() and count as production where
 * the old value implied work had started.
 */
enum BookingStatus: string
{
    // --- New pipeline (in operational order) ---
    case ReviewBrief = 'review_brief';

    case CheckMaterial = 'check_material';

    case Slicing = 'slicing';

    case SetPrice = 'set_price';

    case AwaitingDp = 'awaiting_dp'; // Upload Bukti Bayar: customer owes the 30% DP

    case Printing = 'printing';

    case Finishing = 'finishing';

    case FinalPayment = 'final_payment'; // Pelunasan

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    // --- Legacy statuses (deprecated, pre-overhaul rows only) ---
    case Pending = 'pending';

    case Negotiating = 'negotiating';

    case InProgress = 'in_progress';

    case Revising = 'revising';

    case Processing = 'processing';

    public function customerStage(): CustomerStatus
    {
        return match ($this) {
            self::ReviewBrief, self::CheckMaterial, self::Pending => CustomerStatus::WarehouseCheck,
            self::Slicing, self::SetPrice, self::AwaitingDp, self::Negotiating => CustomerStatus::SetPrice,
            self::Printing, self::InProgress, self::Revising, self::Processing => CustomerStatus::Processing,
            self::Finishing => CustomerStatus::PostProcessing,
            self::FinalPayment, self::Completed => CustomerStatus::Finish,
            self::Cancelled => CustomerStatus::Cancelled,
        };
    }

    /**
     * Production and beyond — the point of no return for cancellation.
     */
    public function isProduction(): bool
    {
        return in_array($this, [
            self::Printing,
            self::Finishing,
            self::FinalPayment,
            self::Completed,
            self::InProgress,
            self::Revising,
            self::Processing,
        ], true);
    }

    public function isCancellable(): bool
    {
        return ! $this->isProduction() && $this !== self::Cancelled;
    }

    /**
     * Stages where the warehouse material check has not yet been passed.
     */
    public function isAtOrBeforeMaterialCheck(): bool
    {
        return in_array($this, [
            self::ReviewBrief,
            self::CheckMaterial,
            self::Pending,
        ], true);
    }

    /**
     * Stages past the warehouse material check. Cancelled is deliberately
     * excluded — cancelling from check_material must remain possible.
     */
    public function isBeyondMaterialCheck(): bool
    {
        return in_array($this, [
            self::Slicing,
            self::SetPrice,
            self::AwaitingDp,
            self::Printing,
            self::Finishing,
            self::FinalPayment,
            self::Completed,
            self::Negotiating,
            self::InProgress,
            self::Revising,
            self::Processing,
        ], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::ReviewBrief => __('Review Brief'),
            self::CheckMaterial => __('Check Material'),
            self::Slicing => __('Slicing'),
            self::SetPrice => __('Set Price'),
            self::AwaitingDp => __('Awaiting DP (Bukti Bayar)'),
            self::Printing => __('Production — Printing'),
            self::Finishing => __('Production — Finishing'),
            self::FinalPayment => __('Final Payment'),
            self::Completed => __('Finished'),
            self::Cancelled => __('Cancelled'),
            self::Pending => __('Review Brief'),
            self::Negotiating => __('Set Price'),
            self::InProgress, self::Revising, self::Processing => __('Production — Printing'),
        };
    }

    /**
     * Ordered pipeline for the admin status dropdown / stepper
     * (excludes legacy cases and Cancelled).
     *
     * @return array<self>
     */
    public static function pipeline(): array
    {
        return [
            self::ReviewBrief,
            self::CheckMaterial,
            self::Slicing,
            self::SetPrice,
            self::AwaitingDp,
            self::Printing,
            self::Finishing,
            self::FinalPayment,
            self::Completed,
        ];
    }

    /**
     * Statuses counted as realized revenue in KPI queries.
     *
     * @return array<string>
     */
    public static function realizedValues(): array
    {
        return [
            self::Finishing->value,
            self::FinalPayment->value,
            self::Completed->value,
        ];
    }

    /**
     * Work-in-progress statuses counted as projected revenue.
     *
     * @return array<string>
     */
    public static function projectedValues(): array
    {
        return [
            self::Slicing->value,
            self::SetPrice->value,
            self::AwaitingDp->value,
            self::Printing->value,
            self::Negotiating->value,
            self::InProgress->value,
            self::Revising->value,
            self::Processing->value,
        ];
    }
}
