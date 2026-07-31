<?php

use App\Actions\Transaction\SetBookingPriceAction;
use App\Actions\Transaction\TransitionBookingStatusAction;
use App\Enums\BookingStatus;
use App\Models\BookingPayment;
use App\Models\Role;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->withoutVite();

    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }

    $this->actingAs(User::factory()->create(['role_id' => 2]));
});

function gateBooking(string $status, array $overrides = []): ServiceBooking
{
    return ServiceBooking::factory()->create(array_merge([
        'user_id' => User::factory()->create(['role_id' => 5])->id,
        'current_status' => $status,
    ], $overrides));
}

function verifiedFields(): array
{
    return [
        'material_verified_at' => now(),
        'material_verified_by' => auth()->id(),
    ];
}

// ── The gate ──────────────────────────────────────────────
test('unverified booking cannot leave check_material', function () {
    $booking = gateBooking('check_material');

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Slicing);
})->throws(ValidationException::class);

test('legacy pending booking is also gated', function () {
    $booking = gateBooking('pending');

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Slicing);
})->throws(ValidationException::class);

test('verified booking transitions past check_material', function () {
    $booking = gateBooking('check_material', verifiedFields());

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Slicing);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::Slicing);
});

test('cancelling from check_material stays possible without verification', function () {
    $booking = gateBooking('check_material');

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Cancelled);

    expect($booking->refresh()->current_status)->toBe(BookingStatus::Cancelled);
});

test('gate is not retroactive for bookings already past check_material', function (string $from, string $to) {
    $booking = gateBooking($from); // no verification at all

    if (BookingStatus::from($to)->isProduction()) {
        BookingPayment::factory()->create([
            'service_booking_id' => $booking->id,
            'termin_name' => SetBookingPriceAction::DP_TERMIN_NAME,
            'amount' => 50000,
            'status' => 'paid',
        ]);
    }

    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::from($to));

    expect($booking->refresh()->current_status->value)->toBe($to);
})->with([
    'slicing → set_price' => ['slicing', 'set_price'],
    'awaiting_dp → printing' => ['awaiting_dp', 'printing'],
    'printing → finishing' => ['printing', 'finishing'],
    'legacy negotiating → slicing' => ['negotiating', 'slicing'],
]);

test('DP gate still applies after material verification', function () {
    $booking = gateBooking('check_material', verifiedFields());

    // Verified materials, but no paid DP: production must stay locked.
    app(TransitionBookingStatusAction::class)->execute($booking, BookingStatus::Printing);
})->throws(ValidationException::class);

// ── Bypass routes are closed ──────────────────────────────
test('setting a price on an unverified check_material booking is blocked', function () {
    $booking = gateBooking('check_material');

    app(SetBookingPriceAction::class)->execute($booking, 200000);
})->throws(ValidationException::class);

test('setting a price on a verified booking moves it to awaiting_dp with a DP termin', function () {
    $booking = gateBooking('check_material', verifiedFields());

    app(SetBookingPriceAction::class)->execute($booking, 200000);

    $booking->refresh();
    expect($booking->current_status)->toBe(BookingStatus::AwaitingDp)
        ->and($booking->payments()->where('termin_name', SetBookingPriceAction::DP_TERMIN_NAME)->value('amount'))->toBe(60000);
});

// ── Model helper ──────────────────────────────────────────
test('isMaterialVerified reflects the timestamp', function () {
    expect(gateBooking('check_material')->isMaterialVerified())->toBeFalse()
        ->and(gateBooking('check_material', verifiedFields())->isMaterialVerified())->toBeTrue();
});
