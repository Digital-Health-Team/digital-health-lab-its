<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

        @viteReactRefresh 
        @vite(['resources/css/public.css', 'resources/js/app.tsx'])

        <x-inertia::head>
            <title>{{ config('app.name', 'Gretiva') }}</title>
        </x-inertia::head>
    </head>
    <body class="antialiased">
        <x-inertia::app />
    <!-- impeccable-live-start -->
<script src="http://localhost:8400/live.js"></script>
<!-- impeccable-live-end -->
</body>
</html>
