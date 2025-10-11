<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PemesananMenuController;
use App\Http\Controllers\Customer\LandingPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customer\DenahMejaController;

// === Halaman Awal ===
Route::get('/', function () {
    return view('welcome');
});

// === Landing Page ===
Route::get('/landing-page', [LandingPageController::class, 'index'])
    ->name('landing.page');

// === Pemesanan Menu ===
Route::get('/pemesanan-menu', [PemesananMenuController::class, 'index'])
    ->name('pemesanan.menu');

// === Dashboard Customer (tanpa login) ===
Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
    ->name('customer.dashboard');

// === Denah Meja (tanpa login) ===
Route::get('/customer/denah-meja', [DenahMejaController::class, 'index'])
    ->name('customer.denah-meja');

// === Dashboard Admin (hanya admin login) ===
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard')
    ->middleware('auth');

// === Redirect /dashboard ke dashboard customer ===
Route::get('/dashboard', function () {
    return redirect()->route('customer.dashboard');
})->name('dashboard');

// === Login dengan Google ===
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// === Profile (hanya untuk user login) ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
