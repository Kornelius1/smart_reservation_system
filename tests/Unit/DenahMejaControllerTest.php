<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Customer\DenahMejaController;
use App\Models\Meja; // Pastikan model Meja diimpor
use Illuminate\Support\Collection;
use Mockery; // Diperlukan untuk mocking Meja::all()

/**
 * Class DenahMejaControllerTest
 * Menguji logika unit dari DenahMejaController.
 */
class DenahMejaControllerTest extends TestCase
{
    // Konstan yang sama dengan yang ada di controller
    private const MINIMUM_ORDER_FOR_TABLE = 50000;

    /**
     * Pastikan metode index mengembalikan view yang benar dengan data yang benar.
     * @return void
     */
    public function test_index_returns_correct_view_with_data()
    {
        // 1. Persiapan Data Palsu (Mocking)
        
        // Buat data meja tiruan (mock data)
        // Gunakan array of objects atau Collection of objects
        $mejasData = collect([
            (object)['id' => 1, 'nama' => 'Meja A1', 'lokasi' => 'Lantai 1'],
            (object)['id' => 2, 'nama' => 'Meja B1', 'lokasi' => 'Lantai 2'],
            (object)['id' => 3, 'nama' => 'Meja A2', 'lokasi' => 'Lantai 1'],
            (object)['id' => 4, 'nama' => 'Meja C1', 'lokasi' => 'Lantai 3'],
        ]);
        
        // Menggantikan (Mock) pemanggilan Meja::all()
        $mockMeja = Mockery::mock('alias:'.Meja::class);
        $mockMeja->shouldReceive('all')
                 ->once() // Pastikan hanya dipanggil 1 kali
                 ->andReturn($mejasData); // Kembalikan koleksi data tiruan

        // 2. Eksekusi
        $controller = new DenahMejaController();
        $response = $controller->index();

        // 3. Verifikasi (Assertions)

        // A. Pastikan view yang benar dipanggil
        // PERBAIKAN: Mengganti $response->getName() menjadi $response->name()
        $this->assertEquals('customer.DenahMeja', $response->name(), 'Memastikan controller memanggil view yang benar.');

        // B. Ambil data yang dilewatkan ke view
        $viewData = $response->getData();
        
        // C. Assert (Pastikan) 'minimumOrder' ada dan nilainya benar
        $this->assertArrayHasKey('minimumOrder', $viewData, 'Memastikan data "minimumOrder" tersedia.');
        $this->assertEquals(self::MINIMUM_ORDER_FOR_TABLE, $viewData['minimumOrder'], 'Memastikan nilai minimum order sesuai konstanta.');

        // D. Assert (Pastikan) 'mejasByLocation' ada dan dikelompokkan dengan benar
        $this->assertArrayHasKey('mejasByLocation', $viewData, 'Memastikan data "mejasByLocation" tersedia.');
        $this->assertInstanceOf(Collection::class, $viewData['mejasByLocation'], 'Memastikan data meja adalah instance dari Collection.');
        
        // E. Verifikasi hasil pengelompokan
        $mejasByLocation = $viewData['mejasByLocation'];
        
        $this->assertArrayHasKey('Lantai 1', $mejasByLocation->toArray(), 'Memastikan ada grup Lantai 1.');
        $this->assertCount(2, $mejasByLocation['Lantai 1'], 'Memastikan ada 2 meja di Lantai 1.');
        
        $this->assertArrayHasKey('Lantai 2', $mejasByLocation->toArray(), 'Memastikan ada grup Lantai 2.');
        $this->assertCount(1, $mejasByLocation['Lantai 2'], 'Memastikan ada 1 meja di Lantai 2.');
        
        $this->assertArrayHasKey('Lantai 3', $mejasByLocation->toArray(), 'Memastikan ada grup Lantai 3.');
        $this->assertCount(1, $mejasByLocation['Lantai 3'], 'Memastikan ada 1 meja di Lantai 3.');
        
        $this->assertCount(3, $mejasByLocation, 'Memastikan total ada 3 kelompok lokasi.');
    }

    /**
     * Membersihkan Mockery setelah setiap test (penting untuk alias mocking).
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}