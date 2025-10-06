<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
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
        .text-dark-green { color: #3C4B44; }
        .text-light-green { color: #9EC0B3; }
        .form-input-custom {
            background-color: #E0E2DA;
            border-radius: 9999px;
            border: none;
            padding: 0.75rem 1.5rem;
            width: 100%;
        }
        .btn-custom {
            background-color: #9EC0B3;
            color: #3C4B44;
            border-radius: 9999px;
            padding: 0.75rem 1.5rem;
            width: 100%;
            font-weight: 600;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(0);

            /* Animasi muncul saat load */
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.4s;
        }
        .btn-custom:hover {
            background-color: #88A699;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            transform: translateY(-3px) scale(1.03);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-custom {
            background-color: #9EC0B3;
            border-radius: 0.75rem;
            padding: 3rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .abstract-shape-1 {
            position: absolute;
            top: 0; left: 0;
            width: 250px; height: 250px;
            background-color: #9EC0B3;
            border-radius: 0 0 9999px 0;
            transform: rotate(-15deg) translateX(-50px) translateY(-50px);
            z-index: 0;
        }
        .abstract-shape-2 {
            position: absolute;
            top: 150px; left: 100px;
            width: 180px; height: 180px;
            background-color: #9EC0B3;
            border-radius: 9999px 0 0 9999px;
            transform: rotate(25deg) translateX(-70px) translateY(-30px);
            z-index: 0;
            opacity: 0.7;
        }
        .footer-link {
            color: #E0E2DA;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-link:hover { text-decoration: underline; }
    </style>
</head>
<body class="relative min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-dark-green text-light-green p-4 text-center text-lg font-bold">
        Register
    </header>

    {{-- Abstract Shapes --}}
    <div class="abstract-shape-1"></div>
    <div class="abstract-shape-2"></div>

    <div class="flex-grow flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-lg card-custom text-dark-green text-center">

            {{-- Logo Homey --}}
            <div class="mb-6 flex justify-center">
                <img src="/images/HOMEYY.png" 
                    alt="Logo Homey" 
                    class="h-16 w-auto brightness-110"
                    style="filter: drop-shadow(0 0 1px black) drop-shadow(0 0 1px black);">
            </div>

            <h2 class="text-3xl font-bold mb-8">Create An Account</h2>

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Username --}}
                <div>
                    <input type="text" class="form-input-custom @error('name') border-red-500 @enderror" 
                        id="name" name="name" value="{{ old('name') }}" required autofocus 
                        placeholder="Username">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <input type="password" class="form-input-custom @error('password') border-red-500 @enderror" 
                        id="password" name="password" required placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <input type="password" class="form-input-custom @error('password_confirmation') border-red-500 @enderror" 
                        id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Password">
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-custom">Create Account</button>

                <div class="text-center mt-4">
                    {{-- <a href="{{ route('login') }}" class="text-dark-green hover:underline text-sm">Sudah punya akun? Login</a> --}}
                </div>
            </form>
        </div>
    </div>

    {{-- Footer --}}
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
