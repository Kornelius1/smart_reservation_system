<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Room;
use App\Models\Product;
use App\Models\Meja;
use App\Http\Controllers\Customer\BayarController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BayarControllerTest extends TestCase
{
    use RefreshDatabase;

    private const MINIMUM_ORDER_FOR_TABLE = 50000;
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BayarController();

        Product::unguard();
        Room::unguard();
        Meja::unguard();

        Product::forceCreate([
            'id' => 1,
            'name' => 'Kopi A',
            'price' => 20000,
            'image_url' => 'test/kopi_a.jpg',
            'category' => 'Minuman'
        ]);
        Product::forceCreate([
            'id' => 2,
            'name' => 'Roti B',
            'price' => 30000,
            'image_url' => 'test/roti_b.jpg',
            'category' => 'Makanan'
        ]);

        Room::forceCreate(['name' => 'VIP A', 'minimum_order' => 100000]);
        Room::forceCreate(['name' => 'Meeting B', 'minimum_order' => 200000]);

        // PERBAIKAN: Gunakan integer untuk nomor_meja + tambahkan kolom yang ada di model
        Meja::forceCreate([
            'nomor_meja' => 10,
            'kapasitas' => 4,
            'lokasi' => 'Indoor',
            'status_aktif' => true
        ]);
        Meja::forceCreate([
            'nomor_meja' => 11,
            'kapasitas' => 6,
            'lokasi' => 'Outdoor',
            'status_aktif' => false
        ]);
    }

    public function testShowSuccessRoomReservation()
    {
        Room::forceCreate(['name' => 'Small Room', 'minimum_order' => 50000]);

        $request = Request::create('/bayar', 'GET', [
            'items' => [
                '1' => 2,
                '2' => 1,
            ],
            'reservation_room_name' => 'Small Room',
            'reservation_table_number' => null,
        ]);

        $response = $this->controller->show($request);

        $this->assertEquals('customer.BayarReservasi', $response->getName());

        $data = $response->getData();
        $this->assertEquals(70000, $data['totalPrice']);
        $this->assertEquals('ruangan', $data['reservationDetails']['type']);
        $this->assertEquals('Small Room', $data['reservationDetails']['detail']);
        $this->assertCount(2, $data['cartItems']);
    }

    public function testShowSuccessTableReservation()
    {
        $request = Request::create('/bayar', 'GET', [
            'items' => [
                '1' => 1,
                '2' => 2,
            ],
            'reservation_room_name' => null,
            'reservation_table_number' => 10, // UBAH: dari 'M10' ke 10
        ]);

        $response = $this->controller->show($request);

        $this->assertEquals('customer.BayarReservasi', $response->getName());

        $data = $response->getData();
        $this->assertEquals(80000, $data['totalPrice']);
        $this->assertEquals('meja', $data['reservationDetails']['type']);
        $this->assertEquals(10, $data['reservationDetails']['detail']); // UBAH: dari 'M10' ke 10
    }

    public function testShowFailureEmptyCart()
    {
        $request = Request::create('/bayar', 'GET', [
            'items' => [],
            'reservation_room_name' => 'VIP A',
        ]);

        $response = $this->controller->show($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/pesanmenu'), $response->getTargetUrl());
        $this->assertStringContainsString('Keranjang Anda kosong!', $response->getSession()->get('errors')->first());
    }

    public function testShowFailureRoomMinimumOrderNotMet()
    {
        $request = Request::create('/bayar', 'GET', [
            'items' => ['1' => 1],
            'reservation_room_name' => 'VIP A',
        ]);

        $response = $this->controller->show($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/pesanmenu'), $response->getTargetUrl());
        $this->assertStringContainsString('tidak memenuhi syarat minimal pemesanan untuk VIP A', $response->getSession()->get('errors')->first());
    }

    public function testShowFailureTableMinimumOrderNotMet()
    {
        $request = Request::create('/bayar', 'GET', [
            'items' => ['1' => 1],
            'reservation_table_number' => 10, // UBAH: dari 'M10' ke 10
        ]);

        $response = $this->controller->show($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/pesanmenu'), $response->getTargetUrl());
        $this->assertStringContainsString('tidak memenuhi syarat minimal pemesanan meja', $response->getSession()->get('errors')->first());
    }

    public function testShowFailureInvalidTable()
    {
        $request = Request::create('/bayar', 'GET', [
            'items' => ['1' => 3],
            'reservation_table_number' => 11, // UBAH: dari 'M11' ke 11
        ]);

        $response = $this->controller->show($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/pilih-meja'), $response->getTargetUrl());
        $this->assertStringContainsString('Meja yang dipilih tidak valid atau tidak tersedia.', $response->getSession()->get('errors')->first());
    }

    public function testShowFailureNoReservationDetail()
    {
        $request = Request::create('/bayar', 'GET', [
            'items' => ['1' => 3],
            'reservation_room_name' => null,
            'reservation_table_number' => null,
        ]);

        $response = $this->controller->show($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/pilih-reservasi'), $response->getTargetUrl());
        $this->assertStringContainsString('Silakan pilih jenis reservasi terlebih dahulu.', $response->getSession()->get('errors')->first());
    }

    public function testProcessPaymentSuccess()
    {
        Room::forceCreate(['name' => 'Big Room', 'minimum_order' => 50000]);

        $request = Request::create('/process-payment', 'POST', [
            'items' => [
                '1' => 2,
                '2' => 2,
            ],
            'reservation_room_name' => 'Big Room',
            'reservation_table_number' => null,
        ]);

        $response = $this->controller->processPayment($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/sukses'), $response->getTargetUrl());
        $this->assertEquals('Pembayaran Anda berhasil diproses!', session('success_message'));
    }

    public function testProcessPaymentFailure()
    {
        $request = Request::create('/process-payment', 'POST', [
            'items' => ['1' => 1],
            'reservation_room_name' => 'VIP A',
            'reservation_table_number' => null,
        ]);

        $response = $this->controller->processPayment($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertNotNull($response->getSession()->get('errors'));
        $this->assertStringContainsString('tidak memenuhi syarat minimal pemesanan untuk VIP A', $response->getSession()->get('errors')->first());
    }
}