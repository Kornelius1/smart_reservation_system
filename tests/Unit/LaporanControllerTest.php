<?php

namespace Tests\Unit;

use App\Http\Controllers\LaporanController;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Response; // Tambahkan import Response Facade

/**
 * Unit Test untuk LaporanController.
 * Menggunakan Mocking untuk mengisolasi Controller dari database dan HTTP Response.
 */
class LaporanControllerTest extends TestCase
{
    /**
     * Pastikan Mockery dibersihkan setelah setiap test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ===============================================
    // TEST UNTUK METHOD INDEX (Menampilkan Laporan)
    // ===============================================

    /**
     * Test case untuk method index tanpa filter (default fetch).
     *
     * @return void
     */
    public function test_index_displays_report_without_filters()
    {
        // ARRANGE: Siapkan data tiruan
        $mockReservations = Collection::make([
            (object)['id' => 1, 'tanggal' => '2024-10-25'],
            (object)['id' => 2, 'tanggal' => '2024-10-24'],
        ]);

        // MOCKING: Memasang Mock pada Model Reservation (Eloquent)
        $reservationMock = Mockery::mock('alias:' . Reservation::class);
        $queryMock = Mockery::mock();
        $paginationMock = Mockery::mock();

        // Step 1: Siapkan MOCK Request. 
        // Kita mock metode filled() untuk memastikan filter tidak terisi.
        // PENTING: Untuk mengatasi validasi, kita mock method validate() agar tidak melakukan apa-apa (lulus).
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->once()->andReturn([]);
        $request->shouldReceive('filled')->with('start_date')->andReturnFalse();
        $request->shouldReceive('filled')->with('end_date')->andReturnFalse();
        // Karena filled() mengembalikan false, property access $request->start_date di Controller di-skip.
        // Tidak perlu mock all() di sini.

        // Step 2: Mocking Chaining Query
        $reservationMock->shouldReceive('query')->andReturn($queryMock);
        
        // getFilteredQuery (tanpa filter): tidak ada whereDate dipanggil
        $queryMock->shouldReceive('latest')->with('tanggal')->once()->andReturnSelf();
        
        // Step 3: Mocking Pagination (Penting untuk method index)
        $queryMock->shouldReceive('paginate')->with(10)->once()->andReturn($paginationMock);
        // Mocking withQueryString agar mengembalikan data tiruan
        $paginationMock->shouldReceive('withQueryString')->once()->andReturn($mockReservations); 

        // ACT
        $controller = new LaporanController();
        // Memanggil Controller secara langsung dengan Request mock
        $view = $controller->index($request);

        // ASSERT
        $this->assertInstanceOf(View::class, $view, 'Metode index() harus mengembalikan instance dari View.');
        $this->assertEquals('admin.manajemen-laporan', $view->getName(), 'Nama view yang dimuat harus admin.manajemen-laporan.');
        
        $dataPassedToView = $view->getData();
        $this->assertArrayHasKey('reservations', $dataPassedToView, 'View harus dilewatkan dengan kunci data "reservations".');
        $this->assertEquals($mockReservations, $dataPassedToView['reservations'], 'Data yang dilewatkan harus sesuai dengan hasil mock paginate.');
    }

    /**
     * Test case untuk method index dengan filter tanggal (start_date dan end_date).
     *
     * @return void
     */
    public function test_index_displays_report_with_date_filters()
    {
        // ARRANGE: Siapkan data filter
        $startDate = '2024-01-01';
        $endDate = '2024-01-31';

        // MOCKING: Memasang Mock pada Model Reservation
        $reservationMock = Mockery::mock('alias:' . Reservation::class);
        $queryMock = Mockery::mock();
        $paginationMock = Mockery::mock();
        
        // Step 1: Siapkan MOCK Request dengan filter.
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->once()->andReturn([]);
        
        // Mocking Request input untuk getFilteredQuery
        $request->shouldReceive('filled')->with('start_date')->andReturnTrue();
        $request->shouldReceive('filled')->with('end_date')->andReturnTrue();

        // FIX: Tambahkan mock untuk method all() yang dipanggil ketika mengakses $request->start_date / $request->end_date
        $request->shouldReceive('all')->andReturn([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        
        // Step 2: Mocking Chaining Query (Ini menguji getFilteredQuery secara implisit)
        $reservationMock->shouldReceive('query')->andReturn($queryMock);
        
        // Harusnya ada 2 whereDate dipanggil dari getFilteredQuery
        $queryMock->shouldReceive('whereDate')->with('tanggal', '>=', $startDate)->once()->andReturnSelf();
        $queryMock->shouldReceive('whereDate')->with('tanggal', '<=', $endDate)->once()->andReturnSelf();
        
        // Step 3: Mocking Pagination
        $queryMock->shouldReceive('latest')->with('tanggal')->once()->andReturnSelf();
        $queryMock->shouldReceive('paginate')->with(10)->once()->andReturn($paginationMock);
        $paginationMock->shouldReceive('withQueryString')->once()->andReturn(new Collection());

        // ACT
        $controller = new LaporanController();
        $controller->index($request);

        // ASSERT: Karena Mockery sudah memastikan bahwa semua `shouldReceive` dipanggil, 
        // kita hanya perlu memastikan tidak ada assertion error.
        $this->assertTrue(true, 'Semua query chaining dipastikan terpanggil dengan parameter filter yang benar.'); 
    }

    // ===============================================
    // TEST UNTUK METHOD EXPORT (Mengekspor CSV)
    // ===============================================

    /**
     * Test case untuk method export (CSV).
     * Karena menggunakan fungsi response()->stream, kita akan fokus 
     * menguji apakah query yang benar dipanggil dan response stream dikembalikan.
     *
     * @return void
     */
    public function test_export_fetches_filtered_data_for_csv()
    {
        // ARRANGE: Siapkan data tiruan yang sudah memiliki created_at dan nama (seperti dari Eloquent)
        $mockReservations = Collection::make([
            (object)[
                'id_transaksi' => 'TR9001', 
                'nama' => 'User A', 
                // Menggunakan Carbon/DateTime instance agar format() dapat dipanggil
                'tanggal' => now()->subDay(), 
                'waktu' => now()->subHours(5), 
                'created_at' => now()->subDay()
            ],
        ]);

        // MOCKING: Memasang Mock pada Model Reservation
        $reservationMock = Mockery::mock('alias:' . Reservation::class);
        $queryMock = Mockery::mock();
        
        // PENTING: Mocking Response Facade agar fungsi global response()->stream() bekerja
        $responseMock = Response::shouldReceive('stream')->once();
        
        // Step 1: Mocking Chaining Query (tanpa filter di request)
        $reservationMock->shouldReceive('query')->andReturn($queryMock);
        $queryMock->shouldReceive('get')->once()->andReturn($mockReservations);

        // Step 2: Mocking response()->stream()
        // Menggunakan andReturnUsing untuk menguji headers dan mengembalikan StreamedResponse
        $responseMock->andReturnUsing(function ($callback, $status, $headers) {
            $this->assertEquals(200, $status);
            $this->assertStringContainsString('text/csv', $headers['Content-type']);
            // Mengembalikan StreamedResponse untuk assertion tipe
            return new \Symfony\Component\HttpFoundation\StreamedResponse($callback, $status, $headers);
        });
        
        // ACT
        $controller = new LaporanController();
        $request = new Request(); // Request tanpa parameter
        // Kita tetap menggunakan Request biasa karena mock Request sudah dilakukan di Response Facade.
        $response = $controller->export($request);

        // ASSERT
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response, 'Metode export() harus mengembalikan instance dari StreamedResponse.');
    }
}