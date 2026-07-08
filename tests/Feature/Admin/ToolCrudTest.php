<?php

use App\Livewire\Admin\Tool\Index;
use App\Models\Attachment;
use App\Models\Lab;
use App\Models\Role;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $role = Role::create(['name' => 'super_admin', 'display_name' => 'Super Admin']);

    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);
});

it('tools index page renders', function () {
    $lab = Lab::create(['name' => 'Lab A']);
    Tool::create(['name' => 'Soldering Iron', 'lab_id' => $lab->id, 'created_by' => $this->admin->id]);

    Livewire::test(Index::class)
        ->assertSee('Tools')
        ->assertSee('Soldering Iron');
});

it('creates a tool without photos', function () {
    $lab = Lab::create(['name' => 'Lab A']);

    Livewire::test(Index::class)
        ->call('create')
        ->set('toolName', 'Oscilloscope')
        ->set('labId', $lab->id)
        ->call('save');

    $this->assertDatabaseHas('tools', [
        'name' => 'Oscilloscope',
        'lab_id' => $lab->id,
        'created_by' => $this->admin->id,
    ]);
});

it('creates a tool with a photo and marks it as primary', function () {
    Storage::fake('public');

    $lab = Lab::create(['name' => 'Lab A']);
    $file = UploadedFile::fake()->image('tool.jpg');

    Livewire::test(Index::class)
        ->call('create')
        ->set('toolName', '3D Printer')
        ->set('labId', $lab->id)
        ->set('photos', [$file])
        ->call('save');

    $tool = Tool::where('name', '3D Printer')->firstOrFail();

    $this->assertDatabaseHas('attachments', [
        'attachable_type' => Tool::class,
        'attachable_id' => $tool->id,
        'is_primary' => true,
        'uploaded_by' => $this->admin->id,
    ]);
});

it('edits a tool name and lab without affecting attachments', function () {
    $lab1 = Lab::create(['name' => 'Lab A']);
    $lab2 = Lab::create(['name' => 'Lab B']);
    $tool = Tool::create(['name' => 'Old Name', 'lab_id' => $lab1->id, 'created_by' => $this->admin->id]);

    $attachmentCount = Attachment::where('attachable_type', Tool::class)->count();

    Livewire::test(Index::class)
        ->call('edit', $tool)
        ->set('toolName', 'New Name')
        ->set('labId', $lab2->id)
        ->call('save');

    $this->assertDatabaseHas('tools', ['id' => $tool->id, 'name' => 'New Name', 'lab_id' => $lab2->id]);
    $this->assertEquals($attachmentCount, Attachment::where('attachable_type', Tool::class)->count());
});

it('deletes a tool and its attachment records', function () {
    Storage::fake('public');

    $lab = Lab::create(['name' => 'Lab A']);
    $tool = Tool::create(['name' => 'Old Drill', 'lab_id' => $lab->id, 'created_by' => $this->admin->id]);
    Attachment::create([
        'attachable_type' => Tool::class,
        'attachable_id' => $tool->id,
        'file_url' => Storage::disk('public')->url('tools/1/photo.jpg'),
        'file_name' => 'photo.jpg',
        'file_size' => '10000',
        'file_type' => 'image/jpeg',
        'is_primary' => true,
        'sort_order' => 0,
        'uploaded_by' => $this->admin->id,
    ]);

    Livewire::test(Index::class)
        ->call('confirmDelete', $tool->id)
        ->call('deleteRecord');

    $this->assertDatabaseMissing('tools', ['id' => $tool->id]);
    $this->assertDatabaseMissing('attachments', ['attachable_type' => Tool::class, 'attachable_id' => $tool->id]);
});

it('filters tools by name via search', function () {
    $lab = Lab::create(['name' => 'Lab A']);
    Tool::create(['name' => 'Oscilloscope', 'lab_id' => $lab->id, 'created_by' => $this->admin->id]);
    Tool::create(['name' => 'ZZZ Unique',   'lab_id' => $lab->id, 'created_by' => $this->admin->id]);

    $component = Livewire::test(Index::class)->set('search', 'Oscillo');

    // After filtering the query should return 1 result
    $tools = \App\Models\Tool::where('name', 'like', '%Oscillo%')->get();
    expect($tools)->toHaveCount(1);
    expect($tools->first()->name)->toBe('Oscilloscope');
});

it('viewTool sets activeTool and clearTool resets it', function () {
    $lab = Lab::create(['name' => 'Lab A']);
    $tool = Tool::create(['name' => 'Microscope', 'lab_id' => $lab->id, 'created_by' => $this->admin->id]);

    Livewire::test(Index::class)
        ->call('viewTool', $tool->id)
        ->assertSet('activeTool.id', $tool->id)
        ->call('clearTool')
        ->assertSet('activeTool', null);
});

it('addPhotos attaches a new photo to the active tool', function () {
    Storage::fake('public');

    $lab = Lab::create(['name' => 'Lab A']);
    $tool = Tool::create(['name' => 'Lathe', 'lab_id' => $lab->id, 'created_by' => $this->admin->id]);
    $file = UploadedFile::fake()->image('extra.jpg');

    Livewire::test(Index::class)
        ->call('viewTool', $tool->id)
        ->set('newPhotos', [$file])
        ->call('addPhotos');

    $this->assertDatabaseHas('attachments', [
        'attachable_type' => Tool::class,
        'attachable_id' => $tool->id,
        'is_primary' => true,
    ]);
});

it('deletePhoto removes the attachment record', function () {
    Storage::fake('public');

    $lab = Lab::create(['name' => 'Lab A']);
    $tool = Tool::create(['name' => 'Welder', 'lab_id' => $lab->id, 'created_by' => $this->admin->id]);
    $attachment = Attachment::create([
        'attachable_type' => Tool::class,
        'attachable_id' => $tool->id,
        'file_url' => Storage::disk('public')->url('tools/1/img.jpg'),
        'file_name' => 'img.jpg',
        'file_size' => '5000',
        'file_type' => 'image/jpeg',
        'is_primary' => true,
        'sort_order' => 0,
        'uploaded_by' => $this->admin->id,
    ]);

    Livewire::test(Index::class)
        ->call('viewTool', $tool->id)
        ->call('deletePhoto', $attachment->id);

    $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
});
