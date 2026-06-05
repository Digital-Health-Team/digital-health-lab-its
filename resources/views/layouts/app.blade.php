<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo_idig_htech.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-slate-100 dark:bg-[#010D1F]">

    <livewire:timezone-detector />

    @php $userRole = auth()->user()->role?->name; @endphp

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{--  MAIN SHELL                                                 --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <x-main full-width>

        {{-- ─── SIDEBAR ───────────────────────────────────────────── --}}
        {{--
            drawer="main-drawer"  → MaryUI wires this to a hidden
              <input id="main-drawer"> checkbox; any <label for="main-drawer">
              anywhere in the DOM toggles the offcanvas on mobile.
            collapsible           → on lg+ the sidebar is docked; on
              narrower viewports it collapses into the drawer overlay.
        --}}
        <x-slot:sidebar drawer="main-drawer" collapsible
            class="bg-white dark:bg-[#031026]
                   border-r border-slate-200 dark:border-[#0A3D7A]/40
                   w-64 shadow-sm">

            {{-- Brand ------------------------------------------------ --}}
            <div
                class="px-5 py-4 flex items-center gap-3
                        border-b border-slate-100 dark:border-[#0A3D7A]/30">
                <img src="{{ asset('assets/images/logo_idig_htech.png') }}" alt="{{ config('app.name') }}"
                    class="w-8 h-8 object-contain shrink-0" onerror="this.style.display='none'">
                <span
                    class="font-black text-lg tracking-tight leading-tight
                             text-slate-800 dark:text-[#F8FAFC] truncate">
                    {{ config('app.name', 'IDIG') }}
                </span>
            </div>

            {{-- Navigation ------------------------------------------ --}}
            <x-menu activate-by-route
                active-bg-color="bg-primary/10 dark:bg-[#0A3D7A]/50 text-primary dark:text-[#22D3EE] font-semibold"
                class="px-3 py-3 gap-0.5 text-sm">

                {{-- Dashboard --}}
                @if ($userRole === 'super_admin')
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home"
                        link="{{ route('super-admin.dashboard') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                @elseif ($userRole === 'admin_lab')
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('admin.dashboard') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                @elseif ($userRole === 'admin_gudang')
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('gudang.dashboard') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                @elseif (in_array($userRole, ['mahasiswa', 'user_publik']))
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('user.dashboard') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                @endif

                {{-- Admin / Super-admin sections --}}
                @if (in_array($userRole, ['super_admin', 'admin_lab']))

                    <x-menu-separator title="{{ __('Operations') }}"
                        class="mt-5 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest
                               text-slate-400 dark:text-[#94A3B8]/50" />
                    <x-menu-item title="{{ __('Order Center') }}" icon="o-shopping-cart"
                        link="{{ route('admin.order-center') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />

                    <x-menu-separator title="{{ __('Inventory Catalog') }}"
                        class="mt-5 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest
                               text-slate-400 dark:text-[#94A3B8]/50" />
                    <x-menu-item title="{{ __('Lab Services') }}" icon="o-briefcase"
                        link="{{ route('admin.services') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    @if ($userRole == 'super_admin')
                        <x-menu-item title="{{ __('Raw Materials') }}" icon="o-cube"
                            link="{{ route('admin.raw-materials') }}"
                            class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    @endif
                    <x-menu-item title="{{ __('Products') }}" icon="o-swatch" link="{{ route('admin.products') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    <x-menu-item title="{{ __('Master Data') }}" icon="o-circle-stack"
                        link="{{ route('admin.master-data') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />

                    <x-menu-separator title="{{ __('Ecosystem') }}"
                        class="mt-5 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest
                               text-slate-400 dark:text-[#94A3B8]/50" />
                    <x-menu-item title="{{ __('Events & Exhibitions') }}" icon="o-calendar-days"
                        link="{{ route('admin.events') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    <x-menu-item title="{{ __('Open Source Projects') }}" icon="o-code-bracket-square"
                        link="{{ route('admin.open-source-projects') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />

                @endif

                {{-- Super-admin only --}}
                @if ($userRole === 'super_admin')
                    <x-menu-separator title="{{ __('System') }}"
                        class="mt-5 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest
                               text-slate-400 dark:text-[#94A3B8]/50" />
                    <x-menu-item title="{{ __('User Management') }}" icon="o-users" link="{{ route('admin.users') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    <x-menu-sub title="{{ __('Web Content') }}" icon="o-globe-alt"
                        class="text-slate-700 dark:text-[#94A3B8]">
                        <x-menu-item title="{{ __('Page Sections (CMS)') }}" icon="o-document-text"
                            link="{{ route('admin.cms.page-sections') }}"
                            class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                                   hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                        <x-menu-item title="{{ __('Lab Structure') }}" icon="o-identification"
                            link="{{ route('admin.cms.structural-members') }}"
                            class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                                   hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    </x-menu-sub>
                @endif

                {{-- Admin Gudang (warehouse operations) --}}
                @if ($userRole === 'admin_gudang')
                    <x-menu-separator title="{{ __('Warehouse') }}"
                        class="mt-5 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest
                               text-slate-400 dark:text-[#94A3B8]/50" />
                    <x-menu-item title="{{ __('Raw Materials') }}" icon="o-cube"
                        link="{{ route('admin.raw-materials') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    <x-menu-item title="{{ __('Master Data') }}" icon="o-circle-stack"
                        link="{{ route('admin.master-data') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                @endif

                {{-- Regular users --}}
                @if (in_array($userRole, ['mahasiswa', 'user_publik']))
                    <x-menu-separator title="{{ __('Activities') }}"
                        class="mt-5 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest
                               text-slate-400 dark:text-[#94A3B8]/50" />
                    <x-menu-item title="{{ __('My Orders') }}" icon="o-shopping-bag"
                        link="{{ route('user.dashboard') }}"
                        class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    @if ($userRole === 'mahasiswa')
                        <x-menu-item title="{{ __('My Projects') }}" icon="o-academic-cap"
                            link="{{ route('user.dashboard') }}"
                            class="rounded-lg text-slate-700 dark:text-[#94A3B8]
                                   hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />
                    @endif
                @endif

            </x-menu>
        </x-slot:sidebar>

        {{-- ─── CONTENT AREA ──────────────────────────────────────── --}}
        <x-slot:content class="p-0! m-0!">

            {{-- ═══ TOP NAVBAR (unified — single bar for all breakpoints) ═══ --}}
            {{--
                Structure (left → right):
                  [Hamburger lg:hidden] [Logo lg:hidden] | [Search sm:flex] [spacer] [icon-search sm:hidden] [lang] [theme] [notif] [profile]

                Hamburger:  <label for="main-drawer"> → toggles MaryUI drawer on mobile/tablet.
                            Hidden on lg+ because the sidebar is always docked there.
                Logo:       visible only when sidebar is collapsed (< lg).
                Search bar: hidden below sm (360 px range); icon link shown instead.
                Utilities:  always visible; name label/chevron hidden below sm.
            --}}
            <nav
                class="sticky top-0 z-40
                        flex items-center gap-1.5 sm:gap-2
                        px-3 sm:px-4 py-2
                        bg-white/95 dark:bg-[#031026]/95
                        backdrop-blur-lg
                        border-b border-slate-200 dark:border-[#0A3D7A]/40
                        shadow-sm">

                {{-- LEFT GROUP ---------------------------------------- --}}
                <div class="flex items-center gap-1.5 shrink-0">

                    {{-- Hamburger: visible only when sidebar is hidden (< lg) --}}
                    <label for="main-drawer"
                        class="lg:hidden
                               inline-flex items-center justify-center
                               w-8 h-8 rounded-lg cursor-pointer
                               text-slate-600 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40
                               transition-colors">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </label>

                    {{-- App name: replaces sidebar brand on narrow screens --}}
                    <a href="{{ $userRole === 'super_admin' ? route('super-admin.dashboard') : ($userRole === 'admin_lab' ? route('admin.dashboard') : ($userRole === 'admin_gudang' ? route('gudang.dashboard') : route('user.dashboard'))) }}"
                        class="lg:hidden font-black text-base tracking-tight
                               text-slate-800 dark:text-[#F8FAFC]
                               hover:text-primary dark:hover:text-[#22D3EE]
                               transition-colors">
                        {{ config('app.name', 'IDIG') }}
                    </a>

                </div>

                {{-- GLOBAL SEARCH — full bar (≥ sm) ------------------- --}}
                @if (in_array($userRole, ['super_admin', 'admin_lab', 'admin_gudang']))
                    <div class="hidden sm:flex flex-1 min-w-0 max-w-xs lg:max-w-sm xl:max-w-md">
                        <livewire:global-search-bar />
                    </div>
                @endif

                {{-- SPACER -------------------------------------------- --}}
                <div class="flex-1"></div>

                {{-- RIGHT GROUP --------------------------------------- --}}
                <div class="flex items-center gap-0.5 sm:gap-1 shrink-0">

                    {{-- Search icon (< sm only): links to search page instead of rendering the full input --}}
                    @if (in_array($userRole, ['super_admin', 'admin_lab', 'admin_gudang']))
                        <a href="{{ route('admin.search') }}"
                            class="sm:hidden inline-flex items-center justify-center
                                   w-8 h-8 rounded-lg
                                   text-slate-600 dark:text-[#94A3B8]
                                   hover:bg-slate-100 dark:hover:bg-[#062E5C]/40
                                   transition-colors">
                            <x-icon name="o-magnifying-glass" class="w-4 h-4" />
                        </a>
                    @endif

                    {{-- Language switcher --}}
                    <livewire:admin.shared.language-switcher />

                    {{-- Dark / light mode toggle --}}
                    <x-theme-toggle
                        class="btn btn-ghost btn-sm btn-circle
                               text-slate-500 dark:text-[#94A3B8]
                               hover:bg-slate-100 dark:hover:bg-[#062E5C]/40" />

                    {{-- Notifications bell --}}
                    <livewire:navbar-notifications />

                    {{-- Profile dropdown -------------------------------- --}}
                    <x-dropdown no-x-anchor right class="w-60">
                        <x-slot:trigger>
                            <button type="button"
                                class="inline-flex items-center gap-1.5
                                       h-8 pl-0.5 pr-1.5 sm:pr-2.5
                                       rounded-full
                                       text-slate-700 dark:text-[#F8FAFC]
                                       hover:bg-slate-100 dark:hover:bg-[#062E5C]/40
                                       transition-colors cursor-pointer">
                                <x-avatar :image="auth()->user()->profile_photo
                                    ? asset('storage/' . auth()->user()->profile_photo)
                                    : null" class="!w-7 !h-7 shrink-0" />
                                <span class="hidden sm:block text-sm font-semibold max-w-[90px] truncate">
                                    {{ auth()->user()->name }}
                                </span>
                                <x-icon name="o-chevron-down"
                                    class="hidden sm:block w-3 h-3 opacity-50
                                           text-slate-500 dark:text-[#94A3B8]" />
                            </button>
                        </x-slot:trigger>

                        {{-- Dropdown header --}}
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-[#0A3D7A]/40">
                            <p class="text-sm font-bold truncate text-slate-800 dark:text-[#F8FAFC]">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs truncate text-slate-500 dark:text-[#94A3B8] mt-0.5">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <x-menu-item title="{{ __('Account Settings') }}" icon="o-cog-6-tooth"
                            link="{{ route('settings') }}" class="my-1" />

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3
                                       px-4 py-2 text-sm
                                       text-red-500 dark:text-[#EF4444]
                                       hover:bg-red-50 dark:hover:bg-[#EF4444]/10
                                       transition-colors cursor-pointer">
                                <x-icon name="o-power" class="w-4 h-4 shrink-0" />
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </x-dropdown>

                </div>
            </nav>

            {{-- ═══ PAGE CONTENT ═══════════════════════════════════════════ --}}
            <div class="p-4 sm:p-6 lg:p-8 max-w-screen-2xl mx-auto">

                {{-- Dynamic breadcrumbs -------------------------------- --}}
                @php
                    $segments = request()->segments();
                    $breadUrl = '';
                @endphp

                @if (count($segments) > 1 &&
                        !request()->routeIs('super-admin.dashboard') &&
                        !request()->routeIs('admin.dashboard') &&
                        !request()->routeIs('gudang.dashboard') &&
                        !request()->routeIs('user.dashboard'))
                    <div
                        class="breadcrumbs text-sm mb-5
                                text-slate-500 dark:text-[#94A3B8] font-medium">
                        <ul>
                            <li>
                                <a href="{{ $userRole === 'super_admin' ? route('super-admin.dashboard') : ($userRole === 'admin_lab' ? route('admin.dashboard') : ($userRole === 'admin_gudang' ? route('gudang.dashboard') : route('user.dashboard'))) }}"
                                    class="flex items-center gap-1
                                           hover:text-primary dark:hover:text-[#22D3EE]
                                           transition-colors cursor-pointer">
                                    <x-icon name="o-home" class="w-3.5 h-3.5" />
                                    {{ __('Dashboard') }}
                                </a>
                            </li>
                            @foreach ($segments as $key => $segment)
                                @if ($key === 0 && in_array($segment, ['admin', 'user', 'super-admin', 'gudang']))
                                    @php $breadUrl .= '/' . $segment; @endphp
                                    @continue
                                @endif
                                @php
                                    $breadUrl .= '/' . $segment;
                                    $isLast = $key === count($segments) - 1;
                                    $label = ucwords(str_replace(['-', '_'], ' ', $segment));
                                    if (is_numeric($segment)) {
                                        $label = '#' . $segment;
                                    }
                                @endphp
                                @if ($isLast)
                                    <li class="font-bold text-primary dark:text-[#22D3EE]">
                                        {{ $label }}
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ url($breadUrl) }}"
                                            class="hover:text-primary dark:hover:text-[#22D3EE]
                                                   transition-colors cursor-pointer">
                                            {{ $label }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Page slot --}}
                {{ $slot }}

            </div>
        </x-slot:content>

    </x-main>

    <x-toast />

</body>

</html>
