<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="{{ asset('assets/images/logo_gretiva.png') }}" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50">

    {{-- Komponen deteksi untuk sinkronisasi Database/Server --}}
    <livewire:timezone-detector />

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <div class="ml-5 pt-5">{{ config('app.name') }}</div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 w-64 border-r border-base-300">

            {{-- BRAND --}}
            <div class="px-6 pb-3 pt-6 flex items-center gap-3">
                <img src="{{ asset('assets/images/logo_gretiva.png') }}" alt="Logo" class="w-10 h-10 object-contain"
                    onerror="this.style.display='none';">
                <div class="leading-tight">
                    <h2 class="font-bold text-lg">{{ env('APP_NAME', 'Gretiva') }}</h2>
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route active-bg-color="bg-primary text-primary-content rounded" class="gap-1 px-3 mt-4">

                @php
                    $userRole = auth()->user()->role?->name;
                @endphp

                {{-- 1. DASHBOARD --}}
                @if (in_array($userRole, ['super_admin', 'admin_lab']))
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('admin.dashboard') }}" />
                @elseif (in_array($userRole, ['mahasiswa', 'user_publik']))
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('user.dashboard') }}" />
                @endif

                {{-- 2. ADMIN LAB & SUPER ADMIN SHARED FEATURES --}}
                @if (in_array($userRole, ['super_admin', 'admin_lab']))

                    {{-- Operasional Lab Harian --}}
                    <x-menu-separator title="{{ __('Lab Operations') }}" />
                    <x-menu-item title="{{ __('Order Center') }}" icon="o-shopping-cart" link="{{ route('admin.dashboard') }}" />
                    <x-menu-item title="{{ __('Project Moderation') }}" icon="o-check-badge" link="{{ route('admin.dashboard') }}" />

                    {{-- Katalog & Master Data --}}
                    <x-menu-separator title="{{ __('Catalog & Inventory') }}" />
                    <x-menu-item title="{{ __('Lab Services') }}" icon="o-briefcase" link="{{ route('admin.services') }}" />
                    <x-menu-item title="{{ __('Raw Materials') }}" icon="o-cube" link="{{ route('admin.raw-materials') }}" />
                    <x-menu-item title="{{ __('Products Catalog') }}" icon="o-swatch" link="{{ route('admin.products') }}" />

                    {{-- Ekosistem & Acara --}}
                    <x-menu-separator title="{{ __('Innovation Ecosystem') }}" />
                    <x-menu-item title="{{ __('Events & Teams') }}" icon="o-calendar-days" link="{{ route('admin.events') }}" />
                    <x-menu-item title="{{ __('Open Source Projects') }}" icon="o-code-bracket-square" link="{{ route('admin.open-source-projects') }}" />

                @endif

                {{-- 3. SUPER ADMIN ONLY FEATURES --}}
                @if ($userRole === 'super_admin')
                    <x-menu-separator title="{{ __('System Administration') }}" />

                    <x-menu-item title="{{ __('User Management') }}" icon="o-users" link="{{ route('admin.users') }}" />

                    <x-menu-sub title="{{ __('CMS & Web Profile') }}" icon="o-globe-alt">
                        <x-menu-item title="{{ __('Page Sections') }}" icon="o-document-text" link="{{ route('admin.dashboard') }}" />
                        <x-menu-item title="{{ __('Lab Structure') }}" icon="o-identification" link="{{ route('admin.dashboard') }}" />
                    </x-menu-sub>
                @endif

                {{-- 4. MAHASISWA & USER PUBLIK FEATURES --}}
                @if (in_array($userRole, ['mahasiswa', 'user_publik']))
                    <x-menu-separator title="{{ __('Activities') }}" />
                    <x-menu-item title="{{ __('My Orders') }}" icon="o-shopping-bag" link="{{ route('user.dashboard') }}" />
                @endif

                @if ($userRole === 'mahasiswa')
                    <x-menu-item title="{{ __('My Projects') }}" icon="o-academic-cap" link="{{ route('user.dashboard') }}" />
                @endif

                <hr class="my-3 border-base-300">
                <x-menu-item title="{{ __('Settings') }}" icon="o-cog-6-tooth" link="{{ route('settings') }}" />
            </x-menu>
        </x-slot:sidebar>

        {{-- CONTENT SLOT --}}
        <x-slot:content class="p-0! bg-base-200/50">

            {{-- TOP NAVBAR --}}
            <div class="bg-base-100 border-b border-base-300 px-8 py-3 flex justify-between items-center gap-4">

                {{-- GLOBAL SEARCH INPUT (Super Admin & Admin Lab) --}}
                <div class="flex-1 max-w-xl flex items-center gap-4">
                    @if (in_array($userRole, ['super_admin', 'admin_lab']))
                        <div class="flex-1">
                            <livewire:global-search-bar />
                        </div>
                    @else
                        <div class="flex-1"></div> {{-- Spacer --}}
                    @endif

                    {{-- LIVE CLOCK & TIMEZONE INDICATOR (ALPINE JS) --}}
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-base-200 rounded-lg text-sm text-base-content/70 border border-base-300 shadow-sm"
                        x-data="{
                            time: '',
                            tz: '',
                            updateTime() {
                                const now = new Date();
                                this.tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                                this.time = now.toLocaleTimeString('en-US', {
                                    hour12: false,
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            }
                        }" x-init="updateTime();
                        setInterval(() => updateTime(), 1000)">

                        <x-icon name="o-clock" class="w-4 h-4 text-primary" />
                        <span x-text="time" class="font-mono font-semibold tracking-wider w-[65px] text-center"></span>
                        <span class="opacity-50">|</span>
                        <span x-text="tz" class="font-medium text-xs"></span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <livewire:language-switcher />
                    <x-theme-toggle class="btn btn-circle btn-ghost btn-sm" />
                    <livewire:navbar-notifications />

                    {{-- User Menu --}}
                    <x-dropdown no-x-anchor right class="min-w-[280px]!">
                        <x-slot:trigger>
                            <div
                                class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg transition">
                                <x-avatar :image="auth()->user()->profile_photo
                                    ? asset('storage/' . auth()->user()->profile_photo)
                                    : null" class="!w-9 !h-9" />
                                <div class="text-sm font-bold hidden md:block">{{ auth()->user()->name }}</div>
                                <x-icon name="o-chevron-down" class="w-3 h-3 text-gray-500" />
                            </div>
                        </x-slot:trigger>

                        <div class="p-4 flex items-center gap-3">
                            <x-avatar :image="auth()->user()->profile_photo
                                ? asset('storage/' . auth()->user()->profile_photo)
                                : null" class="!w-10 !h-10" />
                            <div class="flex flex-col overflow-hidden">
                                <span class="font-bold truncate">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</span>
                            </div>
                        </div>

                        <div class="border-t border-base-300 my-1"></div>
                        <x-menu-item title="{{ __('Settings') }}" icon="o-cog-6-tooth"
                            link="{{ route('settings') }}" />

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-error hover:bg-base-200 flex items-center gap-2">
                                <x-icon name="o-power" class="w-4 h-4" /> {{ __('Logout') }}
                            </button>
                        </form>
                    </x-dropdown>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="p-8">
                @if (!request()->routeIs('admin.global-search'))
                    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                        <x-icon name="o-home" class="w-4 h-4" />
                        <x-icon name="o-chevron-right" class="w-3 h-3 opacity-50" />
                        <span class="opacity-80">{{ __('Dashboard') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </div>

        </x-slot:content>
    </x-main>

    <x-toast />

</body>

</html>
