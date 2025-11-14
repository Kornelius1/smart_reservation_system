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
        // URL gambar default untuk produk TANPA gambar spesifik
        $defaultImageUrl = 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp';
        
        // Stok default untuk menu baru
        $defaultStock = 20;

        // Hapus data lama agar tidak ada duplikasi saat seeding ulang
        DB::table('products')->truncate();

        // Data menu utama (dengan path image_url .webp yang sudah diperbaiki ke 'images/menus/')
        $productsData = [
            // --- SNACKS ---
            ['name' => 'Burger Telur', 'price' => 15000, 'category' => 'snack', 'image_url' => 'images/menus/burger.webp'],
            ['name' => 'Burger Daging', 'price' => 17000, 'category' => 'snack', 'image_url' => 'images/menus/burger_daging.webp'],
            ['name' => 'Burger Special', 'price' => 20000, 'category' => 'snack', 'image_url' => 'images/menus/burger_spesial.webp'],
            ['name' => 'Roti Bakar', 'price' => 15000, 'category' => 'snack', 'image_url' => 'images/menus/roti_bakar.webp'],
            ['name' => 'Toast Cream', 'price' => 20000, 'category' => 'snack', 'image_url' => 'images/menus/toast_cream.webp'],
            ['name' => 'Jamur Krispi', 'price' => 15000, 'category' => 'snack', 'image_url' => 'images/menus/jamur_crispi.webp'],
            ['name' => 'Lumpia Baso', 'price' => 15000, 'category' => 'snack', 'image_url' => 'images/menus/lumpia_bakso.webp'],
            ['name' => 'Tahu Bakso', 'price' => 12000, 'category' => 'snack', 'image_url' => 'images/menus/tahu_bakso.webp'],
            ['name' => 'Sosis Bakar', 'price' => 12000, 'category' => 'snack', 'image_url' => 'images/menus/sosis_bakar.webp'],
            ['name' => 'Nugget Ayam', 'price' => 12000, 'category' => 'snack', 'image_url' => 'images/menus/nugget_ayam.webp'],
            ['name' => 'Ubi Goreng', 'price' => 12000, 'category' => 'snack', 'image_url' => 'images/menus/ubi_goreng.webp'],
            ['name' => 'French Fries', 'price' => 12000, 'category' => 'snack', 'image_url' => 'images/menus/kentang_goreng.webp'],
            ['name' => 'Pisang Krispi', 'price' => 13000, 'category' => 'snack', 'image_url' => 'images/menus/pisang_goreng.webp'],
            ['name' => 'Mix Platter', 'price' => 20000, 'category' => 'snack', 'image_url' => 'images/menus/mix_platter.webp'], // <-- Akan pakai default

            // --- HEAVY MEAL ---
            ['name' => 'Nasi Telur Ceplok', 'price' => 13000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_telur.webp'],
            ['name' => 'Nasi Telur Dadar', 'price' => 13000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_telur_dadar.webp'],
            ['name' => 'Nasi Ayam Krispi', 'price' => 17000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_ayam_krispi.webp'],
            ['name' => 'Nasi Ayam Penyet', 'price' => 19000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/ayam_penyet.webp'],
            ['name' => 'Nasi Ayam Katsu', 'price' => 22000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_ayam_katsu.webp'],
            ['name' => 'Nasi Goreng Telur', 'price' => 15000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_goreng.webp'],
            ['name' => 'Nasi Goreng Ayam Krispi', 'price' => 22000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_goreng_ayam_crispi.webp'],
            ['name' => 'Nasi Goreng Ayam Katsu', 'price' => 25000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/nasi_goreng_ayam_katsu.webp'],
            ['name' => 'Mie Goreng Reguler', 'price' => 15000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/mie_goreng.webp'],
            ['name' => 'Mie Goreng Special', 'price' => 23000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/mie_goreng_spesial.webp'],
            ['name' => 'Mie Rebus Reguler', 'price' => 15000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/mie_rebus.webp'],
            ['name' => 'Mie Rebus Special', 'price' => 22000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/mie_rebus_spesial.webp'],
            ['name' => 'Mie Nyemek', 'price' => 15000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/mie_nyemek.webp'],
            ['name' => 'Mie Tiaw Goreng', 'price' => 15000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/mie_tiaw.webp'],
            ['name' => 'Mie Nas', 'price' => 20000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/minas.webp'],
            ['name' => 'Spaghetti Reguler', 'price' => 18000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/spaghetti.webp'],
            ['name' => 'Spaghetti Ayam Katsu', 'price' => 25000, 'category' => 'heavy-meal', 'image_url' => 'images/menus/spaghetti_katsu.webp'],

            // --- TRADITIONAL ---
            ['name' => 'Teh Telang Horney (hot)', 'price' => 13000, 'category' => 'traditional', 'image_url' => 'images/menus/teh_telang.webp'],
            ['name' => 'Ginger Horney (hot)', 'price' => 13000, 'category' => 'traditional', 'image_url' => 'images/menus/ginger.webp'],
            ['name' => 'Ginger Milk (hot)', 'price' => 13000, 'category' => 'traditional', 'image_url' => 'images/menus/ginger_milk.webp'],
            ['name' => 'Wedang Jahe (hot)', 'price' => 13000, 'category' => 'traditional', 'image_url' => 'images/menus/wedang_jahe.webp'],
            ['name' => 'Bandrek Susu (hot)', 'price' => 13000, 'category' => 'traditional', 'image_url' => 'images/menus/bandrek_susu.webp'],
            ['name' => 'Bandrek Telor (hot)', 'price' => 17000, 'category' => 'traditional', 'image_url' => 'images/menus/bandrek_telor.webp'], 
            ['name' => 'Teh Telur (hot/ice)', 'price' => 15000, 'category' => 'traditional', 'image_url' => 'images/menus/teh_telur.webp'],
            ['name' => 'Teh Tarik (hot/ice)', 'price' => 15000, 'category' => 'traditional', 'image_url' => 'images/menus/teh_tarik.webp'],
            ['name' => 'Kopi Telor (hot)', 'price' => 15000, 'category' => 'traditional', 'image_url' => 'images/menus/kopi_telul.webp'],
            ['name' => 'Kopi Cingcong (hot/ice)', 'price' => 15000, 'category' => 'traditional', 'image_url' => 'images/menus/kopi_cingcong.webp'], // <-- Akan pakai default

            // --- FRESH DRINK ---
            ['name' => 'Jasmine Tea (hot/ice)', 'price' => 12000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/jasmine_tea.webp'],
            ['name' => 'Lemon Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/lemon_tea.webp'],
            ['name' => 'Horney Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/homey_tea.webp'],
            ['name' => 'Apple Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/apple_tea.webp'],
            ['name' => 'Blackcurrant Tea (ice)', 'price' => 13000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/blackcurrant_tea.webp'],
            ['name' => 'Peach Tea (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/peach_tea_ice.webp'],
            ['name' => 'Lychee Tea (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/lychee_tea_ice.webp'],
            ['name' => 'Orange Squash (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/orange_squash_ice.webp'],
            ['name' => 'Orange Sky (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/orange_sky_ice.webp'],
            ['name' => 'Blue Blood (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/blue_blood_ice.webp'],
            ['name' => 'Blue Squash (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/blue_squash_ice.webp'],
            ['name' => 'Soda Gembira (ice)', 'price' => 15000, 'category' => 'fresh-drink', 'image_url' => 'images/menus/soda_gembira_ice.webp'],
            
            // --- JUICE ---
            ['name' => 'Alpukat', 'price' => 16000, 'category' => 'juice', 'image_url' => 'images/menus/jus_pokat.webp'],
            ['name' => 'Apel', 'price' => 15000, 'category' => 'juice', 'image_url' => 'images/menus/jus_apel.webp'],
            ['name' => 'Buah Naga', 'price' => 16000, 'category' => 'juice', 'image_url' => 'images/menus/jus_buah_naga.webp'],
            ['name' => 'Mangga', 'price' => 16000, 'category' => 'juice', 'image_url' => 'images/menus/jus_mangga.webp'],
            ['name' => 'Jambu Biji', 'price' => 15000, 'category' => 'juice', 'image_url' => 'images/menus/jus_jambu_biji.webp'],
            ['name' => 'Sirsak', 'price' => 15000, 'category' => 'juice', 'image_url' => 'images/menus/jus_sirsak.webp'],

            // --- SPECIAL TASTE ---
            ['name' => 'Chocolate Classic (ice)', 'price' => 16000, 'category' => 'special-taste', 'image_url' => 'images/menus/chocolate_classic_ice.webp'],
            ['name' => 'Chocolate Frappe (ice)', 'price' => 20000, 'category' => 'special-taste', 'image_url' => 'images/menus/chocolate_frappe_ice.webp'],
            ['name' => 'Milo (ice)', 'price' => 15000, 'category' => 'special-taste', 'image_url' => 'images/menus/milo_ice.webp'],
            ['name' => 'Taro (ice)', 'price' => 16000, 'category' => 'special-taste', 'image_url' => 'images/menus/taro_ice.webp'],
            ['name' => 'Taro Frappe (ice)', 'price' => 20000, 'category' => 'special-taste', 'image_url' => 'images/menus/taro_frappe_ice.webp'],
            ['name' => 'Red Velvet (ice)', 'price' => 16000, 'category' => 'special-taste', 'image_url' => 'images/menus/red_velvet_ice.webp'],
            ['name' => 'Red Velvet Frappe (ice)', 'price' => 20000, 'category' => 'special-taste', 'image_url' => 'images/menus/red_velvet_frappe_ice.webp'],
            ['name' => 'Charcoal (ice)', 'price' => 16000, 'category' => 'special-taste', 'image_url' => 'images/menus/charcoal_ice.webp'],
            ['name' => 'Matcha Greentea (ice)', 'price' => 20000, 'category' => 'special-taste', 'image_url' => 'images/menus/matcha_greentea_ice.webp'],
            ['name' => 'Matcha Greentea Frappe', 'price' => 24000, 'category' => 'special-taste', 'image_url' => 'images/menus/matcha_greentea_frappe_ice.webp'],
            ['name' => 'Cookies and Cream (ice)', 'price' => 20000, 'category' => 'special-taste', 'image_url' => 'images/menus/cookies_and_cream_ice.webp'],

            // --- ICE CREAM ---
            ['name' => 'Vanilla', 'price' => 15000, 'category' => 'ice-cream', 'image_url' => 'images/menus/vanilla_ice_cream.webp'],
            ['name' => 'Chocolate', 'price' => 15000, 'category' => 'ice-cream', 'image_url' => 'images/menus/ice_cream_chocolate.webp'],
            ['name' => 'Mix', 'price' => 15000, 'category' => 'ice-cream', 'image_url' => 'images/menus/ice_cream_mix.webp'],
            
            // --- COFFEE ---
            ['name' => 'Black Coffee (hot/ice)', 'price' => 10000, 'category' => 'coffee', 'image_url' => 'images/menus/black_coffee_hot_ice.webp'],
            ['name' => 'Chocolate Coffee (ice)', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/chocolate_coffee_ice.webp'],
            ['name' => 'Brown Sugar Coffee (ice)', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/brown_sugar_coffee_ice.webp'],
            ['name' => 'Brown Sugar Pandan (ice)', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/brown_sugar_pandan.webp'],
            ['name' => 'Original Milk Coffee (ice)', 'price' => 17000, 'category' => 'coffee', 'image_url' => 'images/menus/original_milk_coffee_ice.webp'],
            ['name' => 'Horney Coffee (ice)', 'price' => 17000, 'category' => 'coffee', 'image_url' => 'images/menus/homey_coffee_ice.webp'],
            ['name' => 'Caramel Latte (ice)', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/caramel_latte_ice.webp'],
            ['name' => 'Vanilla Latte (ice)', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/vanilla_latte_ice.webp'],
            ['name' => 'Butterscotch Coffee (ice)', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/butterscotch_coffee_ice.webp'],
            ['name' => 'Affogato', 'price' => 18000, 'category' => 'coffee', 'image_url' => 'images/menus/affogato.webp'],
            ['name' => 'Kopmil (ice)', 'price' => 15000, 'category' => 'coffee', 'image_url' => 'images/menus/kopmil_ice.webp'],
            ['name' => 'Vietnam Drip (hot)', 'price' => 10000, 'category' => 'coffee', 'image_url' => 'images/menus/vietnam_drip_hot.webp'],
        ];

        // Loop data dan buat record menggunakan Model
        foreach ($productsData as $data) {
            // Setel gambar default HANYA JIKA 'image_url' belum ada di array asli
            $data['image_url'] = $data['image_url'] ?? $defaultImageUrl;
            
            // Tambahkan data stok dan ketersediaan
            $data['stock']     = $defaultStock;
            $data['tersedia']  = true; // Otomatis true karena stok > 0

            // Eloquent akan otomatis mengisi created_at dan updated_at
            Product::create($data);
        }

        // --- (OPSIONAL) Buat beberapa item stok habis untuk testing ---
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
