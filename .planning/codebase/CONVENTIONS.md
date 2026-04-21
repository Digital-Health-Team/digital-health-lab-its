# Conventions

## 1. Hybrid Separation
- Public landing pages utilize **Inertia.js over React**.
- Authentication, Admin Dashboard, and User settings utilize **Livewire 4**.

## 2. Frontend Development (React/Inertia)
- **Tailwind v4**: Using inline CSS variables, `app.css` defines the theme instead of `tailwind.config.js`.
- **DaisyUI Theme**: Used alongside Tailwind for UI components.
- **Wayfinder Route Generation**: Import route paths via `@/routes` and `@/actions` rather than hardcoding string URLs or using `ziggy-js`.

## 3. Backend Development (PHP/Laravel)
- **PHP 8.4**: Constructor property promotion, strict typing, arrow functions.
- **Formatting**: Must adhere to `laravel/pint --format agent` styling.
- **Middleware**: Defined natively in `bootstrap/app.php`. No `Http/Kernel.php` per Laravel 11/12 conventions.
- **Auth Actions**: Any custom authentication logic should reside in `App\Actions\Auth\` (or Fortify namespace depending on refactoring).

## 4. Environment & Tooling
- Use `npm run dev` to invoke `concurrently`, spinning up Laravel server, Vite, queues, and logs simultaneously.
