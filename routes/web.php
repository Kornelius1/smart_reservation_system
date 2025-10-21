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
use App\Http\Controllers\Customer\DenahMejaController;
use App\Http\Controllers\Customer\PesanMenuController;
use App\Http\Controllers\ManajemenRescheduleController;
use App\Http\Controllers\Customer\LandingPageController;



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
Route::get('/reschedule', [RescheduleController::class, 'showForm'])->name('reschedule.form');
Route::get('/reschedule/find', [RescheduleController::class, 'findReservation'])->name('reschedule.find');
Route::post('/reschedule/update', [RescheduleController::class, 'updateSchedule'])->name('reschedule.update');


// === ROUTES UNTUK HALAMAN ADMIN === PERLU AUTENTIKASI

// 1. dashboard admin
// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard'); // ganti "admin.dashboard" sesuai nama file blade kamu
// })->name('admin.dashboard')->middleware('auth');

Route::get('/dashboard', function () {
    return view('admin.DashboardAdmin');
})->name('admin.dashboard');

// 1. Manajemen Menu
Route::get('/manajemen-menu', function () {
    // DATA MENU LENGKAP
    $menuItems = [
        // Snacks
        ['id' => 1, 'nama' => 'Burger Daging', 'harga' => 17000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 2, 'nama' => 'Burger Special', 'harga' => 20000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 3, 'nama' => 'Roti Bakar', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 4, 'nama' => 'Toast Cream', 'harga' => 20000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 5, 'nama' => 'Jamur Krispi', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 6, 'nama' => 'Lumpia Bakso', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 7, 'nama' => 'Tahu Bakso', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 8, 'nama' => 'Sosis Bakar', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 9, 'nama' => 'Nugget Ayam', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 10, 'nama' => 'Ubi Goreng', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 11, 'nama' => 'French Fries', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 12, 'nama' => 'Pisang Krispi', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 13, 'nama' => 'Mix Platter', 'harga' => 20000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'Affogato.png'],

        // Heavy Meal
        ['id' => 14, 'nama' => 'Nasi Telur Ceplok', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 15, 'nama' => 'Nasi Telur Dadar', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 16, 'nama' => 'Nasi Ayam Krispi', 'harga' => 17000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 17, 'nama' => 'Nasi Ayam Penyet', 'harga' => 19000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 18, 'nama' => 'Nasi Ayam Katsu', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 19, 'nama' => 'Nasi Goreng Telur', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 20, 'nama' => 'Nasi Goreng Ayam Krispi', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 21, 'nama' => 'Nasi Goreng Ayam Katsu', 'harga' => 25000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 22, 'nama' => 'Mie Goreng Reguler', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 23, 'nama' => 'Mie Goreng Special', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 24, 'nama' => 'Mie Rebus Reguler', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 25, 'nama' => 'Mie Rebus Special', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 26, 'nama' => 'Mie Nyemek', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 27, 'nama' => 'Mie Tiaw Goreng', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 28, 'nama' => 'Mie Nas', 'harga' => 18000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 29, 'nama' => 'Spaghetti Reguler', 'harga' => 20000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 30, 'nama' => 'Spaghetti Ayam Katsu', 'harga' => 25000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'Affogato.png'],

        // Traditional
        ['id' => 31, 'nama' => 'Teh Talang Horney', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 32, 'nama' => 'Ginger Horney', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 33, 'nama' => 'Ginger Milk', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 34, 'nama' => 'Wedang Jahe', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 35, 'nama' => 'Bandrek Susu', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 36, 'nama' => 'Bandrek Telur', 'harga' => 17000, 'kategori' => 'Traditional', 'tersedia' => false, 'foto' => 'Affogato.png'],
        ['id' => 37, 'nama' => 'Teh Telur', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 38, 'nama' => 'Teh Tarik', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 39, 'nama' => 'Kopi Telur', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 40, 'nama' => 'Kopi Cingcang', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'Affogato.png'],

        // Juice
        ['id' => 41, 'nama' => 'Alpukat', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus pokat.png'],
        ['id' => 42, 'nama' => 'Apel', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus apel.png'],
        ['id' => 43, 'nama' => 'Buah Naga', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => false, 'foto' => 'jus buah naga.png'],
        ['id' => 44, 'nama' => 'Mangga', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus mangga.png'],
        ['id' => 45, 'nama' => 'Jambu Biji', 'harga' => 15000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus jambu biji.png'],
        ['id' => 46, 'nama' => 'Sirsak', 'harga' => 15000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus sirsak.png'],

        // Fresh Drink
        ['id' => 47, 'nama' => 'Jasmine Tea', 'harga' => 12000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'jasmine tea.png'],
        ['id' => 48, 'nama' => 'Lemon Tea', 'harga' => 13000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'lemon tea.png'],
        ['id' => 49, 'nama' => 'Horney Tea', 'harga' => 13000, 'kategori' => 'Fresh Drink', 'tersedia' => false, 'foto' => 'homey tea.png'],
        ['id' => 50, 'nama' => 'Apple Tea', 'harga' => 13000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'apple tea.png'],
        ['id' => 51, 'nama' => 'Blackcurrant Tea', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'Blackcurrant Tea.png'],
        ['id' => 52, 'nama' => 'Peach Tea', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => false, 'foto' => 'Peach Tea (ice).png'],
        ['id' => 53, 'nama' => 'Lychee Tea', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'Lychee Tea (ice).png'],
        ['id' => 54, 'nama' => 'Orange Squash', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'Orange Squash (ice).png'],
        ['id' => 55, 'nama' => 'Orange Sky', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'Orange Sky (ice).png'],
        ['id' => 56, 'nama' => 'Blue Blood', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => false, 'foto' => 'Blue Blood (ice).png'],
        ['id' => 57, 'nama' => 'Blue Squash', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'Blue Squash (ice).png'],
        ['id' => 58, 'nama' => 'Soda Cembira', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'SodaGembira (ice).png'],

        // Special Taste
        ['id' => 59, 'nama' => 'Chocolate Classic', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Chocolate Classic (ice).png'],
        ['id' => 60, 'nama' => 'Chocolate Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'cChocolate Frappe (ice).png'],
        ['id' => 61, 'nama' => 'Milo', 'harga' => 15000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Milo (ice).png'],
        ['id' => 62, 'nama' => 'Taro', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => false, 'foto' => 'Taro(ice).png'],
        ['id' => 63, 'nama' => 'Taro Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Taro-Frappe (ice).png'],
        ['id' => 64, 'nama' => 'Red Velvet', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Red Velvet (ice).png'],
        ['id' => 65, 'nama' => 'Red Velvet Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Red Velvet frappe (ice).png'],
        ['id' => 66, 'nama' => 'Charcoal', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => false, 'foto' => 'Charcoal (ice).png'],
        ['id' => 67, 'nama' => 'Matcha Greentea', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Matcha Greentea (ice).png'],
        ['id' => 68, 'nama' => 'Matcha Greentea Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Matcha Greentea frappe (ice).png'],
        ['id' => 69, 'nama' => 'Cookies and Cream', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'Cookies and Cream (ice).png'],

        // Ice Cream
        ['id' => 70, 'nama' => 'Vanila', 'harga' => 15000, 'kategori' => 'Ice Cream', 'tersedia' => true, 'foto' => 'Vanilla-ice cream.png'],
        ['id' => 71, 'nama' => 'Chocolate', 'harga' => 15000, 'kategori' => 'Ice Cream', 'tersedia' => true, 'foto' => 'ice cream chocolate.png'],
        ['id' => 72, 'nama' => 'Mix', 'harga' => 15000, 'kategori' => 'Ice Cream', 'tersedia' => true, 'foto' => 'ice cream mix.png'],

        // Coffee
        ['id' => 73, 'nama' => 'Black Coffee', 'harga' => 15000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Black Coffee (hot_ice).png'],
        ['id' => 74, 'nama' => 'Chocolate Coffee', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Chocolate Coffee (ice).png'],
        ['id' => 75, 'nama' => 'Brown Sugar Coffee', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Brown Sugar Coffee (ice).png'],
        ['id' => 76, 'nama' => 'Brown Sugar Pandan', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => false, 'foto' => 'Brown Sugar Pandan.png'],
        ['id' => 77, 'nama' => 'Original Milk Coffee', 'harga' => 17000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Original Milk Coffee (ice).png'],
        ['id' => 78, 'nama' => 'Horney Coffee', 'harga' => 17000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Homey Coffee (ice).png'],
        ['id' => 79, 'nama' => 'Caramel Latte', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Caramel Latte (ice).png'],
        ['id' => 80, 'nama' => 'Vanilla Latte', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Vanilla-Latte (ice).png'],
        ['id' => 81, 'nama' => 'Butterscotch Coffee', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => false, 'foto' => 'Butterscotch Coffee (ice).png'],
        ['id' => 82, 'nama' => 'Affogato', 'harga' => 24000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Affogato.png'],
        ['id' => 83, 'nama' => 'Kopmil', 'harga' => 15000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Kopmil (ice).png'],
        ['id' => 84, 'nama' => 'Vietnam Drip', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'Vietnam-Drip (hot).png'],
    ];

    return view('admin.manajemen-menu', ['menuItems' => $menuItems]);
})->name('manajemen-menu');

// 2. Manajemen Meja
Route::get('/manajemen-meja', [TableController::class, 'index'])->name('manajemen-meja');


// 3. Manajemen Reservasi
Route::get('/manajemen-reservasi', [ReservationController::class, 'index'])->name('manajemen-reservasi');


// 4. Manajemen Reschedule
Route::get('/manajemen-reschedule', [ManajemenRescheduleController::class, 'index'])->name('manajemen-reschedule');


// 5. Manajemen Laporan
Route::get('/manajemen-laporan', [LaporanController::class, 'index'])->name('manajemen-laporan');

// 6. Manajemen Ruangan
// Route::get('/manajemen-ruangan', [ManajemenRuanganController::class, 'index'])->name('manajemen-laporan');



require __DIR__.'/auth.php';