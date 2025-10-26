<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/css/loginregister.css'])

</head>

<body class="relative min-h-screen flex flex-col">

    <header>
        <div>
            <img src="/images/homeywhite.png" alt="Homey">
        </div>
    </header>


    <div class="flex-grow flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-lg card-custom text-dark-green text-center">


            <h2 class="text-3xl font-bold mt-4 mb-8">Register</h2>

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nama (Username) --}}
                <div>
                    <input type="text" class="form-input-custom @error('name') border-red-500 @enderror" id="name"
                        name="name" value="{{ old('name') }}" required autofocus placeholder="Username">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email (WAJIB ADA) --}}
                <div>
                    <input type="email" class="form-input-custom shadow-2xs @error('email') border-red-500 @enderror"
                        id="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                    @error('email')
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
                    <input type="password"
                        class="form-input-custom @error('password_confirmation') border-red-500 @enderror"
                        id="password_confirmation" name="password_confirmation" required
                        placeholder="Konfirmasi Password">
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-custom">Create Account</button>

                <div class="text-center mt-4">
                    {{-- Link login diaktifkan --}}
                    <a href="{{ route('login') }}" class="text-dark-green hover:underline text-sm">Sudah punya akun?
                        Login</a>
                </div>
            </form>
        </div>
    </div>



</body>

</html>