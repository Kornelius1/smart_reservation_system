<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test; // <-- 1. TAMBAHKAN INI
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BayarControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test] // <-- 2. GANTI DARI /** @test */ MENJADI INI
    public function bayar_berhasil_ketika_amount_valid()
    {
        $response = $this->postJson('/bayar', ['amount' => 10000]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Pembayaran berhasil',
                     'amount' => 10000
                 ]);
    }

    #[Test] // <-- 3. GANTI INI JUGA
    public function bayar_gagal_ketika_amount_tidak_valid()
    {
        $response = $this->postJson('/bayar', ['amount' => 0]);

        $response->assertStatus(400)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Jumlah pembayaran tidak valid'
                 ]);
    }

    #[Test] // <-- 4. GANTI INI JUGA
    public function cek_status_pembayaran_berhasil()
    {
        $response = $this->getJson('/bayar/status/123');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'payment_id' => 123,
                     'message' => 'Pembayaran terkonfirmasi'
                 ]);
    }
}