<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Homey Cafe')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/css/reservasi.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="body-custom min-h-screen flex flex-col">


    @yield('header')

    <main class="flex-grow flex items-center justify-center p-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>