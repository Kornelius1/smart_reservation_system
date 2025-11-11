<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- 1. Import DB
use Illuminate\Support\Facades\Schema; // <-- 1. Import Schema

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================================
        // PERBAIKAN: Tambahkan truncate (pembersihan) data
        // ==========================================================
        
        // 1. Nonaktifkan foreign key checks (khusus PostgreSQL)
        // Untuk MySQL, gunakan: DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::disableForeignKeyConstraints();

        // 2. Truncate tabel dalam URUTAN TERBALIK dari migrasi
        // (Anak -> Induk)
        // DB::table('reservation_product')->truncate(); // Anak dari Reservation & Product
        // DB::table('reservations')->truncate();      // Anak dari Room & Meja (secara logika)
        // DB::table('products')->truncate();          // Induk
        // DB::table('meja')->truncate();             // Induk
        // DB::table('rooms')->truncate();             // Induk
        // DB::table('users')->truncate();             // Induk (jika ada relasi)
        // ... (tabel lain jika ada)

        // 3. Aktifkan kembali foreign key checks
        // Untuk MySQL, gunakan: DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Schema::enableForeignKeyConstraints();

        // ==========================================================
        
        // 4. Panggil Seeder dalam URUTAN YANG BENAR (Induk -> Anak)
        $this->call([
            UserSeeder::class,      
            RoomSeeder::class, 
            MejaSeeder::class, 
            ProductImageSeeder::class,
            RoomImageSeeder::class,
            ProductSeeder::class, 
            ReservationSeeder::class, 
        ]);
    }
}
