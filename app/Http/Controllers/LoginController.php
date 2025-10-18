<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class LoginController extends Controller
{
    // Redirect ke Google (paksa pilih akun)
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account']) // selalu tampilkan pilihan akun
            ->redirect();
    }

    // Callback dari Google
    public function handleGoogleCallback()
    {
        try {
            // Coba pakai mode "sessionful" dulu
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            // Kalau state/session bermasalah, fallback ke stateless
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            Log::error('Google login gagal (sebelum fetch user): '.$e->getMessage());
            return redirect('/login')->with('error', 'Login dengan Google gagal.');
        }

        try {
            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Buat user baru bila belum ada
                $user = User::create([
                    'name'     => $googleUser->getName(),
                    'email'    => $googleUser->getEmail(),
                    'password' => bcrypt(str()->random(16)),
                    'role'     => 'customer', // default role
                ]);
            }

            // Login user
            Auth::login($user);

            // ✅ Semua user diarahkan ke /dashboard default
            return redirect('/dashboard');

        } catch (\Throwable $e) {
            Log::error('Google login gagal (setelah fetch user): '.$e->getMessage());
            return redirect('/login')->with('error', 'Login dengan Google gagal.');
        }
    }
}
