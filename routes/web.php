<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE UNTUK MANAJEMEN MENU DENGAN DATA LENGKAP
Route::get('/manajemen-menu', function () {
    // DATA MENU LENGKAP
    $menuItems = [
        // Snacks
        ['id' => 1, 'nama' => 'Burger Daging', 'harga' => 17000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'burger-daging.jpg'],
        ['id' => 2, 'nama' => 'Burger Special', 'harga' => 20000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'burger-special.jpg'],
        ['id' => 3, 'nama' => 'Roti Bakar', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'roti-bakar.jpg'],
        ['id' => 4, 'nama' => 'Toast Cream', 'harga' => 20000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 5, 'nama' => 'Jamur Krispi', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'jamur-krispi.jpg'],
        ['id' => 6, 'nama' => 'Lumpia Bakso', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 7, 'nama' => 'Tahu Bakso', 'harga' => 15000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'tahu-bakso.jpg'],
        ['id' => 8, 'nama' => 'Sosis Bakar', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'sosis-bakar.jpg'],
        ['id' => 9, 'nama' => 'Nugget Ayam', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'nugget-ayam.jpg'],
        ['id' => 10, 'nama' => 'Ubi Goreng', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 11, 'nama' => 'French Fries', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'french-fries.jpg'],
        ['id' => 12, 'nama' => 'Pisang Krispi', 'harga' => 12000, 'kategori' => 'Snacks', 'tersedia' => true, 'foto' => 'pisang-krispi.jpg'],
        ['id' => 13, 'nama' => 'Mix Platter', 'harga' => 20000, 'kategori' => 'Snacks', 'tersedia' => false, 'foto' => 'mix-platter.jpg'],

        // Heavy Meal
        ['id' => 14, 'nama' => 'Nasi Telur Ceplok', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'nasi-telur-ceplok.jpg'],
        ['id' => 15, 'nama' => 'Nasi Telur Dadar', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'nasi-telur-dadar.jpg'],
        ['id' => 16, 'nama' => 'Nasi Ayam Krispi', 'harga' => 17000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'nasi-ayam-krispi.jpg'],
        ['id' => 17, 'nama' => 'Nasi Ayam Penyet', 'harga' => 19000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'nasi-ayam-penyet.jpg'],
        ['id' => 18, 'nama' => 'Nasi Ayam Katsu', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'nasi-ayam-katsu.jpg'],
        ['id' => 19, 'nama' => 'Nasi Goreng Telur', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'nasi-goreng.jpg'],
        ['id' => 20, 'nama' => 'Nasi Goreng Ayam Krispi', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'nasi-goreng-krispi.jpg'],
        ['id' => 21, 'nama' => 'Nasi Goreng Ayam Katsu', 'harga' => 25000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'nasi-goreng-katsu.jpg'],
        ['id' => 22, 'nama' => 'Mie Goreng Reguler', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'mie-goreng.jpg'],
        ['id' => 23, 'nama' => 'Mie Goreng Special', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'mie-goreng-special.jpg'],
        ['id' => 24, 'nama' => 'Mie Rebus Reguler', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 25, 'nama' => 'Mie Rebus Special', 'harga' => 22000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 26, 'nama' => 'Mie Nyemek', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => false, 'foto' => 'placeholder.png'],
        ['id' => 27, 'nama' => 'Mie Tiaw Goreng', 'harga' => 15000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 28, 'nama' => 'Mie Nas', 'harga' => 18000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 29, 'nama' => 'Spaghetti Reguler', 'harga' => 20000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'spaghetti.jpg'],
        ['id' => 30, 'nama' => 'Spaghetti Ayam Katsu', 'harga' => 25000, 'kategori' => 'Heavy Meal', 'tersedia' => true, 'foto' => 'spaghetti-katsu.jpg'],

        // Traditional
        ['id' => 31, 'nama' => 'Teh Talang Horney', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 32, 'nama' => 'Ginger Horney', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 33, 'nama' => 'Ginger Milk', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => false, 'foto' => 'placeholder.png'],
        ['id' => 34, 'nama' => 'Wedang Jahe', 'harga' => 13000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 35, 'nama' => 'Bandrek Susu', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 36, 'nama' => 'Bandrek Telur', 'harga' => 17000, 'kategori' => 'Traditional', 'tersedia' => false, 'foto' => 'placeholder.png'],
        ['id' => 37, 'nama' => 'Teh Telur', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 38, 'nama' => 'Teh Tarik', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'teh-tarik.jpg'],
        ['id' => 39, 'nama' => 'Kopi Telur', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 40, 'nama' => 'Kopi Cingcang', 'harga' => 15000, 'kategori' => 'Traditional', 'tersedia' => true, 'foto' => 'placeholder.png'],

        // Juice
        ['id' => 41, 'nama' => 'Alpukat', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus-alpukat.jpg'],
        ['id' => 42, 'nama' => 'Apel', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus-apel.jpg'],
        ['id' => 43, 'nama' => 'Buah Naga', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => false, 'foto' => 'jus-naga.jpg'],
        ['id' => 44, 'nama' => 'Mangga', 'harga' => 16000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus-mangga.jpg'],
        ['id' => 45, 'nama' => 'Jambu Biji', 'harga' => 15000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus-jambu.jpg'],
        ['id' => 46, 'nama' => 'Sirsak', 'harga' => 15000, 'kategori' => 'Juice', 'tersedia' => true, 'foto' => 'jus-sirsak.jpg'],

        // Fresh Drink
        ['id' => 47, 'nama' => 'Jasmine Tea', 'harga' => 12000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'jasmine-tea.jpg'],
        ['id' => 48, 'nama' => 'Lemon Tea', 'harga' => 13000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'lemon-tea.jpg'],
        ['id' => 49, 'nama' => 'Horney Tea', 'harga' => 13000, 'kategori' => 'Fresh Drink', 'tersedia' => false, 'foto' => 'placeholder.png'],
        ['id' => 50, 'nama' => 'Apple Tea', 'harga' => 13000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'apple-tea.jpg'],
        ['id' => 51, 'nama' => 'Blackcurrant Tea', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'blackcurrant-tea.jpg'],
        ['id' => 52, 'nama' => 'Peach Tea', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => false, 'foto' => 'peach-tea.jpg'],
        ['id' => 53, 'nama' => 'Lychee Tea', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'lychee-tea.jpg'],
        ['id' => 54, 'nama' => 'Orange Squash', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'orange-squash.jpg'],
        ['id' => 55, 'nama' => 'Orange Sky', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 56, 'nama' => 'Blue Blood', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => false, 'foto' => 'placeholder.png'],
        ['id' => 57, 'nama' => 'Blue Squash', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 58, 'nama' => 'Soda Cembira', 'harga' => 15000, 'kategori' => 'Fresh Drink', 'tersedia' => true, 'foto' => 'soda-gembira.jpg'],

        // Special Taste
        ['id' => 59, 'nama' => 'Chocolate Classic', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'chocolate.jpg'],
        ['id' => 60, 'nama' => 'Chocolate Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'chocolate-frappe.jpg'],
        ['id' => 61, 'nama' => 'Milo', 'harga' => 15000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'milo.jpg'],
        ['id' => 62, 'nama' => 'Taro', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => false, 'foto' => 'taro.jpg'],
        ['id' => 63, 'nama' => 'Taro Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'taro-frappe.jpg'],
        ['id' => 64, 'nama' => 'Red Velvet', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'red-velvet.jpg'],
        ['id' => 65, 'nama' => 'Red Velvet Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'red-velvet-frappe.jpg'],
        ['id' => 66, 'nama' => 'Charcoal', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => false, 'foto' => 'charcoal.jpg'],
        ['id' => 67, 'nama' => 'Matcha Greentea', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'matcha.jpg'],
        ['id' => 68, 'nama' => 'Matcha Greentea Frappe', 'harga' => 20000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'matcha-frappe.jpg'],
        ['id' => 69, 'nama' => 'Cookies and Cream', 'harga' => 16000, 'kategori' => 'Special Taste', 'tersedia' => true, 'foto' => 'cookies-cream.jpg'],

        // Ice Cream
        ['id' => 70, 'nama' => 'Vanila', 'harga' => 15000, 'kategori' => 'Ice Cream', 'tersedia' => true, 'foto' => 'ice-cream-vanilla.jpg'],
        ['id' => 71, 'nama' => 'Chocolate', 'harga' => 15000, 'kategori' => 'Ice Cream', 'tersedia' => true, 'foto' => 'ice-cream-chocolate.jpg'],
        ['id' => 72, 'nama' => 'Mix', 'harga' => 15000, 'kategori' => 'Ice Cream', 'tersedia' => true, 'foto' => 'placeholder.png'],

        // Coffee
        ['id' => 73, 'nama' => 'Black Coffee', 'harga' => 15000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'black-coffee.jpg'],
        ['id' => 74, 'nama' => 'Chocolate Coffee', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'chocolate-coffee.jpg'],
        ['id' => 75, 'nama' => 'Brown Sugar Coffee', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'brown-sugar-coffee.jpg'],
        ['id' => 76, 'nama' => 'Brown Sugar Pandan', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => false, 'foto' => 'placeholder.png'],
        ['id' => 77, 'nama' => 'Original Milk Coffee', 'harga' => 17000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'milk-coffee.jpg'],
        ['id' => 78, 'nama' => 'Horney Coffee', 'harga' => 17000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 79, 'nama' => 'Caramel Latte', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'caramel-latte.jpg'],
        ['id' => 80, 'nama' => 'Vanilla Latte', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'vanilla-latte.jpg'],
        ['id' => 81, 'nama' => 'Butterscotch Coffee', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => false, 'foto' => 'butterscotch-coffee.jpg'],
        ['id' => 82, 'nama' => 'Affogato', 'harga' => 24000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'affogato.jpg'],
        ['id' => 83, 'nama' => 'Kopmil', 'harga' => 15000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'placeholder.png'],
        ['id' => 84, 'nama' => 'Vietnam Drip', 'harga' => 18000, 'kategori' => 'Coffee', 'tersedia' => true, 'foto' => 'vietnam-drip.jpg'],
    ];

    return view('manajemen-menu', ['menuItems' => $menuItems]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';