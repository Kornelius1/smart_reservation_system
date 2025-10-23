<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TableController; 
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RescheduleController;
use App\Http\Controllers\ReservationController; 
use App\Http\Controllers\Customer\BayarController;
use App\Http\Controllers\ManajemenRuanganController;
use App\Http\Controllers\Customer\DenahMejaController;
use App\Http\Controllers\Customer\PesanMenuController;
use App\Http\Controllers\ManajemenRescheduleController;
use App\Http\Controllers\Customer\LandingPageController;
use App\Http\Controllers\MenuController;



// === ROUTES UNTUK HALAMAN CUSTOMER ===
// 1. Landing Page
Route::get('/', [LandingPageController::class, 'index'])
    ->name('customer.landing.page');



// 3. Pilih Jenis Reservasi
Route::get('/pilih-reservasi', function () {
    return view('customer.reservasi');
});

// 3a. Pilih Ruangan
Route::get('/reservasi-ruangan', function () {
    return view('customer.reservasi-ruangan'); 
});

// 3b. Pilih Meja
Route::get('/pilih-meja', [DenahMejaController::class, 'index'])
    ->name('tables.map'); // nanti nama di view reservasi ganti jadi route ini

// 4. Routes untuk pesanmenu
Route::get('/pesanmenu', [PesanMenuController::class, 'index'])->name('pesanmenu');

// 5. Routes untuk pembayaran
// Rute untuk MENAMPILKAN halaman konfirmasi (dari PesanMenu.vue)
Route::post('/konfirmasi-pesanan', [BayarController::class, 'show'])->name('payment.confirmation');

// Rute untuk MEMPROSES PEMBAYARAN (dari halaman konfirmasi)
Route::post('/proses-pembayaran', [BayarController::class, 'processPayment'])->name('payment.process');

// 6. Route Apabila ingin Reschedule
Route::controller(RescheduleController::class)->group(function () {
    Route::get('/reschedule', 'showForm')->name('reschedule.form');
    Route::get('/reschedule/find', 'findReservation')->name('reschedule.find');
    Route::post('/reschedule/update', 'updateSchedule')->name('reschedule.update');
});


// === ROUTES UNTUK HALAMAN ADMIN === PERLU AUTENTIKASI

// 1. dashboard admin
// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard'); // ganti "admin.dashboard" sesuai nama file blade kamu
// })->name('admin.dashboard')->middleware('auth');


Route::get('/dashboard', function () {
    return view('admin.DashboardAdmin');
})->name('admin.dashboard');

// 1. Manjemen Menu
Route::resource('manajemen-menu', MenuController::class)
     ->parameters(['manajemen-menu' => 'id'])
     ->names('menu');

Route::patch('manajemen-menu/{id}/status', [MenuController::class, 'updateStatus'])
     ->name('menu.updateStatus'); 

// 2. Manajemen Meja
Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');


// 3. Manajemen Reservasi
Route::get('/manajemen-reservasi', [ReservationController::class, 'index'])->name('manajemen-reservasi');


// 4. Manajemen Reschedule
Route::get('/manajemen-reschedule', [ManajemenRescheduleController::class, 'index'])->name('manajemen-reschedule');


// 5. Manajemen Laporan
Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
// Route untuk mengunduh/export laporan
Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

// 6. Manajemen Ruangan
Route::get('/admin/manajemen-ruangan', [ManajemenRuanganController::class, 'index'])
     ->name('admin.manajemen-ruangan.index'); // <-- Beri nama .index
// Route untuk menampilkan FORM EDIT - BUTUH ID
Route::get('/admin/manajemen-ruangan/{id}/edit', [ManajemenRuanganController::class, 'edit'])
     ->name('admin.manajemen-ruangan.edit'); // <-- Beri nama .edit
// Route untuk proses UPDATE - BUTUH ID
Route::put('/admin/manajemen-ruangan/{id}', [ManajemenRuanganController::class, 'update'])
     ->name('admin.manajemen-ruangan.update');


require __DIR__.'/auth.php';