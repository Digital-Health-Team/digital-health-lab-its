# Concerns & Tech Debt

## 1. Hybrid Stack Complexity
- Mixing **Livewire** (for Admin/Auth) and **React+Inertia** (for Home) introduces two completely separate state management paradigms and component lifecycles within the same application.
  - **Risk**: Duplication of UI components (e.g., a "Button" component might need to be built in Blade/MaryUI for Livewire, and also in React/DaisyUI for Inertia).
  - **Risk**: Difficult onboarding context-switching for developers.

## 2. TailwindCSS v4 Setup
- The `package.json` configures Tailwind v4 and DaisyUI 5, but Vite config has both `tailwindcss()` and `laravel({ input: [... 'resources/css/app.css'] })`.
- Ensuring Tailwind v4's new `@theme` configurations don't conflict with DaisyUI requires careful testing.

## 3. Wayfinder Maintenance
- Types must re-generate whenever backend routes are altered. Failure to continuously run the Wayfinder generator/dev watcher may cause frontend type errors.

## 4. Fortify Namespacing
- Recent logs show a goal of renaming `App\Actions\Fortify` to `App\Actions\Auth`. Make sure all configs and providers reflect this to avoid runtime namespace errors.
