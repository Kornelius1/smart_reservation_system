<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homey Cafe</title>

    {{-- Baris ini yang memanggil CSS dari Vite --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    {{-- HEADER / NAVBAR --}}
    <header class="navbar bg-base-100 shadow-lg">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl">Homey Cafe</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li><a>Home</a></li>
                <li><a>Reservasi</a></li>
            </ul>
        </div>
    </header>

    {{-- KONTEN UTAMA HALAMAN --}}
    <main>
        {{-- Di sinilah konten dari reschedule.blade.php akan ditampilkan --}}
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer footer-center p-4 bg-base-300 text-base-content mt-10">
        <aside>
            <p>Copyright © 2025 - All right reserved by Homey Cafe</p>
        </aside>
    </footer>

</body>
</html>