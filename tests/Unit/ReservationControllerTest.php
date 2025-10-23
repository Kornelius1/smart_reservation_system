<?php

namespace Tests\Unit;

use App\Http\Controllers\ReservationController;
use Tests\TestCase;

/**
 * Class ReservationControllerTest
 * Menguji logika unit dari ReservationController.
 *
 * @package Tests\Unit
 */
class ReservationControllerTest extends TestCase
{
    /**
     * Memastikan metode index mengembalikan view yang benar dengan data reservasi.
     *
     * @return void
     */
    public function test_index_returns_correct_view_with_reservations_data()
    {
        // 1. Persiapan
        // Buat instance controller-nya secara langsung.
        // Tidak perlu Mockery karena tidak ada dependensi yang di-inject.
        $controller = new ReservationController();

        // 2. Eksekusi
        // Panggil method index()
        $response = $controller->index();

        // 3. Verifikasi (Assert)

        // 3a. Pastikan response-nya adalah objek View
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        // 3b. Pastikan nama view-nya sudah benar
        $this->assertEquals('admin.manajemen-reservasi', $response->name());

        // 3c. Ambil data yang dikirim ke view
        $data = $response->getData();

        // 3d. Pastikan ada key 'reservations' di dalam data
        $this->assertArrayHasKey('reservations', $data);

        // 3e. Pastikan data 'reservations' adalah sebuah array
        $this->assertIsArray($data['reservations']);

        // 3f. Pastikan jumlah data reservasi-nya benar (ada 9 di controller Anda)
        $this->assertCount(9, $data['reservations']);

        // 3g. (Opsional) Cek data pertama untuk memastikan datanya tidak salah
        $expectedFirstReservation = [
            'id_reservasi' => 'RSVM001',
            'id_transaksi' => 'TR5001',
            'nomor_meja' => 1,
            'nomor_ruangan' => null,
            'nama_customer' => 'Sylya',
            'nomor_telepon' => '0812 xxx xxx',
            'jumlah_orang' => 4,
            'tanggal' => '17/01/24',
            'waktu_reservasi' => '11.00 WIB',
            'status' => false
        ];
        $this->assertEquals($expectedFirstReservation, $data['reservations'][0]);
    }
}
