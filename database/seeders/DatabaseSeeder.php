<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gabungkan semua seeder ke dalam satu array.
        // Urutan penting jika ada dependensi antar tabel.
        $this->call([
            // Asumsi: Ruangan (Room) dibuat terlebih dahulu.
            RoomSeeder::class, 
            
            // Asumsi: Meja (Meja) dibuat setelah Room (mungkin meja ada di dalam room).
            MejaSeeder::class, 
            
            // Asumsi: Produk (Product) bisa dibuat kapan saja (independen).
            ProductSeeder::class, 
            
            // Asumsi: Reservasi (Reservation) dibuat terakhir 
            // karena membutuhkan Meja yang sudah ada.
            ReservationSeeder::class, 
        ]);
    }
}