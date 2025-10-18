<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Reschedule</title>
    <link href="{{ asset('css/reschedule.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1>Pilih Ruangan Anda!</h1>
        </div>
    </header>

    <main class="container main-content">
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <small>Jl. Mawar, Simpang Baru, Kec. Tampan, Kota Pekanbaru</small>
        </div>
    </footer>
</body>
</html>
