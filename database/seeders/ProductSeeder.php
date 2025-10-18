<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URL gambar default untuk semua produk
        $defaultImageUrl = 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp';

        // Hapus data lama agar tidak ada duplikasi saat seeding ulang
        DB::table('products')->truncate();

        DB::table('products')->insert([
            // --- SNACKS ---
            ['name' => 'Burger Telur', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Burger Daging', 'price' => 17000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Burger Special', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Roti Bakar', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Toast Cream', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Jamur Krispi', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Lumpia Baso', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Tahu Bakso', 'price' => 12000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Sosis Bakar', 'price' => 12000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Nugget Ayam', 'price' => 12000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Ubi Goreng', 'price' => 12000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'French Fries', 'price' => 12000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Pisang Krispi', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],
            ['name' => 'Mix Platter', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'snack'],

            // --- HEAVY MEAL ---
            ['name' => 'Nasi Telur Ceplok', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Telur Dadar', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Ayam Krispi', 'price' => 17000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Ayam Penyet', 'price' => 19000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Ayam Katsu', 'price' => 22000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Goreng Telur', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Goreng Ayam Krispi', 'price' => 22000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Goreng Ayam Katsu', 'price' => 25000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Goreng Reguler', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Goreng Special', 'price' => 23000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Rebus Reguler', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Rebus Special', 'price' => 22000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Nyemek', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Tiaw Goreng', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Mie Nas', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Spaghetti Reguler', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],
            ['name' => 'Spaghetti Ayam Katsu', 'price' => 25000, 'image_url' => $defaultImageUrl, 'category' => 'heavy-meal'],

            // --- TRADITIONAL ---
            ['name' => 'Teh Telang Horney (hot)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Ginger Horney (hot)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Ginger Milk (hot)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Wedang Jahe (hot)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Bandrek Susu (hot)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Bandrek Telor (hot)', 'price' => 17000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Teh Telur (hot/ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Teh Tarik (hot/ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Kopi Telor (hot)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],
            ['name' => 'Kopi Cingcong (hot/ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'traditional'],

            // --- FRESH DRINK ---
            ['name' => 'Jasmine Tea (hot/ice)', 'price' => 12000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Lemon Tea (ice)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Horney Tea (ice)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Apple Tea (ice)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Blackcurrant Tea (ice)', 'price' => 13000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Peach Tea (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Lychee Tea (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Orange Squash (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Orange Sky (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Blue Blood (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Blue Squash (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            ['name' => 'Soda Gembira (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'fresh-drink'],
            
            // --- JUICE ---
            ['name' => 'Alpukat', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'juice'],
            ['name' => 'Apel', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'juice'],
            ['name' => 'Buah Naga', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'juice'],
            ['name' => 'Mangga', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'juice'],
            ['name' => 'Jambu Biji', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'juice'],
            ['name' => 'Sirsak', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'juice'],

            // --- SPECIAL TASTE ---
            ['name' => 'Chocolate Classic (ice)', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Chocolate Frappe (ice)', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Milo (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Taro (ice)', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Taro Frappe (ice)', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Red Velvet (ice)', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Red Velvet Frappe (ice)', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Charcoal (ice)', 'price' => 16000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Matcha Greentea (ice)', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Matcha Greentea Frappe', 'price' => 24000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],
            ['name' => 'Cookies and Cream (ice)', 'price' => 20000, 'image_url' => $defaultImageUrl, 'category' => 'special-taste'],

            // --- ICE CREAM ---
            ['name' => 'Vanilla', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'ice-cream'],
            ['name' => 'Chocolate', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'ice-cream'],
            ['name' => 'Mix', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'ice-cream'],
            
            // --- COFFEE ---
            ['name' => 'Black Coffee (hot/ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Chocolate Coffee (ice)', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Brown Sugar Coffee (ice)', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Brown Sugar Pandan (ice)', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Original Milk Coffee (ice)', 'price' => 17000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Horney Coffee (ice)', 'price' => 17000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Caramel Latte (ice)', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Vanilla Latte (ice)', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Butterscotch Coffee (ice)', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Affogato', 'price' => 18000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Kopmil (ice)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
            ['name' => 'Vietnam Drip (hot)', 'price' => 15000, 'image_url' => $defaultImageUrl, 'category' => 'coffee'],
        ]);
    }
}