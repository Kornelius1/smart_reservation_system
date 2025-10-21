<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation; // <--- 1. Gunakan Model
use Illuminate\Support\Facades\DB; // <--- 2. Gunakan DB facade

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat setiap kali seeding
        DB::table('reservations')->delete();

        // Data dummy Anda
        $data = [
            [
                'id_transaksi' => 'TRS001', 
                'nama' => 'Budi', 
                'tanggal' => '2025-10-17', // Tanggal di masa lalu
                'waktu' => '19:00:00'
            ],
            [
                'id_transaksi' => 'TRS002', 
                'nama' => 'Citra', 
                'tanggal' => '2025-11-20', // Tanggal di masa depan
                'waktu' => '12:00:00'
            ],
            [
                'id_transaksi' => 'TRS003', 
                'nama' => 'Dewi', 
                'tanggal' => '2025-11-22', // Tanggal di masa depan
                'waktu' => '20:00:00'
            ],
            [
                'id_transaksi' => 'TRS004', 
                'nama' => 'Fahira', 
                'tanggal' => '2025-10-13', // Tanggal di masa lalu
                'waktu' => '20:00:00'
            ],
        ];

        // Masukkan data ke database
        foreach ($data as $reservasi) {
            Reservation::create($reservasi);
        }
    }
}