<?php

use App\Actions\Transaction\AddBookingPaymentAction;
use App\Actions\Transaction\AddProgressUpdateAction;
use App\Actions\Transaction\SendBookingMessageAction;
use App\Actions\Transaction\UploadPaymentProofAction;
use App\Actions\Transaction\VerifyPaymentAction;
use App\DTOs\Transaction\BookingPaymentData;
use App\DTOs\Transaction\ProgressUpdateData;
use App\DTOs\Transaction\SendMessageData;
use App\Events\BookingMessageSent;
use App\Models\BookingPayment;
use App\Models\Role;
use App\Models\ServiceBooking;
use App\Models\User;
use App\Notifications\OrderProgressUpdated;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->withoutVite();

    // Mirror production role IDs so User::isAdmin() (role_id in [1, 2]) behaves correctly.
    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

function adminUser(): User
{
    return User::factory()->create(['role_id' => 2]);
}

function workflowBooking(): ServiceBooking
{
    $customer = User::factory()->create(['role_id' => 5]);

    return ServiceBooking::factory()->create([
        'user_id' => $customer->id,
        'agreed_price' => 200000,
        'current_status' => 'negotiating',
    ]);
}

// ── Payment termins ───────────────────────────────────────
test('admin can add a termin, upload proof, and verify it', function () {
    Storage::fake('public');
    $this->actingAs(adminUser());
    $booking = workflowBooking();

    $payment = app(AddBookingPaymentAction::class)->execute(
        new BookingPaymentData($booking->id, 'DP', 100000)
    );

    expect($payment->status)->toBe('pending')
        ->and($booking->refresh()->total_paid)->toBe(0)
        ->and($booking->remaining_balance)->toBe(200000);

    app(UploadPaymentProofAction::class)->execute($payment, UploadedFile::fake()->image('proof.jpg'));
    $payment->refresh();
    expect($payment->status)->toBe('awaiting_verification');
    Storage::disk('public')->assertExists($payment->payment_proof);

    app(VerifyPaymentAction::class)->execute($payment, true);
    $payment->refresh();

    expect($payment->status)->toBe('paid')
        ->and($payment->paid_at)->not->toBeNull()
        ->and($booking->refresh()->total_paid)->toBe(100000)
        ->and($booking->remaining_balance)->toBe(100000);
});

test('rejecting a payment leaves it unpaid', function () {
    $this->actingAs(adminUser());
    $booking = workflowBooking();
    $payment = BookingPayment::factory()->create([
        'service_booking_id' => $booking->id,
        'amount' => 50000,
        'status' => 'awaiting_verification',
    ]);

    app(VerifyPaymentAction::class)->execute($payment, false);

    expect($payment->refresh()->status)->toBe('rejected')
        ->and($booking->refresh()->total_paid)->toBe(0);
});

// ── Progress notifications ────────────────────────────────
test('logging a progress update notifies the booking owner', function () {
    Notification::fake();
    $this->actingAs(adminUser());
    $booking = workflowBooking();

    app(AddProgressUpdateAction::class)->execute(
        new ProgressUpdateData($booking->id, 'printing', 40, 'Halfway there')
    );

    Notification::assertSentTo($booking->user, OrderProgressUpdated::class);
    expect($booking->refresh()->current_status)->toBe('printing');
});

// ── Chat ──────────────────────────────────────────────────
test('sending a message persists it and broadcasts the event', function () {
    Event::fake([BookingMessageSent::class]);
    $admin = adminUser();
    $this->actingAs($admin);
    $booking = workflowBooking();

    $message = app(SendBookingMessageAction::class)->execute(
        new SendMessageData($booking->id, 'Hello, here is your quote.')
    );

    expect($message->sender_id)->toBe($admin->id)
        ->and($message->body)->toBe('Hello, here is your quote.')
        ->and($booking->messages()->count())->toBe(1);

    Event::assertDispatched(BookingMessageSent::class, fn ($e) => $e->message->id === $message->id);
});

// ── Channel authorization ─────────────────────────────────
test('booking channel authorizes owner and admin but rejects others', function () {
    $booking = workflowBooking();
    $owner = $booking->user;
    $admin = adminUser();
    $stranger = User::factory()->create(['role_id' => 5]);

    $callback = Broadcast::getChannels()['booking.{bookingId}'] ?? null;
    expect($callback)->not->toBeNull();

    expect((bool) $callback($owner, $booking->id))->toBeTrue()
        ->and((bool) $callback($admin, $booking->id))->toBeTrue()
        ->and((bool) $callback($stranger, $booking->id))->toBeFalse();
});
