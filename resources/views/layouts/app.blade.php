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

<body class="min-h-screen font-sans antialiased bg-base-200/30">

    {{-- Sync Timezone --}}
    <livewire:timezone-detector />

    {{-- NAVBAR MOBILE --}}
    <x-nav sticky class="lg:hidden bg-base-100/90 backdrop-blur-md border-b border-base-200">
        <x-slot:brand>
            <div class="ml-5 font-bold text-primary">{{ config('app.name') }}</div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-2 btn btn-ghost btn-circle btn-sm">
                <x-icon name="o-bars-3" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN LAYOUT --}}
    <x-main full-width>

        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 w-64 border-r border-base-200 shadow-sm">

            {{-- BRAND AREA --}}
            <div class="p-6 flex items-center gap-3">
                <img src="{{ asset('assets/images/logo_idig_htech.png') }}" alt="Logo" class="w-8 h-8 object-contain" onerror="this.style.display='none';">
                <div class="font-black text-xl tracking-tight text-base-content">{{ env('APP_NAME', 'IDIG') }}</div>
            </div>

            {{-- MENU NAVIGATION --}}
            <x-menu activate-by-route active-bg-color="bg-primary/10 text-primary font-semibold rounded-lg" class="px-4 gap-1">

                @php $userRole = auth()->user()->role?->name; @endphp

                {{-- DASHBOARD --}}
                @if (in_array($userRole, ['super_admin', 'admin_lab']))
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('admin.dashboard') }}" />
                @elseif (in_array($userRole, ['mahasiswa', 'user_publik']))
                    <x-menu-item title="{{ __('Dashboard') }}" icon="o-home" link="{{ route('user.dashboard') }}" />
                @endif

                {{-- ADMIN LAB & SUPER ADMIN --}}
                @if (in_array($userRole, ['super_admin', 'admin_lab']))

                    <x-menu-separator title="{{ __('Operations') }}" class="mt-4 mb-2 text-xs opacity-50" />
                    <x-menu-item title="{{ __('Order Center') }}" icon="o-shopping-cart" link="{{ route('admin.order-center') }}" />
                    <x-menu-item title="{{ __('Project Moderation') }}" icon="o-check-badge" link="{{ route('admin.dashboard') }}" />

                    <x-menu-separator title="{{ __('Inventory & Catalog') }}" class="mt-4 mb-2 text-xs opacity-50" />
                    <x-menu-item title="{{ __('Lab Services') }}" icon="o-briefcase" link="{{ route('admin.services') }}" />
                    <x-menu-item title="{{ __('Raw Materials') }}" icon="o-cube" link="{{ route('admin.raw-materials') }}" />
                    <x-menu-item title="{{ __('Products') }}" icon="o-swatch" link="{{ route('admin.products') }}" />

                    <x-menu-separator title="{{ __('Ecosystem') }}" class="mt-4 mb-2 text-xs opacity-50" />
                    <x-menu-item title="{{ __('Events & Teams') }}" icon="o-calendar-days" link="{{ route('admin.events') }}" />
                    <x-menu-item title="{{ __('Open Source') }}" icon="o-code-bracket-square" link="{{ route('admin.open-source-projects') }}" />

                @endif

                {{-- SUPER ADMIN --}}
                @if ($userRole === 'super_admin')
                    <x-menu-separator title="{{ __('System') }}" class="mt-4 mb-2 text-xs opacity-50" />
                    <x-menu-item title="{{ __('Users') }}" icon="o-users" link="{{ route('admin.users') }}" />
                    <x-menu-sub title="{{ __('Web Content') }}" icon="o-globe-alt">
                        <x-menu-item title="{{ __('Page Sections') }}" icon="o-document-text" link="{{ route('admin.cms.page-sections') }}" />
                        <x-menu-item title="{{ __('Lab Structure') }}" icon="o-identification" link="{{ route('admin.cms.structural-members') }}" />
                    </x-menu-sub>
                @endif

                {{-- USERS --}}
                @if (in_array($userRole, ['mahasiswa', 'user_publik']))
                    <x-menu-separator title="{{ __('Activities') }}" class="mt-4 mb-2 text-xs opacity-50" />
                    <x-menu-item title="{{ __('My Orders') }}" icon="o-shopping-bag" link="{{ route('user.dashboard') }}" />
                    @if ($userRole === 'mahasiswa')
                        <x-menu-item title="{{ __('My Projects') }}" icon="o-academic-cap" link="{{ route('user.dashboard') }}" />
                    @endif
                @endif

            </x-menu>
        </x-slot:sidebar>

        {{-- CONTENT AREA --}}
        <x-slot:content class="p-0! m-0!">

            {{-- TOP NAVBAR --}}
            <div class="bg-base-100/80 backdrop-blur-lg border-b border-base-200 px-6 py-3 flex justify-between items-center sticky top-0 z-40">

                {{-- Left: Global Search --}}
                <div class="flex-1 max-w-md">
                    @if (in_array($userRole, ['super_admin', 'admin_lab']))
                        <livewire:global-search-bar />
                    @endif
                </div>

                {{-- Right: Utilities & Profile --}}
                <div class="flex items-center gap-2 md:gap-4">
                    <livewire:language-switcher />
                    <x-theme-toggle class="btn btn-circle btn-ghost btn-sm text-gray-500" />
                    <livewire:navbar-notifications />

                    {{-- User Dropdown Minimalis --}}
                    <x-dropdown no-x-anchor right class="w-56">
                        <x-slot:trigger>
                            <button class="btn btn-ghost btn-sm rounded-full pl-1 pr-3 flex items-center gap-2 hover:bg-base-200 transition cursor-pointer">
                                <x-avatar :image="auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : null" class="!w-7 !h-7" />
                                <span class="text-sm font-semibold hidden md:block max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <x-icon name="o-chevron-down" class="w-3 h-3 opacity-50 hidden md:block" />
                            </button>
                        </x-slot:trigger>

                        <div class="px-4 py-3 border-b border-base-200 bg-base-50/50">
                            <div class="font-bold text-sm truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                        </div>

                        <x-menu-item title="{{ __('Account Settings') }}" icon="o-cog-6-tooth" link="{{ route('settings') }}" class="my-1" />

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-error hover:bg-error/10 flex items-center gap-3 transition-colors cursor-pointer">
                                <x-icon name="o-power" class="w-4 h-4" /> {{ __('Logout') }}
                            </button>
                        </form>
                    </x-dropdown>
                </div>
            </div>

            {{-- PAGE CONTENT SLOT --}}
            <div class="p-6 md:p-8 max-w-screen-2xl mx-auto">

                {{-- DYNAMIC BREADCRUMBS --}}
                @php
                    $segments = request()->segments();
                    $url = '';
                @endphp

                @if(count($segments) > 1 && !request()->routeIs('admin.dashboard') && !request()->routeIs('user.dashboard'))
                    <div class="breadcrumbs text-sm mb-6 text-base-content/60 font-medium">
                        <ul>
                            <li>
                                <a href="{{ in_array(auth()->user()->role->name, ['super_admin', 'admin_lab']) ? route('admin.dashboard') : route('user.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer">
                                    <x-icon name="o-home" class="w-4 h-4 mr-1" /> {{ __('Dashboard') }}
                                </a>
                            </li>
                            @foreach($segments as $key => $segment)
                                {{-- Lewati prefix 'admin' atau 'user' karena sudah digantikan oleh tombol Dashboard --}}
                                @if($key === 0 && in_array($segment, ['admin', 'user']))
                                    @php $url .= '/' . $segment; @endphp
                                    @continue
                                @endif

                                @php
                                    $url .= '/' . $segment;
                                    $isLast = $key == count($segments) - 1;

                                    // Bersihkan dan rapikan teks URL menjadi format baca
                                    $name = ucwords(str_replace(['-', '_'], ' ', $segment));

                                    // Jika segmen adalah ID (angka), tambahkan hashtag
                                    if(is_numeric($segment)) {
                                        $name = '#' . $segment;
                                    }
                                @endphp

                                @if($isLast)
                                    <li class="text-primary font-bold">{{ $name }}</li>
                                @else
                                    <li><a href="{{ url($url) }}" class="hover:text-primary transition-colors cursor-pointer">{{ $name }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- SLOT UTAMA --}}
                {{ $slot }}

            </div>

        </x-slot:content>
    </x-main>

    <x-toast />

</body>

</html>
