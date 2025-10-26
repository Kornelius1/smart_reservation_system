<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- Tambahkan ini untuk mengelola tanggal

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum diisi
        DB::table('reservations')->delete();

        // Siapkan data dummy
        $data = [
            [
                'id_transaksi' => 'TRS001',
                'nama' => 'Budi Santoso',
                'nomor_telepon' => '081234567890',
                'jumlah_orang' => 2,
                'tanggal' => '2025-10-17', // Tanggal di masa lalu
                'waktu' => '19:00:00', 
                'status' => 'Selesai', // <-- Diubah dari false
                'nomor_meja' => 1,
                'nomor_ruangan' => null 
            ],
            [
                'id_transaksi' => 'TRS002',
                'nama' => 'Citra Lestari',
                'nomor_telepon' => '08111222333',
                'jumlah_orang' => 5,
                'tanggal' => '2025-11-20', // Tanggal di masa depan
                'waktu' => '12:00:00',
                'status' => 'Akan Datang', // <-- Diubah dari true
                'nomor_meja' => null,
                'nomor_ruangan' => 1 
            ],
            [
                'id_transaksi' => 'TRS003',
                'nama' => 'Dewi Anggraini',
                'nomor_telepon' => '085566778899',
                'jumlah_orang' => 4,
                'tanggal' => '2025-11-22', // Tanggal di masa depan
                'waktu' => '20:00:00',
                'status' => 'Akan Datang', // <-- Diubah dari true
                'nomor_meja' => 3,
                'nomor_ruangan' => null 
            ],
            [
                'id_transaksi' => 'TRS004',
                'nama' => 'Fahira Anindita',
                'nomor_telepon' => '087712341234',
                'jumlah_orang' => 10,
                'tanggal' => '2025-10-13', // Tanggal di masa lalu
                'waktu' => '20:00:00',
                'status' => 'Selesai', // <-- Diubah dari false
                'nomor_meja' => null,
                'nomor_ruangan' => 2 
            ],

            // --- DATA BARU UNTUK CONTOH WORKFLOW ---
            
            [
                'id_transaksi' => 'TRS005',
                'nama' => 'Eka Wijaya',
                'nomor_telepon' => '081987654321',
                'jumlah_orang' => 3,
                'tanggal' => Carbon::today()->toDateString(), // Reservasi untuk HARI INI
                'waktu' => '14:00:00',
                'status' => 'Berlangsung', // <-- Status baru
                'nomor_meja' => 5,
                'nomor_ruangan' => null 
            ],
            [
                'id_transaksi' => 'TRS006',
                'nama' => 'Gilang Pratama',
                'nomor_telepon' => '081222333444',
                'jumlah_orang' => 6,
                'tanggal' => Carbon::tomorrow()->toDateString(), // Reservasi untuk BESOK
                'waktu' => '18:00:00',
                'status' => 'Dibatalkan', // <-- Status baru
                'nomor_meja' => 6,
                'nomor_ruangan' => null 
            ],
            [
                'id_transaksi' => 'TRS007',
                'nama' => 'Hana Yulita',
                'nomor_telepon' => '085211112222',
                'jumlah_orang' => 2,
                'tanggal' => Carbon::yesterday()->toDateString(), // Reservasi KEMARIN
                'waktu' => '19:00:00',
                'status' => 'Tidak Datang', // <-- Status baru
                'nomor_meja' => 7,
                'nomor_ruangan' => null 
            ],
        ];

        // Masukkan data ke database
        // Dibungkus dengan Carbon agar created_at dan updated_at nya berbeda-beda sedikit
        foreach ($data as $reservasi) {
            Reservation::create(array_merge($reservasi, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]));
        }
    }
}