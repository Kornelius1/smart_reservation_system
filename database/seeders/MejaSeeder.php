<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meja; // 1. Pastikan Anda mengimpor model Meja

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar tidak ada duplikat jika seeder dijalankan lagi
        Meja::truncate();
        

        // 2. Tentukan lokasi-lokasi yang ada di denah Anda
        $locations = ['indoor1', 'indoor2', 'out1', 'out2'];

        // 3. Lakukan perulangan untuk setiap lokasi
        foreach ($locations as $location) {
            // 4. Buat 6 meja untuk setiap lokasi
            for ($i = 1; $i <= 24; $i++) {
                Meja::create([
                    'nomor_meja'   => $i,
                    'kapasitas'    => 4, // Asumsi semua meja kapasitasnya 4
                    'lokasi'       => $location,
                    'status_aktif' => true, // Asumsi semua meja awalnya tersedia
                ]);
            }
        }
    }
}