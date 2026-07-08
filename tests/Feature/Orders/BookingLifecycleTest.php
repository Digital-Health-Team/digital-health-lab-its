<?php

use App\Actions\Transaction\SetBookingPriceAction;
use App\Actions\Transaction\TransitionBookingStatusAction;
use App\Actions\Transaction\VerifyPaymentAction;
use App\Enums\BookingStatus;
use App\Enums\CustomerStatus;
use App\Models\BookingPayment;
use App\Models\Role;
use App\Models\ServiceBooking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->withoutVite();
    Notification::fake();

    // Mirror production role IDs so User::isAdmin() (role_id in [1, 2]) behaves correctly.
    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

function lifecycleAdmin(): User
{
    return User::factory()->create(['role_id' => 2]);
}

function lifecycleBooking(string $status = 'review_brief', ?int $price = null): ServiceBooking
{
    $customer = User::factory()->create(['role_id' => 5]);
    $transaction = Transaction::create([
        'user_id' => $customer->id,
        'total_amount' => 0,
        'payment_status' => 'unpaid',
    ]);

    return ServiceBooking::factory()->create([
        'user_id' => $customer->id,
        'transaction_id' => $transaction->id,
        'agreed_price' => $price,
        'current_status' => $status,
    ]);
}

function paidDp(ServiceBooking $booking, int $amount = 30000): BookingPayment
{
    return BookingPayment::factory()->create([
        'service_booking_id' => $booking->id,
        'termin_name' => SetBookingPriceAction::DP_TERMIN_NAME,
        'amount' => $amount,
        'status' => 'paid',
    ]);
}

// ── Set Price + mandatory 30% DP ─────────────────────────
test('setting the price creates the mandatory 30% DP termin and moves to awaiting_dp', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('set_price');

    app(SetBookingPriceAction::class)->execute($booking, 200000);

    expect($booking->agreed_price)->toBe(200000)
        ->and($booking->current_status)->toBe(BookingStatus::AwaitingDp)
        ->and($booking->transaction->refresh()->total_amount)->toBe(200000);

    $dp = $booking->payments()->where('termin_name', SetBookingPriceAction::DP_TERMIN_NAME)->first();
    expect($dp)->not->toBeNull()
        ->and($dp->amount)->toBe(60000)
        ->and($dp->status)->toBe('pending');
});

test('DP amount rounds to the nearest rupiah', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('set_price');

    app(SetBookingPriceAction::class)->execute($booking, 99999);

    expect($booking->payments()->first()->amount)->toBe((int) round(99999 * 0.3));
});

test('re-setting the price updates a pending DP but never creates a duplicate', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('set_price');

    app(SetBookingPriceAction::class)->execute($booking, 200000);
    app(SetBookingPriceAction::class)->execute($booking, 300000);

    $dps = $booking->payments()->where('termin_name', SetBookingPriceAction::DP_TERMIN_NAME)->get();
    expect($dps)->toHaveCount(1)
        ->and($dps->first()->amount)->toBe(90000);
});

test('re-setting the price leaves a paid DP untouched', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('set_price');

    app(SetBookingPriceAction::class)->execute($booking, 200000);
    $booking->payments()->first()->update(['status' => 'paid']);

    app(SetBookingPriceAction::class)->execute($booking, 300000);

    expect($booking->payments()->first()->amount)->toBe(60000);
});

// ── DP verification auto-starts production ───────────────
test('verifying the DP termin auto-advances the booking to printing', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('set_price');

    app(SetBookingPriceAction::class)->execute($booking, 200000);
    $dp = $booking->payments()->first();
    $dp->update(['status' => 'awaiting_verification']);

    app(VerifyPaymentAction::class)->execute($dp, true);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::Printing);
});

test('verifying a non-DP termin does not advance the booking', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('awaiting_dp', 200000);
    $termin = BookingPayment::factory()->create([
        'service_booking_id' => $booking->id,
        'termin_name' => 'Pelunasan',
        'amount' => 140000,
        'status' => 'awaiting_verification',
    ]);

    app(VerifyPaymentAction::class)->execute($termin, true);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::AwaitingDp);
});

test('rejecting the DP termin keeps the booking out of production', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('set_price');

    app(SetBookingPriceAction::class)->execute($booking, 200000);
    $dp = $booking->payments()->first();
    $dp->update(['status' => 'awaiting_verification']);

    app(VerifyPaymentAction::class)->execute($dp, false);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::AwaitingDp);
});

// ── Production gate ───────────────────────────────────────
test('transition into production is rejected without a paid DP', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('awaiting_dp', 200000);

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Printing);
})->throws(ValidationException::class);

test('transition into production succeeds once the DP is paid', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('awaiting_dp', 200000);
    paidDp($booking);

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Printing);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::Printing);
});

test('moving between production stages needs no second DP check', function () {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking('printing', 200000);

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Finishing);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::Finishing);
});

// ── Cancellation lock ─────────────────────────────────────
test('a booking can be cancelled before production', function (string $status) {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking($status, 200000);

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Cancelled);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::Cancelled);
})->with(['review_brief', 'check_material', 'slicing', 'set_price', 'awaiting_dp']);

test('a booking in production can no longer be cancelled', function (string $status) {
    $this->actingAs(lifecycleAdmin());
    $booking = lifecycleBooking($status, 200000);

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Cancelled);
})->with(['printing', 'finishing', 'final_payment', 'completed'])
    ->throws(ValidationException::class);

// ── Cancellation policy ───────────────────────────────────
test('the owner may cancel pre-production but not during production', function () {
    $booking = lifecycleBooking('awaiting_dp', 200000);
    $owner = $booking->user;

    expect($owner->can('cancel', $booking))->toBeTrue();

    $booking->update(['current_status' => 'printing']);
    expect($owner->can('cancel', $booking->refresh()))->toBeFalse();
});

test('a stranger may never cancel someone else\'s booking', function () {
    $booking = lifecycleBooking('review_brief');
    $stranger = User::factory()->create(['role_id' => 5]);

    expect($stranger->can('cancel', $booking))->toBeFalse();
});

test('an admin may cancel pre-production but not during production', function () {
    $admin = lifecycleAdmin();
    $booking = lifecycleBooking('check_material');

    expect($admin->can('cancel', $booking))->toBeTrue();

    $booking->update(['current_status' => 'finishing']);
    expect($admin->can('cancel', $booking->refresh()))->toBeFalse();
});

// ── Customer stage mapping ────────────────────────────────
test('admin statuses map to the five public customer stages', function (string $status, CustomerStatus $stage) {
    expect(BookingStatus::from($status)->customerStage())->toBe($stage);
})->with([
    ['review_brief', CustomerStatus::WarehouseCheck],
    ['check_material', CustomerStatus::WarehouseCheck],
    ['slicing', CustomerStatus::SetPrice],
    ['set_price', CustomerStatus::SetPrice],
    ['awaiting_dp', CustomerStatus::SetPrice],
    ['printing', CustomerStatus::Processing],
    ['finishing', CustomerStatus::PostProcessing],
    ['final_payment', CustomerStatus::Finish],
    ['completed', CustomerStatus::Finish],
    ['cancelled', CustomerStatus::Cancelled],
    // Legacy statuses on pre-overhaul rows
    ['pending', CustomerStatus::WarehouseCheck],
    ['negotiating', CustomerStatus::SetPrice],
    ['in_progress', CustomerStatus::Processing],
    ['revising', CustomerStatus::Processing],
    ['processing', CustomerStatus::Processing],
]);

test('a legacy booking still casts and exposes its customer stage', function () {
    $booking = lifecycleBooking('negotiating', 100000);

    expect($booking->current_status)->toBe(BookingStatus::Negotiating)
        ->and($booking->customer_stage)->toBe('set_price')
        ->and($booking->current_status->isCancellable())->toBeTrue();
});
