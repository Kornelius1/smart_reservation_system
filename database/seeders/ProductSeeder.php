<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    DB::table('products')->insert([
        ['id' => 1, 'name' => 'Espresso', 'jenis' => 'Coffee', 'price' => 18000],
        ['id' => 2, 'name' => 'Latte', 'jenis' => 'Coffee', 'price' => 22000],
        // ... Lanjutkan untuk semua produk lainnya ...
        ['id' => 10, 'name' => 'French Fries', 'jenis' => 'Snack', 'price' => 18000],
        // ...
        ['id' => 20, 'name' => 'Nasi Goreng', 'jenis' => 'Heavy Meal', 'price' => 35000],
        // ...
    ]);
    }
}
