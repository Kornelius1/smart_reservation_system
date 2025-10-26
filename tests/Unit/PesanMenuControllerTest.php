<?php

namespace Tests\Unit\Customer; 

use Tests\TestCase;
use App\Http\Controllers\Customer\PesanMenuController; 
use Illuminate\View\View; 
use PHPUnit\Framework\Attributes\Test;

class PesanMenuControllerTest extends TestCase
{
    /**
     * Tes ini akan memverifikasi bahwa metode index()
     * pada PesanMenuController mengembalikan view yang benar.
     */
    #[Test]
    public function test_index_method_returns_correct_view(): void
    {
        // ARRANGE
        // Buat instance baru dari controller
        $controller = new PesanMenuController();

        // ACT
        // Panggil metode index() yang ingin kita tes
        $response = $controller->index();

        // ASSERT
        // 1. Pastikan bahwa respons yang dikembalikan adalah instance dari class View
        $this->assertInstanceOf(
            View::class,
            $response,
            'Metode index() seharusnya mengembalikan sebuah instance dari Illuminate\View\View.'
        );

        // 2. Pastikan bahwa nama view yang dikembalikan sudah sesuai
        $this->assertEquals(
            'customer.pesanmenu',
            $response->getName(),
            'Nama view yang dikembalikan seharusnya adalah "customer.pesanmenu".'
        );
    }
}