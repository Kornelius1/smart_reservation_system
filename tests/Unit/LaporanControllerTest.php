<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Admin\LaporanController;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;


class LaporanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Reservation::unguard();
    }

    public function tearDown(): void
    {
        Reservation::reguard();
        parent::tearDown();
    }

    #[Test]
    public function test_index_displays_report_without_filters()
    {
        // HANYA gunakan kolom yang PASTI ada
        Reservation::create([
            'id_transaksi' => 'TR001',
            'nama' => 'Customer A',
            'tanggal' => '2025-10-25',
            'waktu' => '10:00:00',
            'jumlah_orang' => 4
        ]);
        Reservation::create([
            'id_transaksi' => 'TR002',
            'nama' => 'Customer B',
            'tanggal' => '2025-10-24',
            'waktu' => '11:00:00',
            'jumlah_orang' => 2
        ]);

        $request = new Request();

        // ACT
        $controller = new LaporanController();
        $view = $controller->index($request);

        // ASSERT
        $this->assertInstanceOf(View::class, $view, 'Harus mengembalikan View.');
        $this->assertEquals('admin.manajemen-laporan', $view->getName(), 'Nama view harus benar.');

        $dataPassedToView = $view->getData();
        $this->assertArrayHasKey('reservations', $dataPassedToView, 'Harus ada data reservations.');

        $paginator = $dataPassedToView['reservations'];
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator, 'Data harus berupa Paginator.');
        $this->assertEquals(2, $paginator->total(), 'Harus ada 2 reservasi.');

        $items = $paginator->items();
        $this->assertEquals('2025-10-25', Carbon::parse($items[0]->tanggal)->toDateString());
        $this->assertEquals('2025-10-24', Carbon::parse($items[1]->tanggal)->toDateString());
    }

    #[Test]
    public function test_index_displays_report_with_date_filters()
    {
        $startDate = '2024-01-05';
        $endDate = '2024-01-15';

        // Data di dalam rentang
        Reservation::create([
            'id_transaksi' => 'T001',
            'nama' => 'Inside A',
            'tanggal' => '2024-01-10',
            'waktu' => '10:00',
            'jumlah_orang' => 1
        ]);
        Reservation::create([
            'id_transaksi' => 'T002',
            'nama' => 'Inside B',
            'tanggal' => '2024-01-05',
            'waktu' => '10:00',
            'jumlah_orang' => 1
        ]);
        
        // Data di luar rentang
        Reservation::create([
            'id_transaksi' => 'T003',
            'nama' => 'Outside Before',
            'tanggal' => '2024-01-01',
            'waktu' => '10:00',
            'jumlah_orang' => 1
        ]);
        Reservation::create([
            'id_transaksi' => 'T004',
            'nama' => 'Outside After',
            'tanggal' => '2024-01-20',
            'waktu' => '10:00',
            'jumlah_orang' => 1
        ]);

        $request = new Request([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // ACT
        $controller = new LaporanController();
        $view = $controller->index($request);

        // ASSERT
        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('admin.manajemen-laporan', $view->getName());

        $dataPassedToView = $view->getData();
        $paginator = $dataPassedToView['reservations'];
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);

        $this->assertEquals(2, $paginator->total(), 'Harus ada 2 reservasi yang cocok filter.');

        $names = collect($paginator->items())->pluck('nama')->toArray();
        $this->assertContains('Inside A', $names);
        $this->assertContains('Inside B', $names);
        $this->assertNotContains('Outside Before', $names);
        $this->assertNotContains('Outside After', $names);
    }

    #[Test]
    public function test_export_fetches_data_and_returns_streamed_response()
    {
        Reservation::create([
            'id_transaksi' => 'TR9001',
            'nama' => 'User Export',
            'tanggal' => now()->subDay()->toDateString(),
            'waktu' => now()->subHours(5)->toTimeString(),
            'jumlah_orang' => 3,
            'created_at' => now()->subDay()
        ]);

        $request = new Request();

        // ACT
        $controller = new LaporanController();
        $response = $controller->export($request);

        // ASSERT
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response);

        $headers = $response->headers;
        $this->assertStringContainsString('text/csv', $headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename=laporan-reservasi-', $headers->get('Content-Disposition'));
    }
}