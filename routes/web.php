<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RescheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Laravel secara otomatis memuat file ini.
|
*/

// Route Utama ('/') akan langsung dialihkan ke halaman daftar reschedule
Route::get('/', function() {
    return redirect()->route('reschedule.index');
});

// Route Resource untuk mengelola data reschedule
// Secara otomatis membuat route untuk: index, create, store, show, edit, update, destroy.
Route::resource('reschedule', RescheduleController::class);

// Catatan: Baris yang duplikat (untuk edit dan update) telah dihapus.
// Nama route yang tersedia sekarang adalah: reschedule.index, reschedule.edit, reschedule.update, dsb.
