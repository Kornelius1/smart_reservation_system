<?php

use App\Http\Controllers\ManajemenMejaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Routes untuk Manajemen Meja
Route::prefix('manajemen-meja')->name('manajemen-meja.')->group(function () {
    Route::get('/', [ManajemenMejaController::class, 'index'])->name('index');
    Route::post('/', [ManajemenMejaController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ManajemenMejaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ManajemenMejaController::class, 'update'])->name('update');
    Route::delete('/{id}', [ManajemenMejaController::class, 'destroy'])->name('destroy');
    Route::get('/search', [ManajemenMejaController::class, 'search'])->name('search');
    Route::patch('/{id}/toggle-status', [ManajemenMejaController::class, 'toggleStatus'])->name('toggle-status');
});