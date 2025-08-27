<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// Tambahkan controller untuk admin jika sudah dibuat
// use App\Http\Controllers\Admin\DashboardController; 

/*
|--------------------------------------------------------------------------
| Rute Publik (Untuk Customer)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Rute KHUSUS ADMIN (Wajib Login & Sebaiknya ada pengecekan role 'admin')
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function() {
    // URL-nya akan menjadi /admin/dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Nanti bisa diarahkan ke view khusus admin: view('admin.dashboard')
    })->name('dashboard');

    // Tempatkan semua rute yang hanya boleh diakses admin di sini
    // Contoh: Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
});


/*
|--------------------------------------------------------------------------
| Rute Pengguna Terautentikasi (Profil, dll)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';