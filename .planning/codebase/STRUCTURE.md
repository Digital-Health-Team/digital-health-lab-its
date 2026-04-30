# Codebase Structure

## Directory Layout

```text
/
├── app/
│   ├── Livewire/      # Livewire components (Auth, Admin, Settings, User)
│   ├── Actions/       # Application logic (Fortify actions)
│   └── ...            # Standard Laravel (Models, Providers, Http)
├── bootstrap/         # bootstrap/app.php (Laravel 11+ declarative config)
├── config/            # Configuration files (fortify.php, etc.)
├── database/          # Migrations, seeders, factories
├── lang/              # Translation files
├── public/            # Static assets
├── resources/
│   ├── css/           # Tailwind entries (app.css, public.css)
│   ├── js/            # React/Inertia frontend
│   │   ├── pages/     # Inertia TSX page components
│   │   ├── actions/   # Typings from Wayfinder
│   │   ├── routes/    # Typings from Wayfinder
│   │   ├── app.js     # Standard JS entry
│   │   ├── app.tsx    # React/Inertia initialization
│   │   └── echo.js    # Websockets initialization
│   └── views/         # Blade templates (Livewire, app.blade.php)
├── routes/            # web.php, console.php, channels.php
└── tests/             # Pest PHP test files
```

## Key Files
- `vite.config.ts`: Configured with plugins for Laravel, Inertia, React, Tailwind, and Wayfinder.
- `routes/web.php`: Defines the hybrid route split (Inertia vs Livewire).
- `boost.json`: CLI configs for `laravel/boost`.
- `composer.json` / `package.json`: Dependency manifests.
