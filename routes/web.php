<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController; 
use Illuminate\Support\Facades\Route;


Route::get('/reservasi', [ReservationController::class, 'create'])
    ->middleware(['auth'])->name('reservasi.create');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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