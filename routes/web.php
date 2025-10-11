<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\BayarController;
use App\Http\Controllers\Customer\PesanMenuController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');

Route::post('/bayar', [BayarController::class, 'index'])->name('bayar.index');


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
