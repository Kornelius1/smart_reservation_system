<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\AdminController; // Controller yang diuji
use Illuminate\View\View; // Untuk cek tipe response
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    /**
     * Memastikan metode dashboard mengembalikan view yang benar.
     */
    public function test_dashboard_returns_correct_view(): void
    {
        // 1. Persiapan: Buat instance controller
        $controller = new AdminController();

        // 2. Eksekusi: Panggil method dashboard()
        $response = $controller->dashboard();

        // 3. Verifikasi: Cek hasilnya
        // Pastikan hasilnya adalah objek View
        $this->assertInstanceOf(View::class, $response);
        // Pastikan nama view-nya adalah 'admin.DashboardAdmin'
        $this->assertEquals('admin.DashboardAdmin', $response->name());
    }
}