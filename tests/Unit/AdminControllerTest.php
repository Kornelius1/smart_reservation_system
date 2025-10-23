<?php
namespace Tests\Unit;


use App\Http\Controllers\AdminController; 
use Illuminate\View\View;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    public function test_dashboard_returns_correct_view(): void
    {

        $controller = new AdminController();

        
        $response = $controller->dashboard();

        $this->assertInstanceOf(View::class, $response);
      
        $this->assertEquals('admin.DashboardAdmin', $response->name());
    }
}
