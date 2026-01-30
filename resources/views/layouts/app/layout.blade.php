<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-[#18181b]">

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
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-[#27272a] text-gray-300 w-64 border-r border-[#3f3f46]">

            {{-- BRAND --}}
            <div class="px-6 pb-3 pt-6 flex items-center gap-3">
                <div class="bg-primary text-black w-12 h-12 flex items-center justify-center rounded-full">
                     <x-icon name="o-book-open" class="w-7 h-7 text-white" /> 
                </div>
                <div class="leading-tight">
                    <h2 class="font-bold text-white text-lg">Logbook MBKM</h2>
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route active-bg-color="bg-[#136daf] !text-white rounded" class="gap-1 px-3 mt-4">
                {{-- Role: Super Admin --}}
                @if(auth()->user()->role === 'super_admin')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('admin.dashboard') }}" />
                    <x-menu-item title="Transaksi" icon="o-document-text" link="####" />
                @endif

                {{-- Role: Dosen --}}
                @if(auth()->user()->role === 'dosen')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('dosen.dashboard') }}" />
                    <x-menu-item title="Mahasiswa Bimbingan" icon="o-academic-cap" link="####" />
                @endif

                {{-- Role: Mahasiswa --}}
                @if(auth()->user()->role === 'mahasiswa')
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('mahasiswa.dashboard') }}" />
                    <x-menu-item title="Logbook" icon="o-book-open" link="####" />
                @endif
                
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content class="!p-0 bg-[#18181b]">
            
            {{-- TOP NAVBAR --}}
            <div class="bg-[#27272a] border-b border-[#3f3f46] px-8 py-3 flex justify-end items-center text-gray-300">
                 {{-- User Menu --}}
                 <x-dropdown no-x-anchor right class="!bg-[#27272a] !border-[#3f3f46] !min-w-[280px]">
                     <x-slot:trigger>
                        <div class="flex items-center gap-3 cursor-pointer hover:text-white transition">
                            <div class="bg-gray-600 text-white rounded text-xs p-1.5 font-bold">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                            <x-icon name="o-chevron-down" class="w-3 h-3" />
                        </div>
                     </x-slot:trigger>

                     <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#38bdf8] flex items-center justify-center text-white font-bold text-sm">
                             {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="font-bold text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-[#3f3f46] my-1"></div>

                    <livewire:actions.logout />
                 </x-dropdown>
            </div>

            {{-- Page Content --}}
            <div class="p-8">
                 {{-- Breadcrumbs / Header --}}
                 <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                    <x-icon name="o-home" class="w-4 h-4" />
                    <x-icon name="o-chevron-right" class="w-3 h-3" />
                    <span class="text-gray-300">Dashboard</span>
                 </div>

                {{ $slot }}
            </div>

        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />


    {{-- Session Toast Bridge --}}
    @if(session('success'))
        <div id="toast-success-icon" class="hidden">
            <x-icon name="o-check-circle" class="w-7 h-7" />
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(() => {
                    const iconHtml = document.getElementById('toast-success-icon').innerHTML;
                    window.dispatchEvent(new CustomEvent('mary-toast', {
                        detail: {
                            toast: {
                                type: 'success',
                                title: 'Success',
                                description: '{{ session('success') }}',
                                position: 'toast-top',
                                icon: iconHtml,
                                css: 'alert-success',
                                timeout: 10000
                            }
                        }
                    }));
                }, 500);
            });
        </script>
    @endif
</body>
</html>
