# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

ITS Medical Technology Digital Repository & Innovation Hub — a dual-purpose **Modern Monolith** platform serving as a digital archive for student innovations and an e-commerce hub for made-by-order 3D printing services.

## Common Commands

```bash
# Full dev environment (server + queue + logs + vite)
composer run dev

# Run tests
php artisan test --compact
php artisan test --compact tests/Feature/SomeTest.php
php artisan test --compact --filter=testName

# Format PHP (run after every PHP change)
vendor/bin/pint --dirty --format agent

# Build frontend assets
npm run build
npm run dev

# Database
php artisan migrate --seed
php artisan tinker --execute 'Your::code();'
```

## Architecture

### Hybrid Frontend Strategy

- **Public/User pages** — React 19 + Inertia.js v3. Entry point: `resources/js/app.tsx`. Inertia pages live in `resources/js/pages/`.
- **Admin dashboard** — Livewire v4 + Alpine.js + Blade. Components live in `app/Livewire/Admin/`.
- Both are bundled by Vite via two separate entry points (`resources/js/app.tsx` for React, `resources/js/app.js` for Livewire/Alpine).

### Action-Oriented Backend

Business logic lives exclusively in `app/Actions/`, not in controllers or Livewire components. Controllers are thin HTTP dispatchers. The flow is always: `Controller/Livewire → DTO → Action`.

- `app/Actions/` — single-responsibility classes (e.g., `CreateServiceBookingAction`, `DeductRawMaterialAction`)
- `app/DTOs/` — strongly-typed data transfer objects that validate payloads between layers
- `app/Services/` — external integrations (e.g., S3StorageService)

### Feature-Based React Frontend

```
resources/js/
├── Core/           # Logic-agnostic primitives: Components/common (Box, Text, Heading, Image, Container),
│                   # Types/, and global helpers
├── components/     # Shared UI components
├── pages/          # Inertia entry points (one file per route)
├── routes/         # Wayfinder-generated type-safe route helpers
└── actions/        # Wayfinder-generated TypeScript bindings for Laravel actions
```

Feature modules must not cross-import each other. Domain isolation is enforced by convention.

### Key Packages

- **`laravel/wayfinder`** — generates TypeScript functions from PHP routes/controllers. Import from `@/actions/` (controllers) or `@/routes/` (named routes). Rebuilt automatically via `@laravel/vite-plugin-wayfinder` during `npm run dev/build`.
- **`livewire/livewire` v4** — admin CRUD. Note: Livewire v4 (not v3) is installed.
- **`robsontenorio/mary`** — MaryUI component library for Livewire admin views.
- **`inertiajs/inertia-laravel` v3** — `Inertia::lazy()` is removed; use `Inertia::optional()` instead.
- **`spatie/laravel-translatable`** — multilingual model fields.
- **HeroUI** (`@heroui/react`) — React UI components for public pages.

### Database Schema (Core Tables)

- `users` + `user_profiles` — authentication and metadata (NIM, Faculty)
- `open_source_projects` — student works requiring admin validation
- `services` + `service_bookings` + `service_progress_updates` — 3D printing order workflow
- `raw_materials` + `raw_material_movements` — inventory tracking tied to bookings
- `inventories` + `inventory_usages` — broader inventory management
- `transactions` — central payment ledger
- `attachments` — polymorphic table for all files (images, 3D models); uses `attachable_type` / `attachable_id`
- `raw_material_movements`, `reimbursements`, master lookup tables (brands, colors, categories, labs)

## Critical Implementation Rules

### React Components

Never use native HTML tags (`<div>`, `<p>`, `<h1>`–`<h6>`) in React pages or feature code. Always use primitives from `Core/Components/common/`:

| Instead of | Use |
|---|---|
| `<div>`, `<section>` | `<Box>` |
| `<h1>`–`<h6>` | `<Heading level={1–6}>` |
| `<p>`, `<span>` | `<Text>` |
| `<img>` | `<Image>` |
| `.container` div | `<Container>` |

### 3D Model Rendering

`React Three Fiber` canvas components **must** use `React.lazy` + `<Suspense>`. Loading Three.js on initial page load destroys Lighthouse scores.

### File Uploads

Large `.stl`/`.obj` files must never pass through Laravel's RAM. The pattern: frontend requests a pre-signed S3 URL from Laravel → frontend uploads directly to S3 → backend saves the resulting S3 path into `attachments`.

### Middleware Registration

Laravel 12: middleware is configured in `bootstrap/app.php` via `Application::configure()->withMiddleware()`, not in `app/Http/Kernel.php`.

### Testing

- Use Pest v4 for all tests (`php artisan make:test --pest {Name}`)
- Tests run against SQLite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`)
- Every code change requires a new or updated test
- Run `vendor/bin/pint --dirty --format agent` before finalizing any PHP changes

## User Roles

| Role | Interface | Access |
|---|---|---|
| Guest | React | Browse catalog, view 3D, download open-source |
| User (Student/Client) | React | Submit works, book services, negotiate pricing |
| Admin Lab | Livewire | Validate submissions, update service progress, manage inventory |
| Super Admin | Livewire | Full system config, role management, financial reporting |
