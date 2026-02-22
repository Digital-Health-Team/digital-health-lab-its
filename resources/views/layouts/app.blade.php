<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="{{ asset('assets/images/logo_gretiva.png') }} type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50">

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
                <x-app-logo-icon class="w-10 h-10" />
                <div class="leading-tight">
                    <h2 class="font-bold text-lg">{{ env('APP_NAME') }}</h2>
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route active-bg-color="bg-primary text-primary-content rounded" class="gap-1 px-3 mt-4">
                {{-- Role: Super Admin --}}
                @if (auth()->user()->role === 'super_admin')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('admin.dashboard') }}" />
                    <x-menu-item title="Users" icon="o-users" link="{{ route('admin.users') }}" />

                    <x-menu-sub title="Project Management" icon="o-briefcase" open>

                        {{-- Global Actions --}}
                        <x-menu-item title="Create Project" icon="o-plus" link="{{ route('admin.projects') }}"
                            class="text-primary font-bold" />

                        <div class="my-2 border-t border-base-200"></div>

                        {{-- Alpine Component untuk filter pencarian client-side --}}
                        <div x-data="{ search: '' }" class="flex flex-col gap-2">

                            {{-- Search Field Minimalis (Auto menyesuaikan Tema) --}}
                            <div class="px-4">
                                <div class="relative flex items-center">
                                    <x-icon name="o-magnifying-glass"
                                        class="absolute left-3 w-4 h-4 text-base-content/50" />
                                    <input type="text" x-model="search" placeholder="Search project..."
                                        class="w-full pl-9 pr-3 py-1.5 text-xs bg-base-200 text-base-content rounded-lg border-none focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-base-content/40" />
                                </div>
                            </div>

                            {{-- Daftar Project (Scrollable Area) --}}
                            <div class="max-h-[45vh] overflow-y-auto custom-scrollbar flex flex-col px-2">
                                @php
                                    $sidebarProjects = \App\Models\Project::where('status', 'active')
                                        ->orderBy('name', 'asc')
                                        ->get();

                                    // Ambil parameter project dari URL saat ini
                                    $currentProjectParam = request()->route('project') ?? request('project_slug');
                                    $currentProjectIdentifier =
                                        $currentProjectParam instanceof \Illuminate\Database\Eloquent\Model
                                            ? $currentProjectParam->slug
                                            : $currentProjectParam;
                                @endphp

                                @forelse($sidebarProjects as $proj)
                                    @php
                                        $pName = is_array($proj->name)
                                            ? $proj->name['id'] ?? $proj->name['en']
                                            : $proj->name;
                                        $isActive = $currentProjectIdentifier === $proj->slug;
                                    @endphp

                                    <div x-show="search === '' || @js(strtolower($pName)).includes(search.toLowerCase())"
                                        x-transition.opacity>
                                        {{-- Menggunakan class standar MaryUI agar Light/Dark Mode aman --}}
                                        <x-menu-item title="{{ Str::limit($pName, 22) }}" icon="o-hashtag"
                                            link="{{ route('admin.projects.show', $proj->slug) }}" :active="$isActive"
                                            class="!text-sm {{ $isActive ? '!font-bold text-primary' : 'text-base-content/70 hover:text-base-content' }}" />
                                    </div>
                                @empty
                                    <div class="py-4 text-xs text-base-content/50 italic text-center">No active
                                        projects.</div>
                                @endforelse

                                {{-- Pesan jika pencarian tidak ditemukan --}}
                                <div x-show="search !== ''" class="py-4 text-xs text-base-content/50 italic text-center"
                                    style="display: none;">
                                    Searching...
                                </div>
                            </div>
                        </div>
                    </x-menu-sub>

                    <x-menu-item title="Announcements" icon="o-bell" link="{{ route('admin.announcements') }}" />
                @endif

                {{-- Role: Project Manager --}}
                @if (auth()->user()->role === 'pm')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('pm.dashboard') }}" />
                @endif

                {{-- Role: Staff --}}
                @if (auth()->user()->role === 'staff' || auth()->user()->role === 'freelance')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('user.dashboard') }}" />
                @endif

                <hr class="my-3 border-base-300">
                <x-menu-item title="{{ __('Settings') }}" icon="o-cog-6-tooth" link="{{ route('settings') }}" />
            </x-menu>
        </x-slot:sidebar>

        {{-- CONTENT SLOT --}}
        <x-slot:content class="p-0! bg-base-200/50">

            {{-- TOP NAVBAR --}}
            <div class="bg-base-100 border-b border-base-300 px-8 py-3 flex justify-between items-center gap-4">

                {{-- [BARU] GLOBAL SEARCH INPUT (Hanya untuk Super Admin) --}}
                <div class="flex-1 max-w-xl">
                    @if (auth()->user()->role === 'super_admin')
                        {{-- Menggunakan component Livewire untuk search real-time --}}
                        <livewire:global-search-bar />
                    @else
                        <div></div> {{-- Spacer --}}
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <livewire:language-switcher />
                    <x-theme-toggle class="btn btn-circle btn-ghost" />

                    {{-- User Menu --}}
                    <x-dropdown no-x-anchor right class="min-w-[280px]!">
                        <x-slot:trigger>
                            <div
                                class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg transition">
                                <x-avatar :image="auth()->user()->profile_photo
                                    ? asset('storage/' . auth()->user()->profile_photo)
                                    : null" class="!w-10 !h-10" />
                                <div class="text-sm font-medium hidden md:block">{{ auth()->user()->name }}</div>
                                <x-icon name="o-chevron-down" class="w-3 h-3" />
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
                        <livewire:actions.logout />
                    </x-dropdown>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="p-8">
                {{-- Breadcrumbs (Optional, bisa dinamis) --}}
                @if (!request()->routeIs('admin.global-search'))
                    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                        <x-icon name="o-home" class="w-4 h-4" />
                        <x-icon name="o-chevron-right" class="w-3 h-3" />
                        <span class="opacity-80">Dashboard</span>
                    </div>
                @endif

                {{ $slot }}
            </div>

        </x-slot:content>
    </x-main>

    <x-toast />
    {{-- Toast Logic --}}
    @if (session('success') || session('error'))
        <script>
            document.addEventListener('livewire:navigated', () => {
                @if (session('success'))
                    Toast.success("{{ session('success') }}", 'Success');
                @endif
                @if (session('error'))
                    Toast.error("{{ session('error') }}", 'Error');
                @endif
            });
            document.addEventListener('DOMContentLoaded', () => {
                @if (session('success'))
                    Toast.success("{{ session('success') }}", 'Success');
                @endif
                @if (session('error'))
                    Toast.error("{{ session('error') }}", 'Error');
                @endif
            });
        </script>
    @endif
</body>

</html>
