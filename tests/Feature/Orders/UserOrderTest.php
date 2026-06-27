<?php

use App\Events\BookingMessageSent;
use App\Models\BookingMessage;
use App\Models\BookingPayment;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceProgressUpdate;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(fn () => $this->withoutVite());

function orderUser(): User
{
    $role = Role::firstOrCreate(['name' => 'user_publik']);

    return User::factory()->create(['role_id' => $role->id]);
}

function orderService(array $overrides = []): Service
{
    return Service::create(array_merge([
        'name' => 'FDM Printing',
        'description' => 'Standard FDM 3D printing service',
        'base_price' => 50000,
        'whatsapp_number' => '6281234567890',
    ], $overrides));
}

// ── Guests ────────────────────────────────────────────────
test('guests are redirected to login from the order flow', function () {
    // /services is public — guests can browse the service catalogue
    $this->get('/services')->assertOk();
    // Order history and creation still require auth
    $this->get('/orders')->assertRedirect('/login');
    $this->post('/orders')->assertRedirect('/login');
});

// ── Catalog ───────────────────────────────────────────────
test('authenticated user can view the service catalog', function () {
    $service = orderService(['service_type' => 'printing']);

    $this->actingAs(orderUser())
        ->get('/services')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Services/Pages/ServicesPage')
            ->has('dbServices', 1)
            ->where('dbServices.0.name', $service->name)
            ->where('dbServices.0.service_type', 'printing')
        );
});

// ── Create ────────────────────────────────────────────────
test('user can create an order from a brief', function () {
    $user = orderUser();
    $service = orderService();

    $response = $this->actingAs($user)->post('/orders', [
        'service_id' => $service->id,
        'brief_description' => 'A 20cm prosthetic hand model in white PLA, 1 unit.',
    ]);

    $booking = ServiceBooking::first();
    $response->assertRedirect(route('orders.show', $booking));

    expect($booking->user_id)->toBe($user->id)
        ->and($booking->service_id)->toBe($service->id)
        ->and($booking->current_status)->toBe('pending')
        ->and($booking->brief_description)->toContain('prosthetic hand');

    $transaction = Transaction::first();
    expect($transaction->user_id)->toBe($user->id)
        ->and($transaction->total_amount)->toBe(0)
        ->and($transaction->payment_status)->toBe('unpaid')
        ->and($booking->transaction_id)->toBe($transaction->id);
});

test('order creation requires a valid brief and service', function () {
    $user = orderUser();
    orderService();

    $this->actingAs($user)
        ->post('/orders', ['service_id' => 999999, 'brief_description' => 'x'])
        ->assertSessionHasErrors(['service_id', 'brief_description']);

    expect(ServiceBooking::count())->toBe(0);
});

// ── History ───────────────────────────────────────────────
test('order history only shows the authenticated user own orders', function () {
    $user = orderUser();
    $other = orderUser();
    $service = orderService();

    ServiceBooking::create(['user_id' => $user->id, 'service_id' => $service->id, 'brief_description' => 'Mine']);
    ServiceBooking::create(['user_id' => $other->id, 'service_id' => $service->id, 'brief_description' => 'Theirs']);

    $this->actingAs($user)
        ->get('/orders')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Orders/Pages/OrderHistoryPage')
            ->has('orders', 1)
            ->where('orders.0.serviceName', $service->name)
        );
});

// ── Detail + ownership ────────────────────────────────────
test('owner can view order detail with progress and whatsapp link', function () {
    $user = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
    ]);
    ServiceProgressUpdate::create([
        'service_booking_id' => $booking->id,
        'status_label' => 'printing',
        'percentage' => 40,
        'notes' => 'Halfway there',
        'updated_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->get("/orders/{$booking->id}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Orders/Pages/OrderDetailPage')
            ->where('order.id', $booking->id)
            ->has('order.progress', 1)
            ->where('order.progress.0.statusLabel', 'printing')
            ->where('whatsappUrl', fn ($url) => str_contains((string) $url, 'wa.me/6281234567890'))
        );
});

test('user cannot view another user order detail', function () {
    $user = orderUser();
    $other = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $other->id,
        'service_id' => $service->id,
        'brief_description' => 'Theirs',
    ]);

    $this->actingAs($user)
        ->get("/orders/{$booking->id}")
        ->assertForbidden();
});

// ── Payment details ───────────────────────────────────────
test('order detail surfaces payment termins and summary', function () {
    $user = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
        'agreed_price' => 200000,
    ]);
    BookingPayment::create(['service_booking_id' => $booking->id, 'termin_name' => 'DP', 'amount' => 100000, 'status' => 'paid']);
    BookingPayment::create(['service_booking_id' => $booking->id, 'termin_name' => 'Pelunasan', 'amount' => 100000, 'status' => 'pending']);

    $this->actingAs($user)
        ->get("/orders/{$booking->id}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Orders/Pages/OrderDetailPage')
            ->has('order.payments', 2)
            ->where('order.payments.0.terminName', 'DP')
            ->where('order.payments.0.amountLabel', 'Rp 100.000')
            ->where('order.paymentSummary.totalLabel', 'Rp 200.000')
            ->where('order.paymentSummary.paidLabel', 'Rp 100.000')
            ->where('order.paymentSummary.remainingLabel', 'Rp 100.000')
        );
});

test('owner can upload payment proof for a termin', function () {
    Storage::fake('public');
    $user = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
        'agreed_price' => 100000,
    ]);
    $payment = BookingPayment::create(['service_booking_id' => $booking->id, 'termin_name' => 'DP', 'amount' => 100000, 'status' => 'pending']);

    $this->actingAs($user)
        ->post(route('orders.payments.proof', [$booking, $payment]), ['proof' => UploadedFile::fake()->image('proof.jpg')])
        ->assertRedirect();

    $payment->refresh();
    expect($payment->status)->toBe('awaiting_verification')
        ->and($payment->payment_proof)->not->toBeNull();
    Storage::disk('public')->assertExists($payment->payment_proof);
});

test('user cannot upload proof for another user order', function () {
    $user = orderUser();
    $other = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $other->id,
        'service_id' => $service->id,
        'brief_description' => 'Theirs',
        'agreed_price' => 100000,
    ]);
    $payment = BookingPayment::create(['service_booking_id' => $booking->id, 'termin_name' => 'DP', 'amount' => 100000, 'status' => 'pending']);

    $this->actingAs($user)
        ->post(route('orders.payments.proof', [$booking, $payment]), ['proof' => UploadedFile::fake()->image('proof.jpg')])
        ->assertForbidden();

    expect($payment->refresh()->status)->toBe('pending');
});

test('proof cannot be uploaded for an already paid termin', function () {
    $user = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
        'agreed_price' => 100000,
    ]);
    $payment = BookingPayment::create(['service_booking_id' => $booking->id, 'termin_name' => 'DP', 'amount' => 100000, 'status' => 'paid']);

    $this->actingAs($user)
        ->post(route('orders.payments.proof', [$booking, $payment]), ['proof' => UploadedFile::fake()->image('proof.jpg')])
        ->assertStatus(422);
});

// ── Consultation chat ─────────────────────────────────────
test('order detail surfaces the consultation thread with ownership flags', function () {
    $user = orderUser();
    $admin = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
    ]);
    BookingMessage::create(['service_booking_id' => $booking->id, 'sender_id' => $user->id, 'body' => 'Hi admin']);
    BookingMessage::create(['service_booking_id' => $booking->id, 'sender_id' => $admin->id, 'body' => 'Hello, here is the quote']);

    $this->actingAs($user)
        ->get("/orders/{$booking->id}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/Orders/Pages/OrderDetailPage')
            ->has('order.messages', 2)
            ->where('order.messages.0.body', 'Hi admin')
            ->where('order.messages.0.isMine', true)
            ->where('order.messages.1.isMine', false)
        );
});

test('owner can send a consultation message', function () {
    Event::fake([BookingMessageSent::class]);
    $user = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
    ]);

    $this->actingAs($user)
        ->post(route('orders.messages.store', $booking), ['body' => 'When can you start?'])
        ->assertRedirect();

    $message = BookingMessage::first();
    expect($message->sender_id)->toBe($user->id)
        ->and($message->body)->toBe('When can you start?')
        ->and($booking->messages()->count())->toBe(1);

    Event::assertDispatched(BookingMessageSent::class);
});

test('message body is required', function () {
    $user = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'brief_description' => 'Mine',
    ]);

    $this->actingAs($user)
        ->post(route('orders.messages.store', $booking), ['body' => ''])
        ->assertSessionHasErrors('body');

    expect(BookingMessage::count())->toBe(0);
});

test('user cannot send a message on another user order', function () {
    $user = orderUser();
    $other = orderUser();
    $service = orderService();
    $booking = ServiceBooking::create([
        'user_id' => $other->id,
        'service_id' => $service->id,
        'brief_description' => 'Theirs',
    ]);

    $this->actingAs($user)
        ->post(route('orders.messages.store', $booking), ['body' => 'Sneaky'])
        ->assertForbidden();

    expect(BookingMessage::count())->toBe(0);
});
