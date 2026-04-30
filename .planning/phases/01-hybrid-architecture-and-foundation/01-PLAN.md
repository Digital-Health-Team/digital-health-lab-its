---
wave: 1
depends_on: []
files_modified:
  - composer.json
  - package.json
  - bootstrap/app.php
  - tsconfig.json
  - vite.config.ts
  - vite.config.js
  - resources/views/app.blade.php
  - resources/js/app.tsx
  - resources/css/public.css
  - resources/js/pages/Home.tsx
  - routes/web.php
autonomous: true
---

# Phase 1: Hybrid Architecture & Foundation - Plan

## Requirements Covered
- **REQ-17:** Public frontend must use React 19 + Inertia v3.
- **REQ-18:** Admin frontend must use Livewire v3 + Alpine.js.

## Task 1: Install Dependencies
<task>
  <read_first>
    - composer.json
    - package.json
  </read_first>
  <action>
    Run composer and npm commands to install the required packages for Inertia, React, and Wayfinder.
    1. Run: `composer require inertiajs/inertia-laravel:^3.0 laravel/wayfinder`
    2. Run: `npm install @inertiajs/react@^3.0 @inertiajs/vite@^3.0 react@^19.0 react-dom@^19.0`
    3. Run: `npm install -D @vitejs/plugin-react @laravel/vite-plugin-wayfinder @types/react @types/react-dom typescript`
  </action>
  <acceptance_criteria>
    - `composer.json` contains `inertiajs/inertia-laravel` and `laravel/wayfinder`.
    - `package.json` contains `@inertiajs/react`, `react`, and `@vitejs/plugin-react`.
  </acceptance_criteria>
</task>

## Task 2: Configure TypeScript and Vite
<task>
  <read_first>
    - vite.config.js
  </read_first>
  <action>
    Create `tsconfig.json` and convert the Vite configuration to TypeScript (`vite.config.ts`).
    
    1. Create `tsconfig.json` with the following content:
    ```json
    {
        "compilerOptions": {
            "target": "ESNext",
            "module": "ESNext",
            "moduleResolution": "bundler",
            "strict": true,
            "jsx": "react-jsx",
            "esModuleInterop": true,
            "skipLibCheck": true,
            "forceConsistentCasingInFileNames": true,
            "resolveJsonModule": true,
            "isolatedModules": true,
            "noEmit": true,
            "baseUrl": ".",
            "paths": {
                "@/*": ["resources/js/*"],
                "@/actions/*": [".vite/wayfinder/actions/*"],
                "@/routes/*": [".vite/wayfinder/routes/*"]
            }
        },
        "include": ["resources/js/**/*.ts", "resources/js/**/*.tsx", ".vite/wayfinder/**/*.ts"],
        "exclude": ["node_modules", "vendor"]
    }
    ```
    
    2. Create `vite.config.ts` (replacing `vite.config.js`) with dual entry points and plugins:
    ```typescript
    import { defineConfig } from "vite";
    import laravel from "laravel-vite-plugin";
    import tailwindcss from "@tailwindcss/vite";
    import inertia from "@inertiajs/vite";
    import react from "@vitejs/plugin-react";
    import { wayfinder } from "@laravel/vite-plugin-wayfinder";

    export default defineConfig({
        plugins: [
            laravel({
                input: [
                    "resources/css/app.css",
                    "resources/js/app.js",
                    "resources/css/public.css",
                    "resources/js/app.tsx"
                ],
                refresh: true,
            }),
            inertia(),
            react({ babel: { plugins: ["babel-plugin-react-compiler"] } }),
            tailwindcss(),
            wayfinder({ formVariants: true }),
        ],
        server: {
            cors: true,
            watch: { ignored: ["**/storage/framework/views/**"] },
        },
    });
    ```
    
    3. Delete `vite.config.js`.
  </action>
  <acceptance_criteria>
    - `tsconfig.json` exists and contains `@/actions/*` path alias.
    - `vite.config.ts` exists and includes `laravel`, `inertia`, `react`, `tailwindcss`, and `wayfinder` plugins.
    - `vite.config.js` is deleted.
  </acceptance_criteria>
</task>

## Task 3: Backend Setup (Middleware & Routing)
<task>
  <read_first>
    - bootstrap/app.php
    - routes/web.php
  </read_first>
  <action>
    Set up the Inertia middleware and update the root route.
    
    1. Run `php artisan inertia:middleware --no-interaction`.
    2. Modify `bootstrap/app.php` to append `\App\Http\Middleware\HandleInertiaRequests::class` to the `web` middleware group:
    ```php
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
        \App\Http\Middleware\SetTimezone::class,
        \App\Http\Middleware\HandleInertiaRequests::class,
    ]);
    ```
    3. Modify `routes/web.php` to serve the React page instead of the Blade view for `GET /`:
    ```php
    use Inertia\Inertia;

    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');
    ```
  </action>
  <acceptance_criteria>
    - `app/Http/Middleware/HandleInertiaRequests.php` exists.
    - `bootstrap/app.php` contains `\App\Http\Middleware\HandleInertiaRequests::class`.
    - `routes/web.php` uses `Inertia::render('Home')` for the `/` route.
  </acceptance_criteria>
</task>

## Task 4: Frontend Scaffolding
<task>
  <read_first>
    - none
  </read_first>
  <action>
    Create the necessary public CSS, Blade shell, React entry, and Home page.
    
    1. Create `resources/css/public.css`:
    ```css
    @import "tailwindcss";
    @source '../js';

    @theme {
        --font-sans: "Inter", ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    ```
    
    2. Create `resources/views/app.blade.php`:
    ```html
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />

            @viteReactRefresh 
            @vite(['resources/css/public.css', 'resources/js/app.tsx'])

            <x-inertia::head>
                <title>{{ config('app.name', 'Gretiva') }}</title>
            </x-inertia::head>
        </head>
        <body class="antialiased">
            <x-inertia::app />
        </body>
    </html>
    ```
    
    3. Create `resources/js/app.tsx`:
    ```tsx
    import { createInertiaApp } from "@inertiajs/react";
    import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
    import { createRoot, hydrateRoot } from "react-dom/client";

    const appName = import.meta.env.VITE_APP_NAME || "Laravel";

    createInertiaApp({
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob("./pages/**/*.tsx")) as any,
        progress: { color: "#4B5563" },
        setup({ el, App, props }) {
            if (import.meta.env.SSR) {
                hydrateRoot(el!, <App {...props} />);
                return;
            }
            createRoot(el!).render(<App {...props} />);
        },
    });
    ```
    
    4. Create `resources/js/pages/Home.tsx`:
    ```tsx
    import { Head } from "@inertiajs/react";

    export default function Home() {
        return (
            <>
                <Head title="Home" />
                <div className="flex min-h-screen items-center justify-center bg-slate-950">
                    <div className="text-center">
                        <h1 className="text-4xl font-bold text-white">Gretiva</h1>
                        <p className="mt-2 text-slate-400">React 19 + Inertia v3 + Wayfinder — Stack Online ✅</p>
                    </div>
                </div>
            </>
        );
    }
    ```
    
    5. Run `php artisan wayfinder:generate --no-interaction` to generate typescript routes.
  </action>
  <acceptance_criteria>
    - `resources/css/public.css` exists with tailwind imports.
    - `resources/views/app.blade.php` exists and contains `<x-inertia::head>` and `<x-inertia::app>`.
    - `resources/js/app.tsx` exists and calls `createInertiaApp`.
    - `resources/js/pages/Home.tsx` exists.
    - `.vite/wayfinder` directory is generated with routes.
  </acceptance_criteria>
</task>

## Verification
- `<must_have>` `npm run build` executes without errors. `</must_have>`
- `<must_have>` Browsing to `/` renders the React Inertia app with "Gretiva" heading. `</must_have>`
