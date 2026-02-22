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
                        {{-- Global Management (Opsional: Tetap ada jika Admin ingin melihat ringkasan semua data) --}}
                        <x-menu-item title="Overview Dashboard" icon="o-presentation-chart-line"
                            link="{{ route('admin.dashboard') }}" />
                        <x-menu-item title="Create New Project" icon="o-plus-circle"
                            link="{{ route('admin.projects') }}" class="text-primary font-bold" />

                        <div class="mt-4 mb-1 border-t border-base-200 dark:border-gray-700"></div>
                        <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Your
                            Projects</div>

                        {{-- Filter Search --}}
                        <div x-data="{ search: '' }" class="flex flex-col">
                            <div class="px-3 py-2">
                                <label
                                    class="input input-sm input-bordered flex items-center gap-2 bg-base-200/50 dark:bg-gray-800 focus-within:ring-1 focus-within:ring-primary w-full border-gray-200 dark:border-gray-700">
                                    <x-icon name="o-magnifying-glass" class="w-3 h-3 opacity-50" />
                                    <input type="text" x-model="search" class="grow text-[11px]"
                                        placeholder="Search project..." />
                                </label>
                            </div>

                            {{-- Scrollable Area --}}
                            <div class="max-h-[400px] overflow-y-auto custom-scrollbar pb-2">
                                @php
                                    $sidebarProjects = \App\Models\Project::where('status', 'active')
                                        ->orderBy('name', 'asc')
                                        ->get();
                                @endphp

                                @forelse($sidebarProjects as $proj)
                                    @php
                                        $pName = is_array($proj->name)
                                            ? $proj->name['id'] ?? $proj->name['en']
                                            : $proj->name;
                                    @endphp

                                    <div x-show="search === '' || @js(strtolower($pName)).includes(search.toLowerCase())"
                                        x-transition>
                                        {{-- Sub-menu untuk tiap Project --}}
                                        <x-menu-sub title="{{ Str::limit($pName, 18) }}" icon="o-hashtag"
                                            class="!text-xs">
                                            {{-- 1. Link ke Detail Utama Project --}}
                                            <x-menu-item title="Project Home" icon="o-home"
                                                link="{{ route('admin.projects.show', $proj->id) }}"
                                                class="!text-[11px] !py-1 opacity-80" />

                                            {{-- 2. Link ke Jobdesk khusus project ini --}}
                                            <x-menu-item title="Tasks / Jobdesk" icon="o-clipboard-document-list"
                                                link="{{ route('admin.jobdesks', ['project_id' => $proj->id]) }}"
                                                class="!text-[11px] !py-1 opacity-80" />

                                            {{-- 3. Link ke Attendance khusus project ini --}}
                                            <x-menu-item title="Work Logs" icon="o-clock"
                                                link="{{ route('admin.attendance', ['project_id' => $proj->id]) }}"
                                                class="!text-[11px] !py-1 opacity-80" />
                                        </x-menu-sub>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-xs text-gray-500 italic text-center">No projects found.
                                    </div>
                                @endforelse
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
