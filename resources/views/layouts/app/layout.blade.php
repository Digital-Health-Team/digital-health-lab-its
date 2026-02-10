<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

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
                <div class="bg-primary text-primary-content w-12 h-12 flex items-center justify-center rounded-full">
                    <x-icon name="o-book-open" class="w-7 h-7" />
                </div>
                <div class="leading-tight">
                    <h2 class="font-bold text-lg">{{ env('APP_NAME') }}</h2>
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route active-bg-color="bg-primary text-primary-content rounded" class="gap-1 px-3 mt-4">
                {{-- Role: Super Admin --}}
                @if (auth()->user()->role === 'admin')
                    <x-menu-item title="Approval Berita" icon="o-clipboard-document-check"
                        link="{{ route('admin.approval') }}">
                        {{-- Badge Count (Opsional, perlu pass variable dari layout) --}}
                        @if (\App\Models\News::where('status', 'draft')->count() > 0)
                            <x-slot:badge>
                                <div class="badge badge-warning badge-sm">
                                    {{ \App\Models\News::where('status', 'draft')->count() }}</div>
                            </x-slot:badge>
                        @endif
                    </x-menu-item>
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('admin.dashboard') }}" />
                    <x-menu-item title="Berita" icon="o-document-text" link="{{ route('admin.news') }}" />
                    <x-menu-item title="Pengguna" icon="o-users" link="{{ route('admin.users') }}" />
                    <x-menu-item title="Kategori" icon="o-tag" link="{{ route('admin.categories') }}" />
                    <x-menu-item title="Tags" icon="o-bookmark" link="{{ route('admin.tags') }}" />
                @endif

                {{-- Role: Super Admin --}}
                @if (auth()->user()->role === 'user')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('user.dashboard') }}" />
                    <x-menu-item title="Profile" icon="o-user" link="{{ route('user.profile') }}" />
                    <x-menu-item title="Berita" icon="o-document-text" link="{{ route('user.news.index') }}" />
                @endif

            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content class="p-0! bg-base-200/50">

            {{-- TOP NAVBAR --}}
            <div class="bg-base-100 border-b border-base-300 px-8 py-3 flex justify-end items-center gap-4">
                {{-- Theme Toggle --}}
                <x-theme-toggle class="btn btn-circle btn-ghost" />

                {{-- User Menu --}}
                <x-dropdown no-x-anchor right class="min-w-[280px]!">
                    <x-slot:trigger>
                        <div class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg transition">
                            <div class="bg-primary text-primary-content rounded text-xs p-1.5 font-bold">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                            <x-icon name="o-chevron-down" class="w-3 h-3" />
                        </div>
                    </x-slot:trigger>

                    <div class="p-4 flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-primary-content font-bold text-sm">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="font-bold truncate">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</span>
                        </div>
                    </div>

                    <div class="border-t border-base-300 my-1"></div>

                    <livewire:actions.logout />
                </x-dropdown>
            </div>

            {{-- Page Content --}}
            <div class="p-8">
                {{-- Breadcrumbs / Header --}}
                <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                    <x-icon name="o-home" class="w-4 h-4" />
                    <x-icon name="o-chevron-right" class="w-3 h-3" />
                    <span class="opacity-80">Dashboard</span>
                </div>

                {{ $slot }}
            </div>

        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />


    {{-- Session Toast Bridge --}}
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

            // Also trigger on initial load
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
