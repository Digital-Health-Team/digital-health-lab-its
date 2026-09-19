<?php

use App\Actions\Chatbot\BuildUserOrderContextAction;
use App\Enums\BookingStatus;
use App\Models\BookingPayment;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceProgressUpdate;
use App\Models\User;

function orderContextFor(User $user): array
{
    return app(BuildUserOrderContextAction::class)->execute($user);
}

it('summarises an order without leaking anything personal', function () {
    $service = Service::create([
        'name' => 'Cetak 3D',
        'service_type' => 'printing',
        'description' => 'Layanan pencetakan tiga dimensi.',
        'base_price' => 50000,
    ]);

    $user = User::factory()->create(['name' => 'Rizky Pratama', 'email' => 'rizky@its.ac.id']);

    $booking = ServiceBooking::factory()->create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'current_status' => BookingStatus::Printing->value,
        'brief_description' => 'Casing untuk sensor detak jantung, tolong rahasia.',
        'model_file_path' => 'uploads/rahasia/model-final.stl',
    ]);

    ServiceProgressUpdate::create([
        'service_booking_id' => $booking->id,
        'status_label' => 'Printing',
        'percentage' => 40,
        'updated_by' => $user->id,
    ]);
    ServiceProgressUpdate::create([
        'service_booking_id' => $booking->id,
        'status_label' => 'Printing',
        'percentage' => 60,
        'updated_by' => $user->id,
    ]);

    BookingPayment::factory()->create([
        'service_booking_id' => $booking->id,
        'termin_name' => 'DP',
        'status' => 'paid',
    ]);
    BookingPayment::factory()->create([
        'service_booking_id' => $booking->id,
        'termin_name' => 'Pelunasan',
        'status' => 'pending',
    ]);

    $line = orderContextFor($user)[0];

    expect($line)
        ->toContain('INV-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT))
        ->toContain('Cetak 3D')
        ->toContain(BookingStatus::Printing->customerStage()->label())
        // The newest progress update wins, not the first one.
        ->toContain('60%')
        ->toContain('DP=paid')
        ->toContain('Pelunasan=pending')
        ->toContain($booking->created_at->toDateString());

    // On the free tier prompts can be used to improve Google's products, so none of this
    // may ever reach the request.
    expect($line)
        ->not->toContain('Rizky')
        ->not->toContain('rizky@its.ac.id')
        ->not->toContain('rahasia')
        ->not->toContain('.stl')
        // Termin status only — amounts are outside the allowed field list.
        ->not->toContain('Rp');
});

it('never returns another user\'s bookings', function () {
    $mine = User::factory()->create();
    $theirs = User::factory()->create();

    $myBooking = ServiceBooking::factory()->create(['user_id' => $mine->id]);
    $theirBooking = ServiceBooking::factory()->create(['user_id' => $theirs->id]);

    $lines = orderContextFor($mine);

    expect($lines)->toHaveCount(1)
        ->and($lines[0])->toContain('INV-'.str_pad((string) $myBooking->id, 4, '0', STR_PAD_LEFT))
        ->and($lines[0])->not->toContain('INV-'.str_pad((string) $theirBooking->id, 4, '0', STR_PAD_LEFT));
});

it('excludes consultation threads', function () {
    $user = User::factory()->create();

    ServiceBooking::factory()->create([
        'user_id' => $user->id,
        'current_status' => BookingStatus::Consultation->value,
    ]);
    $order = ServiceBooking::factory()->create([
        'user_id' => $user->id,
        'current_status' => BookingStatus::Printing->value,
    ]);

    // A consultation is a chat thread with no stage, price or progress — listing it as an
    // order only muddies the answer.
    expect(orderContextFor($user))->toHaveCount(1)
        ->and(orderContextFor($user)[0])->toContain('INV-'.str_pad((string) $order->id, 4, '0', STR_PAD_LEFT));
});

it('returns the five most recent orders, newest first', function () {
    $user = User::factory()->create();

    $bookings = collect(range(1, 7))->map(fn (int $i) => ServiceBooking::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subDays(10 - $i),
    ]));

    $lines = orderContextFor($user);

    expect($lines)->toHaveCount(5)
        ->and($lines[0])->toContain('INV-'.str_pad((string) $bookings->last()->id, 4, '0', STR_PAD_LEFT));
});

it('marks missing service, progress and payments rather than dropping the columns', function () {
    $user = User::factory()->create();
    $booking = ServiceBooking::factory()->create(['user_id' => $user->id, 'service_id' => null]);

    // Column count must stay fixed, or the model has to guess which value is which.
    expect(explode(' | ', orderContextFor($user)[0]))->toBe([
        'INV-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT),
        '-',
        $booking->current_status->customerStage()->label(),
        '-',
        '-',
        $booking->created_at->toDateString(),
    ]);
});

it('returns nothing for a user with no orders', function () {
    expect(orderContextFor(User::factory()->create()))->toBe([]);
});
