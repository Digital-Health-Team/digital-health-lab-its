<?php

use App\Livewire\Gudang\Orders\Index as GudangOrders;
use App\Models\Role;
use App\Models\ServiceBooking;
use App\Models\User;
use App\Notifications\MaterialUnavailable;
use App\Notifications\MaterialVerified;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutVite();

    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

function gudangAdmin(): User
{
    return User::factory()->create(['role_id' => 3]);
}

function gudangOrder(string $status = 'check_material'): ServiceBooking
{
    return ServiceBooking::factory()->create([
        'user_id' => User::factory()->create(['role_id' => 5])->id,
        'current_status' => $status,
    ]);
}

// ── Route access ──────────────────────────────────────────
test('warehouse admin can open the incoming orders page', function () {
    $this->actingAs(gudangAdmin())
        ->get('/gudang/orders')
        ->assertOk();
});

test('other roles are redirected away from the gudang orders page', function (int $roleId) {
    $this->actingAs(User::factory()->create(['role_id' => $roleId]))
        ->get('/gudang/orders')
        ->assertRedirect();
})->with([
    'admin_lab' => [2],
    'mahasiswa' => [4],
]);

test('guests are redirected to login', function () {
    $this->get('/gudang/orders')->assertRedirect('/login');
});

// ── Listing ───────────────────────────────────────────────
test('the list shows only orders awaiting the material check', function () {
    $reviewBrief = gudangOrder('review_brief');
    $checkMaterial = gudangOrder('check_material');
    $legacyPending = gudangOrder('pending');
    $slicing = gudangOrder('slicing');

    Livewire::actingAs(gudangAdmin())
        ->test(GudangOrders::class)
        ->assertSee('INV-'.str_pad($reviewBrief->id, 4, '0', STR_PAD_LEFT))
        ->assertSee('INV-'.str_pad($checkMaterial->id, 4, '0', STR_PAD_LEFT))
        ->assertSee('INV-'.str_pad($legacyPending->id, 4, '0', STR_PAD_LEFT))
        ->assertDontSee('INV-'.str_pad($slicing->id, 4, '0', STR_PAD_LEFT));
});

// ── Verify ────────────────────────────────────────────────
test('verifying sets the verification fields and notifies lab admins', function () {
    Notification::fake();
    $labAdmin = User::factory()->create(['role_id' => 2]);
    $superAdmin = User::factory()->create(['role_id' => 1]);
    $gudang = gudangAdmin();
    $booking = gudangOrder();

    Livewire::actingAs($gudang)
        ->test(GudangOrders::class)
        ->call('verify', $booking->id);

    $booking->refresh();
    expect($booking->isMaterialVerified())->toBeTrue()
        ->and($booking->material_verified_by)->toBe($gudang->id)
        ->and($booking->material_flagged_at)->toBeNull();

    Notification::assertSentTo($labAdmin, MaterialVerified::class);
    Notification::assertSentTo($superAdmin, MaterialVerified::class);
    Notification::assertNotSentTo($gudang, MaterialVerified::class);
});

test('verifying clears a previous flag', function () {
    $gudang = gudangAdmin();
    $booking = gudangOrder();
    $booking->update(['material_flagged_at' => now(), 'material_flag_note' => 'PLA stock empty']);

    Livewire::actingAs($gudang)
        ->test(GudangOrders::class)
        ->call('verify', $booking->id);

    $booking->refresh();
    expect($booking->isMaterialVerified())->toBeTrue()
        ->and($booking->material_flagged_at)->toBeNull()
        ->and($booking->material_flag_note)->toBeNull();
});

test('lab admin cannot verify materials', function () {
    $booking = gudangOrder();

    Livewire::actingAs(User::factory()->create(['role_id' => 2]))
        ->test(GudangOrders::class)
        ->call('verify', $booking->id)
        ->assertForbidden();

    expect($booking->refresh()->isMaterialVerified())->toBeFalse();
});

test('a booking already past check_material cannot be verified', function () {
    $booking = gudangOrder('printing');

    Livewire::actingAs(gudangAdmin())
        ->test(GudangOrders::class)
        ->call('verify', $booking->id)
        ->assertForbidden();
});

// ── Flag ──────────────────────────────────────────────────
test('flagging requires a note, stores it, and notifies lab admins', function () {
    Notification::fake();
    $labAdmin = User::factory()->create(['role_id' => 2]);
    $gudang = gudangAdmin();
    $booking = gudangOrder();

    $component = Livewire::actingAs($gudang)
        ->test(GudangOrders::class)
        ->call('viewBooking', $booking->id)
        ->call('flag')
        ->assertHasErrors(['flagNote' => 'required']);

    $component->set('flagNote', 'Out of white PLA — restock ETA next week')
        ->call('flag')
        ->assertHasNoErrors();

    $booking->refresh();
    expect($booking->material_flagged_at)->not->toBeNull()
        ->and($booking->material_flag_note)->toContain('white PLA')
        ->and($booking->isMaterialVerified())->toBeFalse()
        ->and($booking->current_status->value)->toBe('check_material');

    Notification::assertSentTo($labAdmin, MaterialUnavailable::class);
});
