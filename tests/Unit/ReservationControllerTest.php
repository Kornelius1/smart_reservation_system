<?php

namespace Tests\Unit;

use App\Http\Controllers\ReservationController;
use App\Models\Reservation; // Penting untuk alias mock
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Collection; // 'all()' mengembalikan Collection

/**
 * Class ReservationControllerTest
 * Menguji logika unit dari ReservationController.
 *
 * @package Tests\Unit
 */
class ReservationControllerTest extends TestCase
{
    /**
     * Membersihkan Mockery setelah setiap test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Memastikan metode index mengembalikan view yang benar dengan data reservasi
     * yang diambil dari model.
     *
     * @return void
     */
    public function test_index_returns_correct_view_with_reservations_data()
    {
        // 1. Persiapan (Setup)

        // 1a. Buat data palsu yang ingin kita kembalikan.
        // 'all()' mengembalikan sebuah Collection, jadi kita buat Collection palsu.
        $mockReservations = new Collection([
            (object)['id_reservasi' => 'RSVM001', 'nama_customer' => 'Sylya (Mocked)'],
            (object)['id_reservasi' => 'RSVM002', 'nama_customer' => 'Gulum (Mocked)']
        ]);

        // 1b. Buat mock untuk Model Reservation menggunakan 'alias'.
        // Ini akan mencegat panggilan static 'Reservation::all()'.
        $mockReservationModel = Mockery::mock('alias:App\Models\Reservation');

        // 1c. Tentukan ekspektasi:
        // Saat 'all()' dipanggil, kembalikan data palsu kita ($mockReservations).
        $mockReservationModel->shouldReceive('all')
                             ->once() // Harapkan dipanggil 1x
                             ->andReturn($mockReservations); // Kembalikan data palsu kita

        // 1d. Buat instance controller-nya
        $controller = new ReservationController();

        // 2. Eksekusi (Act)
        // Panggil method index()
        // Di dalam method ini, 'Reservation::all()' akan dipanggil,
        // tetapi dicegat oleh Mockery.
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

        // 3e. Pastikan data 'reservations' adalah data palsu yang kita siapkan
        $this->assertSame($mockReservations, $data['reservations']);

        // 3f. (Opsional) Pastikan jumlahnya sesuai data mock
        $this->assertCount(2, $data['reservations']);
    }
}

