<?php

use App\Http\Controllers\ManajemenMejaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController; // Tambahkan ini
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE BARU UNTUK MANAJEMEN MEJA
Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === Login dengan Google ===
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// === Dashboard Admin ===
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard'); // ganti "admin.dashboard" sesuai nama file blade kamu
})->name('admin.dashboard')->middleware('auth');

// === Dashboard Customer ===
Route::get('/customer/dashboard', function () {
    return view('customer.dashboard'); // ganti "customer.dashboard" sesuai nama file blade kamu
})->name('customer.dashboard')->middleware('auth');

require __DIR__.'/auth.php';


// Routes untuk Manajemen Meja
Route::prefix('manajemen-meja')->name('manajemen-meja.')->group(function () {
    Route::get('/', [ManajemenMejaController::class, 'index'])->name('index');
    Route::put('/{id}', [ManajemenMejaController::class, 'update'])->name('update');
    Route::get('/search', [ManajemenMejaController::class, 'search'])->name('search');
    Route::patch('/{id}/toggle-status', [ManajemenMejaController::class, 'toggleStatus'])->name('toggle-status');
});
require __DIR__.'/auth.php';
