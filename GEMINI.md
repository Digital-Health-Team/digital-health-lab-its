<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines - MBKM Internship System

The Laravel Boost guidelines are specifically curated for the MBKM Internship Logbook System. These guidelines serve as the "Source of Truth" for the AI regarding architecture, tech stack, and coding standards.

## Foundational Context
This is a production-grade Laravel application using the TALL stack with strict architectural patterns.

- php - 8.2+
- laravel/framework - v12
- livewire/livewire - v3 (Class-based, NO Volt)
- robsontenorio/mary - (MARY_UI) Latest
- laravel/reverb - (REVERB) Latest
- barryvdh/laravel-dompdf - PDF Generation
- phpoffice/phpword - DOCX Generation
- pestphp/pest - v3
- tailwindcss - v3
- alpinejs - v3

## Architectural Strictness
- **Service Layer Pattern:** Business logic MUST be placed in `App\Services\` (e.g., `InternshipService`, `LogbookService`), NOT in Controllers or Livewire components.
- **Class-based Livewire:** Use standard PHP classes for Livewire components (`app/Livewire`). Do NOT use Volt functional API.
- **MaryUI Components:** Always use MaryUI blade components (`<x-input>`, `<x-table>`, etc.) instead of raw HTML or raw DaisyUI classes.

## Development Environment
- The user is using a Standard Local Environment (XAMPP/Laragon/Native).
- Do not assume Docker/Sail commands unless explicitly asked.

=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools. Use them to understand the project state.

## Searching Documentation
- Use the `search-docs` tool.
- Keywords to use: `mary-ui`, `livewire 3`, `laravel 12`, `reverb`, `pest`.

=== php rules ===

## PHP Standards

- Use **Strict Typing**: `declare(strict_types=1);` in Services and Domain classes.
- Use **Constructor Property Promotion**.
- Use **Enums** for Statuses (e.g., `InternshipStatus`, `LogbookStatus`).

=== service-layer rules ===

## Service Layer Pattern (MANDATORY)

- Controllers and Livewire components should only handle HTTP/Input/Output.
- Complex logic (e.g., overlapping dates check, validation workflows) must live in Service classes.
- Service classes should be injected via the constructor.

<code-snippet name="Service Pattern Example" lang="php">
// app/Services/InternshipService.php
public function createPeriod(User $student, array $data): InternshipPeriod
{
    // Logic for overlap checking here
    if ($this->checkOverlap($student, $data['start_date'], $data['end_date'])) {
        throw ValidationException::withMessages(['date' => 'Overlap detected']);
    }
    
    return InternshipPeriod::create([...]);
}

// app/Livewire/Student/Dashboard.php
public function save(InternshipService $service) 
{
    try {
        $service->createPeriod(auth()->user(), $this->all());
    } catch (ValidationException $e) {
        $this->addError('date', $e->getMessage());
    }
}
</code-snippet>

=== mary-ui/core rules ===

## Mary UI (UI Framework)

- This project uses **Mary UI** exclusively for UI components.
- Do NOT use `flux:` components.
- Do NOT use raw Bootstrap or plain Tailwind if a Mary component exists.
- Components start with `<x-` (e.g., `<x-button>`, `<x-icon>`, `<x-header>`).

### Common Components
- **Forms:** `<x-form>`, `<x-input>`, `<x-select>`, `<x-textarea>`, `<x-file>`, `<x-datetime>`.
- **Layout:** `<x-main>`, `<x-nav>`, `<x-sidebar>`.
- **Data:** `<x-table>`, `<x-card>`, `<x-stat>`.
- **Feedback:** `<x-alert>`, `<x-toast>`, `<x-modal>`, `<x-drawer>`.

<code-snippet name="Mary UI Form Example" lang="blade">
<x-form wire:submit="save">
    <x-input label="Name" wire:model="name" icon="o-user" />
    <x-select label="Role" wire:model="role" :options="$roles" />
    
    <x-slot:actions>
        <x-button label="Cancel" @click="$wire.showModal = false" />
        <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
    </x-slot:actions>
</x-form>
</code-snippet>

<code-snippet name="Mary UI Table Example" lang="blade">
<x-table :headers="$headers" :rows="$users" striped>
    @scope('cell_status', $user)
        <x-badge :value="$user->status" class="badge-primary" />
    @endscope
    @scope('actions', $user)
        <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner />
    @endscope
</x-table>
</code-snippet>

=== livewire/class-based rules ===

## Livewire 3 (Class-Based)

- Do NOT use Volt (`Livewire\Volt\Component`). Use standard classes extending `Livewire\Component`.
- Properties should be public typed properties.
- Use `#[Validate]` attributes for validation rules.
- Use `mount()` for initialization.

<code-snippet name="Class-Based Livewire Example" lang="php">
namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Validate;

class LogbookForm extends Component
{
    #[Validate('required|min:10')]
    public string $activity = '';

    public function save(LogbookService $service)
    {
        $this->validate();
        $service->createEntry($this->activity);
        $this->dispatch('logbook-saved');
    }

    public function render()
    {
        return view('livewire.student.logbook-form');
    }
}
</code-snippet>

=== laravel/reverb rules ===

## Realtime & Broadcasting (Reverb)

- Use Laravel Reverb for WebSockets.
- Events implementing `ShouldBroadcast` should define channels in `channels.php`.
- Frontend listening is handled via Laravel Echo in Livewire or Alpine.

<code-snippet name="Broadcasting Event" lang="php">
class LogbookValidated implements ShouldBroadcast
{
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->studentId),
        ];
    }
}
</code-snippet>

=== pest/core rules ===

## Testing (Pest PHP)

- Prioritize testing the **Service Layer** (Business Logic) and **Livewire Components** (UI Logic).
- Use `php artisan test` to run tests.

<code-snippet name="Service Test Example" lang="php">
it('prevents overlapping internships', function () {
    $student = User::factory()->create();
    // Setup existing internship...
    
    expect(fn() => app(InternshipService::class)->createPeriod($student, [...]))
        ->toThrow(ValidationException::class);
});
</code-snippet>

</laravel-boost-guidelines>