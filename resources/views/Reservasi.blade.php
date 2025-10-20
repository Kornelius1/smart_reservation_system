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
            background: 
                linear-gradient(rgba(248, 244, 234, 0.85), rgba(248, 244, 234, 0.85)),
                url('/images/background.jpg');
            background-size: cover;
            background-position: center 20%;
            background-attachment: fixed;
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

        /* ✅ Footer link style (hilang sebelumnya) */
        .footer-link {
            color: #E0E2DA;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
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
                <img src="{{ asset('images/mejaa.svg') }}" alt="Reservasi Meja" class="mx-auto w-40 h-50 mb-6">
                <h2 class="text-2xl font-bold mb-4">Reservasi Meja</h2>
                <p class="mb-6">Pilih reservasi meja biasa sesuai jumlah kursi yang tersedia.</p>
                <a href="{{ url('/reservasi-meja') }}" class="btn-custom inline-block">Pilih</a>
            </div>

            <!-- Kartu Private Room -->
            <div class="card-custom text-center">
                <img src="{{ asset('images/privatee.svg') }}" alt="Private Room" class="mx-auto w-40 h-50 mb-6">
                <h2 class="text-2xl font-bold mb-4">Private Room</h2>
                <p class="mb-6">Pesan ruangan khusus dengan privasi penuh untuk acara Anda.</p>
                <a href="{{ url('/private-room') }}" class="btn-custom inline-block">Pilih</a>
            </div>
        </div>
    </main>

    {{-- ✅ Footer tampil sekarang --}}
    <footer class="bg-dark-green text-light-green p-4 flex flex-col sm:flex-row justify-between items-center text-xs relative z-10 mt-auto">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mb-3 sm:mb-0">
            <span class="font-bold text-lg text-white">HOMEY</span>
            <a href="#" class="footer-link">HOME</a>
            <a href="#" class="footer-link">MENU</a>
            <a href="#" class="footer-link">TENTANG KAMI</a>
        </div>
        <div class="flex flex-col items-end space-y-1">
            <div class="flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="footer-link">Jl. Mawar, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293</span>
            </div>
            <div class="flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="footer-link">(+62) 852 2183 6131</span>
            </div>
        </div>
    </footer>

</body>
</html>