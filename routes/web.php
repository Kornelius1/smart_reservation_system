<?php

use App\Http\Controllers\ManajemenMejaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PemesananMenuController;
use App\Http\Controllers\Customer\LandingPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customer\DenahMejaController;
use App\Http\Controllers\Customer\BayarController;
use App\Http\Controllers\Customer\PesanMenuController;
use App\Http\Controllers\RescheduleController;
use App\Http\Controllers\ReservationController; 



// === Halaman Awal ===
Route::get('/', function () {
    return view('welcome');
});

// ROUTE BARU UNTUK MANAJEMEN MEJA
Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');
Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');

Route::post('/bayar', [BayarController::class, 'index'])->name('bayar.index');


// ROUTE BARU UNTUK MANAJEMEN MEJA
Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');
Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');

Route::post('/bayar', [BayarController::class, 'index'])->name('bayar.index');

Route::get('/reschedule', [RescheduleController::class, 'showForm'])->name('reschedule.form');
Route::get('/reschedule/find', [RescheduleController::class, 'findReservation'])->name('reschedule.find');
Route::post('/reschedule/update', [RescheduleController::class, 'updateSchedule'])->name('reschedule.update');


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


// Routes untuk Manajemen Meja
Route::prefix('manajemen-meja')->name('manajemen-meja.')->group(function () {
    Route::get('/', [ManajemenMejaController::class, 'index'])->name('index');
    Route::put('/{id}', [ManajemenMejaController::class, 'update'])->name('update');
    Route::get('/search', [ManajemenMejaController::class, 'search'])->name('search');
    Route::patch('/{id}/toggle-status', [ManajemenMejaController::class, 'toggleStatus'])->name('toggle-status');
});


//Pilih Reservasi Route
Route::get('/reservasi', [ReservationController::class, 'create'])
    ->middleware(['auth'])->name('reservasi.create');

    Route::get('/reservasi-ruangan', function () {
    return view('reservasi');
});

Route::get('/private-room', function () {
    return view('private_room'); 
});

Route::view('/private-room', 'private_room');

Route::get('/reservasi-meja', function () {
    return view('reservasi_meja'); // nanti kamu buat file ini juga
});

require __DIR__.'/auth.php';