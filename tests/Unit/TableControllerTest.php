<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Meja;
use App\Http\Controllers\Admin\TableController; // <-- Ini sudah benar
use Illuminate\Foundation\Testing\RefreshDatabase;

class TableControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Meja::unguard();
        
        Meja::create([
            'nomor_meja' => 1,
            'kapasitas' => 4,
            'lokasi' => 'Lantai 1',
            'status_aktif' => 1
        ]);
        
        Meja::create([
            'nomor_meja' => 2,
            'kapasitas' => 6,
            'lokasi' => 'Lantai 2',
            'status_aktif' => 0
        ]);
        
        Meja::reguard();

        $controller = new TableController(); // <-- PERBAIKAN DI SINI
        $response = $controller->index();

        $this->assertEquals('admin.manajemen-meja', $response->getName());
        $this->assertCount(2, $response->getData()['tables']);
    }
}
