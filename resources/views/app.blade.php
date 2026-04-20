<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
