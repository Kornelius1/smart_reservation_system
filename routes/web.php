<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TableController;

use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Customer\BayarController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Customer\DenahMejaController;
use App\Http\Controllers\Customer\PesanMenuController;
use App\Http\Controllers\Admin\ManajemenMenuController;
use App\Http\Controllers\Customer\RescheduleController;
use App\Http\Controllers\Customer\LandingPageController;
use App\Http\Controllers\Admin\ManajemenRuanganController;
use App\Http\Controllers\Admin\ManajemenRescheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute ini
| dimuat oleh RouteServiceProvider dalam grup yang berisi
| grup middleware "web". Buat sesuatu yang hebat!
|
*/

// === ROUTES UNTUK HALAMAN CUSTOMER ===
// Halaman publik (bisa diakses siapa saja)
Route::get('/', [LandingPageController::class, 'index'])
    ->name('customer.landing.page');

Route::get('/pilih-reservasi', function () {
    return view('customer.reservasi');
});

Route::get('/reservasi-ruangan', function () {
    return view('customer.reservasi-ruangan');
});

Route::get('/pilih-meja', [DenahMejaController::class, 'index'])
    ->name('tables.map');

Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');
Route::post('/konfirmasi-pesanan', [BayarController::class, 'show'])->name('payment.confirmation');
Route::post('/proses-pembayaran', [BayarController::class, 'processPayment'])->name('payment.process');

Route::controller(RescheduleController::class)->group(function () {
    Route::get('/reschedule', 'showForm')->name('reschedule.form');
    Route::get('/reschedule/find', 'findReservation')->name('reschedule.find');
    Route::post('/reschedule/update', 'updateSchedule')->name('reschedule.update');
});


// === ROUTES UNTUK HALAMAN ADMIN ===
// Semua route di dalam grup ini akan:
// 1. Membutuhkan login ('auth')
// 2. Membutuhkan email terverifikasi ('verified')

Route::middleware(['auth', 'verified'])->group(function () {

    // Mengarahkan /dashboard ke DashboardAdmin
    Route::get('/dashboard', function () {
        return view('admin.DashboardAdmin');
    })->name('dashboard'); // <-- Nama 'dashboard' PENTING untuk redirect Breeze

    // Manajemen Meja
    Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');

    // Manajemen Reservasi
    Route::get('/manajemen-reservasi', [ReservationController::class, 'index'])->name('manajemen-reservasi');

    // Manajemen Reschedule
    Route::get('/manajemen-reschedule', [ManajemenRescheduleController::class, 'index'])->name('manajemen-reschedule');

    // Manajemen Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Manajemen Ruangan (menggunakan prefix agar lebih rapi)
    Route::prefix('admin/manajemen-ruangan')->name('admin.manajemen-ruangan.')->group(function () {
        Route::get('/', [ManajemenRuanganController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [ManajemenRuanganController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ManajemenRuanganController::class, 'update'])->name('update');
    });

    Route::get('/manajemen-menu', [ManajemenMenuController::class, 'index'])->name('menu.index');
        
   
        Route::post('/manajemen-menu', [ManajemenMenuController::class, 'store'])->name('menu.store');

        Route::patch('/manajemen-menu/{product}/status', [ManajemenMenuController::class, 'updateStatus'])->name('menu.updateStatus');


        Route::put('/manajemen-menu/{product}/detail', [ManajemenMenuController::class, 'updateDetail'])->name('menu.updateDetail');
   
        //bisa menambahkan route admin lainnya di sini

});


require __DIR__.'/auth.php';

