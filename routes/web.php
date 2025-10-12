<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RescheduleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reschedule', [RescheduleController::class, 'showForm'])->name('reschedule.form');
Route::post('/reschedule/find', [RescheduleController::class, 'findReservation'])->name('reschedule.find');