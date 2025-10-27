<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product; // <-- PENTING: Gunakan Model Product

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URL gambar default untuk semua produk
        $defaultImageUrl = 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp';
        
        // Stok default untuk menu baru
        $defaultStock = 20; // Anda bisa ubah angka ini

        // Hapus data lama agar tidak ada duplikasi saat seeding ulang
        DB::table('products')->truncate();

        // Data menu utama (tanpa data yang berulang)
        $productsData = [
            // --- SNACKS ---
            ['name' => 'Burger Telur', 'price' => 15000, 'category' => 'snack'],
            ['name' => 'Burger Daging', 'price' => 17000, 'category' => 'snack'],
            ['name' => 'Burger Special', 'price' => 20000, 'category' => 'snack'],
            ['name' => 'Roti Bakar', 'price' => 15000, 'category' => 'snack'],
            ['name' => 'Toast Cream', 'price' => 20000, 'category' => 'snack'],
            ['name' => 'Jamur Krispi', 'price' => 15000, 'category' => 'snack'],
            ['name' => 'Lumpia Baso', 'price' => 15000, 'category' => 'snack'],
            ['name' => 'Tahu Bakso', 'price' => 12000, 'category' => 'snack'],
            ['name' => 'Sosis Bakar', 'price' => 12000, 'category' => 'snack'],
            ['name' => 'Nugget Ayam', 'price' => 12000, 'category' => 'snack'],
            ['name' => 'Ubi Goreng', 'price' => 12000, 'category' => 'snack'],
            ['name' => 'French Fries', 'price' => 12000, 'category' => 'snack'],
            ['name' => 'Pisang Krispi', 'price' => 13000, 'category' => 'snack'],
            ['name' => 'Mix Platter', 'price' => 20000, 'category' => 'snack'],

            // --- HEAVY MEAL ---
            ['name' => 'Nasi Telur Ceplok', 'price' => 13000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Telur Dadar', 'price' => 13000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Ayam Krispi', 'price' => 17000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Ayam Penyet', 'price' => 19000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Ayam Katsu', 'price' => 22000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Goreng Telur', 'price' => 15000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Goreng Ayam Krispi', 'price' => 22000, 'category' => 'heavy-meal'],
            ['name' => 'Nasi Goreng Ayam Katsu', 'price' => 25000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Goreng Reguler', 'price' => 15000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Goreng Special', 'price' => 23000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Rebus Reguler', 'price' => 15000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Rebus Special', 'price' => 22000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Nyemek', 'price' => 15000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Tiaw Goreng', 'price' => 15000, 'category' => 'heavy-meal'],
            ['name' => 'Mie Nas', 'price' => 20000, 'category' => 'heavy-meal'],
            ['name' => 'Spaghetti Reguler', 'price' => 18000, 'category' => 'heavy-meal'],
            ['name' => 'Spaghetti Ayam Katsu', 'price' => 25000, 'category' => 'heavy-meal'],

            // --- TRADITIONAL ---
            ['name' => 'Teh Telang Horney (hot)', 'price' => 13000, 'category' => 'traditional'],
            ['name' => 'Ginger Horney (hot)', 'price' => 13000, 'category' => 'traditional'],
            ['name' => 'Ginger Milk (hot)', 'price' => 13000, 'category' => 'traditional'],
            ['name' => 'Wedang Jahe (hot)', 'price' => 13000, 'category' => 'traditional'],
            ['name' => 'Bandrek Susu (hot)', 'price' => 13000, 'category' => 'traditional'],
            ['name' => 'Bandrek Telor (hot)', 'price' => 17000, 'category' => 'traditional'],
            ['name' => 'Teh Telur (hot/ice)', 'price' => 15000, 'category' => 'traditional'],
            ['name' => 'Teh Tarik (hot/ice)', 'price' => 15000, 'category' => 'traditional'],
            ['name' => 'Kopi Telor (hot)', 'price' => 15000, 'category' => 'traditional'],
            ['name' => 'Kopi Cingcong (hot/ice)', 'price' => 15000, 'category' => 'traditional'],

            // --- FRESH DRINK ---
            ['name' => 'Jasmine Tea (hot/ice)', 'price' => 12000, 'category' => 'fresh-drink'],
            ['name' => 'Lemon Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink'],
            ['name' => 'Horney Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink'],
            ['name' => 'Apple Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink'],
            ['name' => 'Blackcurrant Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink'],
            ['name' => 'Peach Tea (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            ['name' => 'Lychee Tea (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            ['name' => 'Orange Squash (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            ['name' => 'Orange Sky (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            ['name' => 'Blue Blood (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            ['name' => 'Blue Squash (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            ['name' => 'Soda Gembira (ice)', 'price' => 15000, 'category' => 'fresh-drink'],
            
            // --- JUICE ---
            ['name' => 'Alpukat', 'price' => 16000, 'category' => 'juice'],
            ['name' => 'Apel', 'price' => 15000, 'category' => 'juice'],
            ['name' => 'Buah Naga', 'price' => 16000, 'category' => 'juice'],
            ['name' => 'Mangga', 'price' => 16000, 'category' => 'juice'],
            ['name' => 'Jambu Biji', 'price' => 15000, 'category' => 'juice'],
            ['name' => 'Sirsak', 'price' => 15000, 'category' => 'juice'],

            // --- SPECIAL TASTE ---
            ['name' => 'Chocolate Classic (ice)', 'price' => 16000, 'category' => 'special-taste'],
            ['name' => 'Chocolate Frappe (ice)', 'price' => 20000, 'category' => 'special-taste'],
            ['name' => 'Milo (ice)', 'price' => 15000, 'category' => 'special-taste'],
            ['name' => 'Taro (ice)', 'price' => 16000, 'category' => 'special-taste'],
            ['name' => 'Taro Frappe (ice)', 'price' => 20000, 'category' => 'special-taste'],
            ['name' => 'Red Velvet (ice)', 'price' => 16000, 'category' => 'special-taste'],
            ['name' => 'Red Velvet Frappe (ice)', 'price' => 20000, 'category' => 'special-taste'],
            ['name' => 'Charcoal (ice)', 'price' => 16000, 'category' => 'special-taste'],
            ['name' => 'Matcha Greentea (ice)', 'price' => 20000, 'category' => 'special-taste'],
            ['name' => 'Matcha Greentea Frappe', 'price' => 24000, 'category' => 'special-taste'],
            ['name' => 'Cookies and Cream (ice)', 'price' => 20000, 'category' => 'special-taste'],

            // --- ICE CREAM ---
            ['name' => 'Vanilla', 'price' => 15000, 'category' => 'ice-cream'],
            ['name' => 'Chocolate', 'price' => 15000, 'category' => 'ice-cream'],
            ['name' => 'Mix', 'price' => 15000, 'category' => 'ice-cream'],
            
            // --- COFFEE ---
            ['name' => 'Black Coffee (hot/ice)', 'price' => 15000, 'category' => 'coffee'],
            ['name' => 'Chocolate Coffee (ice)', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Brown Sugar Coffee (ice)', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Brown Sugar Pandan (ice)', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Original Milk Coffee (ice)', 'price' => 17000, 'category' => 'coffee'],
            ['name' => 'Horney Coffee (ice)', 'price' => 17000, 'category' => 'coffee'],
            ['name' => 'Caramel Latte (ice)', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Vanilla Latte (ice)', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Butterscotch Coffee (ice)', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Affogato', 'price' => 18000, 'category' => 'coffee'],
            ['name' => 'Kopmil (ice)', 'price' => 15000, 'category' => 'coffee'],
            ['name' => 'Vietnam Drip (hot)', 'price' => 15000, 'category' => 'coffee'],
        ];

        // Loop data dan buat record menggunakan Model
        foreach ($productsData as $data) {
            // Tambahkan data stok, status, dan gambar
            $data['image_url'] = $defaultImageUrl;
            $data['stock']     = $defaultStock;
            $data['tersedia']  = true; // Otomatis true karena stok > 0

            // Eloquent akan otomatis mengisi created_at dan updated_at
            Product::create($data);
        }

        // --- (OPSIONAL) Buat beberapa item stok habis untuk testing ---
        // Anda bisa memilih beberapa nama menu untuk di-set stoknya ke 0
        Product::where('name', 'Burger Telur')->update([
            'stock' => 0,
            'tersedia' => false
        ]);
        
        Product::where('name', 'Nasi Goreng Telur')->update([
            'stock' => 0,
            'tersedia' => false
        ]);

        Product::where('name', 'Jasmine Tea (hot/ice)')->update([
            'stock' => 0,
            'tersedia' => false
        ]);
    }
}