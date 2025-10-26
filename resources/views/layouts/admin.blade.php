<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Homey Cafe')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources\css\manajemen.css', 'resources/js/manajemen-meja.js', 'resources/js/manajemen-menu.js', 'resources/js/manajemen-reservasi.js', 'resources/js/manajemen-reschedule.js'])
    <style>
        body {
            background-color: var(--brand-bg);
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('layouts.partials.landingnavbar')
    <div class="drawer drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle peer" />

        <div class="drawer-content transition-all duration-300 peer-checked:ml-64 ml-20">
            <main>
                @yield('content')
            </main>
        </div>

        @include('layouts.partials.sidebar')
    </div>



    @stack('scripts')





</body>

</html>