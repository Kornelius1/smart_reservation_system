<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Homey Cafe')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/css/component.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div id="app" class="flex-wrapper">
        @include('layouts.partials.navbar')
        <main class="content-grow"> @yield('content')
        </main>
        @include('layouts.partials.footer')
    </div>
    @stack('scripts')
</body>

</html>