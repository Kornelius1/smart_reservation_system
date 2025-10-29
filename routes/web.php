<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\XenditController;
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
    return view('customer.reservasi');
});


Route::get('/reservasi-ruangan', [ReservasiRoomController::class, 'index'])
    ->name('reservasi-ruangan');

Route::get('/pilih-meja', [DenahMejaController::class, 'index'])
    ->name('tables.map');

Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');


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
    Route::post('/admin/meja/{meja}/toggle-status', [TableController::class, 'toggleStatus'])
     ->name('admin.meja.toggleStatus');

    // Manajemen Reservasi
    Route::get('/manajemen-reservasi', [ReservationController::class, 'index'])
         ->name('admin.reservasi.index'); // <-- Nama disesuaikan agar standar

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

// === ROUTES UNTUK Halaman Pembayaran  ===
Route::post('/proses-pembayaran', [BayarController::class, 'processPayment'])
     ->name('payment.process');

// ==========================================================
// BARU: Rute untuk Webhook Xendit
// ==========================================================
// Form dari 'pesanmenu' akan posting ke sini
Route::match(['get', 'post'], '/konfirmasi-pesanan', [BayarController::class, 'show'])
     ->name('payment.show');
     
Route::post('/xendit-webhook', [XenditController::class, 'handle'])
     ->name('xendit.webhook');

// ==========================================================
// Rute Halaman Sukses
// ==========================================================
Route::get('/sukses', function (Request $request) {
    
    // 1. Ambil ID dari SESSION. session()->pull() mengambil data & menghapusnya.
    $orderId = session()->pull('last_transaction_id', 'TRANSAKSI_ANDA'); 

    // 2. Buat pesan suksesnya
    $message = 'Reservasi Anda (ID: ' . e($orderId) . ') berhasil dikonfirmasi! Harap simpan ID Transaksi Anda untuk keperluan reschedule atau check-in.';
    
    // 3. Simpan pesan ke session flash (untuk ditampilkan di view)
    session()->flash('success_message', $message);
    
    // 4. LANGSUNG TAMPILKAN VIEW
    return view('customer.sukses');

})->name('payment.success');

// ==========================================================
// Rute Halaman Gagal (Sudah Benar)
// ==========================================================
Route::get('/gagal', function (Request $request) {
    
    $orderId = $request->query('external_id', 'TRANSAKSI_GATAL');
    $message = 'Pembayaran untuk ID ' . e($orderId) . ' gagal atau dibatalkan.';
    
    // Arahkan kembali ke halaman pesan menu agar user bisa coba lagi
    return redirect()->route('pesanmenu')->withErrors(['msg' => $message]);

})->name('payment.failed');

require __DIR__.'/auth.php';

