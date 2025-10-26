<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Homey Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/css/loginregister.css'])

</head>

<body class="relative min-h-screen flex flex-col">

    {{-- Header --}}
    <header>
        <div>
            <img src="/images/homeywhite.png" alt="Homey">
        </div>
    </header>



    <div class="flex-grow flex items-center justify-center relative z-10">
        <div class="w-full max-w-lg card-custom text-dark-green text-center">



            <h2 class="text-3xl font-bold mt-4 mb-8">Sign In</h2>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif


            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-left">
                    <ul class="list-disc list-inside">
                        <li>Email atau password yang Anda masukkan salah.</li>
                    </ul>
                </div>
            @endif


            {{-- Form Login --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <input type="email" class="form-input-custom" id="email" name="email" value="{{ old('email') }}"
                        required autofocus placeholder="Email">
                </div>

                {{-- Password --}}
                <div>
                    <input type="password" class="form-input-custom" id="password" name="password" required
                        placeholder="Password">
                </div>

                <div class="text-left">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-dark-green shadow-sm focus:ring-dark-green"
                            name="remember">
                        <span class="ms-2 text-sm text-dark-green">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <button type="submit" class="btn-custom">Login</button>

                <div class="flex items-center justify-between mt-4 text-sm">
                    <a href="{{ route('register') }}" class="text-dark-green hover:underline">
                        Belum punya akun? Register
                    </a>
                    @if (Route::has('password.request'))
                        <a class="text-dark-green hover:underline" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>


</body>

</html>