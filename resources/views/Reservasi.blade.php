<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background-color: #F8F4EA;
            color: #3C4B44;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .bg-dark-green { background-color: #3C4B44; }
        .bg-light-green { background-color: #9EC0B3; }
        .card-custom {
            background-color: #9EC0B3;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card-custom:hover { transform: scale(1.03); }
        .btn-custom {
            background-color: #3C4B44;
            color: #fff;
            border-radius: 9999px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
        }
        .btn-custom:hover { background-color: #2e3833; }
    </style>
</head>
<body class="relative min-h-screen flex flex-col">

    <header class="bg-dark-green text-white p-4 text-center text-lg font-bold">
        Silahkan Pilih Reservasi Anda!
    </header>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-4xl">
            
            <!-- Kartu Reservasi Meja -->
            <div class="card-custom text-center">
                <!-- Logo -->
                <img src="{{ asset('images/mejaa.svg') }}" alt="Reservasi Meja" class="mx-auto w-40 h-50 mb-6">
                <h2 class="text-2xl font-bold mb-4">Reservasi Meja</h2>
                <p class="mb-6">Pilih reservasi meja biasa sesuai jumlah kursi yang tersedia.</p>
                <a href="{{ url('/reservasi-meja') }}" class="btn-custom inline-block">Pilih</a>
            </div>

            <!-- Kartu Private Room -->
            <div class="card-custom text-center">
                <!-- Logo -->
                <img src="{{ asset('images/privatee.svg') }}" alt="Private Room" class="mx-auto w-40 h-50 mb-6">
                <h2 class="text-2xl font-bold mb-4">Private Room</h2>
                <p class="mb-6">Pesan ruangan khusus dengan privasi penuh untuk acara Anda.</p>
                <a href="{{ url('/private-room') }}" class="btn-custom inline-block">Pilih</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-green text-light-green p-4 flex flex-col sm:flex-row justify-between items-center text-xs mt-auto">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mb-3 sm:mb-0">
            <span class="font-bold text-lg text-white">HOMEY</span>
            <a href="#" class="footer-link">HOME</a>
            <a href="#" class="footer-link">MENU</a>
            <a href="#" class="footer-link">TENTANG KAMI</a>
        </div>
        <div class="flex flex-col items-end space-y-1">
            <span>(123)456-7890</span>
            <span>ABC Company, 123 East, 17th Street, St. Louis 10001</span>
        </div>
    </footer>

</body>
</html>