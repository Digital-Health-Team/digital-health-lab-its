<?php

use App\Livewire\Admin\Product\Index;
use App\Models\Event;
use App\Models\OpenSourceProject;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Role;
use App\Models\Service;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $role = Role::create(['name' => 'super_admin', 'display_name' => 'Super Admin']);

    $this->admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
    ]);

    $this->actingAs($this->admin);
});

/**
 * Each admin form must round-trip its `_en` inputs all the way to the database:
 * public prop → rules() → DTO → Action → column. A typo anywhere in that chain
 * silently drops the English copy, and the page then falls back to Indonesian —
 * which looks exactly like "not translated yet" rather than a bug.
 */
test('the product form persists and reloads english copy', function () {
    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'Splint Pergelangan')
        ->set('name_en', 'Wrist Splint')
        ->set('description', 'Splint kustom berbasis scan.')
        ->set('description_en', 'A custom splint built from a scan.')
        ->set('price_min', 1000)
        ->set('price_max', 2000)
        ->call('save')
        ->assertHasNoErrors();

    $product = Product::firstWhere('name', 'Splint Pergelangan');

    expect($product->name_en)->toBe('Wrist Splint');
    expect($product->description_en)->toBe('A custom splint built from a scan.');

    app()->setLocale('en');
    expect($product->localized('name'))->toBe('Wrist Splint');

    // The form must reload the raw columns, never the resolved ones — otherwise an
    // English-locale admin saving again would overwrite the Indonesian copy.
    Livewire::test(Index::class)
        ->call('edit', $product)
        ->assertSet('name', 'Splint Pergelangan')
        ->assertSet('name_en', 'Wrist Splint');
});

test('the service form persists and reloads english copy', function () {
    Livewire::test(App\Livewire\Admin\Service\Index::class)
        ->call('create')
        ->set('name', 'Jasa Scanning 3D')
        ->set('name_en', '3D Scanning Service')
        ->set('service_type', 'scanning')
        ->set('description', 'Pemindaian objek fisik.')
        ->set('description_en', 'Scanning physical objects.')
        ->set('base_price', 100000)
        ->call('save')
        ->assertHasNoErrors();

    $service = Service::firstWhere('name', 'Jasa Scanning 3D');

    expect($service->name_en)->toBe('3D Scanning Service');
    expect($service->description_en)->toBe('Scanning physical objects.');

    Livewire::test(App\Livewire\Admin\Service\Index::class)
        ->call('edit', $service)
        ->assertSet('name', 'Jasa Scanning 3D')
        ->assertSet('name_en', '3D Scanning Service');
});

test('the event form persists and reloads english copy', function () {
    Livewire::test(App\Livewire\Admin\Event\Index::class)
        ->call('create')
        ->set('name', 'Innovatech Medika 2027')
        ->set('year', 2027)
        ->set('theme_title', 'Inovasi Teknologi Kesehatan')
        ->set('theme_title_en', 'Health Technology Innovation')
        ->set('subtitle', 'Edisi ketujuh.')
        ->set('subtitle_en', 'The seventh edition.')
        ->set('description', 'Deskripsi bahasa Indonesia.')
        ->set('description_en', 'An English description.')
        ->set('location', 'Laboratorium Teknologi Kesehatan')
        ->set('location_en', 'Health Technology Laboratory')
        ->call('save')
        ->assertHasNoErrors();

    $event = Event::firstWhere('name', 'Innovatech Medika 2027');

    expect($event->theme_title_en)->toBe('Health Technology Innovation');
    expect($event->subtitle_en)->toBe('The seventh edition.');
    expect($event->description_en)->toBe('An English description.');
    expect($event->location_en)->toBe('Health Technology Laboratory');

    app()->setLocale('en');
    expect($event->localized('location'))->toBe('Health Technology Laboratory');

    Livewire::test(App\Livewire\Admin\Event\Index::class)
        ->call('edit', $event)
        ->assertSet('theme_title', 'Inovasi Teknologi Kesehatan')
        ->assertSet('theme_title_en', 'Health Technology Innovation');
});

test('the publication form persists and reloads english copy including lists', function () {
    Livewire::test(App\Livewire\Admin\Publication\Index::class)
        ->call('create')
        ->set('title', 'Analisis Ortosis Cetak 3D')
        ->set('title_en', 'Analysis of 3D-Printed Orthoses')
        ->set('author', 'Budi Santoso')
        ->set('category', 'Papers')
        ->set('abstract', 'Abstrak bahasa Indonesia.')
        ->set('abstract_en', 'An English abstract.')
        ->set('description', ['Paragraf satu.', 'Paragraf dua.'])
        ->call('addEnItem', 'description_en')
        ->set('description_en', ['Paragraph one.', 'Paragraph two.'])
        ->set('keywords', ['ortosis'])
        ->set('keywords_en', ['orthosis', 'FDM'])
        ->call('save')
        ->assertHasNoErrors();

    $publication = Publication::firstWhere('title', 'Analisis Ortosis Cetak 3D');

    expect($publication->title_en)->toBe('Analysis of 3D-Printed Orthoses');
    expect($publication->abstract_en)->toBe('An English abstract.');
    expect($publication->description_en)->toBe(['Paragraph one.', 'Paragraph two.']);
    expect($publication->keywords_en)->toBe(['orthosis', 'FDM']);

    app()->setLocale('en');
    expect($publication->localized('keywords'))->toBe(['orthosis', 'FDM']);

    Livewire::test(App\Livewire\Admin\Publication\Index::class)
        ->call('edit', $publication)
        ->assertSet('title', 'Analisis Ortosis Cetak 3D')
        ->assertSet('title_en', 'Analysis of 3D-Printed Orthoses')
        ->assertSet('description_en', ['Paragraph one.', 'Paragraph two.']);
});

test('the open source project form persists and reloads english copy including lists', function () {
    Livewire::test(App\Livewire\Admin\OpenSourceProject\Index::class)
        ->call('create')
        ->set('user_id', $this->admin->id)
        ->set('title', 'Deteksi Kraniosinostosis')
        ->set('title_en', 'Craniosynostosis Detection')
        ->set('category', '3d_model')
        ->set('listing_type', 'open_source')
        ->set('caption', 'Analisis sutura kranial.')
        ->set('caption_en', 'Cranial suture analysis.')
        ->set('description', ['Paragraf satu.'])
        ->set('description_en', ['Paragraph one.'])
        ->set('highlights', ['Akurasi tinggi'])
        ->set('highlights_en', ['High accuracy'])
        ->set('includes', ['Berkas STL'])
        ->set('includes_en', ['STL files'])
        ->call('save')
        ->assertHasNoErrors();

    $project = OpenSourceProject::firstWhere('title', 'Deteksi Kraniosinostosis');

    expect($project->title_en)->toBe('Craniosynostosis Detection');
    expect($project->caption_en)->toBe('Cranial suture analysis.');
    expect($project->description_en)->toBe(['Paragraph one.']);
    expect($project->highlights_en)->toBe(['High accuracy']);
    expect($project->includes_en)->toBe(['STL files']);

    app()->setLocale('en');
    expect($project->localized('includes'))->toBe(['STL files']);

    Livewire::test(App\Livewire\Admin\OpenSourceProject\Index::class)
        ->call('edit', $project)
        ->assertSet('title', 'Deteksi Kraniosinostosis')
        ->assertSet('includes_en', ['STL files']);
});

test('the training form persists and reloads english copy including the curriculum', function () {
    Livewire::test(App\Livewire\Admin\Training\Index::class)
        ->call('create')
        ->set('title', 'Pengenalan Cetak 3D')
        ->set('title_en', 'Intro to 3D Printing')
        ->set('subtitle', 'Untuk pemula.')
        ->set('subtitle_en', 'For beginners.')
        ->set('description', 'Deskripsi bahasa Indonesia.')
        ->set('description_en', 'An English description.')
        ->set('location', 'Lab A')
        ->set('location_en', 'Laboratory A')
        ->set('date', '2026-09-01T09:00')
        ->set('instructor_name', 'Budi Santoso')
        ->set('instructor_title', 'Insinyur Biomedis Senior')
        ->set('instructor_title_en', 'Senior Biomedical Engineer')
        ->set('instructor_bio', 'Biografi bahasa Indonesia.')
        ->set('instructor_bio_en', 'An English bio.')
        ->set('price', 0)
        ->set('what_you_will_learn', ['Memahami FDM'])
        ->set('what_you_will_learn_en', ['Understand FDM'])
        ->set('includes', ['Video 5 jam'])
        ->set('includes_en', ['5h of video'])
        ->set('curriculum_modules', [['module' => 'Modul 1', 'lessons' => ['Pelajaran 1']]])
        ->set('curriculum_modules_en', [['module' => 'Module 1', 'lessons' => ['Lesson 1']]])
        ->call('save')
        ->assertHasNoErrors();

    $training = Training::firstWhere('title', 'Pengenalan Cetak 3D');

    expect($training->title_en)->toBe('Intro to 3D Printing');
    expect($training->subtitle_en)->toBe('For beginners.');
    expect($training->location_en)->toBe('Laboratory A');
    expect($training->instructor_title_en)->toBe('Senior Biomedical Engineer');
    expect($training->instructor_bio_en)->toBe('An English bio.');
    expect($training->what_you_will_learn_en)->toBe(['Understand FDM']);
    expect($training->includes_en)->toBe(['5h of video']);
    expect($training->curriculum_en)->toBe([['module' => 'Module 1', 'lessons' => ['Lesson 1']]]);

    app()->setLocale('en');
    expect($training->localized('curriculum'))->toBe([['module' => 'Module 1', 'lessons' => ['Lesson 1']]]);

    Livewire::test(App\Livewire\Admin\Training\Index::class)
        ->call('edit', $training->id)
        ->assertSet('title', 'Pengenalan Cetak 3D')
        ->assertSet('title_en', 'Intro to 3D Printing')
        ->assertSet('curriculum_modules_en', [['module' => 'Module 1', 'lessons' => ['Lesson 1']]]);
});

test('a blank english field leaves the column null so the page falls back', function () {
    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'Produk Tanpa Terjemahan')
        ->set('description', 'Hanya bahasa Indonesia.')
        ->set('price_min', 1000)
        ->set('price_max', 2000)
        ->call('save')
        ->assertHasNoErrors();

    $product = Product::firstWhere('name', 'Produk Tanpa Terjemahan');

    expect($product->name_en)->toBeNull();

    app()->setLocale('en');
    expect($product->localized('name'))->toBe('Produk Tanpa Terjemahan');
});

test('the english repeater helpers reject a field name that is not whitelisted', function () {
    // The field name arrives from the browser, so an un-whitelisted name must not
    // let a caller append to arbitrary component state.
    Livewire::test(App\Livewire\Admin\Publication\Index::class)
        ->call('addEnItem', 'search')
        ->assertStatus(400);
});
