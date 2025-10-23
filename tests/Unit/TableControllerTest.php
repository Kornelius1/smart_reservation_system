<?php

namespace Tests\Unit;

use App\Http\Controllers\TableController;
use Tests\TestCase;
// Karena controller ini sangat sederhana dan tidak memiliki
// dependensi (seperti Request, Auth, Model), kita tidak
// perlu menggunakan Mockery.

/**
 * Class TableControllerTest
 * Menguji logika unit dari TableController.
 */
class TableControllerTest extends TestCase
{
    /**
     * Memastikan metode index mengembalikan view yang benar dengan data meja.
     *
     * @return void
     */
    public function test_index_returns_correct_view_with_tables_data()
    {
        // 1. Persiapan
        // Kita bisa langsung membuat instance controller karena tidak ada
        // dependensi yang perlu di-mock di constructor.
        $controller = new TableController();

        // 2. Eksekusi
        // Panggil metode index()
        // Ini akan mengembalikan objek Illuminate\View\View
        $response = $controller->index();

        // 3. Verifikasi

        // 3a. Pastikan tipe response adalah Illuminate\View\View
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        // 3b. Pastikan nama view-nya benar
        $this->assertEquals('admin.manajemen-meja', $response->name());

        // 3c. Ambil data dari view
        $data = $response->getData();

        // 3d. Pastikan ada key 'tables' di dalam data
        $this->assertArrayHasKey('tables', $data);

        // 3e. Pastikan data 'tables' adalah array
        $this->assertIsArray($data['tables']);

        // 3f. Pastikan jumlah datanya benar (24 meja)
        $this->assertCount(24, $data['tables']);

        // 3g. (Opsional) Cek data pertama untuk memastikan
        $expectedFirstTable = ['id' => 1, 'nomor_meja' => '1', 'kapasitas' => 4, 'lokasi' => 'Indoor 1', 'tersedia' => true];
        $this->assertEquals($expectedFirstTable, $data['tables'][0]);
    }
}
