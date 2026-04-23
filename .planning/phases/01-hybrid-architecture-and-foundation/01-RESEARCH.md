# Phase 1: Hybrid Architecture & Foundation - Research

## Objective
Establish the base application with React 19 + Inertia v3 for the public frontend and Livewire for the admin backend without breaking the existing Livewire/maryUI application.

## 1. Current System State
- **Backend:** Laravel 12, Livewire 4, Fortify v1.
- **Admin Frontend:** Blade + maryUI + daisyUI (`resources/css/app.css` + `resources/js/app.js`).
- **Public Frontend:** Uses static `welcome.blade.php`.
- **Vite Configuration:** Single entry (`app.css`, `app.js`).

## 2. Requirements & Constraints
- Must introduce **Inertia.js v3** and **React 19** for the public frontend.
- Must preserve the **Livewire** application for the Admin frontend.
- Inertia pages must be served via Blade components (`<x-inertia::head>` and `<x-inertia::app>`).
- Must use **Laravel Wayfinder** for strict frontend-backend type safety.
- Dual CSS entry points: `app.css` (Admin/daisyUI) and `public.css` (React/Raw Tailwind v4) to prevent class pollution.

## 3. Implementation Plan Details
- **Dependencies:** 
  - Backend: `inertiajs/inertia-laravel:^3.0`, `laravel/wayfinder`.
  - Frontend: `@inertiajs/react@^3.0`, `@inertiajs/vite@^3.0`, `react@^19.0`, `react-dom@^19.0`.
  - Dev: `@vitejs/plugin-react`, `@laravel/vite-plugin-wayfinder`, TypeScript definitions.
- **Middleware:** `HandleInertiaRequests` needs to be published and registered in `bootstrap/app.php`.
- **Vite:** Needs to be migrated to `vite.config.ts` to support both `react()` and `laravel()` plugins, along with `wayfinder()` and `inertia()` plugins.
- **Typescript:** `tsconfig.json` is required with path aliases for `@/*`, `@/actions/*`, and `@/routes/*`.
- **Routing:** Change `routes/web.php` for `GET /` to render `Inertia::render('Home')`.

## 4. Risks & Mitigations
- **CSS Bleed:** DaisyUI styles from `app.css` could break the React frontend.
  - *Mitigation:* Explicitly isolate entries. `public.css` will only use raw `@import "tailwindcss"`.
- **SSR Conflicts:** We are setting up Inertia without SSR initially, but the setup code must account for it just in case.
- **Wayfinder Route Generation:** Requires running `php artisan wayfinder:generate` before Vite runs to ensure types exist.

## 5. Security Threat Model
- ASVS Level 1 baseline.
- No significant changes to auth mechanisms in Phase 1 (Fortify remains in use).
- React components must safely render user inputs (React handles XSS by default).

## RESEARCH COMPLETE
Ready for planning.
