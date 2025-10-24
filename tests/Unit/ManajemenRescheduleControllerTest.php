<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ManajemenRescheduleController;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;
use PHPUnit\Framework\Attributes\Test;

class ManajemenRescheduleControllerTest extends TestCase
{
    use RefreshDatabase; // Menggunakan database testing

    private $controller;

    public function setUp(): void
    {
        parent::setUp();
        // Buat instance controller-nya
        $this->controller = new ManajemenRescheduleController();
        // Izinkan mass assignment selama tes
        Reservation::unguard();
    }

    public function tearDown(): void
    {
        // Jaga-jaga, kembalikan perlindungan model
        Reservation::reguard();
        parent::tearDown();
    }

    /**
     * Tes ini memverifikasi metode index()
     * 1. Mengembalikan view yang benar
     * 2. Mengirim data SEMUA reservasi
     * 3. Data terurut berdasarkan tanggal (DESC)
     */
    #[Test]
    public function test_index_returns_view_with_all_reservations_ordered_by_date_desc()
    {
        // ARRANGE
        // Buat 3 reservasi dengan urutan tanggal acak
        $resA_palingBaru = Reservation::create([
            'id_transaksi' => 'TR001',
            'nama' => 'Budi',
            'tanggal' => '2025-10-25', // Paling baru
            'waktu' => '10:00:00',
            'jumlah_orang' => 2
        ]);

        $resB_palingLama = Reservation::create([
            'id_transaksi' => 'TR002',
            'nama' => 'Ani',
            'tanggal' => '2025-10-23', // Paling lama
            'waktu' => '12:00:00',
            'jumlah_orang' => 4
        ]);
        
        $resC_diTengah = Reservation::create([
            'id_transaksi' => 'TR003',
            'nama' => 'Citra',
            'tanggal' => '2025-10-24', // Di tengah
            'waktu' => '14:00:00',
            'jumlah_orang' => 1
        ]);

        // ACT
        // Panggil metode index
        $response = $this->controller->index();

        // ASSERT
        // 1. Pastikan respons adalah objek View
        $this->assertInstanceOf(View::class, $response);

        // 2. Pastikan nama view-nya benar
        $this->assertEquals('admin.ManajemenReschedule', $response->getName());

        // 3. Ambil data yang dikirim ke view
        $viewData = $response->getData();

        // 4. Pastikan ada key 'reservations' di dalam data view
        $this->assertArrayHasKey('reservations', $viewData);

        // 5. Pastikan data 'reservations' adalah sebuah Collection
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $viewData['reservations']);

        // 6. Pastikan SEMUA (3) data reservasi terambil
        $this->assertCount(3, $viewData['reservations']);

        // 7. Pastikan data terurut dengan benar (DESC)
        // Urutan yang diharapkan: TR001 (25-Okt), TR003 (24-Okt), TR002 (23-Okt)
        $this->assertEquals('TR001', $viewData['reservations']->first()->id_transaksi);
        $this->assertEquals('TR003', $viewData['reservations'][1]->id_transaksi);
        $this->assertEquals('TR002', $viewData['reservations']->last()->id_transaksi);
    }
}