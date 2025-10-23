<?php

namespace Tests\Unit\Http\Controllers\Customer;

use App\Http\Controllers\Customer\BayarController;
use App\Models\Meja; // Import model yang akan di-mock
use App\Models\Product; // Import model yang akan di-mock
use App\Models\Room; // Import model yang akan di-mock
use Illuminate\Database\Eloquent\Collection; // Untuk membuat collection palsu
use Illuminate\Http\RedirectResponse; // Untuk cek redirect
use Illuminate\Http\Request; // Untuk membuat request palsu
use Illuminate\View\View; // Untuk cek view
use Mockery; // Import Mockery
use Tests\TestCase;

class BayarControllerTest extends TestCase
{
    /**
     * Selalu jalankan ini setelah test yang menggunakan Mockery
     */
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // --- SKENARIO 1: GAGAL (Keranjang Kosong) ---
    public function test_show_redirects_if_cart_is_empty(): void
    {
        // 1. Buat request palsu (tanpa 'items')
        $request = new Request([
            'reservation_room_name' => 'Room A' // Ada reservasi, tapi items kosong
        ]);

        // 2. Buat controller & panggil method
        $controller = new BayarController();
        $response = $controller->show($request);

        // 3. Assertions (Harusnya redirect ke '/pesanmenu')
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/pesanmenu', $response->getTargetUrl());
    }

    // --- SKENARIO 2: GAGAL (Pesan Ruangan, tapi total belanja kurang) ---
    public function test_show_redirects_if_room_minimum_order_not_met(): void
    {
        // 1. Mock Model Product DULU
        $productMock = Mockery::mock('alias:App\Models\Product');
        
        // --- PERBAIKAN: Gunakan objek standar, bukan 'new Product' ---
        $fakeProduct = new \stdClass();
        $fakeProduct->id = 1;
        $fakeProduct->price = 20000;
        
        $productMock->shouldReceive('findMany')
                      ->once()
                      ->with([1]) // Harus dipanggil dengan id [1]
                      ->andReturn(new Collection([$fakeProduct])); // Kembalikan produk palsu

        // 2. Mock Model Room DULU
        $roomMock = Mockery::mock('alias:App\Models\Room');

        // --- PERBAIKAN: Gunakan objek standar, bukan 'new Room' ---
        $fakeRoom = new \stdClass();
        $fakeRoom->name = 'Room A';
        $fakeRoom->minimum_order = 50000;

        $roomMock->shouldReceive('where')->once()->with('name', 'Room A')->andReturnSelf();
        $roomMock->shouldReceive('first')->once()->andReturn($fakeRoom);

        // 3. Buat request palsu (Belanja 20.000, min 50.000)
        $request = new Request([
            'items' => [1 => 1], // 1 Kopi @ 20.000
            'reservation_room_name' => 'Room A'
        ]);

        // 4. Panggil method
        $controller = new BayarController();
        $response = $controller->show($request);

        // 5. Assertions (Harusnya redirect)
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/pesanmenu', $response->getTargetUrl());
    }

    // --- SKENARIO 3: SUKSES (Pesan Meja, total belanja cukup) ---
    public function test_show_returns_view_on_valid_table_order(): void
    {
        // 1. Mock Model Product DULU
        $productMock = Mockery::mock('alias:App\Models\Product');

        // --- PERBAIKAN: Gunakan objek standar, bukan 'new Product' ---
        $fakeProduct = new \stdClass();
        $fakeProduct->id = 1;
        $fakeProduct->name = 'Steak';
        $fakeProduct->price = 60000;
        
        $productMock->shouldReceive('findMany')
                      ->once()
                      ->with([1])
                      ->andReturn(new Collection([$fakeProduct]));

        // 2. Mock Model Meja DULU
        $mejaMock = Mockery::mock('alias:App\Models\Meja');

        // --- PERBAIKAN: Gunakan objek standar, bukan 'new Meja' ---
        $fakeMeja = new \stdClass();

        $mejaMock->shouldReceive('where')->once()->with('nomor_meja', 'T1')->andReturnSelf();
        $mejaMock->shouldReceive('where')->once()->with('status_aktif', true)->andReturnSelf();
        $mejaMock->shouldReceive('first')->once()->andReturn($fakeMeja);

        // 3. Buat request palsu (Belanja 60.000 > min 50.000)
        $request = new Request([
            'items' => [1 => 1], // 1 Steak @ 60.000
            'reservation_table_number' => 'T1'
        ]);

        // 4. Panggil method
        $controller = new BayarController();
        $response = $controller->show($request);

        // 5. Assertions (Harusnya menampilkan view)
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('customer.BayarReservasi', $response->name());

        // 6. (Opsional) Cek data yang dikirim ke view
        $viewData = $response->getData();
        $this->assertEquals(60000, $viewData['totalPrice']);
        $this->assertEquals('meja', $viewData['reservationDetails']['type']);
        $this->assertEquals('T1', $viewData['reservationDetails']['detail']);
    }

    // --- SKENARIO 4: GAGAL (Pesan Meja, tapi total belanja kurang) ---
    public function test_show_redirects_if_table_minimum_order_not_met(): void
    {
        // 1. Mock Model Product DULU
        $productMock = Mockery::mock('alias:App\Models\Product');

        // --- PERBAIKAN: Gunakan objek standar, bukan 'new Product' ---
        $fakeProduct = new \stdClass();
        $fakeProduct->id = 1;
        $fakeProduct->price = 30000;

        $productMock->shouldReceive('findMany')->once()->with([1])->andReturn(new Collection([$fakeProduct]));

        // 2. Mock Model Meja DULU
        $mejaMock = Mockery::mock('alias:App\Models\Meja');
        
        // --- PERBAIKAN: Gunakan objek standar, bukan 'new Meja' ---
        $fakeMeja = new \stdClass();

        $mejaMock->shouldReceive('where')->once()->with('nomor_meja', 'T1')->andReturnSelf();
        $mejaMock->shouldReceive('where')->once()->with('status_aktif', true)->andReturnSelf();
        $mejaMock->shouldReceive('first')->once()->andReturn($fakeMeja);

        // 3. Buat request palsu (Belanja 30.000 < min 50.000)
        $request = new Request([
            'items' => [1 => 1], // 1 Kopi @ 30.000
            'reservation_table_number' => 'T1'
        ]);

        // 4. Panggil method
        $controller = new BayarController();
        $response = $controller->show($request);

        // 5. Assertions (Harusnya redirect)
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/pesanmenu', $response->getTargetUrl());
    }
}

