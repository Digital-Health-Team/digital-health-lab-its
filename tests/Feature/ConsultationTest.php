<?php

use App\Enums\BookingStatus;
use App\Livewire\Gudang\Orders\Index as GudangOrders;
use App\Models\BookingMessage;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use Inertia\Testing\AssertableInertia;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutVite();

    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

function consultationCustomer(): User
{
    return User::factory()->create(['role_id' => 5]);
}

test('guests are redirected to login', function () {
    $this->get('/services/consultation')->assertRedirect('/login');
});

test('a verified user gets a consultation thread', function () {
    $this->actingAs(consultationCustomer())
        ->get('/services/consultation')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Features/Services/Pages/ConsultationPage')
            ->has('order.id')
            ->has('order.messages', 0)
        );

    expect(Service::where('service_type', 'consultation')->count())->toBe(1);

    $booking = ServiceBooking::sole();
    expect($booking->current_status)->toBe(BookingStatus::Consultation);
});

test('revisiting reuses the same thread instead of creating another', function () {
    $user = consultationCustomer();

    $this->actingAs($user)->get('/services/consultation')->assertOk();
    $this->actingAs($user)->get('/services/consultation')->assertOk();

    expect(ServiceBooking::count())->toBe(1)
        ->and(Service::where('service_type', 'consultation')->count())->toBe(1);
});

test('each user gets their own thread', function () {
    $this->actingAs(consultationCustomer())->get('/services/consultation')->assertOk();
    $this->actingAs(consultationCustomer())->get('/services/consultation')->assertOk();

    expect(ServiceBooking::count())->toBe(2);
});

test('a message posted to the consultation thread is stored', function () {
    $user = consultationCustomer();
    $this->actingAs($user)->get('/services/consultation');
    $booking = ServiceBooking::sole();

    $this->actingAs($user)
        ->post("/orders/{$booking->id}/messages", ['body' => 'Can you print PETG?'])
        ->assertRedirect();

    $this->assertDatabaseHas('booking_messages', [
        'service_booking_id' => $booking->id,
        'sender_id' => $user->id,
        'body' => 'Can you print PETG?',
    ]);
});

test('admin replies are marked read when the user reopens the thread', function () {
    $user = consultationCustomer();
    $this->actingAs($user)->get('/services/consultation');
    $booking = ServiceBooking::sole();

    $message = BookingMessage::create([
        'service_booking_id' => $booking->id,
        'sender_id' => User::factory()->create(['role_id' => 2])->id,
        'body' => 'Yes, we do.',
    ]);

    $this->actingAs($user)->get('/services/consultation')->assertOk();

    expect($message->fresh()->read_at)->not->toBeNull();
});

// ── The consultation thread must stay out of the order pipeline ──

test('the consultation thread is not listed as one of the user orders', function () {
    $user = consultationCustomer();
    $this->actingAs($user)->get('/services/consultation');

    $this->actingAs($user)
        ->get('/profile')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->has('orders', 0));
});

test('the consultation thread 404s on the order detail page', function () {
    $user = consultationCustomer();
    $this->actingAs($user)->get('/services/consultation');

    $this->actingAs($user)
        ->get('/orders/'.ServiceBooking::sole()->id)
        ->assertNotFound();
});

test('the consultation thread does not reach the warehouse queue', function () {
    $this->actingAs(consultationCustomer())->get('/services/consultation');
    $consultation = ServiceBooking::sole();

    $order = ServiceBooking::factory()->create([
        'user_id' => consultationCustomer()->id,
        'current_status' => BookingStatus::CheckMaterial,
    ]);

    Livewire::actingAs(User::factory()->create(['role_id' => 3]))
        ->test(GudangOrders::class)
        ->assertSee('INV-'.str_pad((string) $order->id, 4, '0', STR_PAD_LEFT))
        ->assertDontSee('INV-'.str_pad((string) $consultation->id, 4, '0', STR_PAD_LEFT));
});
