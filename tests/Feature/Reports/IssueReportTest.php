<?php

use App\Enums\ReportStatus;
use App\Livewire\Admin\Report\Index as ReportCenter;
use App\Models\Brand;
use App\Models\IssueReport;
use App\Models\Lab;
use App\Models\RawMaterial;
use App\Models\Role;
use App\Models\Tool;
use App\Models\User;
use App\Notifications\IssueReportStatusUpdated;
use App\Notifications\IssueReportSubmitted;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutVite();

    foreach ([1 => 'super_admin', 2 => 'admin_lab', 3 => 'admin_gudang', 4 => 'mahasiswa', 5 => 'user_publik'] as $id => $name) {
        Role::firstOrCreate(['id' => $id], ['name' => $name]);
    }
});

function reportAdmin(int $roleId = 2): User
{
    return User::factory()->create(['role_id' => $roleId]);
}

// ── Route access ──────────────────────────────────────────
test('all admin roles can open the reports page', function (int $roleId) {
    $this->actingAs(reportAdmin($roleId))
        ->get('/admin/reports')
        ->assertOk();
})->with([
    'super_admin' => [1],
    'admin_lab' => [2],
    'admin_gudang' => [3],
]);

test('regular users are redirected away from the reports page', function () {
    $this->actingAs(reportAdmin(4))
        ->get('/admin/reports')
        ->assertRedirect();
});

// ── Create ────────────────────────────────────────────────
test('any admin can submit a free-form report and warehouse admins are notified', function (int $roleId) {
    Notification::fake();
    $gudang1 = reportAdmin(3);
    $gudang2 = reportAdmin(3);
    $reporter = reportAdmin($roleId);

    Livewire::actingAs($reporter)
        ->test(ReportCenter::class)
        ->call('create')
        ->set('reportType', 'other')
        ->set('description', 'The compressor in lab 2 is making a loud noise.')
        ->call('save')
        ->assertHasNoErrors();

    $report = IssueReport::first();
    expect($report->reporter_id)->toBe($reporter->id)
        ->and($report->status)->toBe(ReportStatus::Open)
        ->and($report->reportable_type)->toBeNull();

    Notification::assertSentTo($gudang1, IssueReportSubmitted::class);
    Notification::assertSentTo($gudang2, IssueReportSubmitted::class);
})->with([
    'super_admin' => [1],
    'admin_lab' => [2],
    'admin_gudang' => [3],
]);

test('a report can target a specific item', function () {
    Notification::fake();
    $brand = Brand::create(['name' => 'eSUN']);
    $material = RawMaterial::create(['brand_id' => $brand->id, 'name' => 'PLA+ White', 'unit' => 'gram']);

    Livewire::actingAs(reportAdmin())
        ->test(ReportCenter::class)
        ->call('create')
        ->set('reportType', 'out_of_stock')
        ->set('reportableType', RawMaterial::class)
        ->set('reportableId', $material->id)
        ->set('description', 'White PLA+ has run out completely.')
        ->call('save')
        ->assertHasNoErrors();

    $report = IssueReport::first();
    expect($report->reportable)->toBeInstanceOf(RawMaterial::class)
        ->and($report->reportable->id)->toBe($material->id);
});

test('type and description are required', function () {
    Livewire::actingAs(reportAdmin())
        ->test(ReportCenter::class)
        ->call('create')
        ->call('save')
        ->assertHasErrors(['reportType', 'description']);

    expect(IssueReport::count())->toBe(0);
});

// ── Scoping ───────────────────────────────────────────────
test('lab admins see only their own reports while gudang sees all', function () {
    $lab = reportAdmin(2);
    $other = reportAdmin(2);

    $mine = IssueReport::factory()->create(['reporter_id' => $lab->id, 'description' => 'my-own-issue-xyz']);
    $theirs = IssueReport::factory()->create(['reporter_id' => $other->id, 'description' => 'foreign-issue-abc']);

    Livewire::actingAs($lab)
        ->test(ReportCenter::class)
        ->assertSee('my-own-issue-xyz')
        ->assertDontSee('foreign-issue-abc');

    Livewire::actingAs(reportAdmin(3))
        ->test(ReportCenter::class)
        ->assertSee('my-own-issue-xyz')
        ->assertSee('foreign-issue-abc');
});

test('a reporter cannot open another admin report detail', function () {
    $foreign = IssueReport::factory()->create(['reporter_id' => reportAdmin()->id]);

    Livewire::actingAs(reportAdmin())
        ->test(ReportCenter::class)
        ->call('viewReport', $foreign->id)
        ->assertForbidden();
});

// ── Reporter edit / delete ────────────────────────────────
test('the reporter can edit their own open report', function () {
    $reporter = reportAdmin();
    $report = IssueReport::factory()->create(['reporter_id' => $reporter->id, 'type' => 'damaged']);

    Livewire::actingAs($reporter)
        ->test(ReportCenter::class)
        ->call('edit', $report->id)
        ->set('description', 'Updated: the spool arrived cracked.')
        ->call('save')
        ->assertHasNoErrors();

    expect($report->refresh()->description)->toContain('cracked');
});

test('a resolved report can no longer be edited or deleted by the reporter', function () {
    $reporter = reportAdmin();
    $report = IssueReport::factory()->resolved()->create(['reporter_id' => $reporter->id]);

    Livewire::actingAs($reporter)
        ->test(ReportCenter::class)
        ->call('edit', $report->id)
        ->assertForbidden();

    Livewire::actingAs($reporter)
        ->test(ReportCenter::class)
        ->call('confirmDelete', $report->id)
        ->call('deleteRecord')
        ->assertForbidden();

    expect(IssueReport::whereKey($report->id)->exists())->toBeTrue();
});

test('the reporter can delete their own open report', function () {
    $reporter = reportAdmin();
    $report = IssueReport::factory()->create(['reporter_id' => $reporter->id]);

    Livewire::actingAs($reporter)
        ->test(ReportCenter::class)
        ->call('confirmDelete', $report->id)
        ->call('deleteRecord');

    expect(IssueReport::count())->toBe(0);
});

// ── Resolution (warehouse only) ───────────────────────────
test('warehouse admin resolves a report and the reporter is notified', function () {
    Notification::fake();
    $reporter = reportAdmin(2);
    $gudang = reportAdmin(3);
    $report = IssueReport::factory()->create(['reporter_id' => $reporter->id]);

    Livewire::actingAs($gudang)
        ->test(ReportCenter::class)
        ->call('viewReport', $report->id)
        ->set('newStatus', 'resolved')
        ->set('resolutionNote', 'Replacement spool ordered and restocked.')
        ->call('updateStatus')
        ->assertHasNoErrors();

    $report->refresh();
    expect($report->status)->toBe(ReportStatus::Resolved)
        ->and($report->resolved_by)->toBe($gudang->id)
        ->and($report->resolved_at)->not->toBeNull()
        ->and($report->resolution_note)->toContain('restocked');

    Notification::assertSentTo($reporter, IssueReportStatusUpdated::class);
});

test('reopening a report clears the resolver metadata', function () {
    $gudang = reportAdmin(3);
    $report = IssueReport::factory()->resolved()->create();

    Livewire::actingAs($gudang)
        ->test(ReportCenter::class)
        ->call('viewReport', $report->id)
        ->set('newStatus', 'in_review')
        ->call('updateStatus');

    $report->refresh();
    expect($report->status)->toBe(ReportStatus::InReview)
        ->and($report->resolved_by)->toBeNull()
        ->and($report->resolved_at)->toBeNull();
});

test('lab admin cannot change a report status', function () {
    $lab = reportAdmin(2);
    $report = IssueReport::factory()->create(['reporter_id' => $lab->id]);

    Livewire::actingAs($lab)
        ->test(ReportCenter::class)
        ->call('viewReport', $report->id)
        ->set('newStatus', 'resolved')
        ->call('updateStatus')
        ->assertForbidden();

    expect($report->refresh()->status)->toBe(ReportStatus::Open);
});

test('tools can be linked as reportables', function () {
    $lab = Lab::create(['name' => 'Lab Tekkes']);
    $tool = Tool::create(['name' => 'Prusa MK4', 'lab_id' => $lab->id, 'created_by' => reportAdmin(3)->id]);
    $report = IssueReport::factory()->forReportable($tool)->create(['type' => 'faulty']);

    expect($report->reportable)->toBeInstanceOf(Tool::class)
        ->and($report->reportable->name)->toBe('Prusa MK4');
});
