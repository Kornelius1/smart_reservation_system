<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Homey Cafe')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/css/reservasi.css', 'resources/js/app.js', 'resources/css/reservasi.css'])
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