<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEY - Home Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-orange-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="navbar bg-orange-100 shadow-md">
            <div class="flex-1">
                <a href="/" class="btn btn-ghost text-2xl font-bold text-gray-700">
                    HOMEY
                </a>
            </div>
            <div class="flex-none gap-2">
                <button class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                <button class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-6xl">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 justify-center">
                    <!-- Menu Management Button -->
                    <a href="{{ route('menu.index') }}" class="h-52">
                        <div class="card bg-gradient-to-b from-green-400 to-green-500 shadow-lg h-full hover:shadow-xl transition-shadow cursor-pointer">
                            <div class="card-body flex flex-col items-center justify-center text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h2 class="card-title text-white text-lg">Manajemen Menu</h2>
                            </div>
                        </div>
                    </a>

                    <!-- Table Management Button -->
                    <a href="{{ route('table.index') }}" class="h-52">
                        <div class="card bg-gradient-to-b from-green-400 to-green-500 shadow-lg h-full hover:shadow-xl transition-shadow cursor-pointer">
                            <div class="card-body flex flex-col items-center justify-center text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h12M6 10h12M6 14h12M4 18h16M3 6a1 1 0 011-1h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6z" />
                                    </svg>
                                </div>
                                <h2 class="card-title text-white text-lg">Manajemen Meja</h2>
                            </div>
                        </div>
                    </a>

                    <!-- Reservation Management Button -->
                    <a href="{{ route('reservation.index') }}" class="h-52">
                        <div class="card bg-gradient-to-b from-green-400 to-green-500 shadow-lg h-full hover:shadow-xl transition-shadow cursor-pointer">
                            <div class="card-body flex flex-col items-center justify-center text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10m7 8a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v12z" />
                                    </svg>
                                </div>
                                <h2 class="card-title text-white text-lg">Manajemen Reservasi</h2>
                            </div>
                        </div>
                    </a>

                    <!-- Reports Button -->
                    <a href="{{ route('report.index') }}" class="h-52">
                        <div class="card bg-gradient-to-b from-green-400 to-green-500 shadow-lg h-full hover:shadow-xl transition-shadow cursor-pointer">
                            <div class="card-body flex flex-col items-center justify-center text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h2 class="card-title text-white text-lg">Laporan</h2>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>