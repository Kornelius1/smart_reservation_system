<!DOCTYPE html>
<html lang="en" data-theme="homey">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homey Cafe</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .btn-gradient {
            background-image: linear-gradient(to right, #9CAF88, #414939);
            border: none;
            color: white;
        }
        .btn-gradient:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body style="background-color: #dfe6da; color: #414939;">

   
    <header class="navbar text-white shadow-lg" style="background-color: #9CAF88;"> {{-- Warna #9CAF88 --}}
        <div class="flex-1">
            <a href="/" class="px-2">
                <img src="{{ asset('images/HOMEY.png') }}" alt="Homey Cafe Logo" class="h-10">
            </a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold">
                <li><a>Home</a></li>
                <li><a>Reservasi</a></li>
                <li><a>Reschedule</a></li>
                <li><a>Tentang Kami</a></li>
            </ul>
        </div>
    </header>

    {{-- KONTEN UTAMA HALAMAN --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer p-10 bg-neutral text-base-100">
    <aside>
        <p class="text-5xl font-extrabold -mt-4">#</p>
        <p class="font-bold">
            Homey Cafe <br>
            <span>Menyediakan kenyamanan sejak 2024</span>
        </p>
        <p>Copyright © 2025 - All right reserved</p>
    </aside> 
    <nav>
        <h6 class="footer-title">Social</h6> 
        <div class="grid grid-flow-col gap-4">
            <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616v.064c0 2.299 1.634 4.208 3.808 4.649-.6.162-1.224.208-1.86.086.634 1.894 2.448 3.273 4.609 3.311-1.87 1.457-4.224 2.32-6.79 2.05 2.19 1.397 4.798 2.22 7.556 2.22 9.054 0 13.999-7.52 13.438-14.312.95-.688 1.773-1.545 2.427-2.518z"></path></svg></a>
            <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
        </div>
    </nav>
</footer>

@stack('scripts')
</body>
</html>