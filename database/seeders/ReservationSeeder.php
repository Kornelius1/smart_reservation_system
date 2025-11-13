<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Product; // <-- 1. TAMBAHKAN IMPORT PRODUCT
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Kosongkan tabel pivot DULU (agar tidak error foreign key)
        DB::table('reservation_product')->delete();
        
        // Kosongkan tabel reservations (dari kode Anda)
        DB::table('reservations')->delete();

        // 3. Ambil data produk untuk di-link
        $products = Product::all();

        // Jika tidak ada produk, hentikan seeder
        if ($products->count() < 3) {
            $this->command->info('Tidak ada data produk. Harap jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        // Siapkan data dummy (dari kode Anda)
        $data = [
            [
                'id_transaksi' => 'TRS001',
                'nama' => 'Budi Santoso',
                'nomor_telepon' => '081234567890',
                'jumlah_orang' => 2,
                'tanggal' => '2025-10-17', // Tanggal di masa lalu (berdasarkan waktu sistem 2025-10-29)
                'waktu' => '19:00:00', 
                'status' => 'Selesai', 
                'nomor_meja' => 1,
                'nomor_ruangan' => null, 
                'payment_token' => null,   
                'expired_at' => null,
                'total_price' => 92000.00 
            ],
            [
                'id_transaksi' => 'TRS002',
                'nama' => 'Citra Lestari',
                'nomor_telepon' => '08111222333',
                'jumlah_orang' => 5,
                'tanggal' => '2025-11-20', // Tanggal di masa depan
                'waktu' => '12:00:00',
                'status' => 'akan datang', // Diperbarui
                'nomor_meja' => null,
                'payment_token' => null,   // <== TAMBAHKAN INI
                'expired_at' => null,
                'nomor_ruangan' => 1,
                'total_price' => 43000.00  
            ],
            [
                'id_transaksi' => 'TRS003',
                'nama' => 'Dewi Anggraini',
                'nomor_telepon' => '085566778899',
                'jumlah_orang' => 4,
                'tanggal' => '2025-11-22', // Tanggal di masa depan
                'waktu' => '20:00:00',
                'status' => 'akan datang', // Diperbarui
                'nomor_meja' => 3,
                'payment_token' => null,   // <== TAMBAHKAN INI
                'expired_at' => null,
                'nomor_ruangan' => null,
                'total_price' => 43000.00 
            ],
            [
                'id_transaksi' => 'TRS004',
                'nama' => 'Fahira Anindita',
                'nomor_telepon' => '087712341234',
                'jumlah_orang' => 10,
                'tanggal' => '2025-10-13', // Tanggal di masa lalu
                'waktu' => '20:00:00',
                'status' => 'Selesai', 
                'nomor_meja' => null,
                'payment_token' => null, 
                'expired_at' => null,
                'nomor_ruangan' => 2,
                'total_price' => 40000.00  
            ],
            [
                'id_transaksi' => 'TRS005',
                'nama' => 'Eka Wijaya',
                'nomor_telepon' => '081987654321',
                'jumlah_orang' => 3,
                'tanggal' => Carbon::today()->toDateString(), // HARI INI
                'waktu' => '14:00:00',
                'status' => 'check-in', // Diperbarui
                'nomor_meja' => 5,
                'payment_token' => null,   
                'expired_at' => null,
                'nomor_ruangan' => null,
                'total_price' => 30000.00
            ],
            [
                'id_transaksi' => 'TRS006',
                'nama' => 'Gilang Pratama',
                'nomor_telepon' => '081222333444',
                'jumlah_orang' => 6,
                'tanggal' => Carbon::tomorrow()->toDateString(), // BESOK
                'waktu' => '18:00:00',
                'status' => 'dibatalkan', // Diperbarui
                'nomor_meja' => 6,
                'payment_token' => null,   
                'expired_at' => null,
                'nomor_ruangan' => null,
                'total_price' => 24000.00  
            ],
            [
                'id_transaksi' => 'TRS007',
                'nama' => 'Hana Yulita',
                'nomor_telepon' => '085211112222',
                'jumlah_orang' => 2,
                'tanggal' => Carbon::yesterday()->toDateString(), // KEMARIN
                'waktu' => '19:00:00',
                'status' => 'Selesai', // Diperbarui (asumsi kemarin selesai)
                'nomor_meja' => 7,
                'payment_token' => null,   
                'expired_at' => null,
                'nomor_ruangan' => null,
                'total_price' => 150000.00
            ],
            [
                'id_transaksi' => 'TRS008',
                'nama' => 'Indra Kusuma',
                'nomor_telepon' => '081311223344',
                'jumlah_orang' => 4,
                'tanggal' => Carbon::today()->toDateString(), // HARI INI
                'waktu' => '10:00:00',
                'status' => 'dibatalkan', // Status 'pending'
                'nomor_meja' => 8,
                'payment_token' => null,   
                'expired_at' => null,
                'nomor_ruangan' => null,
                'total_price' => 45000.00  
            ],
        ];

        // Masukkan data ke database
        foreach ($data as $reservasiData) {
            
            // 4. Buat reservasi DAN tangkap objeknya
            $reservation = Reservation::create(array_merge($reservasiData, [
                'created_at' => Carbon::now()->subMinutes(rand(1, 55)),
                'updated_at' => Carbon::now()->subMinutes(rand(1, 55))
            ]));

            // 5. Lampirkan (attach) 1 s/d 3 produk acak ke reservasi ini
            $productsToAttach = $products->random(rand(1, 3));
            
            foreach ($productsToAttach as $product) {
                $reservation->products()->attach($product->id, [
                    'quantity' => rand(1, 2), // 1 atau 2 porsi
                    'price'    => $product->price // Ambil harga dari produk
                ]);
            }
        }
    }
}
