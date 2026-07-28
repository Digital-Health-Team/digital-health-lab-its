<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="{{ asset('assets/images/logo_idig_htech.png') }} type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50">
    {{-- Auth screens are full-bleed two-column grids, so the switcher floats above them. --}}
    <div class="fixed top-4 right-4 z-50">
        <livewire:language-switcher />
    </div>

    {{ $slot }}
    <x-toast />
</body>

</html>
