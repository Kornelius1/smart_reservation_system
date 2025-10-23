<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\RescheduleController;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

use Illuminate\Validation\ValidationException; 

class RescheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    private $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new RescheduleController();
        Reservation::unguard();
        
   
        Carbon::setTestNow(Carbon::parse('2025-10-23 10:00:00'));
    }

    public function tearDown(): void
    {
        Reservation::reguard();
        Carbon::setTestNow(); // Reset waktu
        parent::tearDown();
    }

    // ===========================
    // TEST METHOD: showForm()
    // ===========================
    
    #[Test]
    public function test_show_form_returns_correct_view()
    {
        // ACT
        $response = $this->controller->showForm();

        // ASSERT
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('customer.reschedule', $response->getName());
    }

    // ===========================
    // TEST METHOD: findReservation()
    // ===========================
    
    #[Test]
    public function test_find_reservation_success_can_reschedule()
    {
        // ARRANGE: Buat reservasi BESOK (bisa reschedule)
        Reservation::create([
            'id_transaksi' => 'TR001',
            'nama' => 'John Doe',
            'tanggal' => '2025-10-25', // H+2, bisa reschedule
            'waktu' => '14:00:00',
            'jumlah_orang' => 4
        ]);

        $request = new Request(['id_transaksi' => 'TR001']);

        // ACT
        $response = $this->controller->findReservation($request);

        // ASSERT
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('customer.reschedule', $response->getName());
        
        $data = $response->getData();
        $this->assertArrayHasKey('reservasi', $data);
        $this->assertArrayHasKey('bisa_reschedule', $data);
        $this->assertEquals('TR001', $data['reservasi']->id_transaksi);
        $this->assertTrue($data['bisa_reschedule'], 'Harus bisa reschedule karena H+2');
    }

    #[Test]
    public function test_find_reservation_success_cannot_reschedule()
    {
        // ARRANGE: Buat reservasi HARI INI (tidak bisa reschedule)
        Reservation::create([
            'id_transaksi' => 'TR002',
            'nama' => 'Jane Doe',
            'tanggal' => '2025-10-23', // Hari ini, tidak bisa reschedule
            'waktu' => '18:00:00',
            'jumlah_orang' => 2
        ]);

        $request = new Request(['id_transaksi' => 'TR002']);

        // ACT
        $response = $this->controller->findReservation($request);

        // ASSERT
        $this->assertInstanceOf(View::class, $response);
        
        $data = $response->getData();
        $this->assertEquals('TR002', $data['reservasi']->id_transaksi);
        $this->assertFalse($data['bisa_reschedule'], 'Tidak bisa reschedule karena hari ini');
    }

    #[Test]
    public function test_find_reservation_not_found()
    {
        // ARRANGE: Tidak ada reservasi dengan ID ini
        $request = new Request(['id_transaksi' => 'TIDAK_ADA']);

        // ACT
        $response = $this->controller->findReservation($request);

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('reschedule.form'), $response->getTargetUrl());
        $this->assertEquals('ID Transaksi tidak ditemukan!', session('error'));
    }

    // ===========================
    // TEST METHOD: updateSchedule()
    // ===========================
    
    #[Test]
    public function test_update_schedule_success()
    {
        // ARRANGE: Buat reservasi yang bisa diubah
        Reservation::create([
            'id_transaksi' => 'TR100',
            'nama' => 'Test User',
            'tanggal' => '2025-10-25',
            'waktu' => '10:00:00',
            'jumlah_orang' => 3
        ]);

        $request = new Request([
            'id_transaksi' => 'TR100',
            'tanggal_baru' => '2025-10-26',
            'waktu_baru' => '15:00'
        ]);

        // ACT
        $response = $this->controller->updateSchedule($request);

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('reschedule.form'), $response->getTargetUrl());
        $this->assertStringContainsString('berhasil diubah', session('success'));

        // Verifikasi data di database sudah berubah
        $updated = Reservation::where('id_transaksi', 'TR100')->first();
        $this->assertEquals('2025-10-26', $updated->tanggal->toDateString());
        $this->assertEquals('15:00:00', $updated->waktu->toTimeString());
    }

    #[Test]
    public function test_update_schedule_fail_past_operating_hours()
    {
        // ARRANGE
        Reservation::create([
            'id_transaksi' => 'TR200',
            'nama' => 'Test User',
            'tanggal' => '2025-10-25',
            'waktu' => '10:00:00',
            'jumlah_orang' => 2
        ]);

        $request = new Request([
            'id_transaksi' => 'TR200',
            'tanggal_baru' => '2025-10-26',
            'waktu_baru' => '23:30' // Melewati jam operasional
        ]);

        // ACT
        $response = $this->controller->updateSchedule($request);

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('Melewati jam operasional', session('update_error'));

        // Verifikasi data TIDAK berubah
        $unchanged = Reservation::where('id_transaksi', 'TR200')->first();
        $this->assertEquals('2025-10-25', $unchanged->tanggal->toDateString());
        $this->assertEquals('10:00:00', $unchanged->waktu->toTimeString());
    }

    #[Test]
    public function test_update_schedule_fail_schedule_conflict()
    {
        // ARRANGE: Buat 2 reservasi
        Reservation::create([
            'id_transaksi' => 'TR300',
            'nama' => 'User A',
            'tanggal' => '2025-10-25',
            'waktu' => '10:00:00',
            'jumlah_orang' => 2
        ]);

        // Reservasi kedua yang SUDAH MENGISI slot tanggal/waktu tujuan
        Reservation::create([
            'id_transaksi' => 'TR301',
            'nama' => 'User B',
            'tanggal' => '2025-10-26',
            'waktu' => '14:00:00',
            'jumlah_orang' => 4
        ]);

        // Coba ubah TR300 ke slot yang SAMA dengan TR301
        $request = new Request([
            'id_transaksi' => 'TR300',
            'tanggal_baru' => '2025-10-26',
            'waktu_baru' => '14:00'
        ]);

        // ACT
        $response = $this->controller->updateSchedule($request);

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('sudah terisi', session('update_error'));

        // Verifikasi TR300 TIDAK berubah
        $unchanged = Reservation::where('id_transaksi', 'TR300')->first();
        $this->assertEquals('2025-10-25', $unchanged->tanggal->toDateString());
    }

    #[Test]
    public function test_update_schedule_fail_past_h_minus_1()
    {
        // ARRANGE: Buat reservasi HARI INI (tidak bisa reschedule)
        Reservation::create([
            'id_transaksi' => 'TR400',
            'nama' => 'User C',
            'tanggal' => '2025-10-23', // Hari ini
            'waktu' => '18:00:00',
            'jumlah_orang' => 2
        ]);

        $request = new Request([
            'id_transaksi' => 'TR400',
            'tanggal_baru' => '2025-10-26',
            'waktu_baru' => '12:00'
        ]);

        // ACT
        $response = $this->controller->updateSchedule($request);

        // ASSERT
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('melewati batas H-1', session('update_error'));

        // Verifikasi data TIDAK berubah
        $unchanged = Reservation::where('id_transaksi', 'TR400')->first();
        $this->assertEquals('2025-10-23', $unchanged->tanggal->toDateString());
    }

    #[Test]
    public function test_update_schedule_fail_reservation_not_found()
    {
        // ARRANGE: Request dengan ID yang tidak ada
        $request = new Request([
            'id_transaksi' => 'TIDAK_ADA',
            'tanggal_baru' => '2025-10-26',
            'waktu_baru' => '12:00'
        ]);

        // ACT & ASSERT: Harus throw exception karena validasi 'exists' gagal
        
        // --- INI PERBAIKANNYA ---
        // Kita mengharapkan ValidationException, BUKAN ModelNotFoundException
        $this->expectException(ValidationException::class);
        // --- SELESAI PERBAIKAN ---

        $this->controller->updateSchedule($request);
    }
}