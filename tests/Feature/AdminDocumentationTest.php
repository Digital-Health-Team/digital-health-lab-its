<?php

use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

test('admin documentation requires authentication', function () {
    $this->get('/admin/documentations')
        ->assertRedirect('/login');
});

test('admin documentation is accessible by admin_lab', function () {
    $user = User::factory()->create(['role_id' => 2]);

    $this->actingAs($user)
        ->get('/admin/documentations')
        ->assertOk()
        ->assertSee('Admin');
});

test('admin documentation is accessible by super_admin', function () {
    $user = User::factory()->create(['role_id' => 1]);

    $this->actingAs($user)
        ->get('/admin/documentations')
        ->assertOk();
});
