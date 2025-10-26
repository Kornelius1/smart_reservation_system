<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Admin\ReservationController; 

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Reservation::unguard();
        
        Reservation::create([
            'id_transaksi' => 'TRX001',
            'nama' => 'John Doe',
            'nomor_telepon' => '081234567890',
            'jumlah_orang' => 10,
            'tanggal' => '2025-10-25',
            'waktu' => '09:00:00',
            'status' => 0,
            'nomor_meja' => 1,
            'nomor_ruangan' => 1
        ]);
        
        Reservation::create([
            'id_transaksi' => 'TRX002',
            'nama' => 'Jane Smith',
            'nomor_telepon' => '081234567891',
            'jumlah_orang' => 20,
            'tanggal' => '2025-10-26',
            'waktu' => '13:00:00',
            'status' => 1,
            'nomor_meja' => 2,
            'nomor_ruangan' => 2
        ]);
        
        Reservation::reguard();

        $controller = new ReservationController(); // <-- UBAH INI
        $response = $controller->index();

        $this->assertEquals('admin.manajemen-reservasi', $response->getName());
        $this->assertCount(2, $response->getData()['reservations']);
    }
}