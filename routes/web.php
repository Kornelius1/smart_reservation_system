<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RescheduleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reschedule', [RescheduleController::class, 'showForm'])->name('reschedule.form');
Route::get('/reschedule/find', [RescheduleController::class, 'findReservation'])->name('reschedule.find');
Route::post('/reschedule/update', [RescheduleController::class, 'updateSchedule'])->name('reschedule.update');