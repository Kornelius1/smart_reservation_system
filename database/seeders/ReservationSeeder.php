<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
       
        DB::table('reservations')->delete();

       
        $data = [
            [
                'id_transaksi' => 'TRS001',
                'nama' => 'Budi Santoso',
                'nomor_telepon' => '081234567890',
                'jumlah_orang' => 2,
                'tanggal' => '2025-10-17',
                'waktu' => '19:00:00', 
                'status' => false, 
                'nomor_meja' => 1,
                'nomor_ruangan' => null 
            ],
            [
                'id_transaksi' => 'TRS002',
                'nama' => 'Citra Lestari',
                'nomor_telepon' => '08111222333',
                'jumlah_orang' => 5,
                'tanggal' => '2025-11-20',
                'waktu' => '12:00:00',
                'status' => true, 
                'nomor_meja' => null,
                'nomor_ruangan' => 1 
            ],
            [
                'id_transaksi' => 'TRS003',
                'nama' => 'Dewi Anggraini',
                'nomor_telepon' => '085566778899',
                'jumlah_orang' => 4,
                'tanggal' => '2025-11-22',
                'waktu' => '20:00:00',
                'status' => true,
                'nomor_meja' => 3,
                'nomor_ruangan' => null 
            ],
             [
                'id_transaksi' => 'TRS004',
                'nama' => 'Fahira Anindita',
                'nomor_telepon' => '087712341234',
                'jumlah_orang' => 10,
                'tanggal' => '2025-10-13',
                'waktu' => '20:00:00',
                'status' => false,
                'nomor_meja' => null,
                'nomor_ruangan' => 2 
            ],
          
        ];

        foreach ($data as $reservasi) {
            Reservation::create($reservasi);
        }
    }
}