<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController; // Pastikan ini ada
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk manajemen meja yang sudah ada
Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');

// Route BARU untuk manajemen reservasi
Route::get('/manajemen-reservasi', [ReservationController::class, 'index'])->name('manajemen-reservasi');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';