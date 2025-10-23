<?php

namespace Tests\Unit\Http\Controllers\Customer;

use App\Http\Controllers\Customer\DenahMejaController;
use App\Models\Meja; // Import model yang akan di-mock
use Illuminate\Database\Eloquent\Collection; // Untuk membuat collection palsu
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;

class DenahMejaControllerTest extends TestCase
{
    /**
     * Selalu jalankan ini setelah test yang menggunakan Mockery
     */
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Misi: Menguji function index() di DenahMejaController
     */
    public function test_index_function_returns_view_with_grouped_mejas(): void
    {
        // --- TAHAP 1: PERSIAPAN (Arrange) ---

        // 1. "Membajak" Model Meja.
        $mejaMock = Mockery::mock('alias:App\Models\Meja');

        // 2. Buat Data Meja Palsu.
        //    Kita sengaja buat 2 meja di 'Indoor' dan 1 di 'Outdoor'
        //    untuk menguji fungsi groupBy('lokasi').
        $fakeMejas = new Collection([
            (object)['id' => 1, 'nomor_meja' => 'A1', 'lokasi' => 'Indoor'],
            (object)['id' => 2, 'nomor_meja' => 'A2', 'lokasi' => 'Indoor'],
            (object)['id' => 3, 'nomor_meja' => 'B1', 'lokasi' => 'Outdoor'],
        ]);

        // 3. Beri perintah pada "pembajak":
        //    "Nanti, kalau controller memanggil Meja::all(), jangan ke database,
        //     tapi kembalikan saja data $fakeMejas yang sudah saya siapkan."
        $mejaMock->shouldReceive('all')->once()->andReturn($fakeMejas);

        // --- TAHAP 2: AKSI (Act) ---

        // Panggil function index() yang mau kita uji.
        $controller = new DenahMejaController();
        $response = $controller->index();

        // --- TAHAP 3: PEMBUKTIAN (Assert) ---

        // 1. Pastikan hasilnya adalah sebuah View (halaman web).
        $this->assertInstanceOf(View::class, $response);

        // 2. Pastikan nama view-nya benar.
        $this->assertEquals('customer.DenahMeja', $response->name());

        // 3. "Intip" data yang dikirim ke view.
        $viewData = $response->getData();

        // 4. Pastikan ada data dengan key 'mejasByLocation'.
        $this->assertArrayHasKey('mejasByLocation', $viewData);

        // 5. Pastikan ada data dengan key 'minimumOrder'.
        $this->assertArrayHasKey('minimumOrder', $viewData);

        // 6. Pastikan nilai minimumOrder adalah 50000.
        $this->assertEquals(50000, $viewData['minimumOrder']);

        // 7. (PENTING) Cek apakah data mejanya sudah dikelompokkan dengan benar.
        $groupedMejas = $viewData['mejasByLocation'];
        $this->assertCount(2, $groupedMejas['Indoor']); // Harus ada 2 meja di grup 'Indoor'
        $this->assertCount(1, $groupedMejas['Outdoor']); // Harus ada 1 meja di grup 'Outdoor'
    }
}
