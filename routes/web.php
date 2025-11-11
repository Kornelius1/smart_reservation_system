<?php

use Illuminate\Http\Request;
use App\Livewire\ManajemenReservasi;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokuController;
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
use App\Http\Controllers\Customer\ReservasiRoomController;
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
    return view('customer.Reservasi');
});


Route::get('/reservasi-ruangan', [ReservasiRoomController::class, 'index'])
    ->name('reservasi-ruangan');

Route::get('/pilih-meja', [DenahMejaController::class, 'index'])
    ->name('tables.map');

Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');


Route::controller(RescheduleController::class)->group(function () {
    Route::get('/reschedule', 'showForm')->name('reschedule.form');
    Route::get('/reschedule/find', 'findReservation')->name('reschedule.find');
    Route::patch('/reschedule/update', 'updateSchedule')->name('reschedule.update'); // <-- GANTI JADI PATCH
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
    Route::post('/admin/meja/{meja}/toggle-status', [TableController::class, 'toggleStatus'])
     ->name('admin.meja.toggleStatus');

    // Manajemen Reservasi
    
    Route::get('/manajemen-reservasi', ManajemenReservasi::class)
         ->name('admin.reservasi.index');   

    // 2. Route untuk aksi CHECK-IN
    Route::patch('/reservasi/{id}/checkin', [ReservationController::class, 'checkin'])
         ->name('admin.reservasi.checkin');

    // 3. Route untuk aksi SELESAIKAN
    Route::patch('/reservasi/{id}/complete', [ReservationController::class, 'complete'])
         ->name('admin.reservasi.complete');

    // 4. Route untuk aksi BATALKAN
    Route::patch('/reservasi/{id}/cancel', [ReservationController::class, 'cancel'])
         ->name('admin.reservasi.cancel');
         
    // Manajemen Reschedule
    Route::get('/manajemen-reschedule', [ManajemenRescheduleController::class, 'index'])->name('manajemen-reschedule');

    // Manajemen Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Manajemen Ruangan (menggunakan prefix agar lebih rapi)
    Route::prefix('admin/manajemen-ruangan')->name('admin.manajemen-ruangan.')->group(function () {
        Route::get('/', [ManajemenRuanganController::class, 'index'])->name('index');
        Route::post('/', [ManajemenRuanganController::class, 'store'])->name('store'); 
        Route::put('/{id}', [ManajemenRuanganController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [ManajemenRuanganController::class, 'updateStatus'])->name('updateStatus'); 
        Route::get('/{id}/edit', [ManajemenRuanganController::class, 'edit'])->name('edit');
        Route::delete('/{id}', [ManajemenRuanganController::class, 'destroy'])->name('destroy');
    });

  
    Route::get('/manajemen-menu', [ManajemenMenuController::class, 'index'])->name('menu.index');
    Route::post('/manajemen-menu', [ManajemenMenuController::class, 'store'])->name('menu.store');
    
    // PERBAIKAN: Menggunakan {id} agar konsisten dengan controller
    Route::patch('/manajemen-menu/{id}/status', [ManajemenMenuController::class, 'updateStatus'])->name('menu.updateStatus');
    
    // PERBAIKAN: Mengarah ke method 'update', bukan 'updateDetail'
    Route::put('/manajemen-menu/{id}', [ManajemenMenuController::class, 'update'])->name('menu.update');
    
    // TAMBAHAN: Rute untuk menghapus menu
    Route::delete('/manajemen-menu/{id}', [ManajemenMenuController::class, 'destroy'])->name('menu.destroy');
});



Route::match(['get', 'post'], '/konfirmasi-pesanan', [BayarController::class, 'show'])
     ->name('payment.show');
     


// POST untuk membuat payment DOKU
// Route::post('/doku/create-payment', [DokuController::class, 'createPayment'])
//     ->name('doku.createPayment');

Route::post('/doku/create-payment', [BayarController::class, 'processPayment'])->name('doku.create');

// Halaman sukses / gagal
Route::get('/sukses', [DokuController::class, 'success'])
    ->name('payment.success');

Route::get('/gagal', [DokuController::class, 'failed'])
    ->name('payment.failed');

// DOKU return & webhook
Route::get('/doku/return', [DokuController::class, 'handleReturn'])->name('doku.return');
Route::post('/api/doku/notification', [DokuController::class, 'handleNotification'])->name('doku.notification');

require __DIR__.'/auth.php';

