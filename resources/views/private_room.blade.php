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

</body>
</html>
