# Architecture

## Paradigm: Hybrid Stack (Blade/Livewire + React/Inertia)

This project employs a unique hybrid approach, likely dividing concerns between frontend user pages and backend admin/auth pages.

**1. Livewire Component Architecture (Admin & Auth)**
- Authentication flows (`/login`, `/register`, `/forgot-password`, `/reset-password`) are handled by Livewire components (`App\Livewire\Auth\*`).
- Admin Dashboards (`/admin/*`) and Settings seem to be implemented via Livewire (`App\Livewire\Admin\Dashboard`, `App\Livewire\Settings`).
- `Mary UI` is installed, likely driving the UI for these Livewire views.

**2. Inertia.js + React architecture (Public/User Frontend)**
- Main user-facing pages or specific SPAs (e.g., `Route::get('/', function () { return Inertia::render('Home'); })`) are built using React and Inertia.
- This allows a rich client-side SPA feel for public visitors or authenticated core user workflows (`Route::get('/user/dashboard')` likely soon to be Inertia-powered or currently Livewire, depending on the migration state).

## Data Flow
- Standard MVC/Action pattern for backend.
- Requests to Inertia routes respond with JSON payloads referencing React components when the client requests via XHR, or SSR/Blade layout rendering on first load.
- Real-time updates delivered via Laravel Echo and Pusher.
