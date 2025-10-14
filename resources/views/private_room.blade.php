<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Ruangan Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
            background-color: #F8F4EA;
            color: #3C4B44;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .card-custom {
            background-color: #9EC0B3;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card-custom:hover {
            transform: scale(1.03);
        }

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

    <header class="bg-green-900 text-white p-4 text-center text-lg font-bold">
        Pilih Ruangan Anda!
    </header>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-5xl">

            <!-- Indoor 1 -->
            <div class="card-custom text-center bg-white">
                <img src="{{ asset('images/indoor1.png') }}" alt="Indoor 1" class="rounded-lg mb-4 w-full h-48 object-cover">
                <h2 class="text-xl font-bold mb-2">Indoor 1 - Cafe Area</h2>
                <p class="text-sm mb-1">👥 Kapasitas: 20 Orang</p>
                <p class="text-sm mb-1">💰 Min. Order: Rp200.000 / 3 jam</p>
                <p class="text-sm mb-4">⏱ Extra Time: Rp50.000 / jam</p>
                <a href="#" class="bg-green-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800">Pilih Ruangan Ini</a>
            </div>

            <!-- Indoor 2 -->
            <div class="card-custom text-center bg-white">
                <img src="{{ asset('images/indoor2.png') }}" alt="Indoor 2" class="rounded-lg mb-4 w-full h-48 object-cover">
                <h2 class="text-xl font-bold mb-2">Indoor 2 - Private Hall</h2>
                <p class="text-sm mb-1">👥 Kapasitas: 35 Orang</p>
                <p class="text-sm mb-1">💰 Min. Order: Rp400.000 / 3 jam</p>
                <p class="text-sm mb-4">⏱ Extra Time: Rp500.000 / jam</p>
                <a href="#" class="bg-green-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800">Pilih Ruangan Ini</a>
            </div>

        </div>
    </main>

    <footer class="bg-green-900 text-light-green p-4 flex flex-col sm:flex-row justify-between items-center text-xs relative z-10 mt-auto">
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
