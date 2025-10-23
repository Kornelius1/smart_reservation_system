<?php

namespace Tests\Unit;

use App\Http\Controllers\LaporanController;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response; // <-- Pastikan ini di-import
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

/**
 * Class LaporanControllerTest
 * Menguji logika unit dari LaporanController dengan mem-mock semua dependency.
 */
class LaporanControllerTest extends TestCase
{
    /**
     * Membersihkan Mockery setelah setiap test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // --- UJI METODE INDEX ---

    /**
     * Memastikan metode index mengembalikan view dengan data yang dipaginasi.
     */
    public function test_index_returns_view_with_paginated_data()
    {
        // 1. Persiapan Mock
        
        // Mock Request
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('validate')->once();
        $mockRequest->shouldReceive('filled')->with('start_date')->once()->andReturn(false);
        $mockRequest->shouldReceive('filled')->with('end_date')->once()->andReturn(false);

        // Mock Paginator (hasil akhir dari query)
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);
        $mockPaginator->shouldReceive('withQueryString')->once()->andReturnSelf();

        // Mock Query Builder (yang dirantai)
        $mockQueryBuilder = Mockery::mock(Builder::class);
        $mockQueryBuilder->shouldReceive('latest')->with('tanggal')->once()->andReturnSelf();
        $mockQueryBuilder->shouldReceive('paginate')->with(10)->once()->andReturn($mockPaginator);

        // === PERBAIKAN DI SINI ===
        // Mock Model Reservation menggunakan 'alias:' untuk static method 'query()'
        $mockReservation = Mockery::mock('alias:App\Models\Reservation');
        $mockReservation->shouldReceive('query')->once()->andReturn($mockQueryBuilder);
        // =========================

        // 2. Eksekusi
        $controller = new LaporanController();
        $response = $controller->index($mockRequest);

        // 3. Verifikasi
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('admin.manajemen-laporan', $response->name());
        $this->assertArrayHasKey('reservations', $response->getData());
        $this->assertSame($mockPaginator, $response->getData()['reservations']);
    }

    // --- UJI METODE EXPORT ---

    /**
     * Memastikan metode export mengembalikan StreamedResponse.
     */
    public function test_export_returns_streamed_response()
    {
        // 1. Persiapan Mock
        
        // Mock Request (tanpa filter)
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('filled')->with('start_date')->once()->andReturn(false);
        $mockRequest->shouldReceive('filled')->with('end_date')->once()->andReturn(false);

        // Data reservasi palsu menggunakan Carbon
        $mockReservationData = [
            (object)[
                'id_transaksi' => 'TR001',
                'nama' => 'User Test 1',
                'tanggal' => Carbon::parse('2025-10-20'),
                'waktu' => Carbon::parse('2025-10-20 10:00:00'),
                'created_at' => Carbon::parse('2025-10-19 08:00:00'),
            ]
        ];
        $mockCollection = collect($mockReservationData);

        // Mock Query Builder (yang dirantai)
        $mockQueryBuilder = Mockery::mock(Builder::class);
        $mockQueryBuilder->shouldReceive('get')->once()->andReturn($mockCollection);

        // === PERBAIKAN DI SINI ===
        // Mock Model Reservation menggunakan 'alias:' untuk static method 'query()'
        $mockReservation = Mockery::mock('alias:App\Models\Reservation');
        $mockReservation->shouldReceive('query')->once()->andReturn($mockQueryBuilder);
        // =========================

        // Mock Response Facade
        $mockStreamedResponse = Mockery::mock(StreamedResponse::class);
        Response::shouldReceive('stream')->once()->andReturn($mockStreamedResponse);

        // 2. Eksekusi
        $controller = new LaporanController();
        $response = $controller->export($mockRequest);

        // 3. Verifikasi
        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertSame($mockStreamedResponse, $response);
    }
}